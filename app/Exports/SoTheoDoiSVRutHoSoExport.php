<?php

namespace App\Exports;

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

class SoTheoDoiSVRutHoSoExport implements FromArray, WithEvents, WithDrawings
{
    protected $data;


    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $carbonDate = Carbon::parse(now());
        $day = $carbonDate->format('d');
        $month = $carbonDate->format('m');
        $year = $carbonDate->format('Y');
        $result = [
            ['TRƯỜNG ĐẠI HỌC HẠ LONG', '', '', 'CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM'],
            ['PHÒNG CÔNG TÁC CHÍNH TRỊ,', '', '', 'Độc lập - Tự do - Hạnh phúc'],
            ['QUẢN LÝ VÀ HỖ TRỢ SINH VIÊN'],
            ['', '', '', 'Quảng Ninh, ngày '.$day.' tháng '.$month.' năm '.$year],
            [''],
            ['SỔ THEO DÕI SINH VIÊN RÚT HỒ SƠ1'],
            [''],
            ['TT', 'Họ và tên', 'Ngày sinh', 'Lớp', 'Ngày lấy', 'Ký tên'],
        ];

        $stt = 1;
        if (is_array($this->data) && !empty($this->data)) {
            foreach ($this->data as $item) {
                if (is_array($item)) {
                    $result[] = array_merge([$stt++], $item);
                }
            }
        }

        $result[] = [
            [''],
            ['', '', '', 'Người lập biểu', '', ''],
            ['', '', '', '(kí,ghi rõ họ tên)', '', ''],
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
                    ->setPrintArea('A1:F' . $sheet->getHighestRow());

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
                $sheet->getColumnDimension('B')->setWidth(width: 20);
                $sheet->getColumnDimension('C')->setWidth(width: 15);
                $sheet->getColumnDimension('D')->setWidth(width: 12);
                $sheet->getColumnDimension('E')->setWidth(width: 15);
                $sheet->getColumnDimension('F')->setWidth(width: 15);


                $sheet->mergeCells('A1:C1');
                $sheet->mergeCells('A2:C2');
                $sheet->mergeCells('A3:C3');
                $sheet->mergeCells('D1:F1');
                $sheet->mergeCells('D2:F2');
                $sheet->mergeCells('D4:F4');

                $sheet->mergeCells('A6:F6');

                $lastRow = $sheet->getHighestRow();
                $secondStart = null;
                for ($i = 1; $i <= $lastRow; $i++) {
                    if ($sheet->getCell('D' . $i)->getValue() === 'Người lập biểu') {
                        $secondStart = $i;
                        break;
                    }
                }

                $this->boldCell($sheet, 'A' . $secondStart . ':F' . $secondStart + 1);
                $sheet->mergeCells('D' . $secondStart . ':F' . $secondStart);
                $sheet->mergeCells('D' . $secondStart + 1 . ':F' . $secondStart + 1);


                $this->applyBorder($sheet, 'A8:F' . $sheet->getHighestRow() - 3);
                $this->centerCell($sheet, 'A1:F' . $sheet->getHighestRow());
                $this->boldCell($sheet, 'A1:F8');
                $this->italicCell($sheet, 'D4');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => false],
                ]);
            },
        ];
    }
    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Underline');

        $width = 150;
        $height = 1;

        $image = imagecreatetruecolor($width, $height);
        $color = imagecolorallocate($image, 0, 0, 0); // Black line
        imagefill($image, 0, 0, $color);

        $tempFile = tempnam(sys_get_temp_dir(), 'underline');
        imagepng($image, $tempFile);
        imagedestroy($image);

        $drawing->setPath($tempFile);
        $drawing->setOffsetX(30);
        $drawing->setOffsetY(30);
        $drawing->setCoordinates('B3');

        return $drawing;
    }

    function italicCell($sheet, string $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => ['italic' => true, 'bold' => false],
        ]);
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
