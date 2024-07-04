@extends('layouts.app', ['title' => 'Dashboard'])
@section('content')
<div class="row mb-3">
    <p class="fw-bold h3">Dashboard</p>
</div>
{{-- Tampilan Our Product --}}
<div class="row mb-3">
    <p class="fw-bold fs-4 text-decoration-underline">Our Product :</p>
    <div class="col-2 mb-3">
        <div class="card">
            <div class="card-header fw-bold fs-5 text-center text-capitalize" >
                gas starcam
            </div>
            <div class="card-body p-auto d-flex justify-content-center align-items-center"  style="margin: 0px !important; padding: 0px !important;">
                <img src="{{ asset('img/gas starcam.webp') }}" class="card-img-top" alt="{{ asset('img/gas starcam.webp') }}" style="width: 10rem;">
            </div>
        </div>
    </div>
</div>
{{-- Tampilan Our Product --}}
<div class="row mb-3 justify-content-center align-item-center">
    <p class="fw-bold fs-4 text-decoration-underline">Our Part's :</p>
    @foreach ($part as $item)
        <div class="col-2 mb-3">
            <div class="card">
                <div class="card-header fw-bold fs-5 text-center text-capitalize" >
                    {{ $item->part_name }}
                </div>
                <div class="card-body p-auto d-flex justify-content-center align-items-center"  style="margin: 0px !important; padding: 0px !important;">
                    <img src="{{ asset('img/'.$item->part_name.'.webp') }}" class="card-img-top" alt="{{ asset('img/'.$item->part_name.'.webp') }}" style="width: 10rem;">
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- Today Reporting --}}
<div class="row mb-3 justify-content-center align-item-center">
    <p class="fw-bold fs-4 text-decoration-underline">Daily Rejection :</p>
    <div class="col-12">
        <div class="table-responsive">
            <table id="Table_DailyRejection" class="table table-striped-columns table-hover table-bordered nowrap display w-100" style="overflow-x: scroll">
                <thead class="table-danger">
                    <tr>
                        <th class="text-center" style="width: 10%;">#</th>
                        <th class="text-center" style="width: 35%;">PART'S NAME</th>
                        <th class="text-center" style="width: 35%;">REJECT'S NAME</th>
                        <th class="text-center" style="width: 20%;">QTY</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script>
        /* Datatables Daily Rejection */
        var Table_DailyRejection = $('#Table_DailyRejection').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('dashboard') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name_part', name: 'name_part', orderable: false, searchable: false },
                { data: 'name_reject', name: 'name_reject', orderable: false, searchable: false },
                { data: 'qty', name: 'qty', orderable: false, searchable: false },
            ],
            "columnDefs": [
                { className: "text-center", "targets": [0, 1, 3] },
                { className: "align-middle fs-5", "targets": [1] },
                { className: "text-capitalize", "targets": [1, 2] }
            ],
            rowsGroup : [1, 2],
            rowReorder : { selector : "td:nth-child(1)"},
            order: [[3, 'desc']],
        });
    </script>
@endpush
