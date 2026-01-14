<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Dimond;
use App\Models\Company;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;

class DiamondSlipStyledExport implements FromCollection, WithEvents, WithTitle
{
    protected $selectedIds;
    protected $party_name;

    public function __construct($selectedIds, $party_name)
    {
        $this->selectedIds = $selectedIds;
        $this->party_name = $party_name;
    }

    public function collection()
    {
        return collect([]); // हम data manually insert करेंगे AfterSheet में
    }

    public function title(): string
    {
        return 'Diamond Slip';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $diamonds = Dimond::whereIn('id', $this->selectedIds)->get();
                $company = Company::first();

                $gstin = $company->gst_no;
                $hsn = "AIZPB0708M";
                $date = Carbon::now()->format('d-m-Y');
                $party = strtoupper($this->party_name);

                // Header for both copies
                $sheet->mergeCells('A1:G1');
                $sheet->mergeCells('I1:O1');
                $sheet->setCellValue('A1', $company->name);
                $sheet->setCellValue('I1', $company->name);

                $sheet->getStyle('A1:O1')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A1:O1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Address
                $address = $company->address;
                $sheet->mergeCells('A2:G2');
                $sheet->mergeCells('I2:O2');
                $sheet->setCellValue('A2', $address);
                $sheet->setCellValue('I2', $address);
                $sheet->getStyle('A2:O2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setWrapText(true);

                // Date and To
                $sheet->setCellValue('A4', "Date: $date");
                $sheet->setCellValue('D4', "To: $party");
                $sheet->setCellValue('I4', "Date: $date");
                $sheet->setCellValue('L4', "To: $party");

                // GSTIN + HSN
                $sheet->setCellValue('A6', "GSTIN: $gstin");
                $sheet->setCellValue('D6', "HSN: $hsn");
                $sheet->setCellValue('I6', "GSTIN: $gstin");
                $sheet->setCellValue('L6', "HSN: $hsn");

                // Table Header
                $headers = ['D Name', 'R W', 'P W', 'S', 'Cut', 'Amt.', 'D Date'];
                $sheet->fromArray($headers, null, 'A8');
                $sheet->fromArray($headers, null, 'I8');
                $sheet->getStyle('A8:G8')->getFont()->setBold(true);
                $sheet->getStyle('I8:O8')->getFont()->setBold(true);

                // Table Data
                $row = 9;
                $totalRW = 0;
                $totalPW = 0;

                foreach ($diamonds as $diamond) {
                    $sheet->setCellValue("A$row", $diamond->dimond_name);
                    $sheet->setCellValue("B$row", $diamond->weight);
                    $sheet->setCellValue("C$row", $diamond->required_weight);
                    $sheet->setCellValue("D$row", $diamond->shape);
                    $sheet->setCellValue("E$row", $diamond->cut);
                    $sheet->setCellValue("F$row", $diamond->amount);
                    $sheet->setCellValue("G$row", Carbon::parse($diamond->delevery_date)->format('d-m-Y'));

                    // duplicate to right side copy
                    $sheet->setCellValue("I$row", $diamond->dimond_name);
                    $sheet->setCellValue("J$row", $diamond->weight);
                    $sheet->setCellValue("K$row", $diamond->required_weight);
                    $sheet->setCellValue("L$row", $diamond->shape);
                    $sheet->setCellValue("M$row", $diamond->cut);
                    $sheet->setCellValue("N$row", $diamond->amount);
                    $sheet->setCellValue("O$row", Carbon::parse($diamond->delevery_date)->format('d-m-Y'));

                    $totalRW += $diamond->weight;
                    $totalPW += $diamond->required_weight;
                    $row++;
                }

                // Total Row
                $sheet->setCellValue("A$row", 'Total');
                $sheet->setCellValue("B$row", number_format($totalRW, 2));
                $sheet->setCellValue("C$row", number_format($totalPW, 2));
                $sheet->setCellValue("I$row", 'Total');
                $sheet->setCellValue("J$row", number_format($totalRW, 2));
                $sheet->setCellValue("K$row", number_format($totalPW, 2));

                // Borders
                $sheet->getStyle("A8:G$row")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle("I8:O$row")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                // Authorized sign
                $signRow = $row + 3;
                $sheet->mergeCells("A$signRow:G$signRow");
                $sheet->mergeCells("I$signRow:O$signRow");
                $sheet->setCellValue("A$signRow", "------------------------------");
                $sheet->setCellValue("I$signRow", "------------------------------");
                $sheet->setCellValue("A" . ($signRow + 1), "Authorized sign");
                $sheet->setCellValue("I" . ($signRow + 1), "Authorized sign");

                $sheet->getStyle("A" . ($signRow + 1) . ":O" . ($signRow + 1))
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Adjust column width
                foreach (range('A', 'O') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
