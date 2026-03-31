<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CampaignDonation;
use App\Models\Donation;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExportController extends Controller
{
    public function exportDonations(Request $request)
    {
        $filter = $request->input('status');

        $query = Donation::with('paymentMethod')->latest();
        if ($filter) {
            $query->where('status', $filter);
        }
        $donations = $query->get();

        $spreadsheet = $this->buildDonationSpreadsheet($donations);

        return $this->download($spreadsheet, 'donasi-umum-' . now()->format('Y-m-d-His'));
    }

    public function exportCampaignDonations(Request $request)
    {
        $filter = $request->input('status');

        $query = CampaignDonation::with(['campaign', 'paymentMethod'])->latest();
        if ($filter) {
            $query->where('status', $filter);
        }
        $donations = $query->get();

        $spreadsheet = $this->buildCampaignDonationSpreadsheet($donations);

        return $this->download($spreadsheet, 'donasi-kampanye-' . now()->format('Y-m-d-His'));
    }

    protected function buildDonationSpreadsheet($donations)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header style
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF10B981'],
            ],
        ];

        $headers = ['No', 'TRX ID', 'Nama Donatur', 'Telepon', 'Tipe', 'Bank', 'Jumlah', 'Anonim', 'Status', 'Tanggal'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        // Apply header style
        $sheet->getStyle('A1:' . $this->colLetter(count($headers)) . '1')->applyFromArray($headerStyle);

        // Data rows
        foreach ($donations as $index => $donation) {
            $row = $index + 2;
            $typeLabel = match ((string) $donation->type) {
                'wakaf_pembangunan' => 'Wakaf Pembangunan',
                'wakaf_produktif' => 'Wakaf Produktif',
                default => 'Donasi Pendidikan',
            };

            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $donation->trx_id);
            $sheet->setCellValue('C' . $row, $donation->is_anonymous ? 'Anonim' : $donation->donatur_name);
            $sheet->setCellValue('D' . $row, $donation->phone ?? '-');
            $sheet->setCellValue('E' . $row, $typeLabel);
            $sheet->setCellValue('F' . $row, $donation->paymentMethod ? $donation->paymentMethod->bank_name : '-');
            $sheet->setCellValue('G' . $row, (int) $donation->amount);
            $sheet->setCellValue('H' . $row, $donation->is_anonymous ? 'Ya' : 'Tidak');
            $sheet->setCellValue('I' . $row, ucfirst($donation->status));
            $sheet->setCellValue('J' . $row, $donation->created_at->setTimezone('Asia/Jakarta')->format('d/m/Y H:i'));

            // Format amount as number
            $sheet->getStyle('G' . $row)->getNumberFormat()->setFormatCode('#,##0');
        }

        // Auto-size columns
        foreach (range('A', $this->colLetter(count($headers))) as $letter) {
            $sheet->getColumnDimension($letter)->setAutoSize(true);
        }

        // Row 1 height
        $sheet->getRowDimension(1)->setRowHeight(20);

        return $spreadsheet;
    }

    protected function buildCampaignDonationSpreadsheet($donations)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF10B981'],
            ],
        ];

        $headers = ['No', 'TRX ID', 'Kampanye', 'Nama Donatur', 'Telepon', 'Bank', 'Jumlah', 'Anonim', 'Status', 'Tanggal'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        $sheet->getStyle('A1:' . $this->colLetter(count($headers)) . '1')->applyFromArray($headerStyle);

        foreach ($donations as $index => $donation) {
            $row = $index + 2;

            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $donation->trx_id);
            $sheet->setCellValue('C' . $row, $donation->campaign ? $donation->campaign->title : '-');
            $sheet->setCellValue('D' . $row, $donation->is_anonymous ? 'Anonim' : $donation->donatur_name);
            $sheet->setCellValue('E' . $row, $donation->phone ?? '-');
            $sheet->setCellValue('F' . $row, $donation->paymentMethod ? $donation->paymentMethod->bank_name : '-');
            $sheet->setCellValue('G' . $row, (int) $donation->amount);
            $sheet->setCellValue('H' . $row, $donation->is_anonymous ? 'Ya' : 'Tidak');
            $sheet->setCellValue('I' . $row, ucfirst($donation->status));
            $sheet->setCellValue('J' . $row, $donation->created_at->setTimezone('Asia/Jakarta')->format('d/m/Y H:i'));

            $sheet->getStyle('G' . $row)->getNumberFormat()->setFormatCode('#,##0');
        }

        foreach (range('A', $this->colLetter(count($headers))) as $letter) {
            $sheet->getColumnDimension($letter)->setAutoSize(true);
        }

        $sheet->getRowDimension(1)->setRowHeight(20);

        return $spreadsheet;
    }

    protected function download(Spreadsheet $spreadsheet, $filename)
    {
        ob_start();
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        $output = ob_get_clean();

        return response($output, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.xlsx"',
        ]);
    }

    protected function colLetter(int $index): string
    {
        $letter = '';
        while ($index > 0) {
            $letter = chr(($index - 1) % 26 + 65) . $letter;
            $index = (int) (($index - 1) / 26);
        }
        return $letter;
    }
}
