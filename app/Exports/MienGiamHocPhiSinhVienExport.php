<?php

namespace App\Exports;

use App\Models\HoSo;
use App\Models\StopStudy;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class MienGiamHocPhiSinhVienExport implements FromArray, WithEvents, WithDrawings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function array(): array
    {
        $result = [
            ['TRƯỜNG ĐẠI HỌC HẠ LONG'],
            ['PHÒNG CTCT, HT&SV'],
            [''],
            ['THEO DÕI KẾT QUẢ GIẢI QUYẾT CHẾ ĐỘ CHÍNH SÁCH'],
            ['MIỄN GIẢM HỌC PHÍ CHO SINH VIÊN'],
            [''],
            ['STT', 'Năm học', 'Học kỳ', 'Số SV hưởng', 'Số tiền hưởng', 'Số QĐ', 'Ngày QĐ'],
        ];
        $stt = 1;
        $hoSo = HoSo::where('ky_hoc', $this->request->ky)
            ->where('nam_hoc', $this->request->nam_hoc)
            ->where('type', 1)
            ->first();

        $query = StopStudy::where('type',  1)
            ->join('students', 'stop_studies.student_id', '=', 'students.id')
            ->whereNull('stop_studies.parent_id')
            ->where('stop_studies.status', '>', 0)
            ->where('ky_hoc', $this->request->ky)
            ->where('nam_hoc', $this->request->nam_hoc)
            ->get();
        $data  = [];
        $soSVHuong = 0;
        $soTienHuong = 0;
        foreach ($query as $item) {
            $soSVHuong++;
            $phantramgiam = $item->phantramgiam ?? 0;
            $hocphi = $item->hocphi ?? 0;
            $so_tien_giam_1_thang = $hocphi * ($phantramgiam / 100) / 5;
            $so_tien_giam = $so_tien_giam_1_thang * 5;
            $soTienHuong += $so_tien_giam;
        }
        $data[] = [
            $this->request->nam_hoc,
            $this->request->ky,
            $soSVHuong == 0 ? '0' : $soSVHuong,
            $soTienHuong == 0 ? '0' : $soTienHuong,
            $hoSo->so_quyet_dinh ?? '',
            $hoSo && $hoSo->ngay_quyet_dinh
                ? \Carbon\Carbon::parse($hoSo->ngay_quyet_dinh)->format('d/m/Y')
                : ''        ];

        if (is_array($data) && !empty($data)) {
            foreach ($data as $item) {
                if (is_array($item)) {
                    $result[] = array_merge([$stt++], $item);
                }
            }
        }

        return $result;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $sheet->getPageSetup()
                    ->setPaperSize(PageSetup::PAPERSIZE_A4)
                    ->setOrientation(PageSetup::ORIENTATION_PORTRAIT)
                    ->setFitToWidth(1)
                    ->setFitToHeight(0)
                    ->setHorizontalCentered(true)
                    ->setPrintArea('A1:G' . $sheet->getHighestRow());

                $sheet->getPageMargins()
                    ->setTop(0.5)
                    ->setRight(0.5)
                    ->setBottom(0.5)
                    ->setLeft(0.5)
                    ->setHeader(0.3)
                    ->setFooter(0.3);


                $sheet->getParent()->getDefaultStyle()->getFont()->setName('Times New Roman');
                $sheet->getParent()->getDefaultStyle()->getFont()->setSize(12);

                $sheet->getColumnDimension('A')->setWidth(width: 7);
                $sheet->getColumnDimension('B')->setWidth(width: 10);
                $sheet->getColumnDimension('C')->setWidth(width: 10);
                $sheet->getColumnDimension('D')->setWidth(width: 12);
                $sheet->getColumnDimension('E')->setWidth(width: 15);
                $sheet->getColumnDimension('F')->setWidth(width: 15);
                $sheet->getColumnDimension('G')->setWidth(width: 15);


                $sheet->mergeCells('A1:D1');
                $sheet->mergeCells('A2:D2');
                $sheet->mergeCells('A4:G4');
                $sheet->mergeCells('A5:G5');

                $this->applyBorder($sheet, 'A7:G' . $sheet->getHighestRow());
                $this->centerCell($sheet, 'A1:G' . $sheet->getHighestRow());
                $this->boldCell($sheet, 'A1:G7');

                $sheet->getStyle('D')->getNumberFormat()->setFormatCode('0');
                $sheet->getStyle('E')->getNumberFormat()->setFormatCode('#,##0');
            },
        ];
    }
    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Underline');

        $width = 100;
        $height = 1;

        $image = imagecreatetruecolor($width, $height);
        $color = imagecolorallocate($image, 0, 0, 0); // Black line
        imagefill($image, 0, 0, $color);

        $tempFile = tempnam(sys_get_temp_dir(), 'underline');
        imagepng($image, $tempFile);
        imagedestroy($image);

        $drawing->setPath($tempFile);
        $drawing->setOffsetX(50);
        $drawing->setOffsetY(30);
        $drawing->setCoordinates('B2');

        return $drawing;
    }
    function leftCell($sheet, string $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
    }
    function boldCell($sheet, string $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => ['bold' => true]
        ]);
    }
    function centerCell($sheet, string $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
    }
    function applyBorder($sheet, string $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);
    }
    function applyOuterBorder($sheet, string $range)
    {
        // Apply outer border only
        $sheet->getStyle($range)->applyFromArray([
            'borders' => [
                'top'    => ['borderStyle' => Border::BORDER_THIN],
                'bottom' => ['borderStyle' => Border::BORDER_THIN],
                'left'   => ['borderStyle' => Border::BORDER_THIN],
                'right'  => ['borderStyle' => Border::BORDER_THIN],
            ],
        ]);
    }
}
