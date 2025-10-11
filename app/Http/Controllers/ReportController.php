<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Sale;
use App\Models\CashRegister;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->from ?? null;
        $to   = $request->to   ?? null;

        $sales = Sale::query()
            ->when($from, fn($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('created_at', '<=', $to));

        $cashes = CashRegister::query()
            ->when($from, fn($q) => $q->whereDate('opened_at', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('opened_at', '<=', $to));

        $metrics = [
            'ventas_total'   => $sales->sum('total'),
            'ventas_count'   => $sales->count(),
            'promedio_venta' => $sales->avg('total'),
            'cajas_abiertas' => (clone $cashes)->where('status', 'abierta')->count(),
            'cajas_cerradas' => (clone $cashes)->where('status', 'cerrada')->count(),
        ];

        return view('reports.index', [
            'metrics' => $metrics, 
            'from'    => $from, 
            'to'      => $to,
        ]);
    }

    public function exportPdf(Request $request, $type = 'sales')
    {
        if($type === 'sales')
        {
            $data = Sale::with('table', 'user')
                ->when($request->from, fn($q) => $q->whereDate('created_at', '>=', $request->from))
                ->when($request->to, fn($q) => $q->whereDate('created_at', '<=', $request->to))
                ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
                ->when($request->status, fn($q) => $q->where('status', $request->status))
                ->when($request->table_id, fn($q) => $q->where('table_id', $request->table_id))
                ->get();

            $view = 'reports.sales_pdf';
            $fileName = 'reporte_ventas.pdf';
        } else {
            $data = CashRegister::with('user')
                ->when($request->from, fn($q) => $q->whereDate('opened_at', '>=', $request->from))
                ->when($request->to, fn($q) => $q->whereDate('opened_at', '<=', $request->to))
                ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
                ->when($request->status, fn($q) => $q->where('status', $request->status))
                ->get();
            
            $view = 'reports.cash_pdf';
            $fileName = 'reporte_caja_'.now()->format('Ymd_His').'.pdf';
        }

        $pdf = Pdf::loadView($view, compact('data'))
                ->setPaper('a4', 'portrait');
        return $pdf->download($fileName);
    }

    public function exportExcel(Request $request, $type = 'sales')
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        if ($type === 'sales') {
            $sales = Sale::with('table', 'user')
                ->when($request->from, fn($q) => $q->whereDate('created_at', '>=', $request->from))
                ->when($request->to, fn($q) => $q->whereDate('created_at', '<=', $request->to))
                ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
                ->when($request->status, fn($q) => $q->where('status', $request->status))
                ->when($request->table_id, fn($q) => $q->where('table_id', $request->table_id))
                ->get();

            $headers = [
                'ID', 'Mesa', 'Fecha', 'Total', 'Método de Pago'
            ];

            $col = 'A';

            foreach ($headers as $head) {
                $sheet->setCellValue($col.'1', $head);
                $sheet->getStyle($col.'1')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
                $sheet->getStyle($col.'1')->getFill()->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('444444');
                $sheet->getStyle($col.'1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle($col.'1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                $col++;
            }

            

            $row = 2;
            foreach ($sales as $sale) {
                $sheet->setCellValue('A'.$row, $sale->id);
                $sheet->setCellValue('B'.$row, $sale->table->name ?? 'N/A');
                $sheet->setCellValue('C'.$row, $sale->created_at->format('Y-m-d H:i'));
                $sheet->setCellValue('D'.$row, $sale->total);
                $sheet->setCellValue('E'.$row, $sale->payment_method ?? 'N/A');
                
                $sheet->getStyle("A{$row}:F{$row}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                
                $row++;
            }

            $fileName = 'ventas_'.date('Ymd_His').'.xlsx';
        } else {
            $cashes = CashRegister::with('user')
                ->when($request->from, fn($q) => $q->whereDate('opened_at', '>=', $request->from))
                ->when($request->to, fn($q) => $q->whereDate('opened_at', '<=', $request->to))
                ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
                ->get();

            $headers = [
                'ID', 'Usuario', 'Apertura', 'Cierre', 'Estado', 'Monto Inicial', 'Monto Final', 'Diferencia'
            ];

            $col = 'A';

            foreach ($headers as $head) {
                $sheet->setCellValue($col.'1', $head);
                $sheet->getStyle($col.'1')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
                $sheet->getStyle($col.'1')->getFill()->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('444444');
                $sheet->getStyle($col.'1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle($col.'1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $col++;
            }

            $row = 2;
            foreach ($cashes as $cash) {
                $sheet->setCellValue('A'.$row, $cash->id);
                $sheet->setCellValue('B'.$row, $cash->user->name ?? 'N/A');
                $sheet->setCellValue('C'.$row, $cash->opened_at->format('Y-m-d H:i'));
                $sheet->setCellValue('D'.$row, $cash->closed_at ? $cash->closed_at->format('Y-m-d H:i') : 'Abierta');
                $sheet->setCellValue('E'.$row, ucfirst($cash->status));
                $sheet->setCellValue('F'.$row, $cash->opening_amount);
                $sheet->setCellValue('G'.$row, $cash->closing_amount ?? 0);
                $sheet->setCellValue('H'.$row, $cash->difference ?? 0);

                $sheet->getStyle("A{$row}:H{$row}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                $row++;
            }

            $fileName = 'caja_'.date('Ymd_His').'.xlsx';
        }

        foreach (range('A', $sheet->getHighestColumn()) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($temp_file);

        return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
    }
}
