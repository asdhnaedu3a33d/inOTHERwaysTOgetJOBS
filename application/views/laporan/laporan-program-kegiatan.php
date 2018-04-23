<main class="col-md-11 mx-auto main w-100" id="mainSection">
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
    <div class="row text-center mt-2">
        <div class="col-md-4 mx-auto">
            <H3>Program<br>RKPD & APBD</H3>
            <span class="px-4 mr-2" style="background-color: #0277BD; height: 15px;"></span>RKPD
            <span class="px-4 mr-2" style="background-color: #EF6C00; height: 15px;"></span>APBD
            <canvas class="mt-2" id="program" height="250px"></canvas>
            <div id="my-legend-a" class="row mt-2"></div>

        </div>
        <div class="col-md-4 mx-auto">
            <h3>Kegiatan<br>RKPD & APBD</h3>
            <span class="px-4 mr-2" style="background-color: #0277BD; height: 15px;"></span>RKPD
            <span class="px-4 mr-2" style="background-color: #EF6C00; height: 15px;"></span>APBD
            <canvas class="mt-2" id="kegiatan" height="250px"></canvas>
            <div id="my-legend-b" class="row mt-2"></div>
        </div>
    </div>
</main>

<script>
    var programContainer = document.getElementById('program');
    var kegiatanContainer = document.getElementById('kegiatan');
    var rkpdapbdKegiatanLabels = [];
    var rkpdapbdKegiatanData = [];
    var rkpdapbdProgramLabels = [];
    var rkpdapbdProgramData = [];
    var program = null;
    var kegiatan = null;

    function initializeChart(container, theChart, label, data, id) {
        Chart.defaults.global.defaultFontFamily = "Lato";
        Chart.defaults.global.defaultFontSize = 16;

        var setups = {
            type: 'doughnut',
            animation: {
                animateScale: true
            },
            data: {
                labels: label,
                datasets: [{
                    label: 'Visitor',
                    data: data,
                    backgroundColor: [
                        "#EF6C00",
                        "#EF6C00",
                        "#EF6C00",
                        "#EF6C00",
                        "#0277BD",
                        "#0277BD",
                        "#0277BD",
                        "#0277BD"
                    ]
                }]
            },
            options: {
                responsive: true,
                legend: {
                    display: false
                },
                tooltips: {
                    enabled: true,
                    mode: 'label',
                    callbacks: {
                        label: function (tooltipItem, data) {
                            var indice = tooltipItem.index;
                            return data.datasets[0].data[indice];
                        }
                    }
                },
                legendCallback: function (chart) {
                    var legendHtml = [];
                    var item = chart.data.datasets[0];
                    var label = chart.data.labels;
                    var total = 0;
                    for (var i = 0; i < item.data.length; i++) {
                        total += parseInt(item.data[i]);
                        if (i === 0)
                            legendHtml.push('<div class="col-md-6 mx-auto order-2">');
                        else if (i === item.data.length / 2)
                            legendHtml.push('<div class="col-md-6 mx-auto order-1">');
                        legendHtml.push('<span><i class="fa fa-database" style="font-weight: bolder; font-size: small; background-color:' + item.backgroundColor[i] + '"></i><strong>&nbsp;' + label[i] + '</strong></span>');
                        legendHtml.push('<p>' + item.data[i] + '</p>');
                        if (i === (item.data.length / 2) - 1 || i === item.data.length - 1) {
                            legendHtml.push('<span><i class="fa fa-database" style="font-weight: bolder; font-size: small; background-color:' + item.backgroundColor[i] + '"></i><strong>&nbsp; Total</strong></span>');
                            legendHtml.push('<p>' + total + '</p>');
                            legendHtml.push('</div>');
                            total = 0;
                        }
                    }
                    return legendHtml.join("");
                }
            }
        }
        theChart = new Chart(container, setups);
        if (id === 0)
            $('#my-legend-a').html(theChart.generateLegend());
        else if (id === 1)
            $('#my-legend-b').html(theChart.generateLegend());
    }

    var GetProgramChartData = function () {
        $.ajax({
            url: "http://103.29.196.246/sirenbangda/2018-dev/laporan/getChartDataJson/rkpd-apbd-program",
            method: 'GET',
            dataType: 'json',
            success: function (json) {
                json['rkpdprogram'].map(function (item) {
                    rkpdapbdProgramLabels.push(item.Nm_Urusan);
                });

                json['apbdprogram'].map(function (item) {
                    rkpdapbdProgramLabels.push(item.Nm_Urusan);
                });

                json['rkpdprogram'].map(function (item) {
                    rkpdapbdProgramData.push(item.jumlah_urusan);
                });

                json['apbdprogram'].map(function (item) {
                    rkpdapbdProgramData.push(item.jumlah_urusan);
                });
                initializeChart(programContainer, program, rkpdapbdProgramLabels, rkpdapbdProgramData, 0);
            }
        });
    };

    var GetKegiatanChartData = function () {
        $.ajax({
            url: "http://103.29.196.246/sirenbangda/2018-dev/laporan/getChartDataJson/rkpd-apbd-kegiatan",
            method: 'GET',
            dataType: 'json',
            success: function (json) {
                json['rkpdkegiatan'].map(function (item) {
                    rkpdapbdKegiatanLabels.push(item.Nm_Urusan);
                });

                json['apbdkegiatan'].map(function (item) {
                    rkpdapbdKegiatanLabels.push(item.Nm_Urusan);
                });

                json['rkpdkegiatan'].map(function (item) {
                    rkpdapbdKegiatanData.push(item.jumlah_urusan);
                });

                json['apbdkegiatan'].map(function (item) {
                    rkpdapbdKegiatanData.push(item.jumlah_urusan);
                });

                initializeChart(kegiatanContainer, kegiatan, rkpdapbdKegiatanLabels, rkpdapbdKegiatanData, 1);
            }
        });
    };

    $(document).ready(function () {
        GetKegiatanChartData();
        GetProgramChartData();
    });
</script>