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
class DanhSachDiemHTCaoNhat implements FromArray, WithEvents, WithDrawings
{
    protected $data;
    protected $topPercent;



    public function __construct($data, $topPercent)
    {
        $this->data = $data;
        $this->topPercent = $topPercent;
    }

    public function array(): array
    {
        $result = [
            ['TRƯỜNG ĐẠI HỌC HẠ LONG'],
            ['PHÒNG CTCT, HT&SV'],
            [''],
            ['DANH SÁCH '.$this->topPercent.'% SINH VIÊN CÓ ĐIỂM HỌC TẬP CAO NHẤT'],
            [''],
            [''],
            ['STT','Mã sinh viên','Họ tên','Lớp','Khoa','Học kỳ','Năm học','Điểm HT','Xếp loại HT','Điểm RL','Xếp loại RL','Xếp loại','Số TC HT'],
        ];
        $stt = 1;

        if (is_array($this->data) && !empty($this->data)) {
            foreach ($this->data as $item) {
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
                    ->setPrintArea('A1:M' . $sheet->getHighestRow());

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
                $sheet->getColumnDimension('B')->setWidth(width: 15);
                $sheet->getColumnDimension('C')->setWidth(width: 20);
                $sheet->getColumnDimension('D')->setWidth(width: 15);
                $sheet->getColumnDimension('E')->setWidth(width: 15);
                $sheet->getColumnDimension('F')->setWidth(width: 8);
                $sheet->getColumnDimension('G')->setWidth(width: 15);
                $sheet->getColumnDimension('H')->setWidth(width: 10);
                $sheet->getColumnDimension('I')->setWidth(width: 12);
                $sheet->getColumnDimension('J')->setWidth(width: 10);
                $sheet->getColumnDimension('K')->setWidth(width: 12);
                $sheet->getColumnDimension('L')->setWidth(width: 10);
                $sheet->getColumnDimension('M')->setWidth(width: 10);


                $sheet->mergeCells('A1:D1');
                $sheet->mergeCells('A2:D2');
                $sheet->mergeCells('A4:M4');
                $sheet->mergeCells('A5:M5');

                $this->applyBorder($sheet, 'A7:M' . $sheet->getHighestRow());
                $this->centerCell($sheet, 'A1:M'.$sheet->getHighestRow());
                $this->boldCell($sheet, 'A1:M7');

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
        $drawing->setOffsetX(80);
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
