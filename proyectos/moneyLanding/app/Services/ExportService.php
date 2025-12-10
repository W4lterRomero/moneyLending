<?php

namespace App\Services;

use App\Exports\SimpleCollectionExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class ExportService
{
    public function toExcel(Collection $rows, string $filename)
    {
        return Excel::download(new SimpleCollectionExport($rows), $filename);
    }

    public function toPdf(View $view, string $filename)
    {
        $pdf = Pdf::loadHTML($view->render());

        return $pdf->download($filename);
    }
}
