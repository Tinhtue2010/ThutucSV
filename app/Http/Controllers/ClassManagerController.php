<?php

namespace App\Http\Controllers;

use App\Models\Lop;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Khoa;
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
            ->leftJoin("khoas", "lops.khoa_id", "=", "khoas.id")
            ->leftJoin("teachers", "lops.teacher_id", "=", "teachers.id")
            ->select("lops.*", "teachers.full_name as teacher_name", "khoas.name as khoa_name");

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

        $data = $this->queryPagination($request, $query, []);

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
                'nganh',
                'khoa_id',
                'teacher_id',
                'hocphi'
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
            'nganh',
            'khoa_id',
            'teacher_id',
            'hocphi'
        ]));
    }

    function importFile(Request $request)
    {
        if ($request->hasFile('csv_file')) {
            $data = $this->importCSV($request->file('csv_file'));
            $header = [
                "ten_lop" => "name",
                "khoa" => "khoa_id",
                "nganh" => "nganh",
                "hoc_phi" => "hocphi"
            ];
            DB::beginTransaction();
            try {
                foreach ($data['data'] as $index => $item) {
                    $lop = new Lop();
                    foreach ($data['header'] as $index_header => $item_header) {
                        if (!isset($header[$data['header'][$index_header]])) {
                            continue;
                        }
                        $columnName = $header[$data['header'][$index_header]];
                        if ($data['header'][$index_header] == 'khoa') {
                            $khoa = Khoa::where('name', 'like', '%' . $item[$index_header] . '%')->first();
                            if ($khoa) {
                                $lop->khoa_id = $khoa->id;
                            } else {
                                throw new \Exception("Không tìm thấy khoa với tên: " . $item[$index_header]);
                            }
                        } else {
                            $lop->$columnName = $item[$index_header];
                        }
                    }
                    $lop->save();
                }
            } catch (\Throwable $th) {
                DB::rollback();
                abort(404);
            }
            DB::commit();
            return true;
        }
        abort(404);
    }
}
