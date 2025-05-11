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
use Mpdf\Mpdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DocumentController extends Controller
{
    function index()
    {
        return view('document.index');
    }
    public function MienGiamHocPhiSinhVien(Request $request)
    {
        $fileName = 'THEO DÕI KẾT QUẢ GIẢI QUYẾT CHẾ ĐỘ MIỄN GIẢM HỌC PHÍ CHO SINH VIÊN.xlsx';
        return Excel::download(new MienGiamHocPhiSinhVienExport(''), $fileName);
    }

    public function TroCapXaHoiSinhVien(Request $request)
    {
        $fileName = 'THEO DÕI KẾT QUẢ GIẢI QUYẾT CHẾ ĐỘ HỖ TRỢ CHI PHÍ HỌC TẬP CHO SINH VIÊN.xlsx';
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
    public function DanhSachMienGiamHocPhi(Request $request)
    {
        $fileName = $this->sanitizeFileName('DANH SÁCH SINH VIÊN ĐƯỢC MIỄN, GIẢM HỌC PHÍ THEO NGHỊ ĐỊNH 81/2021/NĐ-CP.xlsx');
        $query = HoSo::where('type',2)->first('list_info');
        $query = json_decode($query->list_info);

        foreach($query as $item)
        {
            $data[] = [
                $item->ho_ten,
                $item->ngay_sinh,
                $item->lop,
                $item->doi_tuong,
                $item->muc_hoc_phi,
                $item->ti_le_giam . '%',
                $item->so_tien_giam_1_thang,
                $item->mien_giam_ky,
               
            ];
        }
        return Excel::download(new DanhSachMienGiamHocPhiExport($data), $fileName);
    }
    public function DanhSachHuongHoTroTienAn(Request $request)
    {
        $fileName = $this->sanitizeFileName('DANH SÁCH SINH VIÊN ĐƯỢC HƯỞNG CHẾ ĐỘ HỖ TRỢ TIỀN ĂN Theo điểm f, g, khoản 3, điều 1, Nghị quyết 35/2021/NQ-HĐND tỉnh Quảng Ninh.xlsx');
        return Excel::download(new DanhSachHuongHoTroTienAnExport(''), $fileName);
    }
    public function DanhSachDuocHoTroHocPhi(Request $request)
    {
        $fileName = $this->sanitizeFileName('DANH SÁCH SINH VIÊN ĐƯỢC HỖ TRỢ HỌC PHÍ Theo điểm c, g, khoản 3, điều 1, Nghị quyết 35/2021/NQ-HĐND tỉnh Quảng Ninh.xlsx');
        return Excel::download(new DanhSachDuocHoTroHocPhiExport(''), $fileName);
    }
    public function DanhSachHuongMienPhiChoOKTX(Request $request)
    {
        $fileName = $this->sanitizeFileName('DANH SÁCH SINH VIÊN ĐƯỢC HƯỞNG CHẾ ĐỘ MIỄN PHÍ CHỖ Ở TẠI KÝ TÚC XÁ Theo điểm g, khoản 3, điều 1, Nghị quyết 35/2021/NQ-HĐND tỉnh Quảng Ninh.xlsx');
        return Excel::download(new DanhSachHuongMienPhiChoOKTXExport(''), $fileName);
    }
    public function TheoDoiKQGiaiQuyetCheDoCS(Request $request)
    {
        $fileName = $this->sanitizeFileName('THEO DÕI KẾT QUẢ GIẢI QUYẾT CHẾ ĐỘ CHÍNH SÁCH CHO SINH VIÊN.xlsx');
        return Excel::download(new TheoDoiKQGiaiQuyetCheDoCSExport(''), $fileName);
    }
    public function DanhSachDuocHuongTroCapXaHoi(Request $request)
    {
        $fileName = $this->sanitizeFileName('DANH SÁCH SINH VIÊN ĐƯỢC HƯỞNG TRỢ CẤP XÃ HỘI.xlsx');
        $query = HoSo::where('type',3)->first('list_info');
        $query = json_decode($query->list_info);
        foreach ($query as $item) {
            $data[] = [
                $item->ho_ten,
                $item->ngay_sinh,
                $item->lop,
                $item->doi_tuong,
                $item->muc_tro_cap_xh,
                $item->tro_cap_ky
            ];
        }
        return Excel::download(new DanhSachDuocHuongTroCapXaHoiExport($data), $fileName);
    }
    public function TheoDoiKQGiaiQuyetCheDoTCXH(Request $request)
    {
        $fileName = $this->sanitizeFileName('THEO DÕI KẾT QUẢ GIẢI QUYẾT CHẾ ĐỘ TRỢ CẤP XÃ HỘI CHO SINH VIÊN.xlsx');
        return Excel::download(new TheoDoiKQGiaiQuyetCheDoTCXHExport(''), $fileName);
    }
    public function SoTheoDoiSVRutHoSo(Request $request)
    {
        $fileName = $this->sanitizeFileName('SỔ THEO DÕI SINH VIÊN RÚT HỒ SƠ.xlsx');
        $data  = [];

        $query = StopStudy::where('type', 0)
        ->where('stop_studies.status', '>=', 1)
        ->studentActive()
        ->whereNull('parent_id')
        ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
        ->leftJoin('lops', 'students.ma_lop', '=', 'lops.ma_lop')
        ->select('stop_studies.*', 'students.full_name', 'students.date_of_birth', 'students.student_code', 'lops.name as lop_name', 'students.hocphi')
        ->get();

        foreach($query as $item)
        {
            $data[] = [$item->full_name,$item->date_of_birth,$item->lop_name,$item->created_at];
        }

        return Excel::download(new SoTheoDoiSVRutHoSoExport($data), $fileName);
    }

}
