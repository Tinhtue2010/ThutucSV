<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use App\Helpers\CommonHelper;
use App\Models\HoSo;
use App\Models\StopStudy;
use Carbon\Carbon;

class DanhSachMienGiamHocPhiExport implements FromArray, WithEvents, WithDrawings
{
    protected $data;
    protected $nam_hoc;
    protected $ky_hoc;

    public function __construct($data, $nam_hoc, $ky_hoc)
    {
        $this->data = $data;
        $this->nam_hoc = $nam_hoc;
        $this->ky_hoc = $ky_hoc;
    }

    public function array(): array
    {
        $hoSo = HoSo::where('ky_hoc', $this->ky_hoc)
            ->where('nam_hoc', $this->nam_hoc)
            ->where('type', 1)
            ->first();

        if ($hoSo) {
            $carbonDate = Carbon::parse($hoSo->ngay_quyet_dinh);
        } else {
            $carbonDate = Carbon::now();
        }

        $day = $carbonDate->format('d');
        $month = $carbonDate->format('m');
        $year = $carbonDate->format('Y');

        $result = [
            ['TRƯỜNG ĐẠI HỌC HẠ LONG'],
            ['PHÒNG CTCT, HT&SV'],
            [''],
            ['DANH SÁCH SINH VIÊN ĐƯỢC MIỄN, GIẢM HỌC PHÍ THEO NGHỊ ĐỊNH'],
            ['81/2021/NĐ-CP. HỌC KỲ ' . $this->ky_hoc . ' NĂM HỌC ' . $this->nam_hoc],
            ['(Kèm theo quyết định số ' . ($hoSo?->so_quyet_dinh ?? '') . '/QĐ-ĐHHL, ngày ' . $day . ' tháng ' . $month . ' năm ' . $year],
            ['của Hiệu trưởng Trường Đại học Hạ Long)'],
            [''],
            ['STT', 'Họ và tên', 'Ngày sinh', 'Lớp', 'Đối tượng', 'Mức học phí/tháng', 'Mức miễn giảm', '', '', 'Số tiền được miễn giảm/kỳ'],
            ['', '', '', '', '', '', 'Tỷ lệ', 'Số tiền miễn, giảm/tháng', 'Số tháng miễn giảm'],
        ];
        $stt = 1;
        $tong = 0;
        if (is_array($this->data) && !empty($this->data)) {
            foreach ($this->data as $item) {
                if (is_array($item)) {
                    $result[] = array_merge([$stt++], $item);
                    $tong += $item[8];
                }
            }
        }
        $result[] = [
            '',
            'Tổng',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            $tong,
        ];
        $result[] = [
            '',
            // 'Số tiền bằng chữ:' . numberInVietnameseCurrency($tong),
            '',
            '',
            '',
            '',
            '',
            '',
            '',
        ];

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
                    ->setPrintArea('A1:J' . $sheet->getHighestRow());

                $sheet->getPageMargins()
                    ->setTop(0.5)
                    ->setRight(0.5)
                    ->setBottom(0.5)
                    ->setLeft(0.5)
                    ->setHeader(0.3)
                    ->setFooter(0.3);


                $sheet->getParent()->getDefaultStyle()->getFont()->setName('Times New Roman');
                $sheet->getParent()->getDefaultStyle()->getFont()->setSize(12);

                $highestColumn = $sheet->getHighestColumn();
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle("A1:{$highestColumn}{$highestRow}")->getAlignment()->setWrapText(true);

                $sheet->getColumnDimension('A')->setWidth(width: 7);
                $sheet->getColumnDimension('B')->setWidth(width: 15);
                $sheet->getColumnDimension('C')->setWidth(width: 10);
                $sheet->getColumnDimension('D')->setWidth(width: 15);
                $sheet->getColumnDimension('E')->setWidth(width: 15);
                $sheet->getColumnDimension('F')->setWidth(width: 15);
                $sheet->getColumnDimension('G')->setWidth(width: 5);
                $sheet->getColumnDimension('H')->setWidth(width: 10);
                $sheet->getColumnDimension('I')->setWidth(width: 10);
                $sheet->getColumnDimension('J')->setWidth(width: 15);


                $sheet->mergeCells('A1:D1');
                $sheet->mergeCells('A2:D2');
                $sheet->mergeCells('A4:J4');
                $sheet->mergeCells('A5:J5');
                $sheet->mergeCells('A6:J6');
                $sheet->mergeCells('A7:J7');
                $sheet->mergeCells('G9:I9');

                $sheet->mergeCells('A9:A10');
                $sheet->mergeCells('B9:B10');
                $sheet->mergeCells('C9:C10');
                $sheet->mergeCells('D9:D10');
                $sheet->mergeCells('E9:E10');
                $sheet->mergeCells('F9:F10');
                $sheet->mergeCells('J9:J10');


                $lastRow = $sheet->getHighestRow();
                $secondStart = null;
                for ($i = 1; $i <= $lastRow; $i++) {
                    if ($sheet->getCell('B' . $i)->getValue() === 'Tổng') {
                        $secondStart = $i;
                        break;
                    }
                }
                $this->boldCell($sheet, 'A' . $secondStart . ':J' . $secondStart + 1);
                $sheet->mergeCells('B' . $secondStart + 1 . ':E' . $secondStart + 1);


                $this->applyBorder($sheet, 'A9:J' . $sheet->getHighestRow() - 1);
                $this->centerCell($sheet, 'A1:J' . $sheet->getHighestRow() - 1);
                $this->boldCell($sheet, 'A1:J10');
                $this->italicCell($sheet, 'A6:J7');


                $sheet->getStyle('F')->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle('H')->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle('J')->getNumberFormat()->setFormatCode('#,##0');
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
    function italicCell($sheet, string $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => ['italic' => true, 'bold' => false],
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
