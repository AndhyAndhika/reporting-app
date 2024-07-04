<?php

namespace App\Http\Controllers;
use App\Helpers\ApiFormatter;
use App\Models\Part;
use App\Models\ReportDaily;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;

class ReportingController extends Controller
{
    /* handle routing name "reporting.index" and parsing view */
    public function index(request $request)
    {
        $dataPart = Part::all();
        return view('reporting.index', compact('dataPart'));
    }

    /* hanlde routing name "reporting.datatables_report" for show datatables and filter by start and end date with part id */
    public function datatables_report(request $request)
    {
        $startDate = $request->query('startDate') ?? null;
        $endDate = $request->query('endDate') ?? null;
        $namaPart_id = $request->query('namaPart') ?? null;
        $summary = [];
        $totals = [];

        if ($namaPart_id != 0) {
            $query = ReportDaily::with('toParts', 'toRejects')->where('parts_id', $namaPart_id)->whereDate('report_date', '>=', $startDate)->whereDate('report_date', '<=', $endDate)->get();
        } else {
            $query = ReportDaily::with('toParts', 'toRejects')->whereDate('report_date', '>=', $startDate)->whereDate('report_date', '<=', $endDate)->get();
        }

        /* First, calculate the total production for each part */
        foreach ($query as $value) {
            $partName = $value->toParts->part_name. '|Total Production|' . $value->report_date;
            if (!isset($totals[$partName])) {
                $totals[$partName] = 0;
            }
            if ($value->rejects_id === null) {
                $totals[$partName] += $value->total_production;
            }
        }

        /* Then build the summary array */
        foreach ($query as $value) {
            $partName = $value->toParts->part_name;
            $rejectName = $value->toRejects ? $value->toRejects->reject_name : 'Total Production';
            $key = $partName . '|' . $rejectName . '|' . $value->report_date;

            if (!isset($summary[$key])) {
                $summary[$key] = [
                    'name_part' => $partName,
                    'report_date' => date('d-m-Y', strtotime($value->report_date)),
                    'name_reject' => $rejectName,
                    'qty' => 0,
                    'created_by' => $value->created_by,
                ];
            }

            if ($value->rejects_id !== null) {
                $summary[$key]['qty'] += $value->qty;
            }
        }

        /* Add the total production entry */
        foreach ($totals as $partName => $totalProduction) {
            $totalKey = $partName;
            $parts = explode('|', $partName);
            $summary[$totalKey] = [
                'name_part' => $parts[0],
                'report_date' => date('d-m-Y', strtotime($parts[2])),
                'name_reject' => 'Total Production',
                'qty' => $totalProduction,
                'created_by' => $value->created_by,
            ];
        }

        /* parsing to datatables */
        if ($request->ajax()) {
            return DataTables::of(array_values($summary))
                ->addIndexColumn()
                ->make(true);
        }else{
            return $summary;
        }

    }

    /* handle routing name "reporting.index_details" and parsing view */
    public function index_details()
    {
        $dataPart = Part::all();
        return view('reporting.index_details', compact('dataPart'));
    }

    /*  hanlde routing name "reporting.update_details" for update data reporting daily by id*/
    public function update_details(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "id_editReport" => 'required|numeric|exists:report_dailies,id',
            "qty" => 'required|numeric',
        ]);

        if ($validator->fails()) {
            Alert::toast('Validation Unsuccessful, Please review and correct your input.', 'error')->autoClose(3000);
            return redirect()->route('reporting.index_details');
        }

        $ReportDaily = ReportDaily::find($request->id_editReport);

        if (!$ReportDaily) {
            Alert::toast('Report Daily not found.', 'error')->autoClose(3000);
            return redirect()->route('reporting.index_details');
        }

        if ($ReportDaily->rejects_id == null){
            $update = $ReportDaily->update([
                'total_production' => $request->qty,
                'updated_by' => auth()->user()->nomor_pegawai,
            ]);
        }else{
            $update = $ReportDaily->update([
                'qty' => $request->qty,
                'updated_by' => auth()->user()->nomor_pegawai,
            ]);
        }

        if ($update) {
            Alert::toast('Report Daily successfully edited.', 'success')->autoClose(3000);
        } else {
            Alert::toast('Failed to edit Report Daily. Please try again.', 'error')->autoClose(3000);
        }

        return redirect()->route('reporting.index_details');

    }

    /* hanlde routing name "reporting.delete_details" for delete data reporting daily by id */
    public function delete_details(Request $request){

        $validator = Validator::make($request->all(), [
            "id_reporting" => 'required|numeric|exists:report_dailies,id',
        ]);

        if ($validator->fails()) {
            Alert::toast('Validation Unsuccessful, Please review and correct your input.', 'error')->autoClose(3000);
            return redirect()->route('reporting.index_details');
        }

        $Report = ReportDaily::find($request->id_reporting);

        if (!$Report) {
            Alert::toast('Report Daily not found.', 'error')->autoClose(3000);
            return redirect()->route('reporting.index_details');
        }

        $inputUpdateAt = $Report->update([
            'updated_by' => auth()->user()->nomor_pegawai,
        ]);

        $delete = $Report->delete();

        if ($delete) {
            Alert::toast('Report successfully deleted.', 'success')->autoClose(3000);
        } else {
            Alert::toast('Failed to delete report. Please try again.', 'error')->autoClose(3000);
        }

        return redirect()->route('reporting.index_details');
    }

    /* hanlde routing name "reporting.datatables_details" for show datatables and filter by start and end date with part id */
    public function datatables_details(Request $request)
    {
        $startDate = $request->query('startDate') ?? null;
        $endDate = $request->query('endDate') ?? null;
        $namaPart_id = $request->query('namaPart') ?? null;

        if ($namaPart_id != 0) {
            $query = ReportDaily::with('toParts', 'toRejects')->where('parts_id', $namaPart_id)->whereDate('report_date', '>=', $startDate)->whereDate('report_date', '<=', $endDate)->get();
        } else {
            $query = ReportDaily::with('toParts', 'toRejects')->whereDate('report_date', '>=', $startDate)->whereDate('report_date', '<=', $endDate)->get();
        }

        if ($request->ajax()) {
            $data = $query;
            return DataTables::of($data)->addIndexColumn()
            ->addColumn('date_report', function ($data) {
                return date('d-m-Y', strtotime($data->report_date));
            })
            ->addColumn('name_part', function ($data) {
                return $data->toParts->part_name;
            })
            ->addColumn('name_reject', function ($data) {
                if ($data->rejects_id == null) {
                    return 'Total Production';
                }else{
                    return $data->toRejects->reject_name;
                }
                // return $data->toRejects->reject_name;
            })
            ->addColumn('qty', function ($data) {
                if ($data->rejects_id == null) {
                    return $data->total_production . ' pcs';
                }else {
                    return $data->qty . ' pcs';
                }
                // return $data->qty . ' pcs';
            })
            ->addColumn('action', function ($data) {
                if ($data->rejects_id == null) {
                    $btn = '<a class="btn fa-solid fa-pen-to-square fa-lg text-warning" onclick="editReport(\'' . $data->id . '\',\'' . "Total Production" . '\')"></a> | <a class="btn fa-solid fa-trash fa-lg text-danger" onclick="deleteReport(\'' . $data->id . '\',\'' . "Total Production" . '\')"></a>';
                }else{
                    $btn = '<a class="btn fa-solid fa-pen-to-square fa-lg text-warning" onclick="editReport(\'' . $data->id . '\',\'' . $data->toRejects->reject_name . '\')"></a> | <a class="btn fa-solid fa-trash fa-lg text-danger" onclick="deleteReport(\'' . $data->id . '\',\'' . $data->toRejects->reject_name . '\')"></a>';
                }
                // $btn = '<a class="btn fa-solid fa-pen-to-square fa-lg text-warning" onclick="editReport(\'' . $data->id . '\',\'' . $data->toRejects->reject_name . '\')"></a> | <a class="btn fa-solid fa-trash fa-lg text-danger" onclick="deleteReport(\'' . $data->id . '\',\'' . $data->toRejects->reject_name . '\')"></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }

    /* handle routing name "reporting.chart_stacked for handle to chart js staccked chart" */
    public function chart_stacked(Request $request)
    {
        $startDate = $request->query('startDate') ?? null;
        $endDate = $request->query('endDate') ?? null;
        $namaPart_id = $request->query('namaPart') ?? null;

        $summary = [];
        $totals = [];
        $labels = [];

        if ($namaPart_id != 0) {
            $query = ReportDaily::with('toParts', 'toRejects')->where('parts_id', $namaPart_id)->whereDate('report_date', '>=', $startDate)->whereDate('report_date', '<=', $endDate)->get();
        } else {
            $query = ReportDaily::with('toParts', 'toRejects')->whereDate('report_date', '>=', $startDate)->whereDate('report_date', '<=', $endDate)->get();
        }

        // Collect unique dates for labels
        foreach ($query as $value) {
            $date = \Carbon\Carbon::parse($value->report_date)->format('Y-m-d');
            if (!in_array($date, $labels)) {
                $labels[] = $date;
            }
        }

        // Initialize dataset arrays
        $rejectionData = array_fill(0, count($labels), 0);
        $goodQualityData = array_fill(0, count($labels), 0);

        // Build the summary data
        foreach ($query as $value) {
            $date = \Carbon\Carbon::parse($value->report_date)->format('Y-m-d');
            $index = array_search($date, $labels);

            if ($value->rejects_id !== null) {
                // Rejected items
                $rejectionData[$index] += $value->qty;
            } else {
                // Good quality items (assuming 'total_production' is total produced minus rejected)
                $goodQualityData[$index] += $value->total_production - $value->qty;
            }
        }

        // Calculate the final 'Good Quality' data
        for ($i = 0; $i < count($goodQualityData); $i++) {
            $goodQualityData[$i] -= $rejectionData[$i];
        }

        // Create the final data structure
        $data = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Rejection',
                    'data' => $rejectionData,
                    'backgroundColor' => 'rgba(220,53,69,255)', // red
                    'stack' => 'Stack 0',
                ],
                [
                    'label' => 'Good Quality',
                    'data' => $goodQualityData,
                    'backgroundColor' => 'rgba(25,135,84,255)', // green
                    'stack' => 'Stack 0',
                ],
            ],
        ];

        return ApiFormatter::createApi(200, 'success', $data);

    }
    /* handle routing name "reporting.chart_line for handle to chart js line chart" */
    public function chart_line(Request $request)
    {
        $startDate = $request->query('startDate') ?? null;
        $endDate = $request->query('endDate') ?? null;
        $namaPart_id = $request->query('namaPart') ?? null;

        $summary = [];
        $totals = [];
        $labels = [];
        $ppm = [];

        if ($namaPart_id != 0) {
            $query = ReportDaily::with('toParts', 'toRejects')->where('parts_id', $namaPart_id)->whereDate('report_date', '>=', $startDate)->whereDate('report_date', '<=', $endDate)->get();
        } else {
            $query = ReportDaily::with('toParts', 'toRejects')->whereDate('report_date', '>=', $startDate)->whereDate('report_date', '<=', $endDate)->get();
        }

        // Collect unique dates for labels
        foreach ($query as $value) {
            $date = \Carbon\Carbon::parse($value->report_date)->format('Y-m-d');
            if (!in_array($date, $labels)) {
                $labels[] = $date;
            }
        }

        // Initialize dataset arrays
        $rejectionData = array_fill(0, count($labels), 0);
        $goodQualityData = array_fill(0, count($labels), 0);

        // Build the summary data
        foreach ($query as $value) {
            $date = \Carbon\Carbon::parse($value->report_date)->format('Y-m-d');
            $index = array_search($date, $labels);

            if ($value->rejects_id !== null) {
                // Rejected items
                $rejectionData[$index] += $value->qty;
            } else {
                // Good quality items (assuming 'total_production' is total produced minus rejected)
                $goodQualityData[$index] += $value->total_production - $value->qty;
            }
        }

        // Calculate the final 'Good Quality' data
        for ($i = 0; $i < count($goodQualityData); $i++) {
            $goodQualityData[$i] -= $rejectionData[$i];
        }

        // Calculate NG / Produksi * 1.000.000
        for ($i = 0; $i < count($goodQualityData); $i++) {
            $ppm[$i] = round(($rejectionData[$i] / ($goodQualityData[$i] + $rejectionData[$i])) * 1000000);
        }


        // Create the final data structure
        $data = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Part Per Milion (PPM)',
                    'data' => $ppm,
                    'fill' => false,
                    'borderColor' => 'rgba(220,53,69,255)', //red
                    'tension' => 0.1
                ],
            ],
        ];

        return ApiFormatter::createApi(200, 'success', $data);
    }
}
