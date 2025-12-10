<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SimpleCollectionExport implements FromCollection, WithHeadings
{
    public function __construct(private readonly Collection $rows, private readonly ?array $headings = null)
    {
    }

    public function collection(): Collection
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return $this->headings ?? ($this->rows->first() ? array_keys($this->rows->first()) : []);
    }
}
