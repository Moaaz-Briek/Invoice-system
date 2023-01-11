<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceReportController extends Controller
{
    public function index()
    {
        return view('reports.invoices_report');
    }

    public function search_invoice(Request $request)
    {
        $rdio = $request->rdio;

//        search with the invoice type
        if($rdio == 1) {

            // if start, end date null
            if($request->type && $request->start_at == "" && $request->end_at == "") {

                $details = Invoice::where('Status', $request->type)->get();
                $type = $request->type;
                return view('reports.invoices_report', compact('type'))->withDetails($details);
            }
            else{
                $start_at = date($request->start_at);
                $end_at = date($request->end_at);
                $type = $request->type;
                $details = Invoice::whereBetween('invoice_Date', [$start_at, $end_at])->where('Status', '=', $type)->get();
                return view('reports.invoices_report', compact('start_at', 'end_at', 'type'))->withDetails($details);
            }
        }

        // Search with invoice number
        else{
            $details = Invoice::select('*')->where('invoice_number', $request->invoice_number)->get();
            return view('reports.invoices_report')->withDetails($details);
        }


    }
}
