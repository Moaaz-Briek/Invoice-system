<?php

namespace App\Exports;

use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class InvoiceExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
            return Invoice::all();
//        return DB::table('invoices')->select('invoice_number')->get();
    }
}
