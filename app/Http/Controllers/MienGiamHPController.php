<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Phieu;
use App\Models\StopStudy;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class MienGiamHPController extends Controller
{
    function index()
    {
        $user = Auth::user();
        $don_parent = StopStudy::where('student_id', $user->student_id)->where('type', 1)->first();
        if ($don_parent) {
            $phieu = Phieu::where('id', $don_parent->phieu_id)->first();
            $phieu = json_decode($phieu->content, true);
            $don = StopStudy::where('parent_id', $don_parent->id)->orderBy('created_at', 'desc')->first();
        } else {
            $phieu = null;
            $don = null;
        }

        $user = Auth::user();
        $student = Student::leftJoin('lops', 'students.lop_id', '=', 'lops.id')
            ->leftJoin('khoas', 'lops.khoa_id', '=', 'khoas.id')
            ->select('students.*', 'lops.name as lop_name', 'khoas.name as khoa_name')
            ->where('students.id', $user->student_id)->first();
        return view('mien_giam_hoc_phi.index', ['don_parent' => $don_parent, 'don' => $don, 'phieu' => $phieu, 'student' => $student]);
    }

    function CreateViewPdf(Request $request)
    {
        if ($request->button_clicked == "xem_truoc") {
            Session::put('noisinh', $request->noisinh);
            Session::put('doituong', $request->doituong);
            Session::put('daduochuong', $request->daduochuong);
        } else {
            $user = Auth::user();

            $student = Student::leftJoin('lops', 'students.lop_id', '=', 'lops.id')
                ->leftJoin('khoas', 'lops.khoa_id', '=', 'khoas.id')
                ->select('students.*', 'lops.name as lop_name', 'khoas.name as khoa_name')
                ->where('students.id', $user->student_id)->first();

            $studentData['full_name'] = $student->full_name;
            $studentData['student_code'] = $student->student_code;
            $studentData['date_of_birth'] = Carbon::createFromFormat('Y-m-d', $student->date_of_birth)->format('d/m/Y');
            $studentData['lop'] = $student->lop_name;
            $studentData['khoa'] = $student->khoa_name;
            $studentData['khoa_hoc'] = $student->school_year;
            $studentData['noisinh'] = $request->noisinh;
            switch ($request->doituong) {
                case 1:
                    $studentData['doituong'] = "Người có công với cách mạng và thân nhân của người có công với cách mạng theo Pháp lệnh số
                    02/2020/UBTVQH14 về ưu đãi người có công với cách mạng: Thân nhân của người có công với cách mạng bao gồm: Cha đẻ, 
                    mẹ đẻ, vợ hoặc chồng, con (con đẻ, con nuôi), người có công nuôi liệt sĩ.";
                    break;
                case 2:
                    $studentData['doituong'] = "Sinh viên bị khuyết tật.";
                    break;
                case 3:
                    $studentData['doituong'] = "Học sinh, sinh viên mồ côi cả cha lẫn mẹ; mồ côi cha hoặc mẹ, người còn lại rơi vào hoàn cảnh đặc biệt, cha mẹ
                    mất tích, … thời điểm mồ côi dưới 16 tuổi (Quy định tại Khoản 1 và Khoản 2 Điều 5, Nghị định 20/2021/NĐ-CP).";
                    break;
                case 4:
                    $studentData['doituong'] = "Học sinh, sinh viên học tại các cơ sở giáo dục nghề nghiệp và giáo dục đại học là người dân tộc thiểu số thuộc hộ
                    nghèo và hộ cận nghèo theo quy định của Thủ tướng Chính phủ (Sinh viên có số hộ nghèo và cận nghèo).";
                    break;
                case 5:
                    $studentData['doituong'] = "Học sinh, sinh viên người dân tộc thiểu số rất ít người (La hủ, La ha, Pà thẻn, Lự, Ngái, Chứt, Lô lô, Mảng, Cống, 
                    Cờ lao, Bố y, Si la, Pu péo, Rơ măm, Brâu, Ơ đu) ở vùng có điều kiện kinh tế – xã hội khó khăn và đặc biệt khó khăn.";
                    break;
                case 6:
                    $studentData['doituong'] = "Học sinh, sinh viên các chuyên ngành Múa, Biểu diễn nhạc cụ truyền thống.";
                    break;
                case 7:
                    $studentData['doituong'] = "Học sinh, sinh viên là người dân tộc thiểu số (không phải là dân tộc thiểu số rất ít người) ở thôn, bản đặc biệt khó
                    khăn, xã khu vực III vùng dân tộc miền núi, xã đặc biệt khó khăn vùng bãi ngang ven biển hải đảo theo quy định của cơ quan 
                    có thẩm quyền (Quy định tại QĐ 433/QĐ-UBMT ngày 18/6/2021; QĐ số 861/QĐ-TTg ngày 04/6/2021; 353/QĐ-TTg ngày 
                    15/3/2022).";
                    break;
                case 8:
                    $studentData['doituong'] = "Học sinh, sinh viên là con cán bộ, công nhân, viên chức mà cha hoặc mẹ bị tai nạn lao động hoặc mắc bệnh nghề
                    nghiệp được hưởng trợ cấp thường xuyên (Có QĐ và Giấy chứng nhận trợ cấp TNLĐ-BNN của Bảo hiểm xã hội cấp).";
                    break;
                default:
                    break;
            }
            $studentData['daduochuong'] = $request->daduochuong;
            $studentData['sdt'] = $student->phone;

            $studentData['day'] = Carbon::now()->day;

            $studentData['month'] = Carbon::now()->month;

            $studentData['year'] = Carbon::now()->year;

            $check = StopStudy::where('student_id', $user->student_id)->where('type', 1)->first();

            if ($check) {

                if (isset($check->files)) {
                    $this->deleteFiles(json_decode($check->files));
                }
                $check->files = json_encode($this->uploadListFile($request, 'files', 'mien_giam_hp'));

                $check->note = $request->data;
                $check->is_update = 1;
                $check->type_miengiamhp = $request->doituong ?? 1;
                $check->update();
                $phieu = Phieu::where('id', $check->phieu_id)->first();
                $phieu->student_id = $user->student_id;
                $phieu->name = "Đơn xin rút hồ sơ";
                $phieu->key = "GHP";
                $phieu->content = json_encode($studentData);
                $phieu->save();
            } else {
                $phieu = new Phieu();
                $phieu->student_id = $user->student_id;
                $phieu->name = "Đơn xin rút hồ sơ";
                $phieu->key = "GHP";
                $phieu->content = json_encode($studentData);
                $phieu->save();

                $query = new StopStudy();
                $query->files = json_encode($this->uploadListFile($request, 'files', 'mien_giam_hp'));
                $query->student_id = $user->student_id;
                $query->type_miengiamhp = $request->doituong ?? 1;
                $query->round = 1;
                $query->type = 1;
                $query->note = $request->data;
                $query->phieu_id = $phieu->id;
                $query->lop_id = $student->lop_id;

                $query->save();

                $this->notification("Đơn xin miễn giảm học phí của bạn đã được gửi, vui lòng chờ thông báo khác", $phieu->id, "GHP");
            }
        }
        return true;
    }
    function viewDemoPdf()
    {
        $noisinh = Session::get('noisinh');
        $doituong = Session::get('doituong');
        $daduochuong = Session::get('daduochuong');
        switch ($doituong) {
            case 1:
                $doituong = "Người có công với cách mạng và thân nhân của người có công với cách mạng theo Pháp lệnh số
                02/2020/UBTVQH14 về ưu đãi người có công với cách mạng: Thân nhân của người có công với cách mạng bao gồm: Cha đẻ, 
                mẹ đẻ, vợ hoặc chồng, con (con đẻ, con nuôi), người có công nuôi liệt sĩ.";
                break;
            case 2:
                $doituong = "Sinh viên bị khuyết tật.";
                break;
            case 3:
                $doituong = "Học sinh, sinh viên mồ côi cả cha lẫn mẹ; mồ côi cha hoặc mẹ, người còn lại rơi vào hoàn cảnh đặc biệt, cha mẹ
                mất tích, … thời điểm mồ côi dưới 16 tuổi (Quy định tại Khoản 1 và Khoản 2 Điều 5, Nghị định 20/2021/NĐ-CP).";
                break;
            case 4:
                $doituong = "Học sinh, sinh viên học tại các cơ sở giáo dục nghề nghiệp và giáo dục đại học là người dân tộc thiểu số thuộc hộ
                nghèo và hộ cận nghèo theo quy định của Thủ tướng Chính phủ (Sinh viên có số hộ nghèo và cận nghèo).";
                break;
            case 5:
                $doituong = "Học sinh, sinh viên người dân tộc thiểu số rất ít người (La hủ, La ha, Pà thẻn, Lự, Ngái, Chứt, Lô lô, Mảng, Cống, 
                Cờ lao, Bố y, Si la, Pu péo, Rơ măm, Brâu, Ơ đu) ở vùng có điều kiện kinh tế – xã hội khó khăn và đặc biệt khó khăn.";
                break;
            case 6:
                $doituong = "Học sinh, sinh viên các chuyên ngành Múa, Biểu diễn nhạc cụ truyền thống.";
                break;
            case 7:
                $doituong = "Học sinh, sinh viên là người dân tộc thiểu số (không phải là dân tộc thiểu số rất ít người) ở thôn, bản đặc biệt khó
                khăn, xã khu vực III vùng dân tộc miền núi, xã đặc biệt khó khăn vùng bãi ngang ven biển hải đảo theo quy định của cơ quan 
                có thẩm quyền (Quy định tại QĐ 433/QĐ-UBMT ngày 18/6/2021; QĐ số 861/QĐ-TTg ngày 04/6/2021; 353/QĐ-TTg ngày 
                15/3/2022).";
                break;
            case 8:
                $doituong = "Học sinh, sinh viên là con cán bộ, công nhân, viên chức mà cha hoặc mẹ bị tai nạn lao động hoặc mắc bệnh nghề
                nghiệp được hưởng trợ cấp thường xuyên (Có QĐ và Giấy chứng nhận trợ cấp TNLĐ-BNN của Bảo hiểm xã hội cấp).";
                break;
            default:
                break;
        }

        $user = Auth::user();
        $student = Student::leftJoin('lops', 'students.lop_id', '=', 'lops.id')
            ->leftJoin('khoas', 'lops.khoa_id', '=', 'khoas.id')
            ->select('students.*', 'lops.name as lop_name', 'khoas.name as khoa_name')
            ->where('students.id', $user->student_id)->first();

        $studentData['full_name'] = $student->full_name;
        $studentData['student_code'] = $student->student_code;
        $studentData['date_of_birth'] = Carbon::createFromFormat('Y-m-d', $student->date_of_birth)->format('d/m/Y');
        $studentData['lop'] = $student->lop_name;
        $studentData['khoa'] = $student->khoa_name;
        $studentData['khoa_hoc'] = $student->school_year;
        $studentData['noisinh'] = $noisinh;
        $studentData['doituong'] = $doituong;
        $studentData['daduochuong'] = $daduochuong;
        $studentData['sdt'] = $student->phone;

        $studentData['day'] = Carbon::now()->day;

        $studentData['month'] = Carbon::now()->month;

        $studentData['year'] = Carbon::now()->year;

        return view('document.miengiamhp', ['data' => $studentData]);
    }

    function viewPdf($id)
    {
        try {
            $user = Auth::user();
            $noti = Notification::where('user_id', $user->id)->where('id', $id)->first();
            if ($noti) {

                $data = Phieu::where('id', $noti->phieu_id)->first();
                return view('document.thoi_hoc', ['data' => json_decode($data->content, true)]);
            } else {
                abort(404);
            }
        } catch (\Throwable $th) {
            abort(404);
        }
    }
}
