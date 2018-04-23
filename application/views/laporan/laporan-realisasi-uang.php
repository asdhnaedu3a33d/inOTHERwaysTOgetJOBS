<main class="col-md-11 mx-auto main w-100 px-0 mt-3" id="mainSection">
    <form class="p-2 form-inline" style="border-bottom:3px solid black;">
        <div class="input-group mb-1">
            <select class="custom-select mx-auto form-inline" id="inputGroupSelect01">
                <option selected>Tahun</option>
                <option value="2014">2014</option>
                <option value="2015">2015</option>
                <option value="2016">2016</option>
                <option value="2017">2017</option>
                <option value="2018">2018</option>
                <option value="2019">2019</option>
                <option value="2020">2020</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
            </select>
            <div class="input-group-append mr-2 ml-2">
                <button class="btn btn-success" type="button">Pencarian</button>
            </div>
        </div>
    </form>
    <div class="chartWrapper">
        <div class="chartAreaWrapper">
            <div class="chartAreaWrapper2">
                <canvas id="realisasiChart" height="500px"></canvas>
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
        var realisasiCanvas = document.getElementById("realisasiChart").getContext("2d");

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

        myLiveChart = new Chart(realisasiCanvas, {
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