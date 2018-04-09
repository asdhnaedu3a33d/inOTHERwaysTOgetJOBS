<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Sirenbangda Jendela Publik</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <style type="text/css">
        #page-wrapper {
            display: none;
        }

        #loading-full {
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            width: 100vw;
            height: 100vh;
            background-color: #1abc9c;
        }

        .spinner {
            margin: auto;
            width: 50px;
            height: 60px;
            text-align: center;
            font-size: 10px;

            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
        }

        .spinner > div {
            background-color: #333;
            height: 100%;
            width: 6px;
            display: inline-block;

            -webkit-animation: sk-stretchdelay 1.2s infinite ease-in-out;
            animation: sk-stretchdelay 1.2s infinite ease-in-out;
        }

        .spinner .rect2 {
            -webkit-animation-delay: -1.1s;
            animation-delay: -1.1s;
        }

        .spinner .rect3 {
            -webkit-animation-delay: -1.0s;
            animation-delay: -1.0s;
        }

        .spinner .rect4 {
            -webkit-animation-delay: -0.9s;
            animation-delay: -0.9s;
        }

        .spinner .rect5 {
            -webkit-animation-delay: -0.8s;
            animation-delay: -0.8s;
        }

        @-webkit-keyframes sk-stretchdelay {
            0%, 40%, 100% {
                -webkit-transform: scaleY(0.4)
            }
            20% {
                -webkit-transform: scaleY(1.0)
            }
        }

        @keyframes sk-stretchdelay {
            0%, 40%, 100% {
                transform: scaleY(0.4);
                -webkit-transform: scaleY(0.4);
            }
            20% {
                transform: scaleY(1.0);
                -webkit-transform: scaleY(1.0);
            }
        }
    </style>
</head>
<body>
<div id="loading-full">
    <div class="spinner">
        <div class="rect1"></div>
        <div class="rect2"></div>
        <div class="rect3"></div>
        <div class="rect4"></div>
        <div class="rect5"></div>
    </div>
</div>

<!-- ################################################# CSS dan JS ################################################# -->
<link href="<?php echo site_url('asset/guest/css/bootstrap.min.css'); ?>" rel="stylesheet">
<link href="<?php echo site_url('asset/guest/css/bootstrap-responsive.min.css'); ?>" rel="stylesheet">
<link href="<?php echo site_url('asset/guest/css/font.google.css'); ?>" rel="stylesheet">
<link href="<?php echo site_url('asset/guest/css/font-awesome.css'); ?>" rel="stylesheet">
<link href="<?php echo site_url('asset/guest/css/style.css'); ?>" rel="stylesheet">
<link href="<?php echo site_url('asset/guest/css/pages/dashboard.css'); ?>" rel="stylesheet">
<link href="<?php echo site_url('asset/css/style-portal-table.css'); ?>" rel="stylesheet">

<script src="<?php echo site_url('asset/guest/js/jquery-1.7.2.min.js'); ?>"></script>
<script src="<?php echo site_url('asset/guest/js/excanvas.min.js'); ?>"></script>
<script src="<?php echo site_url('asset/guest/js/chart.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo site_url('asset/guest/js/bootstrap.js'); ?>"></script>
<script language="javascript" type="text/javascript"
        src="<?php echo site_url('asset/guest/js/full-calendar/fullcalendar.min.js'); ?>"></script>

<script src="<?php echo site_url('asset/guest/js/base.js'); ?>"></script>
<style type="text/css">
    @media (min-width: 600px) {
        .modal.medium {
            width: 60%; /* respsonsive width */
            margin-left: -30%; /* width/2) */
        }

        .modal.large {
            width: 80%; /* respsonsive width */
            margin-left: -40%; /* width/2) */
        }

        .modal.extra-large {
            width: 90%; /* respsonsive width */
            margin-left: -45%; /* width/2) */
        }
    }

    .footer-plus-plus {
        background-image: url("<?php echo site_url('asset/images/footer.png');?>");
        background-repeat: no-repeat;
        background-size: 600px 400px;
        background-position: right center;
    }
</style>
<script type="text/javascript">
    function reset_modal() {
        $("#myModal").removeClass('medium');
        $("#myModal").removeClass('large');
        $("#myModal").removeClass('extra-large');
    }

    $(document).on('hidden', '#myModal', function () {
        reset_modal();
        $('#myModal').removeData();
    });

    $(document).on('shown', '#myModal', function () {
        $('html, body').animate({
            scrollTop: $("#myModal").offset().top
        }, 'normal');
    })
</script>

<!-- Data tables-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('asset/css/jquery.dataTables.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('asset/css/jquery.dataTables_themeroller.css'); ?>">
<script src="<?php echo base_url('asset/js/jquery.dataTables.min.js'); ?>" type="text/javascript"></script>
<!-- ################################################# CSS dan JS ################################################# -->

<div id="page-wrapper-full">
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">
                <a style="transition:.5s; font-family:'Droid Sans', Helvetica, Arial, sans-serif; padding-top:10; padding-bottom:0;"
                   class="brand" href="<?php echo site_url(); ?>">
                    <img style="margin-top:-10px; margin-right:-3px;" height="35" width="35"
                         src="<?php echo site_url('asset/images/S_4_sirenbangda.png'); ?>"><font size="5">I</font>RENBANGDA
                    <i style="color: white; font-size: 10px;">Sistem Informasi Perencananaan Pembangunan Daerah</i>
                </a>
                <div class="pull-right">
                    <a href="<?php echo site_url(); ?>"><img
                                src="<?php echo site_url('asset/themes/modify-style/images/template/klk.png'); ?>"
                                height="40" width="40" alt="Klungkung"> <font class="brand">PEMKAB. KLUNGKUNG</font></a>
                </div>
            </div>
            <!-- /container -->
        </div>
        <!-- /navbar-inner -->
    </div>
    <!-- /navbar -->
    <div class="subnavbar">
        <div class="subnavbar-inner">
            <div class="container">
                <ul class="mainnav">
                    <li <?php echo (!empty($active_menu) && $active_menu == "dashboard") ? 'class="active"' : ''; ?>><a
                                href="<?php echo site_url('guest/home'); ?>"><i
                                    class="icon-dashboard"></i><span>Beranda</span> </a></li>
                    <li <?php echo (!empty($active_menu) && $active_menu == "report") ? 'class="active"' : ''; ?>><a
                                href="<?php echo site_url('guest/report'); ?>"><i class="icon-list-alt"></i><span>Laporan</span>
                        </a></li>
                    <li class="dropdown"><a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"> <i
                                    class="icon-long-arrow-down"></i><span>Menu Pintas</span> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo site_url('guest/report?report=renstra'); ?>"><i
                                            class="shortcut-icon icon-list-alt"></i><span class="shortcut-label"> Renstra</span></a>
                            </li>
                            <li><a href="<?php echo site_url('guest/report?report=rpjmd'); ?>"><i
                                            class="shortcut-icon icon-th"></i><span class="shortcut-label"> RPJMD</span></a>
                            </li>
                            <li><a href="<?php echo site_url('guest/report?report=renja'); ?>" class="shortcut"><i
                                            class="shortcut-icon icon-th-list"></i><span
                                            class="shortcut-label"> Renja</span></a></li>
                            <li><a href="<?php echo site_url('guest/report?report=rka'); ?>" class="shortcut"><i
                                            class="shortcut-icon icon-list"></i><span class="shortcut-label"> RKA</span></a>
                            </li>
                            <li><a href="<?php echo site_url('guest/report?report=dpa'); ?>" class="shortcut"><i
                                            class="shortcut-icon icon-file"></i><span class="shortcut-label"> DPA</span></a>
                            </li>
                            <li><a href="<?php echo site_url('guest/report?report=musrenbangdes'); ?>" class="shortcut"><i
                                            class="shortcut-icon icon-th-large"></i><span class="shortcut-label"> Rekapitulasi Desa</span></a>
                            </li>
                            <li><a href="<?php echo site_url('guest/report?report=rekap_kecamatan'); ?>"
                                   class="shortcut"><i class="shortcut-icon icon-briefcase"></i><span
                                            class="shortcut-label"> Rekapitulasi Kecamatan</span></a></li>
                            <li><a href="<?php echo site_url('guest/report?report=rekap_skpd'); ?>" class="shortcut"><i
                                            class="shortcut-icon icon-tasks"></i><span class="shortcut-label"> Rekapitulasi SKPD</span></a>
                            </li>
                        </ul>
                    </li>
                    <li><a href="<?php echo site_url('authenticate/login'); ?>"><i
                                    class="icon-signin"></i><span>Login</span> </a></li>
                    <!--<li><a href="guidely.html"><i class="icon-facetime-video"></i><span>App Tour</span> </a></li>
                    <li><a href="charts.html"><i class="icon-bar-chart"></i><span>Charts</span> </a> </li>
                    <li><a href="shortcodes.html"><i class="icon-code"></i><span>Shortcodes</span> </a> </li>
                    -->
                </ul>
            </div>
            <!-- /container -->
        </div>
        <!-- /subnavbar-inner -->
    </div>
    <!-- /subnavbar -->
    <!-- main -->
    <?php
    // dynamic content loaded here
    echo $contents;
    ?>
    <!-- /main -->
    <div class="extra">
        <div class="extra-inner footer-plus-plus">
            <div class="container">
                <div class="row">
                    <div class="span3">
                        <h4>
                            Sirenbangda</h4>
                        <ul>
                            <li><a href="<?php echo site_url(); ?>">Halaman Publik</a></li>
                            <li><a href="<?php echo site_url('authenticate/login'); ?>">Login</a></li>
                        </ul>
                    </div>
                    <div class="span3">
                        <h4>
                            Klungkung</h4>
                        <ul>
                            <li><a href="http://www.klungkungkab.go.id/index.php/home">Pemkab. Klungkung</a></li>
                            <li><a href="#">BAPPEDA</a></li>
                        </ul>
                    </div>
                    <div class="span2">
                        <h4>
                            Bali</h4>
                        <ul>
                            <li><a href="http://www.baliprov.go.id/">Pemprov. Bali</a></li>
                        </ul>
                    </div>
                    <!--<div class="span3">
                        <h4>
                            Open Source jQuery Plugins</h4>
                        <ul>
                            <li><a href="#">Open Source jQuery Plugins</a></li>
                        </ul>
                    </div>-->
                    <!-- /span3 -->
                    <!--<img style="float: right; margin-right:-100px;" src="<?php echo site_url('asset/images/footer.png'); ?>">-->
                </div>
                <!-- /row -->
            </div>
            <!-- /container -->
        </div>
        <!-- /extra-inner -->
    </div>
    <!-- /extra -->
    <div class="footer">
        <div class="footer-inner">
            <div class="container">
                <div class="row">
                    <div class="span12"> &copy; 2015 <a href="#">Badan Perencanaan Pembangunan Daerah Kabupaten
                            Klungkung</a>.
                    </div>
                    <!-- /span12 -->
                </div>
                <!-- /row -->
            </div>
            <!-- /container -->
        </div>
        <!-- /footer-inner -->
    </div>
    <!-- /footer -->
</div>
</body>
<script type="text/javascript">
    function dokumenReady(callback) {
        var intervalID = window.setInterval(cekDokumenReady, 1000);

        function cekDokumenReady() {
            if (document.getElementsByTagName('body')[0] !== undefined) {
                window.clearInterval(intervalID);
                callback.call(this);
            }
        }
    }

    function action(id, value) {
        document.getElementById(id).style.display = value ? 'block' : 'none';
    }

    dokumenReady(function () {
        action('page-wrapper-full', true);
        action('loading-full', false);
    });
</script>
</html>