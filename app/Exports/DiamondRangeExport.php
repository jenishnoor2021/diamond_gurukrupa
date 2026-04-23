<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class DiamondRangeExport implements FromCollection, WithHeadings, WithStyles
{
  protected $diamonds;

  public function __construct($diamonds)
  {
    $this->diamonds = $diamonds;
  }

  public function collection()
  {
    return $this->diamonds->map(function ($diamond) {
      return [
        $diamond->barcode_number ?? 'N/A',
        $diamond->dimond_name ?? 'N/A',
        $diamond->weight,
        $diamond->shape ?? 'N/A',
        $diamond->color ?? 'N/A',
        $diamond->clarity ?? 'N/A',
        $diamond->cut ?? 'N/A',
        $diamond->polish ?? 'N/A',
        $diamond->symmetry ?? 'N/A',
        $diamond->status ?? 'N/A',
      ];
    });
  }

  public function headings(): array
  {
    return [
      'Barcode',
      'Diamond Name',
      'Weight',
      'Shape',
      'Color',
      'Clarity',
      'Cut',
      'Polish',
      'Symmetry',
      'Status',
    ];
  }

  public function styles($sheet)
  {
    return [
      // Style the header row
      1 => [
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '366092']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
      ],
    ];
  }
}
