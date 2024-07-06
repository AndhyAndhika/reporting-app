<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\ReportDaily;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $part = Part::all();

        /* ROCK Algoritma  */
        $dateNow = now()->format('Y-m-d');
        // $dateNow = "2024-06-30";

        $data = ReportDaily::with(['toParts', 'toRejects'])->where('rejects_id', '!=', null)->whereDate('report_date', $dateNow)->get();

        $summary = [];
        $totals = [];

        /* Then build the summary array */
        foreach ($data as $value) {
            $partName = $value->toParts->part_name;
            $rejectName = $value->toRejects ? $value->toRejects->reject_name : 'Total Production';
            $key = $partName . '|' . $rejectName;

            if (!isset($summary[$key])) {
                $summary[$key] = [
                    'name_part' => $partName,
                    'name_reject' => $rejectName,
                    'qty' => 0,
                    'created_by' => $value->created_by,
                ];
            }

            if ($value->rejects_id !== null) {
                $summary[$key]['qty'] += $value->qty;
            }
        }

        /* Sort the summary array by qty */
        uasort($summary, function($a, $b) {
            return $b['qty'] - $a['qty'];
        });

        if ($request->ajax()) {
            return DataTables::of(array_values($summary))
                ->addIndexColumn()
                ->make(true);
        }

        /* Distribution Algoritma */
        $dataMeanReport = ReportDaily::select(DB::raw('DATE(report_date) as date'), DB::raw('SUM(total_production) as total_production_sum'))
        ->where('total_production', '!=', null)
        ->whereDate('report_date', $dateNow)->first();

        $meanReport = $dataMeanReport->total_production_sum;
        $meanReject = 0;
        foreach ($summary as $item) {
            $meanReject += $item['qty'];
        }
        return view('home', compact('part', 'meanReport', 'meanReject'));
    }
}
