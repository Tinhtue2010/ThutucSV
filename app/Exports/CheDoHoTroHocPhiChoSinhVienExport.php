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
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class CheDoHoTroHocPhiChoSinhVienExport implements FromArray, WithEvents, WithDrawings
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
            ['THEO DÕI KẾT QUẢ GIẢI QUYẾT'],
            ['CHẾ ĐỘ HỖ TRỢ HỌC PHÍ CHO SINH VIÊN'],
            ['THEO NGHỊ QUYẾT 35/2021/NQ-HĐND'],
            [''],
            ['STT', 'Năm học', 'Học kỳ', 'Số SV hưởng', 'Số tiền hưởng', 'Số QĐ', 'Ngày QĐ'],
        ];

        $query = StopStudy::join('students', 'stop_studies.student_id', '=', 'students.id')
            ->where('stop_studies.type',  4)
            ->whereNull('stop_studies.parent_id')
            ->where('stop_studies.is_giam_hp', 1)
            ->where('stop_studies.status', '>', 0)
            ->where('stop_studies.ky_hoc', $this->request->ky)
            ->where('stop_studies.nam_hoc', $this->request->nam_hoc)
            ->get();

        $hoSo = HoSo::where('ky_hoc', $this->request->ky)
            ->where('nam_hoc', $this->request->nam_hoc)
            ->where('type', 4)
            ->first();
        $data  = [];
        $soSVHuong = 0;
        $soTienHuong = 0;
        foreach ($query as $item) {
            $soSVHuong++;
            $hocphi = $item->hocphi ?? 0;
            $soTienHuong += $hocphi;
        }
        $data[] = [
            $this->request->nam_hoc,
            $this->request->ky,
            $soSVHuong == 0 ? '0' : $soSVHuong,
            $soTienHuong == 0 ? '0' : $soTienHuong,
            $hoSo->so_quyet_dinh ?? '',
            $hoSo && $hoSo->ngay_quyet_dinh
                ? \Carbon\Carbon::parse($hoSo->ngay_quyet_dinh)->format('d/m/Y')
                : ''
        ];

        $stt = 1;
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
                $sheet->mergeCells('A6:G6');

                $this->applyBorder($sheet, 'A8:G' . $sheet->getHighestRow());
                $this->centerCell($sheet, 'A1:G' . $sheet->getHighestRow());
                $this->boldCell($sheet, 'A1:G8');

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
