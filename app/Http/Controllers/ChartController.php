<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public static function Bar()
    {
        $count_all =Invoice::count();
        $count_invoices1 = Invoice::where('Value_Status', 1)->count();
        $count_invoices2 = Invoice::where('Value_Status', 2)->count();
        $count_invoices3 = Invoice::where('Value_Status', 3)->count();

        if($count_invoices2 == 0){
            $nspainvoices2=0;
        }
        else{
            $nspainvoices2 = number_format($count_invoices2/ $count_all*100, 2);
        }

        if($count_invoices1 == 0){
            $nspainvoices1=0;
        }
        else{
            $nspainvoices1 = number_format($count_invoices1/ $count_all*100, 2);
        }

        if($count_invoices3 == 0){
            $nspainvoices3=0;
        }
        else{
            $nspainvoices3 = number_format($count_invoices3/ $count_all*100, 2);
        }

        $chartjs = app()->chartjs
            ->name('barChartTest')
            ->type('bar')
            ->size(['width' => 350, 'height' => 200])
            ->labels(['الفواتير الغير المدفوعة', 'الفواتير المدفوعة','الفواتير المدفوعة جزئيا'])
            ->datasets([
                [
                    "label" => "الفواتير الغير مدفوعة",
                    'backgroundColor' => ['blue'],
                    'data' => [$nspainvoices2]
                ],
                [
                    "label" => "الفواتير المدفوعة",
                    'backgroundColor' => ['red'],
                    'data' => [$nspainvoices1]
                ],
                [
                    "label" => "الفواتير المدفوعة جزئياً",
                    'backgroundColor' => ['green'],
                    'data' => [$nspainvoices3]
                ],
            ])
            ->options([
                'layout' => [
                    'margin' => [
                        'right' => '10'
                    ]
                        ]
                    ]);

            $chartjs_2 = app()->chartjs
            ->name('pieChartTest')
            ->type('pie')
            ->size(['width' => 340, 'height' => 200])
            ->labels(['الفواتير الغير المدفوعة', 'الفواتير المدفوعة','الفواتير المدفوعة جزئيا'])
            ->datasets([
                [
                    'backgroundColor' => ['#ec5858', '#81b214','#ff9642'],
                    'data' => [$nspainvoices2, $nspainvoices1,$nspainvoices3]
                ]
            ])
            ->options([]);
        return [$chartjs, $chartjs_2];
    }
}
