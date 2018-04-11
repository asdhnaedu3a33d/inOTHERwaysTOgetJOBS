<main class="col-md-9 float-left mx-auto main w-100" id="mainSection">
    <div class="p-2" style="border-bottom:3px solid black;">
        <div class="input-group mb-1">
            <select class="custom-select mr-2 ml-2" id="inputGroupSelect01">
                <option selected>Tahun</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
            </select>
            <select class="custom-select mr-2 ml-2" id="inputGroupSelect01">
                <option selected>Triwulan</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
            </select>
            <select class="custom-select mr-2 ml-2" id="inputGroupSelect01">
                <option selected>Perangkat Daerah</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
            </select>
            <div class="input-group-append mr-2 ml-2">
                <button class="btn btn-outline-secondary" type="button">Button</button>
            </div>
        </div>
    </div>
    <!--            <div class="chartjs-size-monitor"-->
    <!--                 style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">-->
    <!--                <div class="chartjs-size-monitor-expand"-->
    <!--                     style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">-->
    <!--                    <div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div>-->
    <!--                </div>-->
    <!--                <div class="chartjs-size-monitor-shrink"-->
    <!--                     style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">-->
    <!--                    <div style="position:absolute;width:200%;height:200%;left:0; top:0"></div>-->
    <!--                </div>-->
    <!--            </div>-->
    <div class="chartWrapper">
        <div class="chartAreaWrapper">
            <div class="chartAreaWrapper2">
                <canvas id="densityChart" height="500px"></canvas>
            </div>
        </div>
    </div>
    <ul id="pagination-demo" class="pagination-centered justify-content-center"></ul>

</main>

<!-- Graphs -->
<script>
    var chartData = {};
    var myLiveChart = null;
    var labels = [];
    var dataAnggaran = [];

    function initializeChart() {
        var densityCanvas = document.getElementById("densityChart").getContext("2d");

        Chart.defaults.global.defaultFontFamily = "Lato";
        Chart.defaults.global.defaultFontSize = 16;

        var chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                xAxes: [{
                    barPercentage: 1,
                    categoryPercentage: 0.6,
                    ticks: {
                        autoSkip: false
                    }
                }],
                yAxes:[{
                    ticks: {
                        callback: function(value, index, values) {
                            return 'Rp. '+ value.toLocaleString();
                        }
                    }
                }]
            }
        };

        myLiveChart = new Chart(densityCanvas, {
            type: 'bar',
            data: chartData,
            options: chartOptions,
        });
    }

    function resetChart(page) {
        var start = 8 * page;
        var end = (8 * page) + 8;
        myLiveChart.destroy();
        var slicedLabel = labels.slice(start, end);
        var slicedAnggaran = dataAnggaran.slice(start, end);
        var data = {
            label: 'Realisasi Uang dan Anggaran',
            data: slicedAnggaran,
            backgroundColor: 'rgba(99, 132, 0, 0.6)'
        };
        chartData = {
            labels: slicedLabel,
            datasets: [data]
        };
        initializeChart();
    }

    var GetChartData = function () {
        $.ajax({
            url: "http://103.29.196.246/sirenbangda/2018-dev/laporan/getChartDataJson/realisasi",
            method: 'GET',
            dataType: 'json',
            success: function (json) {
                labels = json.map(function (item) {
//                    .split(" ").join("\n")
                    return item.nama_skpd;
                });

                for (i = 0; i < labels.length; i++) {
                    labels[i] = labels[i].split(" ");
                }

                dataAnggaran = json.map(function (item) {
                    return item.anggaran;
                });

                var slicedLabel = labels.slice(0, 7);
                var slicedAnggaran = dataAnggaran.slice(0, 7);
                var data = {
                    label: 'Realisasi Uang dan Anggaran',
                    data: slicedAnggaran,
                    backgroundColor: 'rgba(99, 132, 0, 0.6)'
                };
                chartData = {
                    labels: slicedLabel,
                    datasets: [data]
                };
                initializeChart();
                console.log("Data anggaran length : "+dataAnggaran.length);
                $('#pagination-demo').twbsPagination({
                    totalPages: Math.ceil(dataAnggaran.length/8),
                    visiblePages: 7,
                    onPageClick: function (event, page) {
                        page = page - 1;
                        resetChart(page);
                    }
                });
            }
        });
    };

    $(document).ready(function () {
        GetChartData();
    });
</script>