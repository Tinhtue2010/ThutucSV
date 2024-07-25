<?php

namespace App\Helpers;

use App\Mail\SendMail;
use App\Models\Notification;
use App\Models\otps;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait CommonHelper
{
    public function importCSV($file)
    {
        $path = $file->getRealPath();

        $file = fopen($path, 'r');
        $row = 1;
        $header = [];
        $datas = [];
        while (($data = fgetcsv($file, 9000000, ',')) !== false) {
            if ($data !== null) {
                $i = 0;
                $tmpData = [];
                while (isset($data[$i])) {
                    if ($row == 1) {
                        $header[] = $this->convertVietnamese($data[$i]);
                    } else {
                        try {
                            $tmpData[] = $data[$i];
                        } catch (Exception $e) {
                            break;
                        }
                    }
                    $i++;
                }
                if ($row > 1) {
                    if (!$this->isAllEmptyStringsArray($tmpData)) {
                        $datas[] = $tmpData;
                    } else {
                        break;
                    }
                }
                $row++;
            } else {
                break;
            }
        }
        fclose($file);
        $dataFile['header'] = $header;
        $dataFile['data'] = $datas;

        return $dataFile;
    }
    function isAllEmptyStringsArray($data)
    {
        return is_array($data) && count(array_filter($data, function ($value) {
            return $value !== "";
        })) === 0;
    }
    function convertVietnamese($str)
    {
        $vietnamese = array(
            'à', 'á', 'ạ', 'ả', 'ã', 'â', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ',
            'è', 'é', 'ẹ', 'ẻ', 'ẽ', 'ê', 'ề', 'ế', 'ệ', 'ể', 'ễ',
            'ì', 'í', 'ị', 'ỉ', 'ĩ',
            'ò', 'ó', 'ọ', 'ỏ', 'õ', 'ô', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ',
            'ù', 'ú', 'ụ', 'ủ', 'ũ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ',
            'ỳ', 'ý', 'ỵ', 'ỷ', 'ỹ',
            'đ',
            'À', 'Á', 'Ạ', 'Ả', 'Ã', 'Â', 'Ầ', 'Ấ', 'Ậ', 'Ẩ', 'Ẫ', 'Ă', 'Ằ', 'Ắ', 'Ặ', 'Ẳ', 'Ẵ',
            'È', 'É', 'Ẹ', 'Ẻ', 'Ẽ', 'Ê', 'Ề', 'Ế', 'Ệ', 'Ể', 'Ễ',
            'Ì', 'Í', 'Ị', 'Ỉ', 'Ĩ',
            'Ò', 'Ó', 'Ọ', 'Ỏ', 'Õ', 'Ô', 'Ồ', 'Ố', 'Ộ', 'Ổ', 'Ỗ', 'Ơ', 'Ờ', 'Ớ', 'Ợ', 'Ở', 'Ỡ',
            'Ù', 'Ú', 'Ụ', 'Ủ', 'Ũ', 'Ư', 'Ừ', 'Ứ', 'Ự', 'Ử', 'Ữ',
            'Ỳ', 'Ý', 'Ỵ', 'Ỷ', 'Ỹ',
            'Đ'
        );

        $latin = array(
            'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
            'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
            'i', 'i', 'i', 'i', 'i',
            'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
            'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
            'y', 'y', 'y', 'y', 'y',
            'd',
            'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A',
            'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E',
            'I', 'I', 'I', 'I', 'I',
            'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O',
            'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U',
            'Y', 'Y', 'Y', 'Y', 'Y',
            'D'
        );
        if (substr($str, 0, 3) == "\xEF\xBB\xBF") {
            $str = substr($str, 3);
        }
        $str = str_replace($vietnamese, $latin, $str);

        $str = strtolower($str);

        $str = str_replace(' ', '_', $str);

        return $str;
    }
    
    public function convertDate($format, $date)
    {
        try {
            return Carbon::createFromFormat($format, $date)->format('Y-m-d');
        } catch (\Throwable $th) {
            return null;
        }
    }
    function getDateNow()
    {
        $currentDate = Carbon::now();
        $day = $currentDate->day;
        $month = $currentDate->month;
        $year = $currentDate->year;
        return "$day/$month/$year";
    }
    function giaiQuyetCongViec($ykien, $stopStudy, $type = 1)
    {
        $teacher = Teacher::find(Auth::user()->teacher_id);
        $data['thoigian'] = $this->getDateNow();
        $data['hoten'] = $teacher->full_name;
        $data['chu_ky'] = $teacher->chu_ky;
        $data['data'] = $ykien;
        if ($type == 1) {
            $stopStudy->update(['tiepnhan' => json_encode($data)]);
        }
        if ($type == 2) {
            $stopStudy->update(['ykien' => json_encode($data)]);
        }
        if ($type == 3) {
            $stopStudy->update(['lanhdaophong' => json_encode($data)]);
        }
        if ($type == 4) {
            $stopStudy->update(['lanhdaotruong' => json_encode($data)]);
        }
    }
    public function queryPagination($request, $query, $searchName = [])
    {

        $per_page = $request->per_page ?? 10;
        $page = $request->page ?? 1;
        $offset = 0;
        $nameOrder = $request->order_name ?? null;
        $order_by = $request->order_by ?? null;
        $search = $request->search ?? '';

        try {
            if (isset($nameOrder) && isset($order_by)) {
                if ($order_by == 'ASC' || $order_by == 'DESC' || $order_by == 'asc' || $order_by == 'desc') {
                    $query = $query->orderBy($nameOrder, $order_by);
                }
            }
            if ($searchName !== [] && $search != '') {

                $query = $query->where(function ($query) use ($searchName, $search) {
                    foreach ($searchName as $field) {
                        $query->orWhere($field, 'like', '%' . $search . '%');
                    }
                });
            }
            if ($per_page == 'all') {
                $query = $query->get()->toArray();

                $data['max_page'] = 1;
                $data['data'] = $query;
                $data['page'] = 1;
            } else {
                $offset = ($page - 1) * $per_page;
                $max_page = clone $query;
                $max_page = ceil($max_page->count() / $per_page);
                if ($page > $max_page) {
                    $page = 1;
                    $offset = 0;
                }
                $query = $query->skip($offset)->take($per_page)->get()->toArray();

                $data['max_page'] = $max_page;
                $data['data'] = $query;
                $data['page'] = $page;
            }

            return $data;
        } catch (QueryException $e) {
            abort(404);
        }
    }

    public function notification($notification, $phieu = null, $type = null, $user_id = null)
    {
        $query = new Notification();
        $query->notification = $notification;
        $query->phieu_id = $phieu;
        $query->type = $type;
        $query->user_id = $user_id ?? Auth::user()->id;
        $query->save();
    }


    public function uploadListFile($request, $name, $folder)
    {
        $fileNames = [];

        if ($request->hasFile($name)) {
            foreach ($request->file($name) as $file) {
                $extension = $file->getClientOriginalExtension();
                $originalName = $file->getClientOriginalName();
                $newFileName = $folder . '/' . date('Y-m-d') . '-' . uniqid() . '.' . $extension;
                Storage::putFileAs('public', $file, $newFileName);
                $fileNames[] = [$originalName, $newFileName];
            }
            return $fileNames;
        } else {
            return [];
        }
    }

    function deleteFiles($fileNames)
    {
        foreach ($fileNames as $fileName) {
            Storage::delete('public/' . $fileName[1]);
        }
        return true;
    }

    function sendOTP($loai = null)
    {
        $user = Auth::user();
        if ($user->teacher_id != null) {
            $email = Teacher::find($user->teacher_id)->email;
        } else {
            $email = Student::find($user->student_id)->email;
        }
        if ($email == null) {
            return false;
        }
        $randomString = Str::random(6);
        $data = [
            "otp" => $randomString,
            "email" => $email,
            'loai' => $loai
        ];
        // try {
        //     Mail::to($email)->send(new SendMail("Thông báo xác nhận chữ ký", 'mail.otp', $data));
        // } catch (\Throwable $th) {
        //     abort(404);
        // }

        otps::updateOrCreate(
            ['user_id' => $user->id],
            ['otp' => $randomString],
            ['status' => 0]
        );
        return true;
    }

    public function checkOtp($otp)
    {
        $user = Auth::user();
        $otpRecord = Otps::where('user_id', $user->id)->first();
        if (!$otpRecord) {
            return false;
        }
        $createdAt = Carbon::parse($otpRecord->updated_at);
        $expirationTime = $createdAt->addSeconds(30);
        if (Carbon::now()->gt($expirationTime)) {
            return false;
        }
        if ($otpRecord->otp === $otp) {
            $otpRecord->status = 1;
            $otpRecord->save();
            return true;
        }
        return false;
    }
    public function checkOtpApi($otp)
    {
        $user = Auth::user();
        $otpRecord = Otps::where('user_id', $user->id)->where('status',1)->first();
        if (!$otpRecord) {
            return false;
        }
        $createdAt = Carbon::parse($otpRecord->updated_at);
        $expirationTime = $createdAt->addSeconds(60);
        if (Carbon::now()->gt($expirationTime)) {
            return false;
        }
        if ($otpRecord->otp === $otp) {
            return true;
        }
        return false;
    }
}
