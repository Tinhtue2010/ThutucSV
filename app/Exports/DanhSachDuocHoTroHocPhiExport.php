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
use Carbon\Carbon;

class DanhSachDuocHoTroHocPhiExport implements FromArray, WithEvents, WithDrawings
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
            ->where('type', 3)
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
            ['UBND TỈNH QUẢNG NINH'],
            ['TRƯỜNG ĐẠI HỌC HẠ LONG'],
            [''],
            ['DANH SÁCH SINH VIÊN ĐƯỢC HỖ TRỢ HỌC PHÍ'],
            ['Theo điểm c, g, khoản 3, điều 1, Nghị quyết 35/2021/NQ-HĐND tỉnh Quảng Ninh.'],
            ['Học kỳ ' . $this->ky_hoc . ', năm học ' . $this->nam_hoc],
            ['(Kèm theo Quyết định số ' . ($hoSo?->so_quyet_dinh ?? '') . '/QĐ-ĐHHL, ngày ' . $day . ' tháng ' . $month . ' năm ' . $year],
            [''],
            ['STT', 'Họ và tên', 'Ngày sinh', 'Tên lớp', 'Đối tượng', 'Điểm HT', 'Điểm RL', 'Xếp loại', '% Học phí được Hỗ trợ', 'Số tiền Học phí/tháng', 'Số tiền Hỗ trợ Học phí (5 tháng)', 'Ghi chú'],
        ];
        $stt = 1;
        $tong = 0;
        if (is_array($this->data) && !empty($this->data)) {
            foreach ($this->data as $item) {
                if (is_array($item)) {
                    $result[] = array_merge([$stt++], $item);
                    $tong += $item[9];
                }
            }
        }
        $result[] = [
            'Tổng',
            '',
            '',
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
            'Số tiền bằng chữ:' . numberInVietnameseCurrency($tong),
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
                    ->setPrintArea('A1:L' . $sheet->getHighestRow());

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
                $sheet->getColumnDimension('H')->setWidth(width: 10);
                $sheet->getColumnDimension('I')->setWidth(width: 15);
                $sheet->getColumnDimension('J')->setWidth(width: 15);
                $sheet->getColumnDimension('K')->setWidth(width: 15);
                $sheet->getColumnDimension('L')->setWidth(width: 15);


                $sheet->mergeCells('A1:D1');
                $sheet->mergeCells('A2:D2');
                $sheet->mergeCells('A3:L3');
                $sheet->mergeCells('A4:L4');
                $sheet->mergeCells('A5:L5');
                $sheet->mergeCells('A6:L6');
                $sheet->mergeCells('A7:L7');


                $lastRow = $sheet->getHighestRow();
                $secondStart = null;
                for ($i = 1; $i <= $lastRow; $i++) {
                    if ($sheet->getCell('A' . $i)->getValue() === 'Tổng') {
                        $secondStart = $i;
                        break;
                    }
                }

                $sheet->mergeCells('A' . $secondStart . ':J' . $secondStart);
                $this->boldCell($sheet, 'A' . $secondStart . ':L' . $secondStart + 1);
                $sheet->mergeCells('A' . $secondStart + 1 . ':L' . $secondStart + 1);

                $lastRow = $sheet->getHighestRow();
                $this->applyBorder($sheet, 'A9:L' . $lastRow - 1);
                $this->centerCell($sheet, 'A1:L' . $lastRow);
                $this->boldCell($sheet, 'A2:L9');
                $this->italicCell($sheet, 'A7:L7');
                $this->centerCell($sheet, 'A' . $lastRow . ':L' . $lastRow);
                $sheet->getRowDimension($lastRow)->setRowHeight(height: 30);

                $sheet->getStyle('J')->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle('K')->getNumberFormat()->setFormatCode('#,##0');
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
