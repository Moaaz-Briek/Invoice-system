<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerReportController extends Controller
{
    public function index()
    {
        $sections = Section::all();
        return view('reports.customers_report', compact('sections'));
    }
    public function search_customers(Request $request)
    {
         // if start, end date null
        if($request->Section && $request->product && $request->start_at == "" && $request->end_at == "") {
            $sections = Section::all();
            $details = Invoice::where('section_id', $request->Section)->where('product', $request->product)->get();
            return view('reports.customers_report', compact('sections'))->withDetails($details);
        }
        else{
            $sections = Section::all();
            $start_at = date($request->start_at);
            $end_at = date($request->end_at);

            $details = Invoice::where('section_id', $request->Section)
                ->where('product', $request->product)->whereBetween('invoice_Date', [$start_at,$end_at])->get();

            return view('reports.customers_report', compact('start_at', 'end_at', 'sections'))->withDetails($details);
        }
    }
}
