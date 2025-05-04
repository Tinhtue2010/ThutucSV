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

class DanhSachHuongMienPhiChoOKTXExport implements FromArray, WithEvents, WithDrawings {
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $result = [
            ['UBND TỈNH QUẢNG NINH'],
            ['TRƯỜNG ĐẠI HỌC HẠ LONG'],
            [''],
            ['DANH SÁCH SINH VIÊN ĐƯỢC HƯỞNG CHẾ ĐỘ MIỄN PHÍ CHỖ Ở'],
            ['TẠI KÝ TÚC XÁ'],
            ['Theo điểm g, khoản 3, điều 1, Nghị quyết 35/2021/NQ-HĐND tỉnh Quảng Ninh.'],
            ['Học kỳ ….., năm học ……………….'],
            ['(Kèm theo Quyết định số ……./QĐ-ĐHHL, ngày….. tháng ……. năm ……'],
            ['của Hiệu trưởng Trường Đại học Hạ Long)'],
            [''],
            ['STT','Họ và tên','Ngày sinh','Tên lớp','Đối tượng hưởng','Ngày vào ở KTX','Số tháng được miễn'],
        ];
        $stt = 1;

        $result[] = [
            $stt++,
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
        ];

        $result[] = [
            'Danh sách có … sinh viên',
            '',
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

                $highestColumn = $sheet->getHighestColumn();
                $highestRow = $sheet->getHighestRow();     
                $sheet->getStyle("A1:{$highestColumn}{$highestRow}")->getAlignment()->setWrapText(true);
                
                $sheet->getColumnDimension('A')->setWidth(width: 7);
                $sheet->getColumnDimension('B')->setWidth(width: 15);
                $sheet->getColumnDimension('C')->setWidth(width: 10);
                $sheet->getColumnDimension('D')->setWidth(width: 15);
                $sheet->getColumnDimension('E')->setWidth(width: 15);
                $sheet->getColumnDimension('F')->setWidth(width: 15);
                $sheet->getColumnDimension('G')->setWidth(width: 10);


                $sheet->mergeCells('A1:D1');
                $sheet->mergeCells('A2:D2');
                $sheet->mergeCells('A4:G4');
                $sheet->mergeCells('A5:G5');
                $sheet->mergeCells('A6:G6');
                $sheet->mergeCells('A7:G7');
                $sheet->mergeCells('A8:G8'); 
                $sheet->mergeCells('A9:G9'); 


                $lastRow = $sheet->getHighestRow();
                $sheet->mergeCells( 'A' . $lastRow . ':G'.$lastRow);

                $lastRow = $sheet->getHighestRow();
                $this->applyBorder($sheet, 'A11:G' . $lastRow-1);
                $this->centerCell($sheet, 'A1:G'.$lastRow-1);
                $this->boldCell($sheet, 'A2:G11');
                $this->italicCell($sheet, 'A8:G9');
                $sheet->getRowDimension($lastRow)->setRowHeight(height: 30);

            },
        ];
    }
    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Underline');

        $width = 200;
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
