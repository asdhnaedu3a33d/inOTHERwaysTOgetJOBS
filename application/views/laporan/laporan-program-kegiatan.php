<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-1 px-4">
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

    <div class="row text-center">
        <div class="col-md-3 mx-auto">
            <p>Program</p>
            <canvas id="program" width="100px" height="100px"></canvas>
        </div>
        <div class="col-md-3 mx-auto">
            <p>Ekstra</p>
            <canvas id="ekstra" width="100px" height="100px"></canvas>
        </div>
        <div class="col-md-3 mx-auto">
            <p>Kegiatan</p>
            <canvas id="kegiatan" width="100px" height="100px"></canvas>
        </div>
    </div>
</main>

<script>
    $(document).ready(function(){
        Chart.defaults.global.defaultFontFamily = "Lato";
        Chart.defaults.global.defaultFontSize = 18;

        var program = document.getElementById("program");
        var ekstra = document.getElementById("ekstra");
        var kegiatan = document.getElementById("kegiatan");

        Chart.defaults.global.defaultFontFamily = "Lato";
        Chart.defaults.global.defaultFontSize = 12;

        var programChart = new Chart(program, {
            type: 'doughnut',
            data: {
                labels: [
                    "RKPD",
                    "APBD",
                ],
                datasets: [
                    {
                        data: [133.3, 86.2],
                        backgroundColor: [
                            "blue",
                            "orange",
                        ]
                    }]
            },
            responsive: true
        });
        var ekstraChart = new Chart(ekstra, {
            type: 'doughnut',
            data: {
                labels: [
                    "RKPD",
                    "APBD",
                ],
                datasets: [
                    {
                        data: [105.3, 178.2],
                        backgroundColor: [
                            "green",
                            "blue",
                        ]
                    }]
            },
            responsive: true
        });
        var kegiatanChart = new Chart(kegiatan, {
            type: 'doughnut',
            data: {
                labels: [
                    "RKPD",
                    "APBD",
                ],
                datasets: [
                    {
                        data: [233.3, 386.2],
                        backgroundColor: [
                            "green",
                            "red",
                        ]
                    }]
            },
            responsive: true
        });
    });
</script>