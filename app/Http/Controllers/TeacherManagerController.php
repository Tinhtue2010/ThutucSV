<?php

namespace App\Http\Controllers;

use App\Models\Khoa;
use App\Models\Teacher;
// use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class TeacherManagerController extends Controller
{
    function index()
    {
        $khoas = Khoa::get();
        return view('teacher_manager.index', ['khoas' => $khoas]);
    }

    public function getData(Request $request)
    {
        $query = Teacher::query()
            ->leftJoin("khoas", "teachers.khoa_id", "=", "khoas.id")
            ->leftJoin("users", "teachers.id", "=", "users.teacher_id")
            ->select("teachers.*", "khoas.name as khoa_name", "users.username as username", "users.role");

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

        $data = $this->queryPagination($request, $query,['khoas.name','users.username','teachers.sdt']);

        return $data;
    }

    public function getDataChild($id)
    {
        try {
            $teacher = DB::table('teachers')
                ->join('users', 'users.teacher_id', '=', 'teachers.id')
                ->select('teachers.*', 'users.role')
                ->where('teachers.id', $id)
                ->first();

            return $teacher;
        } catch (QueryException $e) {
            abort(404);
        }
    }

    function detele($id)
    {
        try {
            return Teacher::findOrFail($id)->delete();
        } catch (QueryException $e) {
            abort(404);
        }
    }

    function create(Request $request)
    {
        try {
            $teacher =  Teacher::create($request->only([
                'full_name',
                'khoa_id',
                'dia_chi',
                'sdt',
                'email',
                'chuc_danh',
                'role'
            ]));

            $user = new User();
            $user->name = $request->full_name;
            $user->username = explode('@', $request->email)[0]; // get the part before @ as username
            $user->password = bcrypt($user->username);
            $user->teacher_id = $teacher->id;
            $user->role = $request->role;
            $user->save();
            return true;
        } catch (QueryException $e) {
            abort(404);
        }
    }


    public function update(Request $request, $id)
    {
        $teacher = Teacher::find($id);
        if (!$teacher) {
            return response()->json(['message' => 'Teacher not found'], 404);
        }

        $teacher->update($request->only([
            'full_name',
            'khoa_id',
            'dia_chi',
            'sdt',
            'email',
            'chuc_danh'
        ]));

        $user = User::where('teacher_id',$teacher->id)->first();
        if ($user) {
            $user->role = $request->role;
            $user->save();
        }
    }

    function importFile(Request $request)
    {
        set_time_limit(900);
        if ($request->hasFile('csv_file')) {
            $data = $this->importCSV($request->file('csv_file'));
            $header = [
                "ho_ten" => "full_name",
                "khoa" => "khoa_id",
                "dia_chi" => "dia_chi",
                "dien_thoai" => "sdt",
                "email" => "email",
                "chuc_danh" => "chuc_danh",
                "tai_khoan" => "tai_khoan",
            ];
            DB::beginTransaction();
            try {
                foreach ($data['data'] as $index => $item) {
                    $teacher = new Teacher();
                    $user = new User();
                    foreach ($data['header'] as $index_header => $item_header) {
                        if (!isset($header[$data['header'][$index_header]])) {
                            continue;
                        }
                        $columnName = $header[$data['header'][$index_header]];
                        if ($data['header'][$index_header] == 'khoa') {
                            if ($item[$index_header] == "" || $item[$index_header] == null) {
                            } else {
                                $khoa = Khoa::where('name', 'like', '%' . $item[$index_header] . '%')->first();
                                if ($khoa) {
                                    $teacher->khoa_id = $khoa->id;
                                } else {
                                    throw new \Exception("Không tìm thấy khoa với tên: " . $item[$index_header]);
                                }
                            }
                        } elseif ($data['header'][$index_header] == 'tai_khoan') {
                            $quyen_index = array_search("quyen", $data['header']);
                            if ($quyen_index !== false) {
                                $user->role =  $item[$quyen_index];
                            }
                            $user->username = $item[$index_header];
                            $user->password = bcrypt($item[$index_header]);
                        } else {
                            $teacher->$columnName = $item[$index_header];
                            if ($data['header'][$index_header] == 'email') {
                                if ($user->username == null) {
                                    $quyen_index = array_search("quyen", $data['header']);
                                    if ($quyen_index !== false) {
                                        $user->role =  $item[$quyen_index];
                                    }
                                    $user->username = explode('@', $item[$index_header])[0];
                                    $user->password = bcrypt(explode('@', $item[$index_header])[0]);
                                }
                            }
                        }
                    }
                    $teacher->save();
                    $user->name = $teacher->full_name;
                    $user->teacher_id = $teacher->id;
                    $user->save();
                }
            } catch (\Throwable $th) {
                dd($th);
                DB::rollback();
                abort(404);
            }
            DB::commit();
            return true;
        }
        abort(404);
    }

    function resetPass($id)
    {
        try {
            $user = User::where('teacher_id', $id)->first();
            $user->update(["password" => bcrypt($user->username)]);
            return true;
        } catch (QueryException $e) {
            abort(404);
        }
    }
}
