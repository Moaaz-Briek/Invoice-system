@extends('layouts.master')
@section('css')
<style>
    @media print {
        #print_button{
            display: none;
        }
    }
</style>
@endsection
@section('title')
    معاينة الفاتورة
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"><a href="/invoices">الفواتير</a></h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ طباعة</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row row-sm" id="content">
        <div class="col-md-12 col-xl-12" >
            <div class=" main-content-body-invoice">
                <div class="card card-invoice">
                    <div class="card-body">
                        <div class="invoice-header">
                            <h1 class="invoice-title">فاتورة تحصيل</h1>
                        </div><!-- invoice-header -->
                        <div class="row mg-t-20">
                            <div class="col-md">
                            </div>
                            <div class="col-md">
                                <label class="tx-gray-600">معلومات الفاتورة</label>
                                <p class="invoice-info-row"><span>رقم الفاتورة</span> <span>{{$invoices->invoice_number}}</span></p>
                                <p class="invoice-info-row"><span>القسم</span> <span>{{$invoices->section->section_name}}</span></p>
                                <p class="invoice-info-row"><span>تاريخ الإصدار:</span> <span>{{$invoices->invoice_Date}}</span></p>
                                <p class="invoice-info-row"><span>تاريخ الإستحقاق:</span> <span>{{$invoices->Due_date}}</span></p>
                            </div>
                        </div>
                        <div class="table-responsive mg-t-40">
                            <table class="table table-invoice border text-md-nowrap mb-0">
                                <thead>
                                <tr>
                                    <th class="wd-20p">#</th>
                                    <th class="wd-40p">المنتج</th>
                                    <th class="tx-center">مبلغ التحصيل</th>
                                    <th class="tx-right">مبلغ العمولة</th>
                                    <th class="tx-right">الإجمالى</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>1</td>
                                    <td class="tx-15">{{$invoices->product}}</td>
                                    <td class="tx-center">{{number_format($invoices->Amount_collection), 2}}</td>
                                    <td class="tx-right">{{number_format($invoices->Amount_Commission, 2)}}</td>
                                    @php
                                        $total = $invoices->Amount_collection + $invoices->Amount_Commission;
                                    @endphp
                                    <td class="tx-right">
                                        {{number_format($total, 2)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="valign-middle" colspan="2" rowspan="4">
                                        <div class="invoice-notes">
                                            <label class="main-content-label tx-13"><i class="fa fas-note">#ملاحظات</i></label>
                                            <p></p>
                                        </div><!-- invoice-notes -->
                                    </td>
                                    <td class="tx-right">الإجمالى</td>
                                    <td class="tx-right" colspan="2">{{number_format($total, 2)}}</td>
                                </tr>
                                <tr>
                                    <td class="tx-right">نسبة الضريبة</td>
                                    <td class="tx-right" colspan="2">{{$invoices->Rate_VAT}}</td>
                                </tr>
                                <tr>
                                    <td class="tx-right">قيمة الخصم</td>
                                    <td class="tx-right" colspan="2">{{$invoices->Value_VAT}}</td>
                                </tr>
                                <tr>
                                    <td class="tx-right tx-uppercase tx-bold tx-inverse">الإجمالى شامل الضريبة</td>
                                    <td class="tx-right" colspan="2">
                                        <h4 class="tx-primary tx-bold">{{number_format($invoices->Total, 2)}}</h4>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <button id="print_button" onclick="printDiv()" class="btn btn-danger float-right mt-3 mr-2">
                            <i class="mdi mdi-printer ml-1"></i>طباعة
                        </button>
                    </div>
                </div>
            </div>
        </div><!-- COL-END -->
    </div>
    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <!--Internal  Chart.bundle js -->
    <script src="{{URL::asset('assets/plugins/chart.js/Chart.bundle.min.js')}}"></script>
    <script>
        function printDiv()
        {
            var print_content = document.getElementById('content').innerHTML;
            var original_content = document.body.innerHTML;
            document.body.innerHTML = print_content;
            window.print();
            document.body.innerHTML = original_content;
            location.reload();
        }
    </script>
@endsection
