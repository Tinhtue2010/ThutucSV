<?php

namespace App\Http\Controllers;

use App\Exports\TheoDoiKQGiaiQuyetCheDoTCXHExport;
use App\Exports\MienGiamHocPhiSinhVienExport;
use App\Exports\HoTroChiPhiHocTapExport;
use App\Exports\CheDoMienPhiChoOKytucXaExport;
use App\Exports\CheDoHoTroHocPhiChoSinhVienExport;
use App\Exports\CheDoHoTroDoDungHocTapExport;
use App\Exports\DanhSachDuocHuongTroCapXaHoiExport;
use App\Exports\DanhSachHuongHoTroTienAnExport;
use App\Exports\DanhSachHuongMienPhiChoOKTXExport;
use App\Exports\DanhSachMienGiamHocPhiExport;
use App\Exports\SoTheoDoiSVRutHoSoExport;
use App\Models\HoSo;
use App\Models\Phieu;
use App\Models\StopStudy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;

class HoSoChungTuPhongDaoTaoController extends Controller
{
    function index()
    {
        return view('phong_dao_tao.ho_so_chung_tu.index');
    }

    function getData(Request $request)
    {
        $query = HoSo::query();

        if (!empty($request->type) && $request->type != 'all') {
            $query->where('type', $request->type);
        }

        if (!empty($request->year) && $request->year != 'all') {
            $query->where('nam_hoc', $request->year);
        }
        $data = $this->queryPagination($request, $query, ['name', 'student_code']);
        return $data;
    }

    function saveAll(Request $request)
    {
        DB::beginTransaction();
        try {

            if (isset($request->RHS)) {
                $query = StopStudy::where('type', 0)
                    ->where('stop_studies.status',  6)
                    ->studentActive()
                    ->whereNull('parent_id')
                    ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
                    ->leftJoin('lops', 'students.ma_lop', '=', 'lops.ma_lop')
                    ->select('stop_studies.*', 'students.full_name', 'students.date_of_birth', 'students.student_code', 'lops.name as lop_name', 'students.hocphi')
                    ->get();

                foreach ($query as $item) {

                    $hoso = new HoSo();
                    $hoso->name = 'Rút hồ sơ SV : ' . $item->student_code . '-' . $item->full_name;
                    $hoso->file_name = $item->file_name;
                    $files = json_decode($item->files, true);

                    if (is_array($files) && isset($files[1])) {
                        $hoso->file_list = $files[0][1];
                    } else {
                        $hoso->file_list = null;
                    }

                    $createdAt = \Carbon\Carbon::parse($item->created_at);
                    $month = $createdAt->month;
                    $year = $createdAt->year;

                    if ($month >= 1 && $month <= 6) {
                        $hoso->ky_hoc = 2;
                        $hoso->nam_hoc = ($year - 1) . '-' . $year;
                    } else {
                        $hoso->ky_hoc = 1;
                        $hoso->nam_hoc = $year . '-' . ($year + 1);
                    }


                    $hoso->type = 1;
                    $hoso->student_code = $item->student_code;
                    $hoso->data = json_encode($item);
                    $hoso->save();


                    StopStudy::where('parent_id', $item->id)->delete();
                    $item->delete();
                }
            }

            if (isset($request->DHP)) {

                $query = StopStudy::where('type', 1)
                    ->where('stop_studies.status',  -99)
                    ->studentActive()
                    ->whereNull('parent_id')
                    ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
                    ->leftJoin('lops', 'students.ma_lop', '=', 'lops.ma_lop')
                    ->select('stop_studies.*', 'students.full_name', 'students.date_of_birth', 'students.student_code', 'lops.name as lop_name', 'students.hocphi')
                    ->get();

                foreach ($query as $item) {

                    $hoso = new HoSo();
                    $hoso->name = 'Đơn xin miễn giảm học phí : ' . $item->student_code . '-' . $item->full_name;
                    $hoso->file_name = $item->file_name;
                    $files = json_decode($item->files, true);

                    if (is_array($files) && isset($files[0][1])) {
                        $hoso->file_list = $files[0][1];
                    } else {
                        $hoso->file_list = null;
                    }

                    $createdAt = \Carbon\Carbon::parse($item->created_at);
                    $month = $createdAt->month;
                    $year = $createdAt->year;

                    if ($month >= 1 && $month <= 6) {
                        $hoso->ky_hoc = 2;
                        $hoso->nam_hoc = ($year - 1) . '-' . $year;
                    } else {
                        $hoso->ky_hoc = 1;
                        $hoso->nam_hoc = $year . '-' . ($year + 1);
                    }


                    $hoso->type = 2;
                    $hoso->student_code = $item->student_code;
                    $hoso->data = json_encode($item);

                    $hoso->save();


                    StopStudy::where('parent_id', $item->id)->delete();
                    StopStudy::where('student_id', $item->student_id)->where('type', 1)->delete();
                    $item->delete();
                }
            }

            if (isset($request->TCXH)) {
                $query = StopStudy::whereIn('type', [2, 3])
                    ->where('stop_studies.status',  -99)
                    ->studentActive()
                    ->whereNull('parent_id')
                    ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
                    ->leftJoin('lops', 'students.ma_lop', '=', 'lops.ma_lop')
                    ->select('stop_studies.*', 'students.full_name', 'students.date_of_birth', 'students.student_code', 'lops.name as lop_name', 'students.hocphi')
                    ->get();

                foreach ($query as $item) {
                    $hoso = new HoSo();

                    if ($item->type == 2) {
                        $hoso->name = 'Đơn xin trợ cấp xã hội : ' . $item->student_code . '-' . $item->full_name;
                        $hoso->type = 3;
                    }

                    if ($item->type == 3) {
                        $hoso->name = 'Đơn xin trợ cấp học phí : ' . $item->student_code . '-' . $item->full_name;
                        $hoso->type = 4;
                    }
                    $hoso->file_name = $item->file_name;
                    $files = json_decode($item->files, true);

                    if (is_array($files) && isset($files[0][1])) {
                        $hoso->file_list = $files[0][1];
                    } else {
                        $hoso->file_list = null;
                    }

                    $createdAt = \Carbon\Carbon::parse($item->created_at);
                    $month = $createdAt->month;
                    $year = $createdAt->year;

                    if ($month >= 1 && $month <= 6) {
                        $hoso->ky_hoc = 2;
                        $hoso->nam_hoc = ($year - 1) . '-' . $year;
                    } else {
                        $hoso->ky_hoc = 1;
                        $hoso->nam_hoc = $year . '-' . ($year + 1);
                    }


                    $hoso->student_code = $item->student_code;
                    $hoso->data = json_encode($item);
                    $hoso->save();


                    StopStudy::where('parent_id', $item->id)->delete();

                    if ($item->type == 2) {
                        StopStudy::where('student_id', $item->student_id)->where('type', 2)->delete();
                    }

                    if ($item->type == 3) {
                        StopStudy::where('student_id', $item->student_id)->where('type', 3)->delete();
                    }
                    $item->delete();
                }
            }
            if (isset($request->CDCS)) {
                $query = StopStudy::where('type', 4)
                    ->where('stop_studies.status',  -99)
                    ->studentActive()
                    ->whereNull('parent_id')
                    ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
                    ->leftJoin('lops', 'students.ma_lop', '=', 'lops.ma_lop')
                    ->select('stop_studies.*', 'students.full_name', 'students.date_of_birth', 'students.student_code', 'lops.name as lop_name', 'students.hocphi')
                    ->get();

                foreach ($query as $item) {

                    $hoso = new HoSo();
                    $hoso->name = 'Đơn xin chế độ chính sách : ' . $item->student_code . '-' . $item->full_name;
                    $hoso->file_name = $item->file_name;
                    $files = json_decode($item->files, true);

                    if (is_array($files) && isset($files[0][1])) {
                        $hoso->file_list = $files[0][1];
                    } else {
                        $hoso->file_list = null;
                    }

                    $createdAt = \Carbon\Carbon::parse($item->created_at);
                    $month = $createdAt->month;
                    $year = $createdAt->year;

                    if ($month >= 1 && $month <= 6) {
                        $hoso->ky_hoc = 2;
                        $hoso->nam_hoc = ($year - 1) . '-' . $year;
                    } else {
                        $hoso->ky_hoc = 1;
                        $hoso->nam_hoc = $year . '-' . ($year + 1);
                    }


                    $hoso->type = 5;
                    $hoso->student_code = $item->student_code;
                    $hoso->data = json_encode($item);
                    $hoso->save();


                    StopStudy::where('parent_id', $item->id)->delete();
                    StopStudy::where('student_id', $item->student_id)->where('type', 4)->delete();
                    $item->delete();
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => "Có lỗi khi thêm dữ liệu",
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                // 'trace' => $e->getTrace() // nếu muốn full trace
            ], 500);
        }
        DB::commit();
    }

    function download(Request $request)
    {
        $ky = $request->input('ky');
        $namHoc = $request->input('nam_hoc');
        $taiHoSo = $request->input('tai_ho_so');
        $loaiHoSo = $request->input('loai_ho_so');
        $query = HoSo::query();

        if ($ky && $ky != 'all') {
            $query->where('ky_hoc', $ky);
        }

        if ($namHoc && $namHoc != 'all') {
            $query->where('nam_hoc', $namHoc);
        }


        switch ($loaiHoSo) {
            case 1:
                $fileName = $this->sanitizeFileName('SỔ THEO DÕI SINH VIÊN RÚT HỒ SƠ.xlsx');
                $data  = [];

                $query = $query->where('type', 1)->get();

                foreach ($query as $item) {
                    $decodedData = json_decode($item->data, true);

                    $createdAt = Carbon::parse($item->created_at)->format('d-m-Y');
                    if ($decodedData) {
                        $data[] = [
                            'full_name' => $decodedData['full_name'] ?? null,
                            'date_of_birth' => Carbon::parse($decodedData['date_of_birth'])->format('d-m-Y') ?? null,
                            'lop_name' => $decodedData['lop_name'] ?? null, // Lớp
                            'created_at' => $createdAt, // Thời gian tạo hồ sơ
                        ];
                    }
                }

                return Excel::download(new SoTheoDoiSVRutHoSoExport($data), $fileName);
            case 2:
                $data  = [];
                $fileName = $this->sanitizeFileName('SỔ THEO DÕI KẾT QUẢ GIẢI QUYẾT CHẾ ĐỘ MIỄN GIẢM HỌC PHÍ CHO SINH VIÊN.xlsx');
                return Excel::download(new MienGiamHocPhiSinhVienExport($data), $fileName);
            case 3:
                $data  = [];
                $fileName = $this->sanitizeFileName('SỔ THEO DÕI KẾT QUẢ GIẢI QUYẾT CHẾ ĐỘ TRỢ CẤP XÃ HỘI CHO SINH VIÊN.xlsx');
                return Excel::download(new TheoDoiKQGiaiQuyetCheDoTCXHExport($data), $fileName);
            case 4:
                $data  = [];
                $fileName = $this->sanitizeFileName('SỔ THEO DÕI KẾT QUẢ GIẢI QUYẾT CHẾ ĐỘ HỖ TRỢ CHI PHÍ HỌC TẬP CHO SINH VIÊN.xlsx');
                return Excel::download(new HoTroChiPhiHocTapExport($data), $fileName);
            case 5:
                $data  = [];
                $fileName = $this->sanitizeFileName('SỔ THEO DÕI KẾT QUẢ GIẢI QUYẾT CHẾ ĐỘ MIỄN PHÍ CHỖ Ở KÝ TÚC XÁ CHO SINH VIÊN THEO NGHỊ QUYẾT 35/2021/NQ-HĐND.xlsx');
                return Excel::download(new CheDoMienPhiChoOKytucXaExport($data), $fileName);
            case 6:
                $data  = [];
                $fileName = $this->sanitizeFileName('SỔ THEO DÕI KẾT QUẢ GIẢI QUYẾT CHẾ ĐỘ CHẾ ĐỘ HỖ TRỢ HỌC PHÍ CHO SINH VIÊN THEO NGHỊ QUYẾT 35/2021/NQ-HĐND.xlsx');
                return Excel::download(new CheDoHoTroHocPhiChoSinhVienExport($data), $fileName);
            case 7:
                $data  = [];
                $fileName = $this->sanitizeFileName('SỔ THEO DÕI KẾT QUẢ GIẢI QUYẾT CHẾ ĐỘ CHẾ ĐỘ HỖ TRỢ ĐỒ DÙNG HỌC TẬP CHO SINH VIÊN THEO NGHỊ QUYẾT 35/2021/NQ-HĐND.xlsx');
                return Excel::download(new CheDoHoTroDoDungHocTapExport($data), $fileName);






                // case 2:
                //     $fileName = $this->sanitizeFileName('DANH SÁCH SINH VIÊN ĐƯỢC MIỄN, GIẢM HỌC PHÍ THEO NGHỊ ĐỊNH 81/2021/NĐ-CP.xlsx');
                //     $query = $query->where('type', 2)->whereNotNull('list_info')->first('list_info');
                //     $query = json_decode($query?->list_info);
                //     $data = [];

                //     if (is_array($query)) {
                //         foreach ($query as $item) {
                //             $data[] = [
                //                 $item->ho_ten,
                //                 $item->ngay_sinh,
                //                 $item->lop,
                //                 $item->doi_tuong,
                //                 $item->muc_hoc_phi,
                //                 $item->ti_le_giam . '%',
                //                 $item->so_tien_giam_1_thang,
                //                 $item->mien_giam_ky,

                //             ];
                //         }
                //     }
                //     return Excel::download(new DanhSachMienGiamHocPhiExport($data), $fileName);
                //     break;
                // case 3:
                //     $fileName = $this->sanitizeFileName('DANH SÁCH SINH VIÊN ĐƯỢC HƯỞNG TRỢ CẤP XÃ HỘI.xlsx');
                //     $query = $query->where('type', 3)->whereNotNull('list_info')->first('list_info');
                //     $query = json_decode($query?->list_info);
                //     $data = [];

                //     if (is_array($query)) {
                //         foreach ($query as $item) {
                //             foreach ($query as $item) {
                //                 $data[] = [
                //                     $item->ho_ten,
                //                     $item->ngay_sinh,
                //                     $item->lop,
                //                     $item->doi_tuong,
                //                     $item->muc_tro_cap_xh,
                //                     $item->tro_cap_ky
                //                 ];
                //             }
                //         }
                //     }
                //     return Excel::download(new DanhSachDuocHuongTroCapXaHoiExport($data), $fileName);
                //     break;
                // case 4:
                //     $fileName = $this->sanitizeFileName('DANH SÁCH SINH VIÊN ĐƯỢC HƯỞNG TRỢ CẤP XÃ HỘI.xlsx');
                //     $query = $query->where('type', 4)->whereNotNull('list_info')->first('list_info');
                //     $query = json_decode($query?->list_info);
                //     $data = [];

                //     if (is_array($query)) {
                //         foreach ($query as $item) {
                //             foreach ($query as $item) {
                //                 $data[] = [
                //                     $item->ho_ten,
                //                     $item->ngay_sinh,
                //                     $item->lop,
                //                     $item->doi_tuong,
                //                     $item->muc_tro_cap_hp,
                //                     $item->tro_cap_ky
                //                 ];
                //             }
                //         }
                //     }
                //     return Excel::download(new DanhSachDuocHuongTroCapXaHoiExport($data), $fileName);
                //     break;
                // case 5:
                //     $fileName = $this->sanitizeFileName('DANH SÁCH SINH VIÊN ĐƯỢC HƯỞNG CHẾ ĐỘ MIỄN PHÍ CHỖ Ở TẠI KÝ TÚC XÁ Theo điểm g, khoản 3, điều 1, Nghị quyết 35/2021/NQ-HĐND tỉnh Quảng Ninh.xlsx');
                //     return Excel::download(new DanhSachHuongMienPhiChoOKTXExport(''), $fileName);
                // case 6:
                //     $fileName = $this->sanitizeFileName('DANH SÁCH SINH VIÊN ĐƯỢC HƯỞNG CHẾ ĐỘ HỖ TRỢ TIỀN ĂN Theo điểm f, g, khoản 3, điều 1, Nghị quyết 35/2021/NQ-HĐND tỉnh Quảng Ninh.xlsx');
                //     return Excel::download(new DanhSachHuongHoTroTienAnExport(''), $fileName);
                // case 7:
                //         $fileName = $this->sanitizeFileName('DANH SÁCH SINH VIÊN ĐƯỢC HƯỞNG CHẾ ĐỘ HỖ TRỢ TIỀN ĂN Theo điểm f, g, khoản 3, điều 1, Nghị quyết 35/2021/NQ-HĐND tỉnh Quảng Ninh.xlsx');


                //         if ($namHoc === '2024-2025') {
                //             $query = $query->where('type', 5)->first();
                //             // Đường dẫn file PDF trong storage/app/demo/
                //             $pdfPath1 = base_path('storage/app/public/' .$query->file_quyet_dinh);
                //             $pdfPath2 = base_path('storage/app/public/' .$query->file_list);


                //             // Kiểm tra file có tồn tại không
                //             if (!file_exists($pdfPath1) || !file_exists($pdfPath2)) {
                //                 return response()->json(['error' => 'File PDF không tồn tại'], 404);
                //             }

                //             // Đường dẫn file zip tạm trong storage/app/
                //             $zipFileName = 'file_2024-2025.zip';
                //             $zipPath = storage_path('app/' . $zipFileName);

                //             $zip = new ZipArchive();

                //             if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                //                 $zip->addFile($pdfPath1, 'quyetdinh.pdf');
                //                 $zip->addFile($pdfPath2, 'danhsach.pdf');
                //                 $zip->close();
                //             } else {
                //                 return response()->json(['error' => 'Không tạo được file zip'], 500);
                //             }

                //             return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
                //         } else {
                //             $fileName = $this->sanitizeFileName('DANH SÁCH SINH VIÊN ĐƯỢC HƯỞNG CHẾ ĐỘ HỖ TRỢ TIỀN ĂN Theo điểm f, g, khoản 3, điều 1, Nghị quyết 35/2021/NQ-HĐND tỉnh Quảng Ninh.xlsx');
                //             return Excel::download(new DanhSachHuongHoTroTienAnExport(''), $fileName);
                //         }

            default:
                break;
        }
    }

    function info()
    {
        $hoso = HoSo::get();
        dd("oke");
    }
}
