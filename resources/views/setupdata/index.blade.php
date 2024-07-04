@extends('layouts.app', ['title' => 'Setup Data'])
@section('content')
<div class="row mb-3">
    <p class="fw-bold h3">Setup Data Section</p>
</div>
<div class="row">
    {{-- Table list Part name --}}
    <div class="col-6 mt-3">
        <div class="card">
            <div class="card-header fw-bold font-monospace fs-5"><i class="fa-solid fa-clipboard-list"></i>
                List of Part's Name
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <button type="button" class="btn btn-primary float-end" onclick="addNewParts()"><i class="fa-solid fa-plus"></i> New Part's</button>
                    </div>
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="Table_Parts" class="table table-striped-columns table-hover table-bordered nowrap display w-100" style="overflow-x: scroll">
                                <thead class="table-info">
                                    <tr>
                                        <th class="text-center" style="width: 10%;">#</th>
                                        <th class="text-center" style="width: 70%;">PART'S NAME</th>
                                        <th class="text-center" style="width: 20%;">ACTION</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Table list Rejection --}}
    <div class="col-6 mt-3">
        <div class="card">
            <div class="card-header fw-bold font-monospace fs-5"><i class="fa-solid fa-clipboard-list"></i>
                List of Rejection
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <button type="button" class="btn btn-primary float-end" onclick="addNewRejection()"><i class="fa-solid fa-plus"></i> New Rejection</button>
                    </div>
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="Table_Rejection" class="table table-striped-columns table-hover table-bordered nowrap display w-100" style="overflow-x: scroll">
                                <thead class="table-danger">
                                    <tr>
                                        <th class="text-center" style="width: 10%;">#</th>
                                        <th class="text-center" style="width: 70%;">REJECTION NAME</th>
                                        <th class="text-center" style="width: 20%;">ACTION</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Table list Rejection on Part name --}}
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-header fw-bold font-monospace fs-5"><i class="fa-solid fa-clipboard-list"></i>
                List Rejection on Part's Name
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="Table_RejectionOnPart" class="table table-striped-columns table-hover table-bordered nowrap display w-100" style="overflow-x: scroll">
                                <thead class="table-primary">
                                    <tr>
                                        <th class="text-center" style="width: 5%;">ID</th>
                                        <th class="text-center" style="width: 30%;">PART'S NAME</th>
                                        <th class="text-center" style="width: 55%;">REJECTION LIST</th>
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
<x-modal id="modal_Parts">
    <x-slot name="title">Add new Part's</x-slot>
    <x-slot name="body">
        <form id="Form_Parts" action="{{ route('setupdata.store_part') }}" method="POST" enctype="multipart/form-data" onsubmit="DisabledButtomSubmit()">
            @csrf
            <div class="row">
                <div class="col-12 mb-3">
                    <label for="part_name" class="form-label">PART'S NAME<sup class="text-danger">*</sup></label>
                    <input type="text" class="form-control" id="part_name" name="part_name" placeholder="Part's name" required>
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

<x-modal id="modal_Reject">
    <x-slot name="title">Add new Rejection</x-slot>
    <x-slot name="body">
        <form id="Form_Reject" action="{{ route('setupdata.store_rejection') }}" method="POST" enctype="multipart/form-data" onsubmit="DisabledButtomSubmit()">
            @csrf
            <div class="row">
                <div class="col-12 mb-3">
                    <label for="reject_name" class="form-label">REJECT'S NAME<sup class="text-danger">*</sup></label>
                    <input type="text" class="form-control" id="reject_name" name="reject_name" placeholder="Reject's name" required>
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

<x-modal id="modal_RejectionOnPart">
    <x-slot name="title">Add new Rejection on Part's</x-slot>
    <x-slot name="body">
        <form id="Form_RejectionOnPart" action="{{ route('setupdata.store_rejection') }}" method="POST" enctype="multipart/form-data" onsubmit="DisabledButtomSubmit()">
            @csrf
            <div class="row text-center fw-bold">
                <div class="col-12 mb-3 text-danger fs-5">
                    <p>- Please choose the rejecting your part. -</p>
                </div>
            </div>
            <div class="row">
                @foreach (App\Models\Reject::all() as $item)
                    <div class="col-2 mb-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="rejects_id[]" id="rejects_id{{ $item->id }}" value="{{ $item->id }}">
                            <label class="form-check-label text-capitalize" for="rejects_id{{ $item->id }}">{{ $item->reject_name }}</label>
                        </div>
                    </div>
                @endforeach
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

<x-modal id="modal_confirmDelete">
    <x-slot name="title">Delete Data</x-slot>
    <x-slot name="body">
        <form id="Form_confirmDelete" action="{{ route('setupdata.store_rejection') }}" method="POST" enctype="multipart/form-data" onsubmit="DisabledButtomSubmit()">
            @csrf
            <div class="row">
                <div class="col-12 mb-3 fs-5">
                    <div class="alert alert-danger" role="alert">
                        <i class="fa-solid fa-exclamation-triangle"></i> Are you sure you want to delete this data?
                    </div>
                </div>
            </div>
            <div class="row">
                <div id="formTambahan_confirmDelete"></div>
                <div class="col">
                    <button type="submit" id="submit" class="btn btn-primary float-end"><i class="fa-regular fa-floppy-disk"></i> Delete</button>
                </div>
            </div>
        </form>
    </x-slot>
</x-modal>

@endsection
@push('js')
    <script> /* add Function */
        /* add new Parts */
        const addNewParts = () => {
            $('#modal_Parts').modal('show');
            $("#modal_PartsLabel").html("Add new Part's");
            $('.modal-dialog').removeClass('modal-xl');

            $("#Form_Parts").attr("action", "{{ route('setupdata.store_part') }}");
            $("#part_name").val("");
            $("#formTambahan").html("");
        }

        /* Add new Rejection */
        const addNewRejection = () => {
            $('#modal_Reject').modal('show');
            $("#modal_RejectLabel").html("Add new Rejection");
            $('.modal-dialog').removeClass('modal-xl');

            $("#Form_Reject").attr("action", "{{ route('setupdata.store_rejection') }}");
            $("#reject_name").val("");
            $("#formTambahan1").html("");
        }
    </script>

    <script> /* Edit Function */

        /* Edit Parts */
        const editParts = (id, name) => {
            $('#modal_Parts').modal('show');
            $("#modal_PartsLabel").html(`Edit Part's : ${name}`);
            $('.modal-dialog').removeClass('modal-xl');
            $("#Form_Parts").attr("action", "{{ route('setupdata.update_part') }}");
            $("#part_name").val("Loading...");
            $.ajax({
                dataType: "json",
                url: "{{ route('setupdata.show_part') }}"+`?id=${id}`,
                success: function (res) {
                    $("#part_name").val(res.data.part_name);
                    $("#formTambahan").html(`<input type="hidden" name="id" value="${res.data.id}" required>`);
                },
                error: function(err) {
                    toastr.error(err)
                },
            });
        }

        /* Edit Reject */
        const editReject = (id, name) => {
            $('#modal_Reject').modal('show');
            $("#modal_RejectLabel").html(`Edit Rejection : ${name}`);
            $('.modal-dialog').removeClass('modal-xl');
            $("#Form_Reject").attr("action", "{{ route('setupdata.update_rejection') }}");
            $("#reject_name").val("Loading...");
            $.ajax({
                dataType: "json",
                url: "{{ route('setupdata.show_rejection') }}"+`?id=${id}`,
                success: function (res) {
                    $("#reject_name").val(res.data.reject_name);
                    $("#formTambahan1").html(`<input type="hidden" name="id" value="${res.data.id}" required>`);
                },
                error: function(err) {
                    toastr.error(err)
                },
            });
        }

        /* Edit Rejection's on Part */
        const editRejectionOnPart = (id, name) => {
            $('#modal_RejectionOnPart').modal('show');
            $("#modal_RejectionOnPartLabel").html(`Set Rejection on Part's : ${name}`);
            $('.modal-dialog').addClass('modal-xl');
            $("#Form_RejectionOnPart").attr("action", "{{ route('setupdata.store_rejection_on_part') }}");
            $("#formTambahan2").html(`<input type="hidden" name="part_id" value="${id}" required>`);

            var checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = false;
            });

            /* Select From Rejection on Part */
            $.ajax({
                dataType: "json",
                url: "{{ route('setupdata.show_rejection_on_part') }}"+`?id=${id}`,
                success: function (res) {
                    res.data.forEach((data) => {
                        $(`#rejects_id${data.rejects_id}`).prop('checked', true);
                    });
                },
                error: function(err) {
                    toastr.error(err)
                },
            });
        }
    </script>

    <script> /* for delete or destroy function */
        /* Delete Parts */
        const deleteParts = (id, name) => {
            $('#modal_confirmDelete').modal('show');
            $("#modal_confirmDeleteLabel").html(`Delete Parts : ${name}`);
            $('.modal-dialog').removeClass('modal-xl');
            $("#Form_confirmDelete").attr("action", "{{ route('setupdata.destroy_part') }}");
            $("#formTambahan_confirmDelete").html(`<input type="hidden" name="id" value="${id}" required>`);
        }

        /* Delete Reject */
        const deleteReject = (id, name) => {
            $('#modal_confirmDelete').modal('show');
            $("#modal_confirmDeleteLabel").html(`Delete Rejection : ${name}`);
            $('.modal-dialog').removeClass('modal-xl');
            $("#Form_confirmDelete").attr("action", "{{ route('setupdata.destroy_rejection') }}");
            $("#formTambahan_confirmDelete").html(`<input type="hidden" name="id" value="${id}" required>`);
        }

    </script>

    <script> /* Datatables Only */
        /* Datatables for Parts */
        var Table_Parts = $('#Table_Parts').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 5,
            ajax: "{{ route('setupdata.datatables_part') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'part_name', name: 'part_name' },
                { data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            "columnDefs": [{ className: "text-center", "targets": [0, 2] }, { className: "text-capitalize", "targets": [1] }]
        });

        /* Datatables for Rejection */
        var Table_Rejection = $('#Table_Rejection').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 5,
            ajax: "{{ route('setupdata.datatables_rejection') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'reject_name', name: 'reject_name' },
                { data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            "columnDefs": [{ className: "text-center", "targets": [0, 2] }, { className: "text-capitalize", "targets": [1] }]
        });

        /* Datatables for Rejection on Part */
        var Table_RejectionOnPart = $('#Table_RejectionOnPart').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 5,
            ajax: "{{ route('setupdata.datatables_rejection_on_part') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'part_name', name: 'part_name' },
                { data: 'rejects', name: 'rejects' },
                { data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            "columnDefs": [{ className: "text-center", "targets": [0, 3] }, { className: "text-capitalize", "targets": [1, 2] }]
        });
    </script>
@endpush
