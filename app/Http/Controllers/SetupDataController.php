<?php

namespace App\Http\Controllers;

use App\Helpers\ApiFormatter;
use App\Models\Part;
use App\Models\Reject;
use App\Models\RejectOnPart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class SetupDataController extends Controller
{
        /* handle routing name "setupdata.index" and parsing view */
        public function index(request $request)
        {
            return view('setupdata.index');
        }

        /* ================================================== PART SECTION ================================================== */

        /* handle routing name "setupdata.store_part dan save to table parts */
        public function store_part(Request $request)
        {
            $validator = Validator::make($request->all(), [
                "part_name" => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                Alert::toast('Validation Unsuccessful, Please review and correct your input.', 'error')->autoClose(3000);
                return redirect()->route('setupdata.index');
            }

            $namapart = Str::lower($request->part_name);
            $part = Part::where('part_name', $namapart)->first();

            if ($part) {
                Alert::toast('Part already exists.', 'error')->autoClose(3000);
                return redirect()->route('setupdata.index');
            } else {
                $create = Part::create([
                    'part_name' => Str::lower($request->part_name),
                    'created_by' => auth()->user()->nomor_pegawai,
                ]);
            }

            if ($create) {
                $createReject = RejectOnPart::create([
                    'parts_id' => $create->id,
                    'created_by' => auth()->user()->nomor_pegawai,
                ]);
                Alert::toast('Part successfully created.', 'success')->autoClose(3000);
            } else {
                Alert::toast('Failed to create part. Please try again.', 'error')->autoClose(3000);
            }
            return redirect()->route('setupdata.index');
        }

        /* handle routing name "setupdata.show_part" dan show data from table find by id */
        public function show_part(Request $request)
        {
            $id = $request->query('id') ?? null;

            if(!empty($id)){
                $data = Part::find($id);
            }else{
                $data = Part::all();
            }

            if (!empty($data)) {
                return ApiFormatter::createApi(200, 'Data found', $data);
            } else {
                return ApiFormatter::createApi(404, 'Data not found');
            }
        }

        /* handle routing from "setupdata.update" for update everyting on table user by id */
        public function update_part(Request $request)
        {
            $validator = Validator::make($request->all(), [
                "id" => 'required|numeric|exists:parts,id',
                "part_name" => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                Alert::toast('Validation Unsuccessful, Please review and correct your input.', 'error')->autoClose(3000);
                return redirect()->route('setupdata.index');
            }

            $part = Part::find($request->id);

            if (!$part) {
                Alert::toast('Part not found.', 'error')->autoClose(3000);
                return redirect()->route('setupdata.index');
            }

            $update = $part->update([
                'part_name' => Str::lower($request->part_name),
                'updated_by' => auth()->user()->nomor_pegawai,
            ]);

            if ($update) {
                Alert::toast('Part successfully edited.', 'success')->autoClose(3000);
            } else {
                Alert::toast('Failed to edit part. Please try again.', 'error')->autoClose(3000);
            }

            return redirect()->route('setupdata.index');

        }

        /* handle routing from "setupdata.destroy" to delete data user by id */
        public function destroy_part(Request $request)
        {
            $validator = Validator::make($request->all(), [
                "id" => 'required|numeric|exists:parts,id',
            ]);

            if ($validator->fails()) {
                Alert::toast('Validation Unsuccessful, Please review and correct your input.', 'error')->autoClose(3000);
                return redirect()->route('setupdata.index');
            }

            $part = Part::find($request->id);

            if (!$part) {
                Alert::toast('Part not found.', 'error')->autoClose(3000);
                return redirect()->route('setupdata.index');
            }

            $inputUpdateAt = $part->update([
                'updated_by' => auth()->user()->nomor_pegawai,
            ]);

            $delete = $part->delete();

            if ($delete) {
                Alert::toast('Part successfully deleted.', 'success')->autoClose(3000);
            } else {
                Alert::toast('Failed to delete part. Please try again.', 'error')->autoClose(3000);
            }

            return redirect()->route('setupdata.index');
        }

        /* handle routing from "setupdata.datatables_part" to show Datatables for Parts */
        public function datatables_part(Request $request)
        {
            if ($request->ajax()) {
                $data = Part::all();
                return DataTables::of($data)->addIndexColumn()
                    ->addColumn('action', function ($data) {
                        $btn = '<a class="btn fa-solid fa-pen-to-square fa-lg text-warning" onclick="editParts(\'' . $data->id . '\',\'' . $data->part_name . '\')"></a> | <a class="btn fa-solid fa-trash fa-lg text-danger" onclick="deleteParts(\'' . $data->id . '\',\'' . $data->part_name . '\')"></a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->addIndexColumn()
                    ->make(true);
            }
        }


        /* ================================================== REJECT SECTION ================================================== */

        /* handle routing name "setupdata.store_rejection dan save to table rejections */
        public function store_rejection(Request $request)
        {
            $validator = Validator::make($request->all(), [
                "reject_name" => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                Alert::toast('Validation Unsuccessful, Please review and correct your input.', 'error')->autoClose(3000);
                return redirect()->route('setupdata.index');
            }

            $namareject = Str::lower($request->reject_name);
            $reject = Reject::where('reject_name', $namareject)->first();

            if ($reject) {
                Alert::toast('Reject already exists.', 'error')->autoClose(3000);
                return redirect()->route('setupdata.index');
            }else{
                $create = Reject::create([
                    'reject_name' => Str::lower($request->reject_name),
                    'created_by' => auth()->user()->nomor_pegawai,
                ]);
            }

            if ($create) {
                Alert::toast('Reject successfully created.', 'success')->autoClose(3000);
            } else {
                Alert::toast('Failed to create reject. Please try again.', 'error')->autoClose(3000);
            }
            return redirect()->route('setupdata.index');
        }

        /* handle routing name "setupdata.show_rejection" dan show data from table find by id */
        public function show_rejection(Request $request)
        {
            $id = $request->query('id') ?? null;

            if(!empty($id)){
                $data = Reject::find($id);
            }else{
                $data = Reject::all();
            }

            if (!empty($data)) {
                return ApiFormatter::createApi(200, 'Data found', $data);
            } else {
                return ApiFormatter::createApi(404, 'Data not found');
            }
        }

        /* handle routing from "setupdata.update" for update everyting on table user by id */
        public function update_rejection(Request $request)
        {
            $validator = Validator::make($request->all(), [
                "id" => 'required|numeric|exists:rejects,id',
                "reject_name" => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                Alert::toast('Validation Unsuccessful, Please review and correct your input.', 'error')->autoClose(3000);
                return redirect()->route('setupdata.index');
            }

            $reject = Reject::find($request->id);

            if (!$reject) {
                Alert::toast('Reject not found.', 'error')->autoClose(3000);
                return redirect()->route('setupdata.index');
            }

            $update = $reject->update([
                'reject_name' => Str::lower($request->reject_name),
                'updated_by' => auth()->user()->nomor_pegawai,
            ]);

            if ($update) {
                Alert::toast('Reject successfully edited.', 'success')->autoClose(3000);
            } else {
                Alert::toast('Failed to edit reject. Please try again.', 'error')->autoClose(3000);
            }

            return redirect()->route('setupdata.index');

        }

        /* handle routing from "setupdata.destroy" to delete data user by id */
        public function destroy_rejection(Request $request)
        {
            $validator = Validator::make($request->all(), [
                "id" => 'required|numeric|exists:rejects,id',
            ]);

            if ($validator->fails()) {
                Alert::toast('Validation Unsuccessful, Please review and correct your input.', 'error')->autoClose(3000);
                return redirect()->route('setupdata.index');
            }

            $reject = Reject::find($request->id);

            if (!$reject) {
                Alert::toast('Reject not found.', 'error')->autoClose(3000);
                return redirect()->route('setupdata.index');
            }

            $inputUpdateAt = $reject->update([
                'updated_by' => auth()->user()->nomor_pegawai,
            ]);

            $delete = $reject->delete();

            if ($delete) {
                Alert::toast('Reject successfully deleted.', 'success')->autoClose(3000);
            } else {
                Alert::toast('Failed to delete reject. Please try again.', 'error')->autoClose(3000);
            }

            return redirect()->route('setupdata.index');
        }

        /* handle routing from "setupdata.datatables_rejection" to show Datatables for Rejection */
        public function datatables_rejection(Request $request)
        {
            if ($request->ajax()) {
                $data = Reject::all();
                return DataTables::of($data)->addIndexColumn()
                    ->addColumn('action', function ($data) {
                        $btn = '<a class="btn fa-solid fa-pen-to-square fa-lg text-warning" onclick="editReject(\'' . $data->id . '\',\'' . $data->reject_name . '\')"></a> | <a class="btn fa-solid fa-trash fa-lg text-danger" onclick="deleteReject(\'' . $data->id . '\',\'' . $data->reject_name . '\')"></a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->addIndexColumn()
                    ->make(true);
            }
        }


        /* ================================================== LIST REJECT SECTION ================================================== */

        /* handle routing name "setupdata.store_rejection_on_part dan save to table rejections */
        public function store_rejection_on_part(Request $request)
        {

            $validator = Validator::make($request->all(), [
                "part_id" => 'required|numeric',
            ]);

            if ($validator->fails()) {
                Alert::toast('Validation Unsuccessful, Please review and correct your input.', 'error')->autoClose(3000);
                return redirect()->route('setupdata.index');
            }

            $remove = RejectOnPart::where('parts_id', $request->part_id)->delete();

            if(!empty($request->rejects_id)){
                foreach ($request->rejects_id as $reject_id) {
                    $create = RejectOnPart::create([
                        'parts_id' => $request->part_id,
                        'rejects_id' => $reject_id,
                        'created_by' => auth()->user()->nomor_pegawai,
                    ]);
                }
            }else{
                $create = RejectOnPart::create([
                    'parts_id' => $request->part_id,
                    'created_by' => auth()->user()->nomor_pegawai,
                ]);
            }

            if ($create) {
                Alert::toast('Reject successfully created.', 'success')->autoClose(3000);
            } else {
                Alert::toast('Failed to create reject. Please try again.', 'error')->autoClose(3000);
            }
            return redirect()->route('setupdata.index');
        }

        /* handle routing name "setupdata.show_rejection_on_part how data from table find by id */
        public function show_rejection_on_part(Request $request)
        {
            $id = $request->query('id') ?? null;

            if(!empty($id)){
                $data = RejectOnPart::with(['toRejects'])->where('parts_id', $id)->get();
            }else{
                $data = RejectOnPart::all();
            }

            if (!empty($data)) {
                return ApiFormatter::createApi(200, 'Data found', $data);
            } else {
                return ApiFormatter::createApi(404, 'Data not found');
            }
        }

        /* Datatables for Rejection on Part */
        public function datatables_rejection_on_part(Request $request)
        {
            $resultData = [];
            $partsData = [];
            $dataRejection = RejectOnPart::with(['toParts', 'toRejects'])->get();

            foreach ($dataRejection as $value) {
                $id_partName = $value->parts_id;
                $partName = $value->toParts ? $value->toParts->part_name : 'N/A';
                $rejectName = $value->toRejects ? $value->toRejects->reject_name : 'N/A';

                /* Group rejections by part name */
                if (!isset($partsData[$partName])) {
                    $partsData[$partName] = [
                        'part_name_id' => $id_partName,
                        'part_name' => $partName,
                        'total_rejections' => 0,
                        'reject_id' => []
                    ];
                }

                /* Increment the total rejections count */
                $partsData[$partName]['total_rejections'] += $value->total_rejections;
                if ($rejectName !== 'N/A') {
                    $partsData[$partName]['reject_id'][] = [
                        'reject_name' => $rejectName
                    ];
                }
            }

            /* Convert the associative array to a numeric indexed array */
            foreach ($partsData as $part) {
                $resultData[] = $part;
            }

            /* Parsing To Datatables */

            if ($request->ajax()) {
                $data = $resultData;
                return DataTables::of($data)->addIndexColumn()
                ->addColumn('part_name', function ($data) {
                    return $data['part_name'];
                })
                ->addColumn('total_rejections', function ($data) {
                    return $data['total_rejections'];
                })
                ->addColumn('rejects', function ($data) {
                    // Combine reject names into a single string or a formatted list
                    $rejects = array_map(function($reject) {
                        $dataReject = $reject['reject_name'];
                        return '<span class="badge text-bg-danger p-2 fs-6">'. $dataReject .'</span>';
                    }, $data['reject_id']);
                    return implode('&ensp;', $rejects); // Or format as you prefer
                })
                ->addColumn('action', function ($data) {
                    $btn = '<a class="btn fa-solid fa-pen-to-square fa-lg text-warning" onclick="editRejectionOnPart(\'' . $data['part_name_id'] . '\', \'' . $data['part_name'] . '\')"></a>';
                    return $btn;
                })
                ->rawColumns(['action', 'rejects'])
                ->make(true);
            }
        }
}
