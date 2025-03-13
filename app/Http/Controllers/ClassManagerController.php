<?php

namespace App\Http\Controllers;

use App\Models\Lop;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Khoa;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class ClassManagerController extends Controller
{
    function index()
    {
        $khoas = Khoa::get();
        $teachers = Teacher::get();
        return view('class_manager.index', ['khoas' => $khoas, 'teachers' => $teachers]);
    }

    public function getData(Request $request)
    {
        $query = Lop::query()
            ->leftJoin("khoas", "lops.ma_khoa", "=", "khoas.ma_khoa")
            ->leftJoin("teachers", "lops.teacher_id", "=", "teachers.id")
            ->leftJoin("nganhs", "lops.nganh_id", "=", "nganhs.manganh")
            ->select("lops.*","nganhs.tennganh as nganh","nganhs.hedaotao as hedaotao", "teachers.full_name as teacher_name", "khoas.name as khoa_name");

        $query->when(
            $request->has('status_error')
                && $request->status_error !== 'all',
            function ($query) use ($request) {
                $query->where(
                    $request->status_error === 0 ? 'return_type' : null,
                    $request->status_error === 0 ? null
                        : $request->status_error
                );
            }
        );

        $data = $this->queryPagination($request, $query, ['khoas.name', 'lops.name']);

        return $data;
    }

    public function getDataChild($id)
    {
        try {
            $error = Lop::findOrFail($id);

            return $error;
        } catch (QueryException $e) {
            abort(404);
        }
    }

    function detele($id)
    {
        try {
            $check = Student::where('lop_id', $id)->exists();
            if ($check) {
                abort(404);
            }
            return Lop::findOrFail($id)->delete();
        } catch (QueryException $e) {
            abort(404);
        }
    }

    function create(Request $request)
    {
        try {
            Lop::create($request->only([
                'name',
                'nganh_id',
                'ma_khoa',
                'teacher_id',
            ]));

            return true;
        } catch (QueryException $e) {
            abort(404);
        }
    }

    public function update(Request $request, $id)
    {
        $lop = Lop::find($id);

        if (!$lop) {
            return response()->json([
                'message' => 'Not found',
            ], 404);
        }

        return $lop->update($request->only([
            'name',
            'nganh_id',
            'ma_khoa',
            'teacher_id',
        ]));
    }

    function importFile(Request $request)
    {
        if ($request->hasFile('csv_file')) {
            $data = $this->importCSV($request->file('csv_file'));

            DB::beginTransaction();
            try {
                foreach ($data['data'] as $index => $item) {
                    $lop = new Lop();

                    $khoa = Khoa::where('name', 'like', '%' . $item[1] . '%')->first();
                    if ($khoa) {
                        $lop->khoa_id = $khoa->id;
                    } else {
                        throw new \Exception("Không tìm thấy khoa với tên: " . $item[1]);
                    }

                    $lop->name = $item[0];
                    $lop->nganh_id = $this->getMaNganh($item[0]);
                    

                    $lop->save();
                }
            } catch (\Throwable $th) {
                DB::rollback();
                return $th;
                abort(404);
            }
            DB::commit();
            return true;
        }
        abort(404);
    }
}
