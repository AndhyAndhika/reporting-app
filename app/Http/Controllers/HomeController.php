<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\ReportDaily;
use Illuminate\Http\Request;
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
        $dateNow = now()->format('Y-m-d');

        $data = ReportDaily::with(['toParts', 'toRejects'])->where('rejects_id', '!=', null)->whereDate('created_at', $dateNow)->get();

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
        return view('home', compact('part'));
    }
}
