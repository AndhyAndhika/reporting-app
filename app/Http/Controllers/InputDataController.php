<?php

namespace App\Http\Controllers;

use App\Helpers\ApiFormatter;
use App\Models\Part;
use App\Models\ReportDaily;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;


class InputDataController extends Controller
{
    /* handle routing name "inputdata.index" and parsing view */
    public function index(request $request)
    {
        $dataPart = Part::all();
        return view('inputdata.index', compact('dataPart'));
    }

    /*  handle routing name "inputdata.store_data" and parsing view */
    public function store_data(request $request)
    {
        $validator = Validator::make($request->all(), [
            "_token" => 'required',
            "tanggal" => 'required|date',
            "Namapart" => 'required',
            "Namapart_id" => 'required|numeric',
        ]);

        if ($validator->fails()) {
            Alert::toast('Validation Unsuccessful, Please review and correct your input.', 'error')->autoClose(3000);
            return ApiFormatter::createApi(400, 'Validation Unsuccessful, Please review and correct your input.', $validator->errors());
        }

        /* for handle if just input total_production */
        if($request->total_production != null && $request->dataQty == null){
            $validator = Validator::make($request->all(), [
                "total_production" => 'required|numeric',
            ]);

            if ($validator->fails()) {
                Alert::toast('Validation Unsuccessful, Please review and correct your input.', 'error')->autoClose(3000);
                return ApiFormatter::createApi(400, 'Validation Unsuccessful, Please review and correct your input.', $validator->errors());
            }

            $create = ReportDaily::create([
                'report_date' => $request->tanggal,
                'parts_id' => $request->Namapart_id,
                'total_production' => $request->total_production,
                'created_by' => auth()->user()->nomor_pegawai,
            ]);
        }else{
            /* for handle if input all */
            $lastKey = array_key_last($request->dataQty);
            foreach ($request->dataQty as $key => $value) {
                $parts = explode('|', $value['reject_id']);

                $create = ReportDaily::create([
                    'report_date' => $request->tanggal,
                    'parts_id' => $request->Namapart_id,
                    'rejects_id' => $parts[1],
                    'qty' => $value['qty_reject'],
                    'created_by' => auth()->user()->nomor_pegawai,
                ]);
                if ($key === $lastKey) {
                    if ($request->total_production != null){
                        $create = ReportDaily::create([
                            'report_date' => $request->tanggal,
                            'parts_id' => $request->Namapart_id,
                            'total_production' => $request->total_production,
                            'created_by' => auth()->user()->nomor_pegawai,
                        ]);
                    }else{}
                }
            }
        }
        if ($create) {
            return ApiFormatter::createApi(200, 'Data has been successfully added.', $create);
        } else {
            return ApiFormatter::createApi(400, 'Data failed to add.', $create);
        }


    }

    /*  handle routing name "inputdata.show_data" and parsing view */
    public function show_data(Request $request)
    {
        $id = $request->query('id') ?? null;

        if(!empty($id)){
            $data = ReportDaily::with(['toParts', 'toRejects'])->where('id', $id)->latest()->first();
        }else{
            $data = ReportDaily::with(['toParts', 'toRejects'])->latest()->first();
        }

        if (!empty($data)) {
            $partName = $data->toParts ? $data->toParts->part_name : 'Nama Part Tidak Ditemukan';
            $rejectName = $data->toRejects ? $data->toRejects->reject_name : 'Total Production';
            $qty = $data->qty ? $data->qty : $data->total_production ;
            $summary = [
                'id' => $data->id,
                'name_part' => $partName,
                'name_reject' => $rejectName,
                'qty' => $qty,
                'created_by' => $data->created_by,
            ];
            return ApiFormatter::createApi(200, 'Data found', $summary);
        } else {
            return ApiFormatter::createApi(404, 'Data not found');
        }
    }

    /*  handle routing name "inputdata.datatables_resume" and parsing view */
    public function datatables_resume(Request $request)
    {
        $dateNow = now()->format('Y-m-d');

        $data = ReportDaily::with(['toParts', 'toRejects'])->whereDate('created_at', $dateNow)->get();

        $summary = [];
        $totals = [];

        // First, calculate the total production for each part
        foreach ($data as $value) {
            if ($value->rejects_id === null) {
                $partName = $value->toParts->part_name;
                if (!isset($totals[$partName])) {
                    $totals[$partName] = 0;
                }
                $totals[$partName] += $value->total_production;
            }
        }

        // Then build the summary array
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

        // Add the total production entry
        foreach ($totals as $partName => $totalProduction) {
            $totalKey = $partName . '|Total Production';
            $summary[$totalKey] = [
                'name_part' => $partName,
                'name_reject' => 'Total Production',
                'qty' => $totalProduction,
                'created_by' => $value->created_by,
            ];
        }

        // return $summary;

        if ($request->ajax()) {
            return DataTables::of(array_values($summary))
                ->addIndexColumn()
                ->make(true);
        }else{
            return $summary;
        }
    }
}
