<?php

namespace App\Http\Controllers;

use App\Exports\InvoiceExport;
use App\Models\User;
use App\Notifications\AddInvoiceNotification;
use Illuminate\Support\Facades\Notification;
use Maatwebsite\Excel\Facades\Excel;
use App\Mail\Send_notify;
use App\Models\Attachment;
use App\Models\Invoice;
use App\Models\Invoice_details;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
class InvoiceController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:قائمة الفواتير', ['only' => ['index']]);
        $this->middleware('permission:الفواتير المدفوعة', ['only' => ['Paid_invoices']]);
        $this->middleware('permission:الفواتير الغير مدفوعة', ['only' => ['Unpaid_invoices']]);
        $this->middleware('permission:الفواتير المدفوعة جزئيا', ['only' => ['Partial_paid_invoices']]);
        $this->middleware('permission:ارشيف الفواتير', ['only' => ['archives']]);
        $this->middleware('permission:اضافة فاتورة', ['only' => ['create','store']]);
        $this->middleware('permission:تعديل الفاتورة', ['only' => ['edit','update']]);
        $this->middleware('permission:حذف الفاتورة', ['only' => ['destroy']]);
        $this->middleware('permission:تصدير EXCEL', ['only' => ['export']]);
        $this->middleware('permission:طباعةالفاتورة', ['only' => ['print_invoice']]);
        $this->middleware('permission:تغير حالة الدفع', ['only' => ['Status_Update', 'edit_invoice_status']]);
        $this->middleware('permission:الاشعارات', ['only' => ['send_notification_mail']]);
        $this->middleware('permission:الاشعارات', ['only' => ['send_notification_db']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = invoice::all();
        return view('invoices.invoices', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sections = Section::all();
        return view('invoices.add_invoice', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        Invoice::create([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
            ]);

        $invoice_id =DB::table('Invoices')->latest()->first()->id;
        Invoice_details::create([
            'id_Invoice' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $request->Section,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);

        if ($request->hasFile('pic')) {
            $invoice_id = DB::table('Invoices')->latest()->first()->id;
            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $invoice_number = $request->invoice_number;

            $attachments = new Attachment();
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->Created_by = Auth::user()->name;
            $attachments->invoice_id = $invoice_id;
            $attachments->save();

            // move pic
            $imageName = $request->pic->getClientOriginalName();
            $request->file('pic')->storeAs($invoice_number,$imageName,'Moaaz');


        }

        //$this->send_notification_mail($invoice_id);
        $this->send_notification_db();

        return redirect('/invoices');
    }

    public function send_notification_db()
    {
        $users = User::get();
        $invoice = Invoice::latest()->first();
        Notification::send($users, new AddInvoiceNotification($invoice));
    }
    public function send_notification_mail($invoice_id)
    {
        //Send notification using Mailgun
        $email = auth()->user()->email;
        $url = 'http://127.0.0.1:8000/InvoiceDetails/' . $invoice_id;
        Mail::to($email)->send(new Send_notify($url));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoices = Invoice::where('id', $id)->first();
        $sections = Section::all();
        return view('invoices.edit_invoice', compact('invoices', 'sections'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        invoice::where('id', $request->invoice_id)->update([

            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'note' => $request->note,
        ]);

        session()->flash('edit', 'تم تعديل الفاتورة بنجاح');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->invoice_id;
        $invoices = invoice::where('id', $id)->first();
        $Details = Invoice_details::where('id_Invoice', $id)->first();

        $id_page =$request->id_page;

        if ($id_page==1) {

            if (!empty($Details->invoice_number)) {

                Storage::disk('Moaaz')->deleteDirectory($Details->invoice_number);
            }

            $invoices->forceDelete();
            session()->flash('delete_invoice');
            return redirect('/invoices');
        }
        else {
            $invoices->delete();
            session()->flash('archive_invoice');
            return redirect('/archives');
        }

    }

    public function getproducts($id)
    {
        $products = DB::table("products")->where("section_id", $id)->pluck("Product_name", "id");
        return json_encode($products);
    }

    public function edit_invoice_status($id)
    {
        $invoices = Invoice::where('id', $id)->first();
        $sections = Section::all();
        return view('invoices.edit_invoice_status', compact('invoices', 'sections'));
    }

    public function Status_Update(Request $request)
    {
        if($request->Status === "مدفوعة")
        {
            Invoice::where('id', $request->id)->update([
                'Value_Status' => 1,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);
        }
        else
        {
            Invoice::where('id', $request->id)->update([
                'Value_Status' => 3,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,

            ]);
        }
        Invoice_details::create([
            'id_Invoice' => $request->invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $request->Section,
            'Status' => $request->Status,
            'Value_Status' => 1,
            'note' => $request->note,
            'Payment_Date' => $request->Payment_Date,
            'user' => (Auth::user()->name),
        ]);
        session()->flash('Status_Update');
        return redirect('/invoices');
    }

    public function Paid_invoices()
    {
        $invoices = Invoice::where('Value_Status', 1)->get();
        return view('invoices.Paid_invoices', compact('invoices'));
    }

    public function Unpaid_invoices()
    {
        $invoices = Invoice::where('Value_Status', 2)->get();
        return view('invoices.Unpaid_invoices', compact('invoices'));
    }
    public function Partial_paid_invoices()
    {
        $invoices = Invoice::where('Value_Status', 3)->get();
        return view('invoices.Partial_paid_invoices', compact('invoices'));
    }

    public function print_invoice($id)
    {
        $invoices = Invoice::where('id', $id)->first();
//        return $invoices;
        return view('invoices.print_invoice', compact('invoices'));
    }

    public function export()
    {
        return Excel::download(new InvoiceExport, 'users.xlsx');
    }

    public function MarkAsRead_all()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }
    public function MarkAsRead($id)
    {
        $notification = auth()->user()->notifications->where('id', $id)->first();

        $inv_id = $notification->data['id'];
        $invoices = Invoice::where('id', $inv_id)->get()->first();
        $details = Invoice_details::where('id_Invoice', $inv_id)->get();
        $attachments = Attachment::where('invoice_id', $inv_id)->get();

        if ($notification) {
            $notification->markAsRead();
            return view('invoices.details_invoices', compact('invoices', 'details', 'attachments'));
        }
    }
}
