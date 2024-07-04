@extends('layouts.app', ['title' => 'Input Data'])
@section('content')
<div class="row mb-3">
    <p class="fw-bold h3">Input Data Section</p>
</div>

<form class="row row-cols-lg-auto g-3 align-items-center mb-3" enctype="multipart/form-data" onsubmit="setupForm()">
    <div class="col-12">
        <div class="input-group">
            <div class="input-group-text fw-bold">PART'S NAME : </div>
            <select class="form-select" name="part_name" id="part_name" required>
                <option value="" selected>Open this select menu</option>
                @foreach ($dataPart as $item)
                    <option class="text-capitalize" value="{{ $item->id }}|{{ $item->part_name }}">{{ $item->part_name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-12">
        <div class="input-group">
            <div class="input-group-text fw-bold">DATE : </div>
            <input type="date" class="form-control" value="{{ now()->format('Y-m-d') }}" name="tanggal" id="tanggal" required>
        </div>
    </div>

    <div class="col-12">
        <button  type="submit" id="submit" name="submit" class="btn btn-info"><i class="fa-solid fa-check-double"></i> SETUP</button>
    </div>

    <div class="col-12">
        <a class="btn btn-warning d-block float-end" onclick="checkResume()"><i class="fa-solid fa-list"></i> RESUME TODAY</a>
    </div>
</form>

<div class="row mb-3">
    <div class="alert alert-info ms-3" role="alert" style="width: 50%">
        <i class="fa-solid fa-info-circle"></i> Please select the part's name and date to setup the data. Make sure everything is correct.
    </div>
</div>

<p class="text-center fw-bold fs-2">
    <i class="fa-solid fa-meteor"></i>&ensp; <span class="text-uppercase" id="nama_part_tag">select the part's name</span> &ensp;<i class="fa-solid fa-meteor"></i>
</p>

<div class="card">
    <div class="card-body">
        <div class="row mb-3 justify-content-center" id="formInputTotal">
            <div class="col-8">
                <div class="alert alert-warning text-center mx-auto" role="alert" style="width: 80%">
                    Make sure everything is correct.
                </div>
            </div>
        </div>
        <div class="row" id="formInput">
        </div>
        <div class="row" id="buttonFormInput">
        </div>
    </div>
</div>

<x-modal id="modal_checkResume">
    <x-slot name="title">Resume data input today</x-slot>
    <x-slot name="body">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3 justify-content-center align-item-center">
                    <div class="col-3">
                        <div class="card">
                            <div class="card-header fw-bold bg-primary text-light fs-4 text-center">
                                PRODUCTION
                            </div>
                            <div class="card-body"  style="margin: 0px !important; padding: 0px !important;">
                                <p class="card-title text-center fs-1 fw-bold"><span id="total_production">0</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card">
                            <div class="card-header fw-bold bg-success text-light fs-4 text-center">
                                GOOD QUALITY
                            </div>
                            <div class="card-body"  style="margin: 0px !important; padding: 0px !important;">
                                <p class="card-title text-center fs-1 fw-bold"><span id="total_ok">0</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card">
                            <div class="card-header fw-bold bg-danger text-light fs-4 text-center">
                                REJECTION
                            </div>
                            <div class="card-body"  style="margin: 0px !important; padding: 0px !important;">
                                <p class="card-title text-center fs-1 fw-bold"><span id="total_ng">0</span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="Table_ResumeToday" class="table table-striped-columns table-hover table-bordered nowrap display w-100" style="overflow-x: scroll">
                                <thead class="table-info">
                                    <tr>
                                        <th class="text-center" style="width: 10%;">#</th>
                                        <th class="text-center" style="width: 35%;">PART'S NAME</th>
                                        <th class="text-center" style="width: 35%;">REMARK'S</th>
                                        <th class="text-center" style="width: 20%;">QTY</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>
</x-modal>

@endsection
@push('js')
    <script>
        /* declare variable */
            var getPart;
            var result;
            var Namapart;
            var Namapart_id;
            var tanggal;
            var total_production;

        /* Setup Form */
        const setupForm = (e) => {
            event.preventDefault();
            getPart = $("#part_name").val();
            result = getPart.split('|')
            Namapart =  result[1];
            Namapart_id =  result[0];
            tanggal = $("#tanggal").val();
            formInputReject(Namapart_id, Namapart)
        }

        /* to Input Rejection */
        const formInputReject = (id, namaPart) => {
            const nama = $("#nama_part_tag").text(namaPart);
            $('#formInput').html('<p class="text-center fs-4 fw-bold"> Loading...</p>')
            $('#buttonFormInput').html("")

            /* get data show_rejection_on_part */
            $.ajax({
                dataType: "json",
                url: "{{ route('setupdata.show_rejection_on_part') }}"+`?id=${id}`,
                success: function (res) {
                    let html = '';
                    if (res.data.length == 0) {
                        html = `<p class="text-center fs-4 fw-bold"> No Rejection Data</p>`;
                        $("#formInputTotal").html(`
                        <div class="col-8">
                            <div class="alert alert-warning text-center mx-auto" role="alert" style="width: 80%">
                                Make sure everything is correct.
                            </div>
                        </div>`);
                    }else{
                        $("#formInputTotal").html(`
                        <div class="col-8">
                            <div class="alert alert-warning text-center mx-auto" role="alert" style="width: 80%">
                                Make sure everything is correct.
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-primary text-light text-uppercase fw-bold fs-5" style="width: 75%;">TOTAL PRODUCTION</span>
                                <input type="number" min="0" class="form-control fs-3 text-center" name="total_production" id="total_production" placeholder="0" style="width: 25%;">
                            </div>
                        </div>`);
                        res.data.forEach(element => {
                            html += `<div class="col-3">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text bg-danger text-light text-uppercase fw-bold fs-5" style="width: 75%;">${element.to_rejects.reject_name}</span>
                                            <input type="number" class="form-control fs-3 text-center" name="qty_reject[]" id="reject_id|${element.to_rejects.id}|${element.to_rejects.reject_name}" placeholder="0" style="width: 25%;">
                                        </div>
                                    </div>`;
                        });
                        $('#buttonFormInput').html(`
                        <div class="col">
                            <button onclick="sendData()" id="button_sendData" class="btn btn-lg btn-success float-end"><i class="fa-regular fa-paper-plane"></i> SEND DATA</button>
                        </div>`);
                    }
                    $('#formInput').html(html);
                },
                error: function(err) {
                    toastr.error(err)
                },
            });

        }

        /* handle if send data on click */
        const sendData = () => {
            $("#button_sendData").prop("disabled", true);
            total_production = $("#total_production").val();
            let validValues = [];
            $('input[name="qty_reject[]"]').each(function() {
                let inputVal = $(this).val();
                let rejectId = $(this).attr('id'); /* Get the id attribute of the input */

                /* Check if the value is not 0 or null */
                if (inputVal && inputVal != 0) {
                    validValues.push({
                        reject_id: rejectId,
                        qty_reject: inputVal
                    });
                }
            });

            /* Send data to the server */
            $.ajax({
                type: "POST",
                url: "{{ route('inputdata.store_data') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    dataQty: validValues,
                    getPart: getPart,
                    tanggal: tanggal,
                    result: result,
                    Namapart: Namapart,
                    Namapart_id: Namapart_id,
                    total_production: total_production,
                    tanggal: tanggal,
                },
                success: function(res) {
                    toastr.success(res.message)
                    location.reload();
                },
                error: function(err) {
                    toastr.error(err.responseJSON.message)
                    location.reload();
                },
            });
        }

        /* resume check  */
        const checkResume = () => {
            $('#modal_checkResume').modal('show');
            $('#Table_ResumeToday').ready(function() {
            getResumeData()
                if ($.fn.DataTable.isDataTable('#Table_ResumeToday')) {
                    $('#Table_ResumeToday').DataTable().destroy();
                }

                /* Datatables for Table_ResumeToday */
                var Table_ResumeToday = $('#Table_ResumeToday').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('inputdata.datatables_resume') }}",
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                        { data: 'name_part', name: 'name_part' },
                        { data: 'name_reject', name: 'name_reject' },
                        { data: 'qty', name: 'qty' },
                    ],
                    "columnDefs": [
                        { className: "text-center", "targets": [0, 1, 3] },
                        { className: "align-middle fs-5", "targets": [1] },
                        { className: "text-capitalize", "targets": [1, 2] }
                    ],
                    rowsGroup : [1, 2],
                    rowReorder : { selector : "td:nth-child(1)"},
                });
            });
        }

        /* get data resume and parsing to card resume */
        const getResumeData = () => {
            $("#total_production, #total_ok, #total_ng").text(0);
            $.ajax({
                dataType: "json",
                url: "{{ route('inputdata.datatables_resume') }}",
                success: function (res) {
                    let total_NG = 0;
                    let total_production = 0;

                    res.data.forEach(item => {
                    if (item.name_reject !== 'Total Production') {
                        total_NG += item.qty;
                    } else {
                        total_production += item.qty;
                    }
                    });

                    $("#total_production").text(total_production);
                    $("#total_ok").text(total_production - total_NG);
                    $("#total_ng").text(total_NG);
                },
                error: function(err) {
                    toastr.error(err)
                },
            });
        }
    </script>
@endpush
