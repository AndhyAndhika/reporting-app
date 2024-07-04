@extends('layouts.app', ['title' => 'Dashboard'])
@section('content')
<div class="row mb-3">
    <p class="fw-bold h3">Manpower Section</p>
</div>
<div class="row">
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-header fw-bold font-monospace fs-5"><i class="fa-solid fa-clipboard-list"></i>
                List of Manpower
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <button type="button" class="btn btn-primary float-end" onclick="addNewManpower()"><i class="fa-solid fa-plus"></i> New Manpower</button>
                    </div>
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="Table_Manpower" class="table table-striped-columns table-hover table-bordered nowrap display  w-100" style="overflow-x: scroll">
                                <thead class="table-primary">
                                    <tr>
                                        <th class="text-center" style="width: 5%;">#</th>
                                        <th class="text-center" style="width: 5%;">MP CODE</th>
                                        <th class="text-center" style="width: 30%;">NAME</th>
                                        <th class="text-center" style="width: 20%;">POSITION</th>
                                        <th class="text-center" style="width: 30%;">EMAIL</th>
                                        <th class="text-center" style="width: 10%;">ACTION</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Area --}}
<x-modal id="modal_manpower">
    <x-slot name="title">New Manpower</x-slot>
    <x-slot name="body">
        <form id="Form_Manpower" action="{{ route('manpower.store') }}" method="POST" enctype="multipart/form-data" onsubmit="DisabledButtomSubmit()">
            @csrf
            <div class="row">
                <div class="col-2 mb-3">
                    <label for="nomor_pegawai" class="form-label">Manpower Number<sup class="text-danger">*</sup></label>
                    <input type="number" min="0" class="form-control" id="nomor_pegawai" name="nomor_pegawai" placeholder="0000" required>
                </div>
                <div class="col-4 mb-3">
                    <label for="name" class="form-label">Manpower Name<sup class="text-danger">*</sup></label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="manpower name" required>
                </div>
                <div class="col-2 mb-3">
                    <label for="position" class="form-label">Manpower Position<sup class="text-danger">*</sup></label>
                    <input type="text" class="form-control" id="position" name="position" placeholder="manpower position" required>
                </div>
                <div class="col-4 mb-3">
                    <label for="email" class="form-label">Manpower Email<sup class="text-danger">*</sup></label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                </div>
            </div>
            <div class="row">
                <div id="formTambahan"></div>
                <div class="col">
                    <button type="submit" id="submit" class="btn btn-primary float-end"><i class="fa-regular fa-floppy-disk"></i> Save</button>
                </div>
            </div>
        </form>
    </x-slot>
</x-modal>

<x-modal id="modal_changePassword">
    <x-slot name="title">Change Password</x-slot>
    <x-slot name="body">
        <form id="Form_changePassword" action="{{ route('manpower.update-password') }}" method="POST" enctype="multipart/form-data" onsubmit="validatePassword()">
            @csrf
            <div class="row">
                <div class="col-6 mb-3">
                    <label for="password" class="form-label">New Password<sup class="text-danger">*</sup></label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="password" required>
                </div>
                <div class="col-6 mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password<sup class="text-danger">*</sup></label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="confirm password" required>
                </div>
                <div class="col-12">
                    <div class="alert alert-warning" role="alert">
                        <i class="fa-solid fa-exclamation-triangle"></i> Password must be at least 8 characters long, contain at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character.
                    </div>
                </div>
            </div>
            <div class="row">
                <div id="formTambahan1"></div>
                <div class="col">
                    <button type="submit" id="submit" class="btn btn-primary float-end"><i class="fa-regular fa-floppy-disk"></i> Save</button>
                </div>
            </div>
        </form>
    </x-slot>
</x-modal>

<x-modal id="modal_deleteData">
    <x-slot name="title">Delete Data</x-slot>
    <x-slot name="body">
        <form id="Form_deleteData" action="{{ route('manpower.destroy') }}" method="POST" enctype="multipart/form-data" onsubmit="DisabledButtomSubmit()">
            @csrf
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="alert alert-danger" role="alert">
                        <i class="fa-solid fa-exclamation-triangle"></i> Are you sure you want to delete this data?
                    </div>
                </div>
            <div class="row">
                <div id="formTambahan2"></div>
                <div class="col">
                    <button type="submit" id="submit" class="btn btn-primary float-end"><i class="fa-regular fa-floppy-disk"></i> Save</button>
                </div>
            </div>
        </form>
    </x-slot>
</x-modal>
@endsection
@push('js')
    <script>
        /* add new manpower */
        const addNewManpower = () => {
            $('#modal_manpower').modal('show');
            $("#modal_manpowerLabel").html("New Manpower");
            $("#Form_Manpower").attr("action", "{{ route('manpower.store') }}");
            $("#nomor_pegawai, #name, #position, #email").val("");
            $("#formTambahan").html("");
        }

        /* Edit data manpower */
        const editManpower = (id, name) => {
            $('#modal_manpower').modal('show');
            $("#modal_manpowerLabel").html(`Edit Manpower : ${name}`);
            $("#Form_Manpower").attr("action", "{{ route('manpower.update') }}");
            $("#nomor_pegawai, #name, #position, #email").val("Loading...");
            $.ajax({
                dataType: "json",
                url: "{{ url('/manpower/data/find') }}"+`/${id}`,
                success: function (res) {
                    $("#nomor_pegawai").val(res.data.nomor_pegawai);
                    $("#name").val(res.data.name);
                    $("#position").val(res.data.role);
                    $("#email").val(res.data.email);
                    $("#formTambahan").html(`<input type="hidden" name="id" value="${res.data.id}" required>`);
                },
                error: function(err) {
                    toastr.error(err)
                },
            });
        }

        /* Change Password */
        const changePassword = (id, name) => {
            $('#modal_changePassword').modal('show');
            $("#modal_changePasswordLabel").html(`Change Password For : ${name}`);
            $("#formTambahan1").html(`<input type="hidden" name="id" value="${id}" required>`);
        }

        const validatePassword = () => {
            var password = $("#password").val();
            var confirm_password = $("#confirm_password").val();
            if (password != confirm_password) {
                event.preventDefault();
                toastr.error('Password is not match please check again.!', 'Password not match!')
                $("#password, #confirm_password").val("");
            }else{
                DisabledButtomSubmit();
            }
        }

        /* Delete Data */
        const deleteData = (id, name) => {
            $('#modal_deleteData').modal('show');
            $("#modal_deleteDataLabel").html(`Delete Data : ${name}`);
            $("#formTambahan2").html(`<input type="hidden" name="id" value="${id}" required>`);
        }

        /* Datatables Manpower */
        var Table_Manpower = $('#Table_Manpower').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('manpower.index') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'nomor_pegawai', name: 'nomor_pegawai' },
                { data: 'name', name: 'name' },
                { data: 'role', name: 'role' },
                { data: 'email', name: 'email' },
                { data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            "columnDefs": [{ className: "text-center", "targets": [0, 5] }, { className: "text-uppercase", "targets": [3] }]
        });
    </script>
@endpush
