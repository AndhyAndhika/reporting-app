<?php

namespace App\Http\Controllers;

use App\Helpers\ApiFormatter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class ManpowerController extends Controller
{
    /* handle routing name "manpower.index" and parsing view */
    public function index(request $request)
    {
        if ($request->ajax()) {
            $data = User::all();
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $btn = '<a class="btn fa-solid fa-pen-to-square fa-lg text-warning" onclick="editManpower(\'' . $data->id . '\',\'' . $data->name . '\')"></a> | <a class="btn fa-solid fa-key fa-lg text-primary" onclick="changePassword(\'' . $data->id . '\',\'' . $data->name . '\')"></a> | <a class="btn fa-solid fa-trash fa-lg text-danger" onclick="deleteData(\'' . $data->id . '\',\'' . $data->name . '\')"></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('manpower.index');
    }

    /* handle post data from "manpower.store" and create to table user */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "nomor_pegawai" => 'required|numeric',
            "name" => 'required|string|max:255',
            "position" => 'required|string',
            "email" => 'required|email|unique:users|max:255',
        ]);

        if ($validator->fails()) {
            Alert::toast('Validation Unsuccessful, Please review and correct your input.', 'error')->autoClose(3000);
            return redirect()->route('manpower.index');
        }

        $create = User::create([
            'nomor_pegawai' => $request->nomor_pegawai,
            'name' => $request->name,
            'role' => $request->position,
            'email' => $request->email,
            'password' => Hash::make($request->nomor_pegawai),
            'created_by' => auth()->user()->name,
        ]);

        if ($create) {
            Alert::toast('Manpower successfully created.', 'success')->autoClose(3000);
        } else {
            Alert::toast('Failed to create manpower. Please try again.', 'error')->autoClose(3000);
        }
        return redirect()->route('manpower.index');
    }

    /* handle routing from "manpower.show" and search user using id */
    public function show(string $id)
    {
        $data = User::find($id);
        if (!empty($data)) {
            return ApiFormatter::createApi(200, 'Data found', $data);
        } else {
            return ApiFormatter::createApi(404, 'Data not found');
        }
    }

    /* handle routing from "manpower.update" for update everyting on table user by id*/
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "id" => 'required|numeric|exists:users,id',
            "nomor_pegawai" => 'required|numeric',
            "name" => 'required|string|max:255',
            "position" => 'required|string',
            "email" => 'required|email|unique:users,email,' . $request->id . '|max:255',
        ]);

        if ($validator->fails()) {
            Alert::toast('Validation Unsuccessful, Please review and correct your input.', 'error')->autoClose(3000);
            return redirect()->route('manpower.index');
        }

        $user = User::find($request->id);

        if (!$user) {
            Alert::toast('User not found.', 'error')->autoClose(3000);
            return redirect()->route('manpower.index');
        }

        $update = $user->update([
            'nomor_pegawai' => $request->nomor_pegawai,
            'name' => $request->name,
            'role' => $request->position,
            'email' => $request->email,
            'updated_by' => auth()->user()->name,
        ]);

        if ($update) {
            Alert::toast('Manpower successfully edited.', 'success')->autoClose(3000);
        } else {
            Alert::toast('Failed to edit manpower. Please try again.', 'error')->autoClose(3000);
        }

        return redirect()->route('manpower.index');
    }

    /* handle routing from "manpower.update-password" for just update password user by id */
    public function update_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "id" => 'required|numeric|exists:users,id',
            "password" => 'required|min:5',
        ]);

        if ($validator->fails()) {
            Alert::toast('Validation Unsuccessful, Please review and correct your input.', 'error')->autoClose(3000);
            return redirect()->route('manpower.index');
        }

        $user = User::find($request->id);

        if (!$user) {
            Alert::toast('User not found.', 'error')->autoClose(3000);
            return redirect()->route('manpower.index');
        }

        $update = $user->update([
            'password' => Hash::make($request->password),
            'updated_by' => auth()->user()->name,
        ]);

        if ($update) {
            Alert::toast('Password successfully edited.', 'success')->autoClose(3000);
        } else {
            Alert::toast('Failed to edit password. Please try again.', 'error')->autoClose(3000);
        }

        return redirect()->route('manpower.index');
    }

    /* handle routing from "manpower.destroy" to delete data user by id */
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "id" => 'required|numeric|exists:users,id',
        ]);

        if ($validator->fails()) {
            Alert::toast('Validation Unsuccessful, Please review and correct your input.', 'error')->autoClose(3000);
            return redirect()->route('manpower.index');
        }

        $user = User::find($request->id);

        if (!$user) {
            Alert::toast('User not found.', 'error')->autoClose(3000);
            return redirect()->route('manpower.index');
        }

        $inputUpdateAt = $user->update([
            'updated_by' => auth()->user()->name,
        ]);

        $delete = $user->delete();

        if ($delete) {
            Alert::toast('Manpower successfully deleted.', 'success')->autoClose(3000);
        } else {
            Alert::toast('Failed to delete manpower. Please try again.', 'error')->autoClose(3000);
        }

        return redirect()->route('manpower.index');
    }
}
