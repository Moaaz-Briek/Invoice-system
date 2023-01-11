<?php


namespace App\Http\Controllers;
use App\Models\Attachment;
use App\Models\Attachments;
use App\Models\Invoice;
use App\Models\Invoice_details;
use Illuminate\Http\Request;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Nette\Utils\Image;

class InvoiceDetailsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:حذف المرفق', ['only' => ['destroy']]);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice_details  $invoice_details
     * @return \Illuminate\Http\Response
     */
//    public function show(Invoice_details $invoice_details)
//    {
//        //
//    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice_details  $invoice_details
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice_details $invoice_details)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice_details  $invoice_details
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice_details $invoice_details)
    {
        //
    }

    public function destroy(Request $request)
    {
        Attachment::where('id', $request->id_file)->delete();
        Storage::disk('Moaaz')->delete($request->invoice_number . '/' . $request->file_name);
        session()->flash('delete', ' تم حذف المرفق بنجاح.');
        return back();
    }

    public function DisplaySection($id)
    {
        $invoices = Invoice::where('id', $id)->first();
        $details = Invoice_details::where('id_Invoice', $id)->get();
        $attachments = Attachment::where('invoice_id', $id)->get();
        return view('invoices.details_invoices', compact('invoices', 'details', 'attachments'));
    }

    public function open_file($invoice_number, $file_name)
    {
        $file = public_path('Attach/') . $invoice_number.'/'.$file_name;
        return Response::file($file);
    }
    public function get_file($invoice_number, $file_name)
    {
        $file = public_path('Attach/') . $invoice_number.'/'.$file_name;
        return Response::download($file);
    }
}
