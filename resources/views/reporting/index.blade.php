@extends('layouts.app', ['title' => 'Bussines Plan'])
@section('content')
<div class="row mb-3">
    <p class="fw-bold h3">Bussines Plan Section <span><a class="btn btn-outline-secondary btn-lg mb-2 float-end" href="{{ route('reporting.index_details') }}"><i class="fa-solid fa-book"></i> Detail's Input Data</a></span></p>
</div>

<form class="row row-cols-lg-auto g-3 align-items-center mb-3" enctype="multipart/form-data" onsubmit="FilterData()">
    {{-- startDate --}}
    <div class="col-12">
        <div class="input-group">
            <div class="input-group-text fw-bold">FROM : </div>
            <input type="date" class="form-control" value="{{ now()->subDays(30)->format('Y-m-d') }}" name="startDate" id="startDate" required>
            {{-- <input type="date" class="form-control" value="2024-05-24" name="startDate" id="startDate" required> --}}
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
                        <table class="fs-5 fw-bold mb-2">
                            <tr>
                                <td>Filtered &ensp;</td>
                                <td>: &ensp;</td>
                                <td><span id="filteredDays">0</span> day's.</td>

                                <td>&ensp;&ensp;&ensp;&ensp;&ensp;</td>

                                <td>Mean Prodution &ensp;</td>
                                <td>: &ensp;</td>
                                <td><span id="MeanProduction">0</span> Pcs.</td>

                                <td>&ensp;&ensp;&ensp;&ensp;&ensp;</td>

                                {{-- <td>Mean Rejection &ensp;</td>
                                <td>: &ensp;</td>
                                <td><span id="MeanRejection">0</span> Pcs.</td> --}}

                                <td>&ensp;&ensp;&ensp;&ensp;&ensp;</td>

                                <td>Standard Deviation &ensp;</td>
                                <td>: &ensp;</td>
                                <td><span id="standartDeviation">0</span> Pcs.</td>
                            </tr>
                            <tr>
                                <td>&ensp;</td>
                                <td>&ensp;</td>
                                <td>&ensp;</td>

                                <td>&ensp;&ensp;&ensp;&ensp;&ensp;</td>

                                <td>Mean NG &ensp;</td>
                                <td>: &ensp;</td>
                                <td><span id="MeanNG">0</span> Pcs.</td>

                                <td>&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                                <td>&ensp;&ensp;&ensp;&ensp;&ensp;</td>

                                <td>Standard Deviation NG &ensp;</td>
                                <td>: &ensp;</td>
                                <td><span id="standartDeviationNG">0</span> Pcs.</td>
                            </tr>
                        </table>
                        <div class="table-responsive">
                            <table id="Table_DetailsReport" class="table table-striped-columns table-hover table-bordered nowrap display w-100" style="overflow-x: scroll">
                                <thead class="table-info">
                                    <tr>
                                        <th class="text-center" style="width: 5%;">#</th>
                                        <th class="text-center" style="width: 22%;">REPORTING DATE</th>
                                        <th class="text-center" style="width: 23%;">PART'S NAME</th>
                                        <th class="text-center" style="width: 24%;">REMARK'S</th>
                                        <th class="text-center" style="width: 22%;">QTY</th>
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

{{-- Grafik --}}
<div class="row mb-3">
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-header fw-bold font-monospace fs-5"><i class="fa-solid fa-clipboard-list"></i>
                Data chart <span id="startDate_fromfilter1"></span> until <span id="endDate_fromfilter1"></span> <span id="part_fromfilter1" class="text-capitalize"></span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 border-end border-dark border-3">
                        <div>
                            <p class="fw-bold text-decoration-underline">Chart Production :</p>
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                    <div class="col-6 border-start border-dark border-3">
                        <div>
                            <p class="fw-bold text-decoration-underline">Chart for PPM :</p>
                            <canvas id="myChart1"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- Modal --}}
@endsection
@push('js')
<script src="{{ asset('js/chart.js') }}"></script>
    <script>
        /* Chart for PPM */
        // const ctx1 = document.getElementById('myChart1').getContext('2d');
        // const data1 = {
        //     labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        //     datasets: [{
        //         label: 'Part Per Milion (PPM)',
        //         data: [10, 20, 30, 40, 50, 40, 10],
        //         fill: false,
        //         borderColor: 'rgba(220,53,69,255)', //red
        //         tension: 0.1
        //     }]
        // };

        // new Chart(ctx1, {
        //     type: 'line',
        //     data: data1,
        //     options: {
        //         responsive: true,
        //         plugins: {
        //             legend: {
        //                 position: 'top',
        //             },
        //             title: {
        //                 display: true,
        //                 text: 'Chart.js Line Chart'
        //             }
        //         }
        //     }
        // });
    </script>
    <script>
        /* Declare */
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        var getPart = $("#part_name").val();
        /* Konversi nilai ke objek Date */
        var start = new Date(startDate);
        var end = new Date(endDate);
        var timeDiff = end - start;
        var dayDiff = timeDiff / (1000 * 60 * 60 * 24);

        var filteredDays = $("#filteredDays").html(dayDiff);
        var MeanProduction =  $("#MeanProduction").html("0");
        var MeanNG =  $("#MeanNG").html("0");
        // var MeanRejection =  $("#MeanRejection").html("0");
        var standartDeviation =  $("#standartDeviation").html("0");
        var standartDeviationNG =  $("#standartDeviationNG").html("0");
        var result = getPart.split('|')
        var namaPart =  result[1];
        var namaPart_id =  result[0];
        const ctxStacked = document.getElementById('myChart').getContext('2d');
        const ctxPPM = document.getElementById('myChart1').getContext('2d');
        let chartStacked;
        let chartPPM;

        $('#startDate_fromfilter, #startDate_fromfilter1').html(startDate);
        $('#endDate_fromfilter, #endDate_fromfilter1').html(endDate);
        $('#part_fromfilter, #part_fromfilter1').html(`On ${namaPart}`);

        /* get mean, and deviation */
        const getMean = () => {
            start = new Date(startDate);
            end = new Date(endDate);
            timeDiff = end - start;
            dayDiff = timeDiff / (1000 * 60 * 60 * 24);
            $("#filteredDays").html(dayDiff);

            $.ajax({
                dataType: "json",
                url: "{{ route('reporting.datatables_report') }}" + `?startDate=${startDate}&endDate=${endDate}&namaPart=${namaPart_id}`,
                success: function (res) {
                    console.log(res.data)
                    /* filter data and get just total production */
                    const totalProductionEntries = res.data.filter(entry => entry.name_reject === "Total Production");
                    if (totalProductionEntries.length === 0) {
                        $("#MeanProduction").html(0);
                        $("#standartDeviation").html(0);
                        return;
                    }
                    const qtyValues = totalProductionEntries.map(entry => entry.qty);
                    const sumQty = qtyValues.reduce((sum, qty) => sum + qty, 0);

                    /* Pilih salah satu dibawah ini buat ambil meannya */
                    // const meanQty = sumQty / dayDiff; /* ini kalo berdasarkan hari */
                    const meanQty = sumQty / qtyValues.length; /* ini kalo berdasarkan jumlah data */


                    /* standart deviation */
                    const squaredDeviations = qtyValues.map(qty => Math.pow(qty - meanQty, 2));
                    const sumSquaredDeviations = squaredDeviations.reduce((sum, sqDev) => sum + sqDev, 0);
                    const variance = sumSquaredDeviations / (qtyValues.length - 1);
                    const standardDeviation = Math.sqrt(variance);

                    /* Munculkan di web */
                    $("#MeanProduction").html(meanQty.toFixed(2));
                    $("#standartDeviation").html(standardDeviation.toFixed(2))

                    /* Standart deviasi untuk NG */
                    const totalNGEntries = res.data.filter(entry => entry.name_reject !== "Total Production");
                    if (totalProductionEntries.length === 0) {
                        $("#MeanNG").html(0);
                        $("#standartDeviationNG").html(0);
                        return;
                    }
                    const qtyValuesNG = totalNGEntries.map(entry => entry.qty);
                    const sumQtyNG = qtyValuesNG.reduce((sum, qty) => sum + qty, 0);

                    /* Pilih salah satu dibawah ini buat ambil meannya */
                    // const meanQtyNG = sumQtyNG / dayDiff; /* ini kalo berdasarkan hari */
                    const meanQtyNG = sumQtyNG / qtyValuesNG.length; /* ini kalo berdasarkan jumlah data */

                    /* standart deviation */
                    const squaredDeviationsNG = qtyValuesNG.map(qty => Math.pow(qty - meanQtyNG, 2));
                    const sumSquaredDeviationsNG = squaredDeviationsNG.reduce((sum, sqDev) => sum + sqDev, 0);
                    const varianceNG = sumSquaredDeviationsNG / (qtyValuesNG.length - 1);
                    const standardDeviationNG = Math.sqrt(varianceNG);
                    $("#MeanNG").html(meanQtyNG.toFixed(2));
                    $("#standartDeviationNG").html(standardDeviationNG.toFixed(2))

                }
            });
        }
        getMean();
        /* filter Data */
        const FilterData = () => {
            event.preventDefault();
            startDate = $('#startDate').val();
            endDate = $('#endDate').val();
            getPart = $("#part_name").val();
            result = getPart.split('|')
            namaPart =  result[1];
            namaPart_id =  result[0];


            var link = "{{ route('reporting.datatables_report') }}" + `?startDate=${startDate}&endDate=${endDate}&namaPart=${namaPart_id}`;
            Table_DetailsReport.ajax.url(link).load();


            $('#startDate_fromfilter, #startDate_fromfilter1').html(startDate);
            $('#endDate_fromfilter, #endDate_fromfilter1').html(endDate);
            $('#part_fromfilter, #part_fromfilter1').html(`On ${namaPart}`);
            updateChartPPM();
            updateChartProduction();
            getMean();
        }

        /* to update data Chart*/
        const updateChartProduction = () => {
            $.ajax({
                dataType: "json",
                url: "{{ route('reporting.chart_stacked') }}" + `?startDate=${startDate}&endDate=${endDate}&namaPart=${namaPart_id}`,
                success: function (res) {
                    chartStacked.data = res.data; // Update the chartStacked data
                    chartStacked.update();
                }
            });
        }

        /* to update data Chart*/
        const updateChartPPM = () => {
            $.ajax({
                dataType: "json",
                url: "{{ route('reporting.chart_line') }}" + `?startDate=${startDate}&endDate=${endDate}&namaPart=${namaPart_id}`,
                success: function (res) {
                    chartPPM.data = res.data; // Update the chartStacked data
                    chartPPM.update();
                }
            });
        }

        /* Chart stacked production */
        const createChartStacked = (dataStacked) => {
            chartStacked = new Chart(ctxStacked, {
                type: 'bar',
                data: dataStacked,
                options: {
                    responsive: true,
                    interaction: {
                        intersect: false,
                    },
                    scales: {
                        x: {
                            stacked: true,
                        },
                        y: {
                            stacked: true
                        }
                    }
                }
            });
        }

        /* Chart Line PPM */
        const createChartPPM = (dataPPM) => {
            chartPPM = new Chart(ctxPPM, {
                type: 'line',
                data: dataPPM,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Chart.js Line Chart'
                        }
                    }
                }
            });
        }

        /* Initial dataStacked */
        const initialDataStacked = {
            labels: ['Waiting for data...'],
            datasets: [
                {
                    label: 'Rejection',
                    data: [0],
                    backgroundColor: 'rgba(220,53,69,255)', //red
                    stack: 'Stack 0',
                },{
                    label: 'Good Quality',
                    data: [0],
                    backgroundColor: 'rgba(25,135,84,255)', //green
                    stack: 'Stack 0',
                },
            ]
        };
        createChartStacked(initialDataStacked);

        /* Initial DataPPM */
        const initialDataPPM = {
            labels: [],
            datasets: [{
                label: 'Part Per Milion (PPM)',
                data: [],
                fill: false,
                borderColor: 'rgba(220,53,69,255)', //red
                tension: 0.1
            }]


        };
        createChartPPM(initialDataPPM)
        updateChartPPM();
        updateChartProduction();

        /* Datatables for Table_DetailsReport */
        var Table_DetailsReport = $('#Table_DetailsReport').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('reporting.datatables_report') }}" + `?startDate=${startDate}&endDate=${endDate}&namaPart=${namaPart_id}`,
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'report_date', name: 'report_date' },
                { data: 'name_part', name: 'name_part' },
                { data: 'name_reject', name: 'name_reject' },
                { data: 'qty', name: 'qty' },
            ],
            rowsGroup: [1, 2],
            // rowReorder: { selector: "td:nth-child(1)" },
            "columnDefs": [ { className: "text-center", "targets": [0, 1, 2, 4] }, { className: "text-capitalize", "targets": [2, 3] }, {className: "align-middle", "targets": [1, 2] }],
        });
    </script>
@endpush
