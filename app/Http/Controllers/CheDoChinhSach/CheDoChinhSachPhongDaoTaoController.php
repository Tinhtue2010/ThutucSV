<?php

namespace App\Http\Controllers\CheDoChinhSach;

use App\Http\Controllers\Controller;
use App\Models\Lop;
use App\Models\StopStudy;
use Illuminate\Http\Request;

class CheDoChinhSachPhongDaoTaoController extends Controller
{
    function index()
    {
        $lop = Lop::get();
        return view('phong_dao_tao.create_ds_che_do_chinh_sach.index', ['lop' => $lop]);
    }

    function getData(Request $request)
    {
        $query = StopStudy::where('type', 4)
        ->studentActive()
        ->whereNull('parent_id')
            ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
            ->leftJoin('lops', 'students.lop_id', '=', 'lops.id')
            ->select('stop_studies.*', 'students.full_name', 'students.date_of_birth', 'students.student_code', 'lops.name as lop_name', 'lops.hocphi');

        
        if (isset($request->type_miengiamhp)) {
            $query->where('stop_studies.type_miengiamhp', $request->type_miengiamhp);
        }
        if (isset($request->year)) {
            $query->whereYear('stop_studies.created_at', $request->year);
        }
        if (isset($request->status)) {
            $query->where('status', $request->status);
        }
        $data = $this->queryPagination($request, $query, ['students.full_name', 'students.student_code']);

        return $data;
    }

    function updatePercent(Request $request)
    {
        $stopStudy =  StopStudy::find($request->id);
        $stopStudy->phantramgiam =  $request->phantramgiam;
        $stopStudy->save();
        return;
    }
    function createList(Request $request)
    {
        $query = StopStudy::where('type', 4)
        ->studentActive()
        ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
            ->where(function ($query) {
                $query->where('stop_studies.status', 1)
                    ->orWhere('stop_studies.status', 2);
            })
            ->select('stop_studies.*')
            ->whereNull('parent_id')
            ->update(['status' => 2]);
        $username = Auth::user()->name;
        $users = User::where('role',6)->get();
        foreach($users as $item)
        {
            $this->notification("Danh sách chế độ chính sách đã được tạo bởi ".$username, null, "GHP", $item->id);
        }
        return redirect()->back();
    }
    function deleteList(Request $request)
    {
        $query = StopStudy::where('type', 4)
        ->studentActive()
        ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
        ->where(function($query) {
            $query->where('stop_studies.status', 1)
                  ->orWhere('stop_studies.status', 2);
        })
        ->select('stop_studies.*')
        ->whereNull('parent_id')
        ->update(['status' => 1]);

        return redirect()->back();
    }

    function guiTBSV() {
        $query = StopStudy::where('type', 4)
        ->studentActive()
        ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
        ->whereNull('parent_id')
        ->where('stop_studies.status', 3)
        ->select('stop_studies.*')
        ->get();
        foreach ($query as $stopStudy) {
            $stopStudy->status = 4; 
            $stopStudy->save();  
            $user_id = User::where('student_id',$stopStudy->student_id)->first()->id;
            $this->notification("Cần kiểm tra lại thông tin trong danh sách miễn giảm học phí", null, "GHP", $user_id);
            
            $users = User::where(function($query) {
                $query->where('role', 2)
                      ->orWhere('role', 3);
            })->get();
            foreach($users as $item)
            {
                $this->notification("Danh sách chế độ chính sách đã bị từ chối bởi lãnh đạo phòng đào tạo ", null, "GHP", $item->id);
            }
            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->status = 1;
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->phieu_id = null;
            $newStopStudy->parent_id = $stopStudy->id;
            $newStopStudy->note = "Gửi thông báo về danh sách";
            $newStopStudy->save();
        }
        return redirect()->back();
    }
    function importFileKTX(Request $request)
    {
        set_time_limit(3600);
        if ($request->hasFile('csv_file')) {
            $data = $this->importCSV($request->file('csv_file'));
            $header = [
                "ma_hs" => "student_id",
                "ma_sinh_vien" => "student_code",
                "ho_ten" => "full_name",
                "ngay_sinh" => "date_of_birth",
                "gioi_tinh" => "gioitinh",
                "so_dt_ca_nhan" => "phone",
                "email" => "email",
                "ten_lop" => "lop_id",
                "he_tuyen_sinh" => "he_tuyen_sinh",
                "nganh_tuyen_sinh" => "nganh_tuyen_sinh",
                "trinh_do_van_hoa" => "trinh_do",
                "ngay_nhap_hoc" => "ngay_nhap_hoc",
                "ghi_chu_ho_so" => "note",
                "can_cuoc" => "cmnd",
                "ngay_cap_can_cuoc" => "date_range_cmnd",
            ];
            DB::beginTransaction();
            try {
                foreach ($data['data'] as $index => $item) {
                    $student = new Student();
                    foreach ($data['header'] as $index_header => $item_header) {
                        if (!isset($header[$data['header'][$index_header]])) {
                            continue;
                        }
                        $columnName = $header[$data['header'][$index_header]];
                        if ($data['header'][$index_header] == 'ten_lop') {
                            $lop = Lop::where('name', 'like', '%' . $item[$index_header] . '%')->first();
                            if ($lop) {
                                $student->lop_id = $lop->id;
                            } else {
                                throw new \Exception("Không tìm thấy lớp với tên: " . $item[$index_header]);
                            }
                        } else {
                            if ($columnName == "phone") {
                                $student->$columnName = '0' . $item[$index_header];
                            } else if ($columnName == "date_of_birth" || $columnName == "ngay_nhap_hoc" || $columnName == "date_range_cmnd") {
                                $student->$columnName = $this->convertDate('m/d/Y', $item[$index_header]);
                            } else if ($columnName == "gioitinh") {
                                if ($this->convertVietnamese($item[$index_header]) == 'nu') {
                                    $student->$columnName = 0;
                                }
                                if ($this->convertVietnamese($item[$index_header]) == 'nam') {
                                    $student->$columnName = 1;
                                }
                            } else if ($columnName == "email" && filter_var($item[$index_header], FILTER_VALIDATE_EMAIL)) {
                                $student->email = $item[$index_header];
                            } else {
                                $student->$columnName = $item[$index_header];
                            }
                        }
                    }
                    $student->save();
            
                    $user = new User();
            
                    $user->name = $student->full_name;
                    $user->username = $student->student_code;
                    $user->password = bcrypt($student->student_code);
                    $user->student_id = $student->id;
                    
                    $user->save();
                }
            } catch (\Exception $e) {
                DB::rollback();
                \Log::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]);
                // Show the error message to the user
                return response()->json([
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ], 500);
            }
            DB::commit();
            
            return true;
        }
        abort(404);
        return true;
    }
}
