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
                <canvas id="densityChart" height="700px"></canvas>
            </div>
        </div>
        <canvas id="myChartAxis" height="300" width="0"></canvas>
    </div>
</main>

<!-- Graphs -->
<script>
    //https://github.com/chartjs/Chart.js/issues/2958#issuecomment-261949718

    var chartData = {};

    var myLiveChart = null;

    function initializeChart() {
        var densityCanvas = document.getElementById("densityChart").getContext("2d");

        Chart.defaults.global.defaultFontFamily = "Lato";
        Chart.defaults.global.defaultFontSize = 12;

        var chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                onComplete: function (animation) {
                    var sourceCanvas = myLiveChart.chart.canvas;
                    var copyWidth = myLiveChart.scales['y-axis-0'].width - 10;
                    var copyHeight = myLiveChart.scales['y-axis-0'].height + myLiveChart.scales['y-axis-0'].top + 10;
                    var targetCtx = document.getElementById("myChartAxis").getContext("2d");
                    targetCtx.canvas.width = copyWidth;
                    targetCtx.drawImage(sourceCanvas, 0, 0, copyWidth, copyHeight, 0, 0, copyWidth, copyHeight);
                }
            },
            scales: {
                xAxes: [{
                    barPercentage: 1,
                    categoryPercentage: 0.6,
                    ticks: {
                        autoSkip: false
                    }
                }]
//                yAxes: [{
//                    id: "y-axis-0"
//                }, {
//                    id: "y-axis-0"
//                }]
            }
        };

        myLiveChart = new Chart(densityCanvas, {
            type: 'bar',
            data: chartData,
            options: chartOptions
        });
    }
    var GetChartData = function () {
        $.ajax({
            url: "http://103.29.196.246/sirenbangda/2018-dev/laporan/test",
            method: 'GET',
            dataType: 'json',
            success: function (json) {
                var labels = json.map(function (item) {
                    return item.nama_skpd;
                });
                var dataAnggaran = json.map(function (item) {
                    return item.anggaran;
                });
                var data = {
                    label: 'Realisasi Uang dan Anggaran',
                    data: dataAnggaran,
                    backgroundColor: 'rgba(99, 132, 0, 0.6)'
                };
                chartData = {
                    labels: labels,
                    datasets: [data]
                };

                initializeChart();
            }
        });
    };

    $(document).ready(function () {
        GetChartData();
    });
</script>