<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Sirenbangda Jendela Publik</title>
    <link rel="stylesheet" href="<?php echo base_url() . 'asset/guest/css/'; ?>font-awesome.css">
    <link rel="stylesheet" href="<?php echo base_url() . 'asset/css/'; ?>bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url() . 'asset/css/'; ?>laporan.css">
    <link rel="stylesheet" href="<?php echo base_url() . 'asset/css/'; ?>animated.min.css">
    <script src="<?php echo base_url() . 'asset/js/'; ?>jquery.min.js"></script>
    <script src="<?php echo base_url() . 'asset/js/'; ?>popper.min.js"></script>
    <script src="<?php echo base_url() . 'asset/js/'; ?>bootstrap.min.js"></script>
    <script src="<?php echo base_url() . 'asset/js/'; ?>jquery.twbsPagination.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url() . 'asset/js/'; ?>Chart.bundle.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url() . 'asset/js/'; ?>Chart.min.js"></script>
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
        feather.replace()

        $(document).ready(function () {
            $('#navbarSideButton').on('click', function () {
                $('#navbarSide').addClass('reveal');
                $('.overlay').show();
            });
        });
        function closeSideBar(){
            $('#navbarSide').removeClass('reveal');
            $('.overlay').hide();
        }
    </script>
</head>
<body>
<nav style="background-color: #00BA8B;" class="navbar navbar-dark sticky-top flex-md-nowrap py-1" id="topnav">
    <button class="navbar-toggler" style="color: white; border: none;" id="navbarSideButton" type="button">
        &#9776;
    </button>
    <ul class="navbar-side" id="navbarSide">
        <li class="navbar-side-item">
            <div class="container">
                <h3>Side Menu <span style="cursor: pointer;" class="pull-right" onclick="closeSideBar()"><i class="fa fa-times"></i></span></h3>
            </div>
            <a href="http://103.29.196.246/sirenbangda/2018-dev/laporan/rkpd_apbd" class="side-link d-block my-1"><i
                        class="icon-calendar mr-2"></i>Jumlah Program Kegiatan</a>
            <a href="#" class="side-link d-block my-1"><i
                        class="icon-calendar mr-2"></i>Kinerja Fisik Perangkat Daerah</a>
            <a href="http://103.29.196.246/sirenbangda/2018-dev/laporan/" class="side-link d-block my-1"><i
                        class="icon-calendar mr-2"></i>Serapan Anggaran Perangkat Daerah</a>
            <a href="#" class="side-link d-block my-1"><i
                        class="icon-calendar mr-2"></i>Peforma Perangkat Daerah</a>
            <a href="#" class="side-link d-block my-1"><i
                        class="icon-calendar mr-2"></i>Fisik VS Keuangan Perangkat Daerah</a>
        </li>
    </ul>
    <div class="container-fluid">
        <a style="transition:.5s; font-family:'Droid Sans', Helvetica, Arial, sans-serif; padding-top:10; padding-bottom:0;"
           class="navbar-brand" href="http://103.29.196.246/sirenbangda/2018-dev/">
            <img style="margin-top:-10px; margin-right:-3px;" height="35" width="35"
                 src="<?php echo site_url('asset/images/S_4_sirenbangda.png'); ?>"><strong>IRENBANGDA</strong> <i
                    style="color: white; font-size: 10px;">Sistem Informasi Perencananaan Pembangunan Daerah</i>
        </a>
        <!--        <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">SIRENBANGDA</a>-->
        <div class="pull-right pr-lg-4 mr-lg-2">
            <a href="http://103.29.196.246/sirenbangda/2018-dev/"><span class="navbar-brand"><strong>PEMKAB.
                    KLUNGKUNG</strong></span><img
                        src="<?php echo site_url("asset/themes/modify-style/images/template/klk.png") ?>" height="40"
                        width="40" alt="Klungkung"></a>
        </div>
    </div>
    <div class="overlay" onclick="closeSideBar()"></div>
</nav>

<nav class="navbar navbar-expand-sm navbar-light bg-light p-0">
    <div class="container-fluid bg-white">
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav my-topnav ml-5">
                <li class="nav-item text-center active" style="border-left: 1px solid #d9d9d9;">
                    <a class="nav-link py-2 px-4 active" href="#"> <i class="icon-dashboard" style="font-size: 20px"></i><br>
                        <span style="font-size: 12px; font-weight: bold;">Beranda</span></a>
                </li>
                <li class="nav-item text-center" style="border-left: 1px solid #d9d9d9;">
                    <a class="nav-link py-2 px-4" href="#"> <i class="icon-list-alt" style="font-size: 20px"></i>
                        <br><span style="font-size: 12px; font-weight: bold;">Laporan</span></a>
                </li>
                <li class="nav-item text-center" style="border-left: 1px solid #d9d9d9;">
                    <a class="nav-link py-2 px-4" href="#"> <i class="icon-long-arrow-down" style="font-size: 20px"></i>
                        <br><span style="font-size: 12px; font-weight: bold;">Menu-pintas</span></a>
                </li>
                <li class="nav-item text-center" style="border-left: 1px solid #d9d9d9;">
                    <a class="nav-link py-2 px-4" href="#"><i class="icon-signin" style="font-size: 20px"></i>
                        <br><span style="font-size: 12px; font-weight: bold;">Login</span></a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container-fluid bg-light">
    <div class="row d-flex d-md-block flex-nowrap wrapper">
        <?php
        // dynamic content loaded here
        echo $contents;
        ?>
    </div>
</div>

</body>
</html>