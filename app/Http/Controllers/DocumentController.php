<?php

namespace App\Http\Controllers;

use App\Exports\MienGiamHocPhiSinhVienExport;
use App\Exports\TroCapXaHoiSinhVienExport;
use App\Exports\CheDoMienPhiChoOKytucXaExport;
use App\Exports\CheDoHoTroDoDungHocTapExport;
use App\Exports\CheDoHoTroHocPhiChoSinhVienExport;
use App\Exports\HoTroChiPhiHocTapExport;
use App\Exports\DanhSachMienGiamHocPhiExport;
use App\Exports\DanhSachHuongHoTroTienAnExport;
use App\Exports\DanhSachDuocHoTroHocPhiExport;
use App\Exports\DanhSachHuongMienPhiChoOKTXExport;
use App\Exports\TheoDoiKQGiaiQuyetCheDoCSExport;
use App\Exports\DanhSachDuocHuongTroCapXaHoiExport;
use App\Exports\TheoDoiKQGiaiQuyetCheDoTCXHExport;
use App\Exports\SoTheoDoiSVRutHoSoExport;
use App\Models\HoSo;
use App\Models\StopStudy;
use App\Models\Student;
use Carbon\Carbon;
use Mpdf\Mpdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DocumentController extends Controller
{
    function index()
    {
        return view('document.index');
    }
    public function downloadDanhSach(Request $request)
    {
        switch ($request->type) {
            case '0':
                $data = $this->SoTheoDoiSVRutStopStudy($request);
                $fileName = $this->sanitizeFileName('SỔ THEO DÕI SINH VIÊN RÚT HỒ SƠ.xlsx');
                return Excel::download(new SoTheoDoiSVRutHoSoExport($data), $fileName);
            case '1':
                $data = $this->DanhSachMienGiamHocPhi($request);
                $fileName = $this->sanitizeFileName('DANH SÁCH SINH VIÊN ĐƯỢC MIỄN, GIẢM HỌC PHÍ THEO NGHỊ ĐỊNH 81/2021/NĐ-CP.xlsx');
                return Excel::download(new DanhSachMienGiamHocPhiExport($data, $request->nam_hoc, $request->ky_hoc), $fileName);
            case '2':
                $data = $this->DanhSachDuocHuongTroCapXaHoi($request);
                $fileName = $this->sanitizeFileName('DANH SÁCH SINH VIÊN ĐƯỢC HƯỞNG TRỢ CẤP XÃ HỘI.xlsx');
                return Excel::download(new DanhSachDuocHuongTroCapXaHoiExport($data, $request->nam_hoc, $request->ky_hoc), $fileName);
            case '3':
                $data = $this->DanhSachDuocHoTroHocPhi($request);
                $fileName = $this->sanitizeFileName('DANH SÁCH SINH VIÊN ĐƯỢC HỖ TRỢ HỌC PHÍ Theo điểm c, g, khoản 3, điều 1, Nghị quyết 35/2021/NQ-HĐND tỉnh Quảng Ninh.xlsx');
                return Excel::download(new DanhSachDuocHoTroHocPhiExport($data, $request->nam_hoc, $request->ky_hoc), $fileName);
            case '4':
                $data = $this->DanhSachHoTroTienAn($request);
                $fileName = $this->sanitizeFileName('DANH SÁCH SINH VIÊN ĐƯỢC HƯỞNG CHẾ ĐỘ HỖ TRỢ TIỀN ĂN Theo điểm f, g, khoản 3, điều 1, Nghị quyết 35/2021/NQ-HĐND tỉnh Quảng Ninh.xlsx');
                return Excel::download(new DanhSachHuongHoTroTienAnExport($data, $request->nam_hoc, $request->ky_hoc), $fileName);
            case '5':
                $data = $this->DanhSachMienPhiKyTucXa($request);
                $fileName = $this->sanitizeFileName('DANH SÁCH SINH VIÊN ĐƯỢC HƯỞNG CHẾ ĐỘ MIỄN PHÍ CHỖ Ở TẠI KÝ TÚC XÁ Theo điểm g, khoản 3, điều 1, Nghị quyết 35/2021/NQ-HĐND tỉnh Quảng Ninh.xlsx');
                return Excel::download(new DanhSachHuongMienPhiChoOKTXExport($data, $request->nam_hoc, $request->ky_hoc), $fileName);
            default:
                $message = "Unknown status.";
                break;
        }
    }
    public function downloadQuyetDinh(Request $request)
    {
        $hoSo = HoSo::where('type', $request->type)
            ->where('nam_hoc', $request->nam_hoc)
            ->where('ky_hoc', $request->ky_hoc)
            ->latest('created_at')
            ->first();
        $fileQuyetDinh = $hoSo->file_quyet_dinh;
        return redirect("/storage/{$fileQuyetDinh}");
    }
    public function MienGiamHocPhiSinhVien(Request $request)
    {
        $fileName = 'THEO DÕI KẾT QUẢ GIẢI QUYẾT CHẾ ĐỘ MIỄN GIẢM HỌC PHÍ CHO SINH VIÊN.xlsx';
        return Excel::download(new MienGiamHocPhiSinhVienExport(''), $fileName);
    }

    public function TroCapXaHoiSinhVien(Request $request)
    {
        $fileName = 'THEO DÕI KẾT QUẢ GIẢI QUYẾT CHẾ ĐỘ TRỢ CẤP XÃ HỘI CHO SINH VIÊN.xlsx';
        return Excel::download(new TroCapXaHoiSinhVienExport(''), $fileName);
    }

    public function CheDoMienPhiChoOKytucXa(Request $request)
    {
        $fileName = $this->sanitizeFileName('THEO DÕI KẾT QUẢ GIẢI QUYẾT CHẾ ĐỘ MIỄN PHÍ CHỖ Ở KÝ TÚC XÁ CHO SINH VIÊN THEO NGHỊ QUYẾT 35/2021/NQ-HĐND.xlsx');
        return Excel::download(new CheDoMienPhiChoOKytucXaExport(''), $fileName);
    }
    public function CheDoHoTroDoDungHocTap(Request $request)
    {
        $fileName = $this->sanitizeFileName('THEO DÕI KẾT QUẢ GIẢI QUYẾT CHẾ ĐỘ CHẾ ĐỘ HỖ TRỢ ĐỒ DÙNG HỌC TẬP CHO SINH VIÊN THEO NGHỊ QUYẾT 35/2021/NQ-HĐND.xlsx');
        return Excel::download(new CheDoHoTroDoDungHocTapExport(''), $fileName);
    }
    public function CheDoHoTroHocPhiChoSinhVien(Request $request)
    {
        $fileName = $this->sanitizeFileName('THEO DÕI KẾT QUẢ GIẢI QUYẾT CHẾ ĐỘ CHẾ ĐỘ HỖ TRỢ HỌC PHÍ CHO SINH VIÊN THEO NGHỊ QUYẾT 35/2021/NQ-HĐND.xlsx');
        return Excel::download(new CheDoHoTroHocPhiChoSinhVienExport(''), $fileName);
    }
    public function HoTroChiPhiHocTap(Request $request)
    {
        $fileName = $this->sanitizeFileName('THEO DÕI KẾT QUẢ GIẢI QUYẾT CHẾ ĐỘ HỖ TRỢ CHI PHÍ HỌC TẬP CHO SINH VIÊN.xlsx');
        return Excel::download(new HoTroChiPhiHocTapExport(''), $fileName);
    }
    public function TheoDoiKQGiaiQuyetCheDoTCXH(Request $request)
    {
        $fileName = $this->sanitizeFileName('THEO DÕI KẾT QUẢ GIẢI QUYẾT CHẾ ĐỘ TRỢ CẤP XÃ HỘI CHO SINH VIÊN.xlsx');
        return Excel::download(new TheoDoiKQGiaiQuyetCheDoTCXHExport(''), $fileName);
    }
    public function TheoDoiKQGiaiQuyetCheDoCS(Request $request)
    {
        $query = StopStudy::where('type', 5)
            ->where('ky_hoc', $request->ky_hoc)
            ->where('nam_hoc', $request->nam_hoc)
            ->where('status', '>', 0)
            ->get();

        foreach ($query as $item) {
            if (empty($item->list_info)) {
                continue;
            }
            $list_info = json_decode($item->list_info, associative: true);
            if (!is_array($list_info) || !isset($list_info[0])) {
                continue;
            }
            $info = $list_info[0];

            $data[] = [
                $item->ho_ten,
                $item->ngay_sinh,
                $item->lop,
                $info['doi_tuong'] ?? '',
                $info['muc_tro_cap_xh'] ?? '',
                $info['tro_cap_ky'] ?? '',
            ];
        }
        return $data;
    }

    public function DanhSachMienGiamHocPhi(Request $request)
    {
        $query = StopStudy::join('students', 'stop_studies.student_id', '=', 'students.id')
            ->leftJoin('lops', 'students.ma_lop', '=',  'lops.ma_lop')
            ->whereNull('stop_studies.parent_id')
            ->where('stop_studies.ky_hoc', $request->ky_hoc)
            ->where('stop_studies.nam_hoc', $request->nam_hoc)
            ->where('stop_studies.type', 1)
            ->where('stop_studies.status', '>', value: 0)
            ->select('stop_studies.*', 'students.full_name', 'students.date_of_birth', 'students.hocphi', 'lops.name as lop_name')
            ->get();
        $data = [];
        foreach ($query as $item) {
            $phantramgiam = $item->phantramgiam ?? 0;
            $hocphi = $item->hocphi ?? 0;
            $so_tien_giam_1_thang = $hocphi * ($phantramgiam / 100) / 5;
            $so_tien_giam = $so_tien_giam_1_thang * 5;
            $data[] = [
                $item->full_name,
                Carbon::parse($item->date_of_birth)->format('d-m-Y'),
                $item->lop_name,
                'Đối tượng',
                $hocphi,
                $phantramgiam ? $phantramgiam . '%' : '',
                $so_tien_giam_1_thang,
                5,
                $so_tien_giam,
            ];
        }

        return $data;
    }

    public function DanhSachDuocHoTroHocPhi(Request $request)
    {
        $query = StopStudy::join('students', 'stop_studies.student_id', '=', 'students.id')
            ->leftJoin('lops', 'stop_studies.ma_lop', '=', 'lops.ma_lop')
            ->leftJoin('scores', 'stop_studies.student_id', '=', 'scores.student_id')
            ->whereNull('stop_studies.parent_id')
            ->where('stop_studies.ky_hoc', $request->ky_hoc)
            ->where('stop_studies.nam_hoc', $request->nam_hoc)
            ->where('stop_studies.type', 3)
            ->where('stop_studies.status', '>', 0)
            ->select('stop_studies.*', 'students.full_name', 'students.date_of_birth', 'lops.name as lop_name', 'students.hocphi')
            ->get();

        $data = [];
        foreach ($query as $item) {
            $phantramgiam = $item->phantramgiam ?? 100;
            $so_tien_giam_1_thang = 894000;
            $so_tien_giam = $so_tien_giam_1_thang * 5;
            $data[] = [
                $item->full_name,
                Carbon::parse($item->date_of_birth)->format('d-m-Y'),
                $item->lop_name,
                'Đối tượng',
                $item->diem_ht ?? '',
                $item->diem_rl ?? '',
                $item->xep_loai ?? '',
                $phantramgiam ? $phantramgiam . '%' : '',
                $so_tien_giam_1_thang,
                $so_tien_giam
            ];
        }
        return $data;
    }
    public function DanhSachHoTroTienAn(Request $request)
    {
        $query = StopStudy::join('students', 'stop_studies.student_id', '=', 'students.id')
            ->leftJoin('lops', 'stop_studies.ma_lop', '=', 'lops.ma_lop')
            ->whereNull('stop_studies.parent_id')
            ->where('stop_studies.ky_hoc', $request->ky_hoc)
            ->where('stop_studies.nam_hoc', $request->nam_hoc)
            ->where('stop_studies.type', 4)
            ->where('stop_studies.is_tien_an', 1)
            ->where('stop_studies.status', '>', 0)
            ->select('stop_studies.*', 'students.full_name', 'students.date_of_birth', 'lops.name as lop_name',)
            ->get();

        $data = [];
        foreach ($query as $item) {
            $data[] = [
                $item->full_name,
                Carbon::parse($item->date_of_birth)->format('d-m-Y'),
                $item->lop_name,
                'Đối tượng',
                600000,
                5,
                600000 * 5,
            ];
        }
        return $data;
    }
    public function DanhSachMienPhiKyTucXa(Request $request)
    {
        $query = StopStudy::join('students', 'stop_studies.student_id', '=', 'students.id')
            ->leftJoin('lops', 'stop_studies.ma_lop', '=', 'lops.ma_lop')
            ->whereNull('stop_studies.parent_id')
            ->where('stop_studies.ky_hoc', $request->ky_hoc)
            ->where('stop_studies.nam_hoc', $request->nam_hoc)
            ->where('stop_studies.type', 4)
            ->where('is_ky_tuc_xa', 1)
            ->where('stop_studies.status', '>', 0)
            ->select('stop_studies.*', 'students.full_name', 'students.date_of_birth', 'lops.name as lop_name')
            ->get();

        foreach ($query as $item) {
            $data[] = [
                $item->full_name,
                Carbon::parse($item->date_of_birth)->format('d-m-Y'),
                $item->lop_name,
                'Đối tượng',
                '',
                5,
            ];
        }
        return $data;
    }

    public function DanhSachDuocHuongTroCapXaHoi(Request $request)
    {
        $query = StopStudy::join('students', 'stop_studies.student_id', '=', 'students.id')
            ->join('lops', 'stop_studies.ma_lop', '=', 'lops.ma_lop')
            ->whereNull('stop_studies.parent_id')
            ->where('stop_studies.ky_hoc', $request->ky_hoc)
            ->where('stop_studies.nam_hoc', $request->nam_hoc)
            ->where('stop_studies.type', 2)
            ->where('stop_studies.status', '>', 0)
            ->select('stop_studies.*', 'students.full_name', 'students.date_of_birth', 'lops.name as lop_name')
            ->get();

        $data = [];
        foreach ($query as $item) {
            $muctrocapxh = $item->muctrocapxh ?? 140000;
            $data[] = [
                $item->full_name,
                Carbon::parse($item->date_of_birth)->format('d-m-Y'),
                $item->lop_name,
                'Đối tượng',
                $muctrocapxh,
                $muctrocapxh * 6,
            ];
        }
        return $data;
    }

    public function SoTheoDoiSVRutStopStudy(Request $request)
    {
        $data  = [];

        $query = StopStudy::where('type', 0)
            ->where('stop_studies.status', '>=', 1)
            ->studentActive()
            ->whereNull('parent_id')
            ->where('stop_studies.status', '>', 0)
            ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
            ->leftJoin('lops', 'students.ma_lop', '=', 'lops.ma_lop')
            ->select('stop_studies.*', 'students.full_name', 'students.date_of_birth', 'students.student_code', 'lops.name as lop_name', 'students.hocphi')
            ->get();

        foreach ($query as $item) {
            $data[] = [
                $item->full_name,
                Carbon::parse($item->date_of_birth)->format('d-m-Y'),
                $item->lop_name,
                $item->created_at
            ];
        }
        return $data;
    }
}
