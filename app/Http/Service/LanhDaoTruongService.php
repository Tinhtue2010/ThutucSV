<?php

namespace App\Http\Service;

use App\Http\Controllers\Controller;
use App\Models\Phieu;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class LanhDaoTruongService  extends Controller
{
    function xacnhanRHS($request, $stopStudy)
    {
        try {
            if ($stopStudy->status != 5 && $stopStudy->status != -6 && $stopStudy->status != 6) {
                abort(404);
            }
            if ($stopStudy->status == -6) {
                $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
                if ($newStopStudy) {
                    try {
                        $phieu = Phieu::find($newStopStudy->phieu_id);
                        if ($phieu) {
                            $phieu->delete();
                        }
                        $newStopStudy->delete();
                    } catch (\Exception $e) {
                    }
                } else {
                }
            }

            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->phieu_id = null;
            $newStopStudy->status = 1;
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->parent_id = $request->id;

            $user_id = User::where('student_id',$stopStudy->student_id)->first()->id;
            $this->notification("Đơn xin rút hồ sơ của bạn đã được lãnh đạo trường xác nhận", null, "RHS",$user_id);

            $newStopStudy->note = $request->note;


            $newStopStudy->save();
            $stopStudy->update(["status" => 6]);
            return true;
        } catch (QueryException $e) {
            abort(404);
        }
    }
    function tuchoihsRHS($request, $stopStudy)
    {
        try {
            if ($stopStudy->status != 5 && $stopStudy->status != -6 && $stopStudy->status != 6) {
                abort(404);
            }

            if ($request->button_clicked == "huy_phieu" && $stopStudy->status == -6) {
                $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
                if ($newStopStudy) {
                    try {
                        $phieu = Phieu::find($newStopStudy->phieu_id);
                        if ($phieu) {
                            $phieu->delete();
                        }
                        $newStopStudy->delete();
                        $stopStudy->update(["status" => 5]);

                        return true;
                    } catch (\Exception $e) {
                        abort(404);
                    }
                } else {
                    abort(404);
                }
            }
            if ($request->button_clicked == "huy_phieu") {
                return true;
            }
            $student = Student::find($stopStudy->student_id);

            $teacher = Teacher::find(Auth::user()->teacher_id);

            $content_phieu['giaovien'] = $teacher->full_name ?? '';
            $content_phieu['sinhvien'] = $student->full_name ?? '';
            $content_phieu['cmnd'] = $student->cmnd ?? '';
            $content_phieu['ngaycap'] = $student->date_range_cmnd ?? '';
            $content_phieu['sdt'] = $student->phone ?? '';
            $content_phieu['email'] = $student->email ?? '';

            if ($student->date_range_cmnd == null) {
                $content_phieu['ngaycap'] = '';
            } else {
                $formattedDate = Carbon::createFromFormat('Y-m-d', $student->date_range_cmnd)->format('d/m/Y');
                $content_phieu['ngaycap'] = $formattedDate;
            }

            $content_phieu['tao_day'] = Carbon::now()->day;
            $content_phieu['tao_month'] = Carbon::now()->month;
            $content_phieu['tao_year'] = Carbon::now()->year;

            $content_phieu['lydo'] = $request->lydo;

            $content_phieu['ndgiaiquyet'] = "đơn xin rút hồ sơ";
            $user_id = User::where('student_id',$stopStudy->student_id)->first()->id;
            $this->notification("Đơn xin rút hồ sơ của bạn bị từ chối bởi lãnh đạo trường", null, "RHS",$user_id);

            // if ($stopStudy->type == 0) {
            //     $content_phieu['ndgiaiquyet'] = "đơn xin rút hồ sơ";
            //     $this->notification("Đơn xin rút hồ sơ của bạn bị từ chối bởi lãnh đạo trường", null, "RHS");
            // }
            // if ($stopStudy->type == 1) {
            //     $content_phieu['ndgiaiquyet'] = "đơn xin miễn giảm học phí";
            //     $this->notification("Đơn xin miễn giảm học phí của bạn bị từ chối bởi lãnh đạo trường", null, "GHP");
            // }
            // if ($stopStudy->type == 2) {
            //     $content_phieu['ndgiaiquyet'] = "đơn xin trợ cấp xã hội";
            //     $this->notification("Đơn xin trợ cấp xã hội của bạn bị từ chối bởi lãnh đạo trường", null, "TCXH");
            // }

            // if ($stopStudy->type == 3) {
            //     $content_phieu['ndgiaiquyet'] = "đơn xin chế độ chính sách";
            //     $this->notification("Đơn xin chế độ chính sách của bạn bị từ chối bởi lãnh đạo trường", null, "CDCS");
            // }

            if ($stopStudy->status == 5 || $stopStudy->status == 6) {

                $phieu = new Phieu();
                $phieu->student_id = $stopStudy->student_id;
                $phieu->teacher_id = Auth::user()->teacher_id;
                $phieu->name = "Phiếu từ chối giải quyết hồ sơ";
                $phieu->key = "TCGQ";
                $phieu->content = json_encode($content_phieu);
                $phieu->save();

                $newStopStudy = $stopStudy->replicate();
                $newStopStudy->status = 0;
                $newStopStudy->teacher_id = Auth::user()->teacher_id;
                $newStopStudy->phieu_id = $phieu->id;
                $newStopStudy->parent_id = $request->id;
                $newStopStudy->note = "Đã bị từ chối bởi lãnh đạo trường";
                $newStopStudy->save();
                $stopStudy->update(["status" => -6]);
                return $phieu->id;
            }
            if ($stopStudy->status == -6) {
                $newStopStudy = $stopStudy->where('parent_id', $request->id)->orderBy('created_at', 'desc')->first();
                $newStopStudy->teacher_id = Auth::user()->teacher_id;
                $newStopStudy->note = "Đã bị từ chối bởi lãnh đạo trường";
                $newStopStudy->save();

                $phieu = Phieu::find($newStopStudy->phieu_id);
                $phieu->student_id = $stopStudy->student_id;
                $phieu->teacher_id = Auth::user()->teacher_id;
                $phieu->name = "Phiếu từ chối giải quyết hồ sơ";
                $phieu->key = "TCGQ";
                $phieu->content = json_encode($content_phieu);
                $phieu->save();
                $stopStudy->update(["status" => -6]);
                return $phieu->id;
            }
        } catch (\Throwable $th) {
            abort(404);
        }
    }
}
