<nav style="background-color: #00BA8B;" class="navbar navbar-dark sticky-top flex-md-nowrap p-2">
    <div class="container"><a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">SIRENBANGDA</a>
        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap">
                <a style="font-weight: bold;color: white;text-decoration: none" class="nav-link" href="#">PEMKAB
                    KLUNGKUNG</a>
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
            <div class="chartjs-size-monitor"
                 style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                <div class="chartjs-size-monitor-expand"
                     style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                    <div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div>
                </div>
                <div class="chartjs-size-monitor-shrink"
                     style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                    <div style="position:absolute;width:200%;height:200%;left:0; top:0"></div>
                </div>
            </div>

            <canvas id="densityChart" class="my-2 chartjs-render-monitor w-100" width="1612" height="680"></canvas>
        </main>
    </div>
</div>