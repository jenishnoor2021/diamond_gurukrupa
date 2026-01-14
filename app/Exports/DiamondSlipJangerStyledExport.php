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

class DiamondSlipJangerStyledExport implements FromCollection, WithEvents, WithTitle
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
                $sheet->mergeCells('A1:H1');
                $sheet->mergeCells('J1:Q1');
                $sheet->setCellValue('A1', $company->name);
                $sheet->setCellValue('J1', $company->name);

                $sheet->getStyle('A1:Q1')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A1:Q1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Address
                $address = $company->address;
                $sheet->mergeCells('A2:H2');
                $sheet->mergeCells('J2:Q2');
                $sheet->setCellValue('A2', $address);
                $sheet->setCellValue('J2', $address);
                $sheet->getStyle('A2:Q2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setWrapText(true);

                // Date and To
                $sheet->setCellValue('A4', "Date: $date");
                $sheet->setCellValue('D4', "To: $party");
                $sheet->setCellValue('J4', "Date: $date");
                $sheet->setCellValue('M4', "To: $party");

                // GSTIN + HSN
                $sheet->setCellValue('A6', "GSTIN: $gstin");
                $sheet->setCellValue('D6', "HSN: $hsn");
                $sheet->setCellValue('J6', "GSTIN: $gstin");
                $sheet->setCellValue('M6', "HSN: $hsn");

                // Table Header
                $headers = ['D Name', 'J No', 'R W', 'P W', 'S', 'Cut', 'Amt.', 'D Date'];
                $sheet->fromArray($headers, null, 'A8');
                $sheet->fromArray($headers, null, 'J8');
                $sheet->getStyle('A8:H8')->getFont()->setBold(true);
                $sheet->getStyle('J8:Q8')->getFont()->setBold(true);

                // Table Data
                $row = 9;
                $totalRW = 0;
                $totalPW = 0;
                $total = 0;

                foreach ($diamonds as $diamond) {
                    $sheet->setCellValue("A$row", $diamond->dimond_name);
                    $sheet->setCellValue("B$row", $diamond->janger_no);
                    $sheet->setCellValue("C$row", $diamond->weight);
                    $sheet->setCellValue("D$row", $diamond->required_weight);
                    $sheet->setCellValue("E$row", $diamond->shape);
                    $sheet->setCellValue("F$row", $diamond->cut);
                    $sheet->setCellValue("G$row", $diamond->amount);
                    $sheet->setCellValue("H$row", Carbon::parse($diamond->delevery_date)->format('d-m-Y'));

                    // duplicate to right side copy
                    $sheet->setCellValue("J$row", $diamond->dimond_name);
                    $sheet->setCellValue("K$row", $diamond->janger_no);
                    $sheet->setCellValue("L$row", $diamond->weight);
                    $sheet->setCellValue("M$row", $diamond->required_weight);
                    $sheet->setCellValue("N$row", $diamond->shape);
                    $sheet->setCellValue("O$row", $diamond->cut);
                    $sheet->setCellValue("P$row", $diamond->amount);
                    $sheet->setCellValue("Q$row", Carbon::parse($diamond->delevery_date)->format('d-m-Y'));

                    $totalRW += $diamond->weight;
                    $totalPW += $diamond->required_weight;
                    $total += $diamond->amount;
                    $row++;
                }

                // Total Row
                $sheet->setCellValue("A$row", 'Total');
                $sheet->setCellValue("C$row", number_format($totalRW, 2));
                $sheet->setCellValue("D$row", number_format($totalPW, 2));
                $sheet->setCellValue("G$row", number_format($total, 2));
                $sheet->setCellValue("J$row", 'Total');
                $sheet->setCellValue("L$row", number_format($totalRW, 2));
                $sheet->setCellValue("M$row", number_format($totalPW, 2));
                $sheet->setCellValue("p$row", number_format($total, 2));

                // Borders
                $sheet->getStyle("A8:H$row")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle("J8:Q$row")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                // Authorized sign
                $signRow = $row + 3;
                $sheet->mergeCells("A$signRow:H$signRow");
                $sheet->mergeCells("J$signRow:Q$signRow");
                $sheet->setCellValue("A$signRow", "------------------------------");
                $sheet->setCellValue("J$signRow", "------------------------------");
                $sheet->setCellValue("A" . ($signRow + 1), "Authorized sign");
                $sheet->setCellValue("J" . ($signRow + 1), "Authorized sign");

                $sheet->getStyle("A" . ($signRow + 1) . ":Q" . ($signRow + 1))
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Adjust column width
                foreach (range('A', 'Q') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
