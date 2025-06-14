<?php

namespace App\Helpers;

use App\Mail\SendMail;
use App\Models\Notification;
use App\Models\Otps;
use App\Models\Phieu;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use setasign\Fpdi\Fpdi;

class OAuth2Config
{
    public $client_id;
    public $client_secret;
    public $pc;

    public function __construct()
    {
        $this->client_id = config('services.vnpt.client_id');
        $this->client_secret = config('services.vnpt.client_secret');
        $this->pc = 'mac';
    }
}


trait CommonHelper
{

    public function importCSV($file)
    {
        $path = $file->getRealPath();
        $file = fopen($path, 'r');
        $header = [];
        $datas = [];

        while (($data = fgetcsv($file, 9000000, ',')) !== false) {
            if (empty($data)) continue;

            if (empty($header)) {
                $header = array_map([$this, 'convertVietnamese'], $data);
            } else {
                $tmpData = array_filter($data, fn($value) => $value !== "");
                if (!empty($tmpData)) {
                    $datas[] = $tmpData;
                } else {
                    break;
                }
            }
        }
        fclose($file);

        return ['header' => $header, 'data' => $datas];
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
            'à',
            'á',
            'ạ',
            'ả',
            'ã',
            'â',
            'ầ',
            'ấ',
            'ậ',
            'ẩ',
            'ẫ',
            'ă',
            'ằ',
            'ắ',
            'ặ',
            'ẳ',
            'ẵ',
            'è',
            'é',
            'ẹ',
            'ẻ',
            'ẽ',
            'ê',
            'ề',
            'ế',
            'ệ',
            'ể',
            'ễ',
            'ì',
            'í',
            'ị',
            'ỉ',
            'ĩ',
            'ò',
            'ó',
            'ọ',
            'ỏ',
            'õ',
            'ô',
            'ồ',
            'ố',
            'ộ',
            'ổ',
            'ỗ',
            'ơ',
            'ờ',
            'ớ',
            'ợ',
            'ở',
            'ỡ',
            'ù',
            'ú',
            'ụ',
            'ủ',
            'ũ',
            'ư',
            'ừ',
            'ứ',
            'ự',
            'ử',
            'ữ',
            'ỳ',
            'ý',
            'ỵ',
            'ỷ',
            'ỹ',
            'đ',
            'À',
            'Á',
            'Ạ',
            'Ả',
            'Ã',
            'Â',
            'Ầ',
            'Ấ',
            'Ậ',
            'Ẩ',
            'Ẫ',
            'Ă',
            'Ằ',
            'Ắ',
            'Ặ',
            'Ẳ',
            'Ẵ',
            'È',
            'É',
            'Ẹ',
            'Ẻ',
            'Ẽ',
            'Ê',
            'Ề',
            'Ế',
            'Ệ',
            'Ể',
            'Ễ',
            'Ì',
            'Í',
            'Ị',
            'Ỉ',
            'Ĩ',
            'Ò',
            'Ó',
            'Ọ',
            'Ỏ',
            'Õ',
            'Ô',
            'Ồ',
            'Ố',
            'Ộ',
            'Ổ',
            'Ỗ',
            'Ơ',
            'Ờ',
            'Ớ',
            'Ợ',
            'Ở',
            'Ỡ',
            'Ù',
            'Ú',
            'Ụ',
            'Ủ',
            'Ũ',
            'Ư',
            'Ừ',
            'Ứ',
            'Ự',
            'Ử',
            'Ữ',
            'Ỳ',
            'Ý',
            'Ỵ',
            'Ỷ',
            'Ỹ',
            'Đ'
        );

        $latin = array(
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'e',
            'e',
            'e',
            'e',
            'e',
            'e',
            'e',
            'e',
            'e',
            'e',
            'e',
            'i',
            'i',
            'i',
            'i',
            'i',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'u',
            'u',
            'u',
            'u',
            'u',
            'u',
            'u',
            'u',
            'u',
            'u',
            'u',
            'y',
            'y',
            'y',
            'y',
            'y',
            'd',
            'A',
            'A',
            'A',
            'A',
            'A',
            'A',
            'A',
            'A',
            'A',
            'A',
            'A',
            'A',
            'A',
            'A',
            'A',
            'A',
            'A',
            'E',
            'E',
            'E',
            'E',
            'E',
            'E',
            'E',
            'E',
            'E',
            'E',
            'E',
            'I',
            'I',
            'I',
            'I',
            'I',
            'O',
            'O',
            'O',
            'O',
            'O',
            'O',
            'O',
            'O',
            'O',
            'O',
            'O',
            'O',
            'O',
            'O',
            'O',
            'O',
            'O',
            'U',
            'U',
            'U',
            'U',
            'U',
            'U',
            'U',
            'U',
            'U',
            'U',
            'U',
            'Y',
            'Y',
            'Y',
            'Y',
            'Y',
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
                if (in_array(strtoupper($order_by), ['ASC', 'DESC'])) {
                    $orderColumn = $this->resolveOrderColumn($nameOrder);
                    $query = $query->orderBy($orderColumn, $order_by);
                }
            }

            if ($searchName !== [] && $search != '') {
                $query = $query->where(function ($query) use ($searchName, $search) {
                    foreach ($searchName as $field) {
                        $query->orWhere($field, 'like', '%' . $search . '%');
                    }
                });
            }

            $total_items = $query->count(); // Get total items after search/filter

            if ($per_page == 'all') {
                $results = $query->get()->toArray();
                $data = [
                    'data' => $results,
                    'page' => 1,
                    'max_page' => 1,
                    'total_items' => $total_items,
                    'current_page_items_count' => count($results),
                ];
            } else {
                $offset = ($page - 1) * $per_page;
                $max_page = ceil($total_items / $per_page);

                if ($page > $max_page && $max_page > 0) {
                    $page = 1;
                    $offset = 0;
                }

                $results = $query->skip($offset)->take($per_page)->get()->toArray();

                $data = [
                    'data' => $results,
                    'page' => $page,
                    'max_page' => $max_page,
                    'total_items' => $total_items,
                    'current_page_items_count' => count($results),
                ];
            }

            return $data;
        } catch (QueryException $e) {
            abort(404);
        }
    }


    /**
     * Resolve ambiguous column names by adding appropriate table prefixes
     */
    private function resolveOrderColumn($columnName)
    {
        $columnMappings = [
            'student_code' => 'students.student_code',
            'full_name' => 'students.full_name',
            'lop_name' => 'lops.name',
            // Add other potentially ambiguous columns here
        ];

        return $columnMappings[$columnName] ?? $columnName;
    }

    public function notification($notification, $phieu = null, $file_name = null, $type = null, $user_id = null)
    {
        $query = new Notification();
        $query->notification = $notification;
        $query->file_name = $file_name;
        $query->type = $type;
        $query->user_id = $user_id ?? Auth::user()->id;
        $query->save();
    }


    public function uploadListFile($request, $name, $folder, $type = 'file-')
    {
        $fileNames = [];

        if ($request->hasFile($name)) {
            foreach ($request->file($name) as $file) {
                $extension = $file->getClientOriginalExtension();
                $originalName = $file->getClientOriginalName();
                $newFileName = $folder . '/' . $type . date('Y-m-d') . '.' . $extension;
                Storage::putFileAs('public', $file, $newFileName);
                $fileNames[] = [$originalName, $newFileName];
            }
            return $fileNames;
        } else {
            return [];
        }
    }
    function textToImage($imagePath, $text, $outputPath, $fontSize = 20)
    {
        $fontFile = public_path('fonts/4.ttf'); // Đường dẫn font

        // Kiểm tra file ảnh có tồn tại không
        if (!file_exists($imagePath)) {
            die("Image file not found: " . $imagePath);
        }

        // Kiểm tra font có tồn tại không
        if (!file_exists($fontFile)) {
            die("Font file not found: " . $fontFile);
        }

        // Tải ảnh gốc
        $image = imagecreatefrompng($imagePath);
        if (!$image) {
            die("Failed to create image from file.");
        }

        // Lấy kích thước ảnh
        $imageWidth = imagesx($image);
        $imageHeight = imagesy($image);

        // Tạo vùng trống phía dưới ảnh để chứa chữ
        $newHeight = $imageHeight + 40; // Thêm 40px để chứa chữ
        $newImage = imagecreatetruecolor($imageWidth, $newHeight);

        // Màu nền trắng
        $white = imagecolorallocate($newImage, 255, 255, 255);

        // Đổ nền trắng cho ảnh mới
        imagefilledrectangle($newImage, 0, $imageHeight, $imageWidth, $newHeight, $white);

        // Copy ảnh gốc vào ảnh mới
        imagecopy($newImage, $image, 0, 0, 0, 0, $imageWidth, $imageHeight);

        // Màu chữ đen
        $black = imagecolorallocate($newImage, 0, 0, 0);

        // Tính toán vị trí để căn giữa chữ
        $textBox = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth = abs($textBox[4] - $textBox[0]);
        $textX = ($imageWidth - $textWidth) / 2; // Căn giữa theo chiều ngang
        $textY = $imageHeight + 30; // Đặt chữ dưới ảnh

        // Vẽ chữ lên ảnh mới
        imagettftext($newImage, $fontSize, 0, $textX, $textY, $black, $fontFile, $text);

        // Lưu ảnh kết quả
        imagepng($newImage, $outputPath);
        imagedestroy($newImage);
        imagedestroy($image);

        return $outputPath;
    }


    function mergeImageWithTextIntoPdf($pdfPath, $imagePath, $outputPath, $fullName, $pageNumber = 1, $x = 50, $y = 100, $height = 30)
    {
        $pdf = new FPDI();
        $pageCount = $pdf->setSourceFile($pdfPath);

        // Tạo ảnh mới với chữ dưới ảnh
        $textImagePath = public_path('temp_text_image.png');
        $this->textToImage($imagePath, $fullName, $textImagePath, 20);

        // Lấy kích thước ảnh gốc
        list($originalWidth, $originalHeight) = getimagesize($textImagePath);

        // Tính chiều rộng dựa trên tỷ lệ gốc
        $width = ($originalWidth / $originalHeight) * $height;

        for ($i = 1; $i <= $pageCount; $i++) {
            $tplIdx = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($tplIdx);
            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($tplIdx);

            // Chỉ chèn ảnh vào trang mong muốn
            if ($i == $pageNumber) {
                $pdf->Image($textImagePath, $x, $y, $width, $height); // Chèn ảnh với tỷ lệ chuẩn
            }
        }

        // Lưu file PDF mới
        $pdf->Output($outputPath, 'F');

        // Xóa ảnh tạm
        unlink($textImagePath);
    }
    public function saveBase64AsPdf($base64Data, $name, $mota = 'don_chung_tu_')
    {
        $user = Auth::user(); // Lấy thông tin người dùng
        $file_name = $mota . $user->id . '_' . date('d_Hi_Y') . '.pdf'; // Tạo tên file

        // Kiểm tra nếu không có dữ liệu
        if (!$base64Data) {
            return response()->json(['error' => 'Không có dữ liệu Base64'], 400);
        }


        // Giải mã base64 thành dữ liệu nhị phân
        $pdfData = base64_decode($base64Data);

        // Kiểm tra xem có lỗi trong quá trình giải mã không
        if ($pdfData === false) {
            return response()->json(['error' => 'Lỗi khi giải mã Base64'], 400);
        }

        // Đường dẫn lưu file
        // $filePath = storage_path("app/public/$name/a.pdf");
        $filePath = storage_path("app/public/$name/$file_name");


        // Tạo thư mục nếu chưa có
        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0777, true);
        }

        // Lưu file vào thư mục
        file_put_contents($filePath, $pdfData);

        return "$name/$file_name";
    }

    function deleteFiles($fileNames)
    {
        foreach ($fileNames as $fileName) {
            Storage::delete('public/' . $fileName[1]);
        }
        return true;
    }

    public function deletePdf($file_name)
    {
        $filePath = $file_name;
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath); // Xóa file


        } else {
        }
    }
    public function deletePdfAndTmp($file_name, $file_name_new)
    {
        $filePath = $file_name;
        if (Storage::disk('public')->exists($filePath)) {
            if ($file_name != $file_name_new) {
                Storage::disk('public')->delete($filePath); // Xóa file
            }
            $oldFilename = $filePath;
            $pathInfo = pathinfo($oldFilename);

            $newFilename = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '-tmp.pdf';
            Storage::disk('public')->delete($newFilename);
        } else {
        }
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
        $otpRecord = Otps::where('user_id', $user->id)->where('status', 1)->first();
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



    function getMaNganh($lop)
    {
        $mappings = [
            'NA' => '7220201',
            'NB' => '7220209',
            'HQ' => '7220210',
            'TQ' => '7220204',
            'LH' => '7810103',
            'KS' => '7810201',
            'AU' => '7810202',
            'QK' => '7340101',
            'TS' => '7620301',
            'MT' => '7850101',
            'VH' => '7229042',
            'KM' => '7480101',
            'CT' => '7480201',
            'ĐH' => '7210403',
            'STH' => '7140202',
            'SMN' => '7140201',
            'CM' => '51140201',
            'VBC' => '7229030',
            'CTN' => '6210225',
            'TTN' => '5210225',
            'THH' => '5210103',
            'TNP' => '5210217',
            'TNT' => '5210216'
        ];

        foreach ($mappings as $shortCode => $maNganh) {
            if (strpos($lop, $shortCode) !== false) {
                return $maNganh;
            }
        }

        return null;
    }

    function convertImageToBase64($imagePath)
    {
        $fullPath = public_path("storage/$imagePath");

        if (!file_exists($fullPath)) {
            return null; // Trả về null nếu file không tồn tại
        }

        $imageData = file_get_contents($fullPath);
        if ($imageData === false) {
            return null; // Trả về null nếu tải ảnh thất bại
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $fullPath);
        finfo_close($finfo);

        $base64 = base64_encode($imageData);

        return $base64;
    }
    function createPDF($phieu, $ngang = 0)
    {
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($options);

        if ($phieu->key == "RHS") {
            $html = view('document.thoi_hoc', ['data' => json_decode($phieu->content, true)])->render();
        }

        if ($phieu->key == "GHP") {
            $html =  view('document.miengiamhp', ['data' => json_decode($phieu->content, true)]);
        }

        if ($phieu->key == "TCGQ") {
            $html =  view('document.tuchoi', ['data' => json_decode($phieu->content, true)]);
        }

        if ($phieu->key == "TCXH") {
            $html =  view('document.trocapxahoi', ['data' => json_decode($phieu->content, true)]);
        }
        if ($phieu->key == "TCHP") {
            $html =  view('document.hotro_cpht', ['data' => json_decode($phieu->content, true)]);
        }
        if ($phieu->key == "CDCS") {
            $html =  view('document.chinhsach_qn', ['data' => json_decode($phieu->content, true)]);
        }
        if ($phieu->key == "HDBSSH") {
            $html =  view('document.huongdanbosung', ['data' => json_decode($phieu->content, true)])->render();
        }

        if ($phieu->key == "TNHS") {
            $html =  view('document.tiepnhan', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "DSMGHP") {
            $html =  view('document.theodoithongke.m01_02_05', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu, 'ngang' => $ngang]);
        }
        if ($phieu->key == "QDGHP") {
            $html =  view('document.theodoithongke.m01_02_06', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "PTGHP") {
            $html =  view('document.theodoithongke.m01_02_07', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "DSTCXH") {
            $html =  view('document.theodoithongke.m01_03_06', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu, 'ngang' => $ngang]);
        }
        if ($phieu->key == "QDTCXH") {
            $html =  view('document.theodoithongke.m01_03_07', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "PTTCXH") {
            $html =  view('document.theodoithongke.m01_03_10', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }

        if ($phieu->key == "DSTCHP") {
            $html =  view('document.theodoithongke.m01_03_08', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu, 'ngang' => $ngang]);
        }
        if ($phieu->key == "QDTCHP") {
            $html =  view('document.theodoithongke.m01_03_09', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "PTTCHP") {
            $html =  view('document.theodoithongke.m01_03_10_2', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "DSCDTA") {
            $html =  view('document.theodoithongke.m01_04_05', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu, 'ngang' => $ngang]);
        }
        if ($phieu->key == "QDCDTA") {
            $html =  view('document.theodoithongke.m01_04_06', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }


        if ($phieu->key == "DSCDHP") {
            $html =  view('document.theodoithongke.m01_04_07', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "QDCDHP") {
            $html =  view('document.theodoithongke.m01_04_08', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }


        if ($phieu->key == "DSCDKTX1") {
            $html =  view('document.theodoithongke.m01_04_09', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "DSCDKTX4") {
            $html =  view('document.theodoithongke.m01_04_10', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }

        if ($phieu->key == "QDCDKTX1") {
            $html =  view('document.theodoithongke.m01_04_11', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }
        if ($phieu->key == "QDCDKTX4") {
            $html =  view('document.theodoithongke.m01_04_12', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }

        if ($phieu->key == "PTQT4") {
            $html =  view('document.theodoithongke.m01_04_13', ['data' => json_decode($phieu->content, true), 'phieu' => $phieu]);
        }


        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        // Lưu vào bộ nhớ tạm thời
        $filePath = 'output.pdf';
        file_put_contents($filePath, $dompdf->output());

        // Đọc file PDF và chuyển thành Base64
        $base64Pdf = base64_encode(file_get_contents($filePath));

        // Xuất Base64
        return $base64Pdf;
    }

    function convertPdfToBase64($filePath)
    {
        $fullPath = storage_path('app/public/' . $filePath);

        if (file_exists($fullPath)) {
            return base64_encode(file_get_contents($fullPath));
        } else {
            return;
        }
    }
    function getGUID()
    {
        mt_srand((int)microtime() * 10000); //optional for php 4.2.0 and up.
        $charid = strtolower(md5(uniqid(rand(), true)));
        $hyphen = chr(45); // "-"
        $uuid = substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12);

        return $uuid;
    }
    function api_smartca($link, $data)
    {
        $config = new OAuth2Config();

        $curl = curl_init();
        $options = [
            CURLOPT_URL => $link,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/json'
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data)
        ];

        if ($config->pc === 'mac') {
            $options[CURLOPT_SSL_CIPHER_LIST] = 'DEFAULT@SECLEVEL=1';
        }


        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            // Nếu cURL bị lỗi (ví dụ không kết nối được)
            $error_msg = curl_error($curl);
            curl_close($curl);
            dd("Lỗi cURL: $error_msg");
        }

        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpcode != 200) {
            // Show response lỗi ra luôn
            dd([
                'HTTP Code' => $httpcode,
                'Response'  => $response
            ]);
        }

        // Nếu ok thì decode trả về
        return json_decode($response);
    }



    function getInfoSignature($cccd)
    {
        $config = new OAuth2Config();
        $data_getCertificate = [
            "sp_id" => $config->client_id,
            "sp_password" => $config->client_secret,
            "user_id" => $cccd,
            "transaction_id" => $this->getGUID()
        ];
        $msg_getCertificate = $this->api_smartca("https://gwsca.vnpt.vn/sca/sp769/v1/credentials/get_certificate", $data_getCertificate);
        // dd($data_getCertificate);
        if (empty($msg_getCertificate->data) || empty($msg_getCertificate->data->user_certificates)) {
            return false;
        } else {
            return $msg_getCertificate;
        }
    }

    function craeteSignature($msg_getCertificate, $pdf, $cccd, $file_name, $page = 1)
    {
        $certificates = $msg_getCertificate->data->user_certificates;

        foreach ($certificates as $certificate) {
            try {
                $result = $this->trySignWithCertificate($certificate, $pdf, $cccd, $file_name, $page);
                if ($result !== null) {
                    return $result;
                }
            } catch (Exception $e) {
                // Log error if needed, then try the next certificate
                continue;
            }
        }

        throw new Exception("Không thể ký với bất kỳ chứng thư nào.");
    }

    private function trySignWithCertificate($certificate, $pdf, $cccd, $file_name, $page)
    {
        $config = new OAuth2Config();

        $certBase64 = str_replace("\r\n", "", $certificate->cert_data);
        $serialNumber = $certificate->serial_number;

        $hashResult = $this->calculateHash($pdf, $certBase64, $page, $config);

        if ($hashResult['code'] !== "sigSuccess") {
            throw new Exception("Không thể calculateHash");
        }

        $signResult = $this->signHash(
            $hashResult['hash'],
            $file_name,
            $cccd,
            $serialNumber,
            $config
        );

        if ($signResult->status_code == 65000) {
            return null; // try next cert
        }

        return [$hashResult['fileID'], $signResult->data->transaction_id, $hashResult['transIdHash']];
    }

    private function calculateHash($pdf, $certBase64, $page, $config)
    {
        $data_calculate_hash = [
            "transaction_id" => $this->getGUID(),
            "sp_id" => $config->client_id,
            "sp_password" => $config->client_secret,
            "signerCert" => $certBase64,
            "digestAlgorithm" => "sha256",
            "sign_files" => [
                [
                    "storage_file_name" => "",
                    "name" => "test.pdf",
                    "pdfContent" => $pdf,
                    "sigOptions" => [
                        "renderMode" => 2,
                        "customImage" => "iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/wcAAwAB/UmMprQAAAAASUVORK5CYII=",
                        "fontSize" => 10,
                        "fontColor" => "#000000",
                        "signatureText" => " ",
                        "signatures" => [
                            [
                                "page" => $page,
                                "rectangle" => "0,581,200,657",
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $msg = $this->api_smartca("https://gwsca.vnpt.vn/rest/v2/signature/calculateHash", $data_calculate_hash);

        return [
            'code' => $msg->hashResps[0]->code ?? null,
            'hash' => $msg->hashResps[0]->hash ?? null,
            'fileID' => $msg->hashResps[0]->fileID ?? null,
            'transIdHash' => $msg->tranId ?? null
        ];
    }

    private function signHash($hashData, $file_name, $cccd, $serialNumber, $config)
    {
        $data_signhash = [
            "sp_id" => $config->client_id,
            "sp_password" => $config->client_secret,
            "user_id" => $cccd,
            "transaction_id" => $this->getGUID(),
            "sign_files" => [
                [
                    "data_to_be_signed" => bin2hex(base64_decode($hashData)),
                    "doc_id" => $file_name,
                    "file_type" => "pdf",
                    "sign_type" => "hash"
                ]
            ],
            "serial_number" => $serialNumber,
        ];

        return $this->api_smartca("https://gwsca.vnpt.vn/sca/sp769/v1/signatures/sign", $data_signhash);
    }



    function api_get_tranInfo_curl($url)
    {
        $config = new OAuth2Config();
        $curl = curl_init();

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/json'
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{}'
        ];

        if ($config->pc === 'mac') {
            $options[CURLOPT_SSL_CIPHER_LIST] = 'DEFAULT@SECLEVEL=1';
        }

        curl_setopt_array($curl, $options);


        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $msg = json_decode($response);
        curl_close($curl);

        return $msg;
    }

    function api_service_get_hash($url, $data)
    {
        $config = new OAuth2Config();
        $curl = curl_init();

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/json'
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data)
        ];

        if ($config->pc === 'mac') {
            $options[CURLOPT_SSL_CIPHER_LIST] = 'DEFAULT@SECLEVEL=1';
        }

        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $msg = json_decode($response);
        curl_close($curl);
        if ($httpcode != 200) {
            print_r('<pre>');
            print_r($response);
            print_r('</pre>');
            exit();
        }
        return $msg;
    }


    function getPDF($fileId, $tranId, $transIDHash)
    {
        try {
            $config = new OAuth2Config();
            $msg = $this->api_get_tranInfo_curl("https://gwsca.vnpt.vn/sca/sp769/v1/signatures/sign/" . $tranId . "/status");
            if ($msg == null) {
                return 0;
            }
            if ($msg->message != "SUCCESS") {
                return 0;
            }
            $hashSigned = $msg->data->signatures[0]->signature_value;

            // 5. signExternal
            $data_signExternal = [
                "tranId" => $transIDHash,
                "sp_id" => $config->client_id,
                "sp_password" => $config->client_secret,
                "signatures" => [
                    [
                        "fileID" => $fileId,
                        "signature" => $hashSigned
                    ]
                ]
            ];

            $msg_signExternal = $this->api_service_get_hash("https://gwsca.vnpt.vn/rest/v2/signature/signExternal", $data_signExternal);


            return $msg_signExternal->signResps[0]->signedData;
        } catch (\Throwable $th) {
            return 0;
        }
    }
    public static function convertNumberToVietnameseWords($number)
    {
        $textNumbers = [
            0 => 'không',
            1 => 'một',
            2 => 'hai',
            3 => 'ba',
            4 => 'bốn',
            5 => 'năm',
            6 => 'sáu',
            7 => 'bảy',
            8 => 'tám',
            9 => 'chín',
        ];

        $units = ['', 'nghìn', 'triệu', 'tỷ', 'nghìn tỷ', 'triệu tỷ'];

        if ($number == 0) {
            return 'Không đồng';
        }

        $number = str_pad($number, ceil(strlen($number) / 3) * 3, '0', STR_PAD_LEFT);
        $groups = str_split($number, 3);
        $result = [];
        $unitIndex = count($groups) - 1;

        foreach ($groups as $group) {
            $hundreds = (int)$group[0];
            $tens = (int)$group[1];
            $ones = (int)$group[2];

            $groupText = '';

            // Hundreds
            if ($hundreds > 0 || ($hundreds == 0 && ($tens > 0 || $ones > 0))) {
                $groupText .= $textNumbers[$hundreds] . ' trăm';
            }

            // Tens
            if ($tens > 1) {
                $groupText .= ' ' . $textNumbers[$tens] . ' mươi';
                if ($ones == 1) {
                    $groupText .= ' mốt';
                } elseif ($ones == 5) {
                    $groupText .= ' lăm';
                } elseif ($ones > 0) {
                    $groupText .= ' ' . $textNumbers[$ones];
                }
            } elseif ($tens == 1) {
                $groupText .= ' mười';
                if ($ones == 1) {
                    $groupText .= ' một';
                } elseif ($ones == 5) {
                    $groupText .= ' lăm';
                } elseif ($ones > 0) {
                    $groupText .= ' ' . $textNumbers[$ones];
                }
            } elseif ($tens == 0 && $ones > 0) {
                $groupText .= ' lẻ ' . $textNumbers[$ones];
            }

            if (trim($groupText) != '') {
                $result[] = trim($groupText) . ' ' . $units[$unitIndex];
            }

            $unitIndex--;
        }

        return ucfirst(trim(implode(' ', $result))) . ' đồng';
    }
    function sanitizeFileName($fileName)
    {
        // Replace slashes and other illegal characters
        return preg_replace('/[\/:*?"<>|]/', '-', $fileName);
    }
}
