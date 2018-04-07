<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Sirenbangda Jendela Publik</title>
    <link rel="stylesheet" href="<?php echo base_url().'asset/css/'; ?>font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url().'asset/css/'; ?>bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url().'asset/css/'; ?>laporan.css">

</head>
<body>
<?php
// dynamic content loaded here
echo $contents;
?>
<script src="<?php echo base_url().'asset/js/'; ?>jquery.min.js"></script>
<script src="<?php echo base_url().'asset/js/'; ?>popper.min.js"></script>
<script src="<?php echo base_url().'asset/js/'; ?>bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url().'asset/js/'; ?>Chart.bundle.min.js"></script>
<script type="text/javascript" src="<?php echo base_url().'asset/js/'; ?>Chart.min.js"></script>
<script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
<script>
    function toggleMain(){
        $("#mainSection").toggleClass("col-md-9 col-md-11");
        $("#mainSection").toggleClass("float-left ");
    }
    feather.replace()
</script>

<!-- Graphs -->
<script>
    $(document).ready(function () {
        var densityCanvas = document.getElementById("densityChart");

        Chart.defaults.global.defaultFontFamily = "Lato";
        Chart.defaults.global.defaultFontSize = 18;

        var densityData = {
            label: 'Density of Planet (kg/m3)',
            data: [5427, 5243, 5514, 3933, 1326, 687, 1271, 1638],
            backgroundColor: 'rgba(0, 99, 132, 0.6)',
            borderWidth: 0,
            yAxisID: "y-axis-density"
        };

        var gravityData = {
            label: 'Gravity of Planet (m/s2)',
            data: [3.7, 8.9, 9.8, 3.7, 23.1, 9.0, 8.7, 11.0],
            backgroundColor: 'rgba(99, 132, 0, 0.6)',
            borderWidth: 0,
            yAxisID: "y-axis-gravity"
        };

        var planetData = {
            labels: ["Mercury", "Venus", "Earth", "Mars", "Jupiter", "Saturn", "Uranus", "Neptune"],
            datasets: [densityData, gravityData]
        };

        var chartOptions = {
            scales: {
                xAxes: [{
                    barPercentage: 1,
                    categoryPercentage: 0.6
                }],
                yAxes: [{
                    id: "y-axis-density"
                }, {
                    id: "y-axis-gravity"
                }]
            }
        };

        var barChart = new Chart(densityCanvas, {
            type: 'bar',
            responsive: true,
            data: planetData,
            options: chartOptions
        });
    });
</script>
</body>
</html>