<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use App\Models\Process;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class AlertDiamondsExport implements FromCollection, WithHeadings, WithStyles
{
  protected $diamonds;

  public function __construct($diamonds)
  {
    $this->diamonds = $diamonds;
  }

  public function collection()
  {
    return $this->diamonds->map(function ($diamond) {
      $partyName = '';
      if ($diamond->parties) {
        $partyName = trim(($diamond->parties->fname ?? '') . ' ' . ($diamond->parties->lname ?? ''));
      }

      $process = Process::where('dimonds_id', $diamond->id)->latest()->first();
      $processName = $process ? $process->designation : '';
      $pendingDays = $diamond->created_at ? $diamond->created_at->diffInDays(now()) : '';

      return [
        $partyName,
        $diamond->barcode_number ?? '',
        $diamond->dimond_name ?? '',
        $diamond->janger_no ?? '',
        $diamond->weight ?? '',
        $diamond->status ?? '',
        $diamond->created_at ? $diamond->created_at->format('Y-m-d') : '',
        $pendingDays,
        $processName,
      ];
    });
  }

  public function headings(): array
  {
    return [
      'Party',
      'Barcode',
      'Diamond Name',
      'Janger No',
      'Weight',
      'Status',
      'Created At',
      'Days Pending',
      'Process',
    ];
  }

  public function styles($sheet)
  {
    return [
      1 => [
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '366092']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
      ],
    ];
  }
}
