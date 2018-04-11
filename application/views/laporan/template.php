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
<script src="<?php echo base_url().'asset/js/'; ?>jquery.min.js"></script>
<script src="<?php echo base_url().'asset/js/'; ?>popper.min.js"></script>
<script src="<?php echo base_url().'asset/js/'; ?>bootstrap.min.js"></script>
<script src="<?php echo base_url().'asset/js/'; ?>jquery.twbsPagination.min.js"></script>
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
<nav style="background-color: #00BA8B;" class="navbar navbar-dark sticky-top flex-md-nowrap p-2">
    <div class="container">
        <a style="transition:.5s; font-family:'Droid Sans', Helvetica, Arial, sans-serif; padding-top:10; padding-bottom:0;"
           class="navbar-brand" href="<?php echo site_url(); ?>">
            <img style="margin-top:-10px; margin-right:-3px;" height="35" width="35"
                 src="<?php echo site_url('asset/images/S_4_sirenbangda.png'); ?>"><font size="5">I</font>RENBANGDA
            <i style="color: white; font-size: 10px;">Sistem Informasi Perencananaan Pembangunan Daerah</i>
        </a>
        <div class="pull-right">

        </div>
        <!--        <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">SIRENBANGDA</a>-->
        <ul class="navbar-nav px-3 ml-auto">
            <li class="nav-item text-nowrap">
                <a class="text-white" href="<?php echo site_url(); ?>">
                    <font class="brand">PEMKAB. KLUNGKUNG</font><img
                            src="<?php echo site_url('asset/themes/modify-style/images/template/klk.png'); ?>"
                            height="40" width="40" alt="Klungkung"></a>
            </li>
        </ul>
    </div>
</nav>

<nav class="navbar navbar-expand-sm navbar-light bg-light p-0">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarNav">
            <span class="mr-3" data-target="#sidebar" data-toggle="collapse" onclick="toggleMain()"><i
                        class="fa fa-gear fa-2x py-3"></i></span>
            <ul class="navbar-nav my-topnav ml-5">
                <li class="nav-item text-center">
                    <a class="nav-link pt-2 pl-3 pr-3 active" href="#"> <i class="fa fa-home"></i><br>
                        Beranda <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item text-center">
                    <a class="nav-link pt-2 pl-3 pr-3" href="#"> <i class="fa fa-address-card-o"></i>
                        <br>Some-text</a>
                </li>
                <li class="nav-item text-center">
                    <a class="nav-link pt-2 pl-3 pr-3" href="#"> <i class="fa fa-automobile"></i>
                        <br>Menu-pintas</a>
                </li>
                <li class="nav-item text-center">
                    <a class="nav-link pt-2 pl-3 pr-3" href="#"><i class="fa fa-sign-in"></i>
                        <br>Login</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container-fluid">
    <div class="row d-flex d-md-block flex-nowrap wrapper">
        <div class="col-md-3 float-left col-1 pl-0 pr-0 collapse width show" id="sidebar">
            <div class="list-group border-0 card text-center text-md-left">
                <a href="#" class="list-group-item d-inline-block collapsed" data-parent="#sidebar"><i
                            class="fa fa-calendar"></i> <span class="d-none d-md-inline">Jumlah Program Kegiatan</span></a>
                <a href="#" class="list-group-item d-inline-block collapsed" data-parent="#sidebar"><i
                            class="fa fa-calendar"></i> <span
                            class="d-none d-md-inline">Kinerja Fisik Perangkat Daerah</span></a>
                <a href="#" class="list-group-item d-inline-block collapsed" data-parent="#sidebar"><i
                            class="fa fa-calendar"></i> <span
                            class="d-none d-md-inline">Serapan Anggaran Perangkat Daerah</span></a>
                <a href="#" class="list-group-item d-inline-block collapsed" data-parent="#sidebar"><i
                            class="fa fa-calendar"></i> <span class="d-none d-md-inline">Peforma Perangkat Daerah</span></a>
                <a href="#" class="list-group-item d-inline-block collapsed" data-parent="#sidebar"><i
                            class="fa fa-calendar"></i> <span
                            class="d-none d-md-inline">Fisik VS Keuangan Perangkat Daerah</span></a>
            </div>
        </div>
        <?php
        // dynamic content loaded here
        echo $contents;
        ?>
    </div>
</div>
</body>
</html>