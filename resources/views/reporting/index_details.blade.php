@extends('layouts.app', ['title' => 'Bussines Plan'])
@section('content')
<div class="row mb-3">
    <p class="fw-bold h3">Details Input Reporting</p>
</div>

<form class="row row-cols-lg-auto g-3 align-items-center mb-3" enctype="multipart/form-data" onsubmit="FilterData()">
    {{-- startDate --}}
    <div class="col-12">
        <div class="input-group">
            <div class="input-group-text fw-bold">FROM : </div>
            <input type="date" class="form-control" value="{{ now()->subDays(30)->format('Y-m-d') }}" name="startDate" id="startDate" required>
        </div>
    </div>
    {{-- endDate --}}
    <div class="col-12">
        <div class="input-group">
            <div class="input-group-text fw-bold">TO : </div>
            <input type="date" class="form-control" value="{{ now()->format('Y-m-d') }}" name="endDate" id="endDate" required>
        </div>
    </div>
    {{-- Nama Part --}}
    <div class="col-12">
        <div class="input-group">
            <div class="input-group-text fw-bold">PART'S NAME : </div>
            <select class="form-select" name="part_name" id="part_name">
                <option value="0|allPart" selected>All part's</option>
                @foreach ($dataPart as $item)
                    <option class="text-capitalize" value="{{ $item->id }}|{{ $item->part_name }}">{{ $item->part_name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-12">
        <button  type="submit" id="submit" name="submit" class="btn btn-warning"><i class="fa-solid fa-filter"></i> FILTER</button>
    </div>
</form>

{{-- Table --}}
<div class="row mb-3">
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-header fw-bold font-monospace fs-5"><i class="fa-solid fa-clipboard-list"></i>
                Bussines Plan From <span id="startDate_fromfilter"></span> until <span id="endDate_fromfilter"></span> <span id="part_fromfilter" class="text-capitalize"></span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="Table_DetailsReport" class="table table-striped-columns table-hover table-bordered nowrap display w-100" style="overflow-x: scroll">
                                <thead class="table-info">
                                    <tr>
                                        <th class="text-center" style="width: 5%;">#</th>
                                        <th class="text-center" style="width: 22%;">REPORTING DATE</th>
                                        <th class="text-center" style="width: 22%;">PART'S NAME</th>
                                        <th class="text-center" style="width: 22%;">REJECT NAME</th>
                                        <th class="text-center" style="width: 22%;">QTY</th>
                                        <th class="text-center" style="width: 5%;">ACTION</th>
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

{{-- Modal --}}
<x-modal id="modal_editReportData">
    <x-slot name="title">Edit report data</x-slot>
    <x-slot name="body">
        <form id="Form_editReportData" action="{{ route('reporting.update_details') }}" method="POST" enctype="multipart/form-data" onsubmit="DisabledButtomSubmit()">
            @csrf
            <div class="row" id="rowEditFrom_editReportData">
                <div class="col-6">
                    <div class="mb-3">
                        <label for="nama_part" class="form-label">PART'S NAME <sup class="text-danger">*</sup></label>
                        <input type="text" readonly class="form-control-plaintext fw-bold fs-5 text-capitalize" id="nama_part" value="PART'S NAME">
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3">
                        <label for="qty" class="form-label">QTY <sup class="text-danger">*</sup></label>
                        <input type="number" min="0" class="form-control fw-bold fs-5" id="qty" name="qty">
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3">
                        <label for="reject_name" class="form-label">REJECT NAME <sup class="text-danger">*</sup></label>
                        <input type="text" readonly class="form-control-plaintext fw-bold fs-5 text-capitalize" id="reject_name" value="REJECT NAME">
                    </div>
                </div>
            </div>
            <div class="row">
                <div id="formTambahan_editReportData"></div>
                <div class="col">
                    <button type="submit" id="submit" class="btn btn-primary float-end"><i class="fa-regular fa-floppy-disk"></i> Delete</button>
                </div>
            </div>
        </form>
    </x-slot>
</x-modal>

<x-modal id="modal_DeleteReportData">
    <x-slot name="title">Delete Data</x-slot>
    <x-slot name="body">
        <form id="Form_confirmDelete" action="{{ route('reporting.delete_details') }}" method="POST" enctype="multipart/form-data" onsubmit="DisabledButtomSubmit()">
            @csrf
            <div class="row">
                <div class="col-12 mb-3 fs-5">
                    <div class="alert alert-danger" role="alert">
                        <i class="fa-solid fa-exclamation-triangle"></i> Are you sure you want to delete this data?
                    </div>
                </div>
            </div>
            <div class="row">
                <div id="formTambahan_DeleteReportData"></div>
                <div class="col">
                    <button type="submit" id="submit" class="btn btn-primary float-end"><i class="fa-regular fa-floppy-disk"></i> Delete</button>
                </div>
            </div>
        </form>
    </x-slot>
</x-modal>

@endsection
@push('js')
    <script>
        /* Declare */
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        var getPart = $("#part_name").val();
        var result = getPart.split('|')
        var namaPart =  result[1];
        var namaPart_id =  result[0];

        $('#startDate_fromfilter').html(startDate);
        $('#endDate_fromfilter').html(endDate);
        $('#part_fromfilter').html(`On ${namaPart}`);

        /* filter Data */
        const FilterData = () => {
            event.preventDefault();
            startDate = $('#startDate').val();
            endDate = $('#endDate').val();
            getPart = $("#part_name").val();
            result = getPart.split('|')
            namaPart =  result[1];
            namaPart_id =  result[0];

            var link = "{{ route('reporting.datatables_details') }}" + `?startDate=${startDate}&endDate=${endDate}&namaPart=${namaPart_id}`;
            Table_DetailsReport.ajax.url(link).load();

            $('#startDate_fromfilter').html(startDate);
            $('#endDate_fromfilter').html(endDate);
            $('#part_fromfilter').html(`On ${namaPart}`);
        }

        /* editReport */
        const editReport = (id, name) => {
            console.log(`data id => ${id} dan data name => ${name}`)
            $('.modal-dialog').addClass('modal-xl');
            $("#modal_editReportData").modal('show');
            $.ajax({
                dataType: "json",
                url: "{{ route('inputdata.show_data') }}" + `?id=${id}`,
                success: function (res) {
                    console.log(res.data);
                    $("#nama_part").val(res.data.name_part);
                    $("#qty").val(res.data.qty);
                    $("#reject_name").val(res.data.name_reject);
                    $("#formTambahan_editReportData").html(`<input type="hidden" name="id_editReport" value="${res.data.id}">`);
                }
            });
        }

        /* Deelte Reports */
        const deleteReport = (id, name) => {
            toastr.info(id, name);
            $("#formTambahan_DeleteReportData").html(`<input type="hidden" name="id_reporting" value="${id}">`);
            $('.modal-dialog').removeClass('modal-xl');
            $("#modal_DeleteReportData").modal('show');
        }

        /* Datatables for Table_DetailsReport */
        var Table_DetailsReport = $('#Table_DetailsReport').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('reporting.datatables_details') }}" + `?startDate=${startDate}&endDate=${endDate}&namaPart=${namaPart_id}`,
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'date_report', name: 'date_report' },
                { data: 'name_part', name: 'name_part' },
                { data: 'name_reject', name: 'name_reject' },
                { data: 'qty', name: 'qty' },
                { data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            "columnDefs": [ { className: "text-center", "targets": [0, 1, 4, 5] }, { className: "text-capitalize", "targets": [2, 3] }],
        });
    </script>
@endpush
