<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ActiveProcessReportExport implements FromArray, WithHeadings, WithCustomStartCell, WithEvents, WithTitle
{
    // Heading row; rows 1-2 hold the report title and filter summary
    const HEADING_ROW = 4;
    // Columns before the step columns: Sr, Name, Mobile, Created Date, Process, Status, Progress
    const FIXED_COLUMNS = 7;

    protected $headings;
    protected $rows;
    protected $filters;

    public function __construct(array $headings, array $rows, array $filters = [])
    {
        $this->headings = $headings;
        $this->rows = $rows;
        $this->filters = $filters;
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function startCell(): string
    {
        return 'A' . self::HEADING_ROW;
    }

    public function title(): string
    {
        return 'Active Process Report';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $columnCount = count($this->headings);
                $lastColumn = Coordinate::stringFromColumnIndex($columnCount);
                $headingRow = self::HEADING_ROW;
                $firstDataRow = $headingRow + 1;
                $lastRow = $headingRow + max(count($this->rows), 1);

                // Title + filter summary
                $sheet->mergeCells('A1:' . $lastColumn . '1');
                $sheet->mergeCells('A2:' . $lastColumn . '2');
                $sheet->setCellValue('A1', 'Active Process Report');
                $sheet->setCellValue('A2', implode('   |   ', array_map(function ($label, $value) {
                    return $label . ': ' . $value;
                }, array_keys($this->filters), $this->filters)));
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1F3864']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => ['italic' => true, 'size' => 10, 'color' => ['rgb' => '1F3864']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D9E1F2']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(30);
                $sheet->getRowDimension(2)->setRowHeight(20);

                // Heading row
                $headingRange = 'A' . $headingRow . ':' . $lastColumn . $headingRow;
                $sheet->getStyle($headingRange)->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2F5597']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                ]);
                $sheet->getRowDimension($headingRow)->setRowHeight(32);

                // Borders + alignment for the whole table
                $tableRange = 'A' . $headingRow . ':' . $lastColumn . $lastRow;
                $sheet->getStyle($tableRange)->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'BFBFBF']]],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getStyle('A' . $firstDataRow . ':A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('C' . $firstDataRow . ':' . $lastColumn . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('E' . $firstDataRow . ':E' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('C' . $firstDataRow . ':C' . $lastRow)->getNumberFormat()->setFormatCode('0');

                // Column widths
                $widths = ['A' => 8, 'B' => 28, 'C' => 16, 'D' => 14, 'E' => 24, 'F' => 11, 'G' => 11];
                foreach ($widths as $column => $width) {
                    $sheet->getColumnDimension($column)->setWidth($width);
                }
                for ($i = self::FIXED_COLUMNS + 1; $i <= $columnCount; $i++) {
                    $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($i))->setWidth(15);
                }

                // Cell colours: status + each step (green = done, red = pending, grey = not part of process)
                foreach ($this->rows as $index => $row) {
                    $rowNumber = $firstDataRow + $index;
                    $sheet->getRowDimension($rowNumber)->setRowHeight(18);

                    $statusCell = 'F' . $rowNumber;
                    $sheet->getStyle($statusCell)->applyFromArray($row[5] == 'Closed'
                        ? $this->cellStyle('C6EFCE', '006100', true)
                        : $this->cellStyle('FFEB9C', '9C5700', true));

                    for ($i = self::FIXED_COLUMNS; $i < $columnCount; $i++) {
                        $value = $row[$i] ?? '';
                        $cell = Coordinate::stringFromColumnIndex($i + 1) . $rowNumber;
                        if ($value === 'Pending') {
                            $style = $this->cellStyle('FFC7CE', '9C0006');
                        } elseif ($value === '-' || $value === '') {
                            $style = $this->cellStyle('F2F2F2', 'A6A6A6');
                        } else {
                            $style = $this->cellStyle('C6EFCE', '006100');
                        }
                        $sheet->getStyle($cell)->applyFromArray($style);
                    }
                }

                // Freeze heading + client name, enable filter dropdowns
                $sheet->freezePane('C' . $firstDataRow);
                $sheet->setAutoFilter($headingRange);
                $sheet->setSelectedCell('A' . $firstDataRow);
            },
        ];
    }

    private function cellStyle($fill, $font, $bold = false)
    {
        return [
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $fill]],
            'font' => ['color' => ['rgb' => $font], 'bold' => $bold],
        ];
    }
}
