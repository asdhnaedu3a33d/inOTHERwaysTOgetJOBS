<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<link href="<?php echo base_url('asset/css/js-image-slider.css');?>" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url('asset/js/js-image-slider.js');?>" type="text/javascript"></script>
<style type="text/css">
    #big_stats i {
        font-size: 20px;
        line-height: 25px;
    }

    #summary2 #big_stats i {
        font-size: 18px;
        line-height: 20px;
    }

    #summary2 .rkpd, #big_stats .rkpd{
        color: #3D77FF;
    }

    #summary2 .apbd, #big_stats .apbd{
        color: #FFB300;
    }

    #big_stats .stat .value {
        font-size: 25px;
        font-weight: bold;
        color: #545454;

    }

    #summary2 #big_stats .stat .value {
        font-size: 17px;
        font-weight: bold;
        color: #545454;

    }

    #big_stats .stat {
        height: auto;
    }

    div.stat i{
        cursor: pointer;

    }

    .idownload{
        font-size: 18px;
    }

    @media (min-width: 1200px){
      .span7_galeri{
        width: 600px;
      }

      .span5_news{
        width: 540px;
      }
    }

    @media (max-width: 979px) and (min-width: 768px){
		#slider{
		    margin-left: -93px !important;
		}

		#slider .navBulletsWrapper{
			margin: auto;
			width: 414px;
		}

		#slider .mc-caption-bg, #slider .mc-caption-bg2{
			margin-left: 93px !important;
			display: table-cell;
			max-width: 414px;
		}
	}

	@media (max-width: 565px){
		.widget-header h3{
			font-size: 10px;
		}
	}

	@media (max-width: 425px){
		.widget-header h3{
			font-size: 8px;
		}
	}

	@media (max-width: 500px){
		#slider{
		    margin-left: -130px !important;
		}

		#slider .navBulletsWrapper{
			margin: auto;
			width: 320px;
		}

		#slider .mc-caption-bg, #slider .mc-caption-bg2{
			margin-left: 130px !important;
			display: table-cell;
			max-width: 320px;
		}
	}

	@media (max-width: 320px){
		#slider{
		    margin-left: -140px !important;
		}

		#slider .navBulletsWrapper{
			display: none;
		}

		#slider .mc-caption-bg, #slider .mc-caption-bg2{
			display: none;
		}
	}
</style>
<div class="main">
  <div class="main-inner">
    <div class="container">
      <div class="row">
        <div class="span7 span7_galeri">
          <div class="widget widget-nopad">
            <div class="widget-header"> <i class="icon-picture"></i>
              <h3>Galeri Foto</h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content">
              <div class="widget big-stats-container" style="margin-bottom:0;">
                <div class="widget-content">
                    <div id="slider" style="margin: auto;">
                        <?php
                            $i=0;
                            foreach ($slider as $row) { $i++;
                        ?>
                        <img src="<?php echo base_url($row->link);?>" alt="<?php echo $row->ket;?>" />
                        <?php } ?>
                    </div>
                </div>
                <!-- /widget-content -->

              </div>
            </div>
          </div>
        </div>
        <div class="span5 span5_news">
            <!--<div class="widget widget-nopad">
                <div class="widget-header"> <i class="icon-columns"></i>
                  <h3> Perbandingan Program Kegiatan</h3>
                </div>
                <div class="widget-content">
                  <div class="widget big-stats-container" style="margin-bottom: 1em;">
                    <div class="widget-content">
                      <h6 class="bigstats" style="padding-bottom:0;margin-bottom:0;">
                        Perbedaan antara program kegiatan yang tercantum dalam RKPD dan APBD Tahun <?php echo $tahun; ?> sesuai jumlah berikut.
                        <BR><i class="icon-sign-blank" style="color: #3D77FF;"><span style="padding-left:5px;" class="value">RKPD</span></i> <i class="icon-sign-blank" style="color: #FFB300;"><span style="padding-left:5px;" class="value">APBD</span></i>
                      </h6>
                      <div id="big_stats" class="cf" style="margin: 0 0.4em 0 0.4em;">
                        <div class="stat" style="border-right: 0;"> <i title="Program RKPD" class="icon-file rkpd"><span style="padding-left:5px;" class="value"><?php echo (!empty($perbandingan_prokeg->program_rkpd))?$perbandingan_prokeg->program_rkpd:0; ?></span></i></div>

                        <div class="stat"> <i title="Kegiatan RKPD" class="icon-tag rkpd"><span style="padding-left:5px;" class="value"><?php echo (!empty($perbandingan_prokeg->kegiatan_rkpd))?$perbandingan_prokeg->kegiatan_rkpd:0; ?></span></i></div>

                        <div class="stat" style="border-right: 0;"> <i title="Program APBD" class="icon-file apbd"><span style="padding-left:5px;" class="value"><?php echo (!empty($perbandingan_prokeg->program_apbd))?$perbandingan_prokeg->program_apbd:0; ?></span></i></div>

                        <div class="stat"> <i title="Kegiatan APBD" class="icon-tag apbd"><span style="padding-left:5px;" class="value"><?php echo (!empty($perbandingan_prokeg->kegiatan_apbd))?$perbandingan_prokeg->kegiatan_apbd:0; ?></span></i></div>
                      </div>
                    </div>
                  </div>
                </div>
            </div>-->
            <div class="widget widget-nopad">
                <div class="widget-header"> <i class="icon-tasks"></i>
                  <h3>Perbandingan Jumlah Program Kegiatan</h3>
                </div>
                <!-- /widget-header -->
                <div class="widget-content">
                  <div class="widget big-stats-container" style="margin-bottom: 1em;">
                    <div class="widget-content">
                      <h6 class="bigstats" style="padding-bottom:0;margin-bottom:0;">
                        RKPD TAHUN <?php echo $tahun; ?> MEMILIKI
                          <?php echo ((!empty($perbandingan_jml_prokeg->program_rkpd_1))?$perbandingan_jml_prokeg->program_rkpd_1:0) + ((!empty($perbandingan_jml_prokeg->program_rkpd_2))?$perbandingan_jml_prokeg->program_rkpd_2:0); ?>
                        PROGRAM
                          <?php echo ((!empty($perbandingan_jml_prokeg->kegiatan_rkpd_1))?$perbandingan_jml_prokeg->kegiatan_rkpd_1:0) + ((!empty($perbandingan_jml_prokeg->kegiatan_rkpd_2))?$perbandingan_jml_prokeg->kegiatan_rkpd_2:0); ?>
                        KEG YAITU
                          <?php echo ((!empty($perbandingan_jml_prokeg->program_rkpd_1))?$perbandingan_jml_prokeg->program_rkpd_1:0); ?>
                        PROG
                          <?php echo ((!empty($perbandingan_jml_prokeg->kegiatan_rkpd_1))?$perbandingan_jml_prokeg->kegiatan_rkpd_1:0); ?>
                        KEG PADA URUSAN WAJIB DAN
                          <?php echo ((!empty($perbandingan_jml_prokeg->program_rkpd_2))?$perbandingan_jml_prokeg->program_rkpd_2:0); ?>
                        PROG
                          <?php echo ((!empty($perbandingan_jml_prokeg->kegiatan_rkpd_2))?$perbandingan_jml_prokeg->kegiatan_rkpd_2:0); ?>
                        KEG PADA URUSAN PILIHAN. SEDANGKAN APBD TA <?php echo $tahun; ?> MEMILIKI
                          <?php echo ((!empty($perbandingan_jml_prokeg->program_apbd_1))?$perbandingan_jml_prokeg->program_apbd_1:0) + ((!empty($perbandingan_jml_prokeg->program_apbd_2))?$perbandingan_jml_prokeg->program_apbd_2:0); ?>
                        PROGR
                          <?php echo ((!empty($perbandingan_jml_prokeg->kegiatan_apbd_1))?$perbandingan_jml_prokeg->kegiatan_apbd_1:0) + ((!empty($perbandingan_jml_prokeg->kegiatan_apbd_2))?$perbandingan_jml_prokeg->kegiatan_apbd_2:0); ?>
                        KEG YAITU
                          <?php echo ((!empty($perbandingan_jml_prokeg->program_apbd_1))?$perbandingan_jml_prokeg->program_apbd_1:0); ?>
                        PROG
                          <?php echo ((!empty($perbandingan_jml_prokeg->kegiatan_apbd_1))?$perbandingan_jml_prokeg->kegiatan_apbd_1:0); ?>
                        KEG PADA URUSAN WAJIB &
                          <?php echo ((!empty($perbandingan_jml_prokeg->program_apbd_2))?$perbandingan_jml_prokeg->program_apbd_2:0); ?>
                        PROG
                          <?php echo ((!empty($perbandingan_jml_prokeg->kegiatan_apbd_2))?$perbandingan_jml_prokeg->kegiatan_apbd_2:0); ?>
                        KEG PADA URUSAN PILIHAN.
                        <BR><i class="icon-sign-blank" style="color: #3D77FF;"><span style="padding-left:5px;" class="value">RKPD</span></i> <i class="icon-sign-blank" style="color: #FFB300;"><span style="padding-left:5px;" class="value">APBD</span></i>
                    </h6>
                      <div id="summary2">
                          <!--<div id="big_stats" class="cf" style="margin: 0 0.4em 0 0.4em;">
                            <div class="stat" style="width: 50%"><h6>RKPD</h6></div>
                            <div class="stat" style="width: 50%"><h6>APBD</h6></div>
                          </div>-->
                          <div id="big_stats" class="cf" style="margin: 0.4em 0.4em 0 0.4em;">
                          	<div style="display: inline-block; text-align: center; width: 49%"><h6>Program</h6></div>
                            <div style="display: inline-block; text-align: center; width: 49%"><h6>Kegiatan</h6></div>
                          </div>
                          <div id="big_stats" class="cf" style="margin: 0.4em 0.4em 0 0.4em;">

                            <div class="stat" style="border-right: 0;">
                                <i title="Program Urusan Wajib RKPD" class="icon-file rkpd"><span style="padding-left:5px;" class="value"><?php echo (!empty($perbandingan_jml_prokeg->program_rkpd_1))?$perbandingan_jml_prokeg->program_rkpd_1:0; ?></span></i>
                            </div>
                            <!-- .stat -->

                            <div class="stat">
                                <i title="Program Urusan Pilihan RKPD" class="icon-copy rkpd"><span style="padding-left:5px;" class="value"><?php echo (!empty($perbandingan_jml_prokeg->program_rkpd_2))?$perbandingan_jml_prokeg->program_rkpd_2:0; ?></span></i>
                            </div>
                            <!-- .stat -->

                            <div class="stat" style="border-right: 0;">
                                <i title="Kegiatan Urusan Wajib RKPD" class="icon-tag rkpd"><span style="padding-left:5px;" class="value"><?php echo (!empty($perbandingan_jml_prokeg->kegiatan_rkpd_1))?$perbandingan_jml_prokeg->kegiatan_rkpd_1:0; ?></span></i>
                            </div>
                            <!-- .stat -->

                            <div class="stat">
                                <i title="Kegiatan Urusan Pilihan RKPD" class="icon-tags rkpd"><span style="padding-left:5px;" class="value"><?php echo (!empty($perbandingan_jml_prokeg->kegiatan_rkpd_2))?$perbandingan_jml_prokeg->kegiatan_rkpd_2:0; ?></span></i>
                            </div>
                            <!-- .stat -->
                          </div>
                          <div id="big_stats" class="cf" style="margin: 0 0.4em 0 0.4em;">
                            <div class="stat" style="border-right: 0;">
                                <i title="Program Urusan Wajib APBD" class="icon-file apbd"><span style="padding-left:5px;" class="value"><?php echo (!empty($perbandingan_jml_prokeg->program_apbd_1))?$perbandingan_jml_prokeg->program_apbd_1:0; ?></span></i>
                            </div>
                            <!-- .stat -->

                            <div class="stat">
                                <i title="Program Urusan Pilihan APBD" class="icon-copy apbd"><span style="padding-left:5px;" class="value"><?php echo (!empty($perbandingan_jml_prokeg->program_apbd_2))?$perbandingan_jml_prokeg->program_apbd_2:0; ?></span></i>
                            </div>
                            <!-- .stat -->

                            <div class="stat" style="border-right: 0;">
                                <i title="Kegiatan Urusan Wajib APBD" class="icon-tag apbd"><span style="padding-left:5px;" class="value"><?php echo (!empty($perbandingan_jml_prokeg->kegiatan_apbd_1))?$perbandingan_jml_prokeg->kegiatan_apbd_1:0; ?></span></i>
                            </div>
                            <!-- .stat -->

                            <div class="stat">
                                <i title="Kegiatan Urusan Pilihan APBD" class="icon-tags apbd"><span style="padding-left:5px;" class="value"><?php echo (!empty($perbandingan_jml_prokeg->kegiatan_apbd_2))?$perbandingan_jml_prokeg->kegiatan_apbd_2:0; ?></span></i>
                            </div>
                            <!-- .stat -->
                          </div>
                          <div id="big_stats" class="cf" style="margin: 0 0.3em 0 0.3em;">
								<div style="display: inline-block; text-align: center; width: 39%; padding-left:5%; padding-right:5%;">
									<canvas id="pie-perbandingan" style="width: 100%" width="250" height="180"></canvas>
								</div>
								<div style="display: inline-block; text-align: center; width: 39%; padding-left:5%; padding-right:5%;">
									<canvas id="pie-perbandingan2" style="width: 100%" width="250" height="180"></canvas>
								</div>
                          </div>
                      </div>
                    </div>
                    <!-- /widget-content -->

                  </div>
                </div>
            </div>
        </div>
    </div>
      <div class="row">
        <div class="span6">
          <div class="widget widget-nopad" id="kendali">
                <div class="widget-header"> <i class="icon-th-large"></i>
                  <h3> Kendali Pelaksanaan Kegiatan Triwulan <?php echo $triwulan; ?> </h3>
                </div>
                <!-- /widget-header -->
                <div class="widget-content" style="text-align:center;">
                  <h6 id="title_kendali" class="kendali bigstats" style="padding:0;margin:0 15px 0 15px;text-align:center;"></h6>
                  <div class="kendali shortcuts" style="margin-top: 10px;">
                    <a style="background: #D46A6A; border-radius: 50%; width: auto; height:auto; padding:0px;" class="shortcut"><div title="Target dibawah 60%" style="display: table-cell; text-align: center; vertical-align: middle;width:100px;height:100px;"><span id="kendali1" style="font-size: 28px;" class="shortcut-label"></span></div></a>
                    <a style="background: #D4CF6A; border-radius: 50%; width: auto; height:auto; padding:0px;" class="shortcut"><div title="Target antara 60% hingga 79%" style="display: table-cell; text-align: center; vertical-align: middle;width:100px;height:100px;"><span id="kendali2" style="font-size: 28px;" class="shortcut-label"></span></div></a>
                    <a style="background: #82B582; border-radius: 50%; width: auto; height:auto; padding:0px;" class="shortcut"><div title="Target antara 90% hingga 99%" style="display: table-cell; text-align: center; vertical-align: middle;width:100px;height:100px;"><span id="kendali3" style="font-size: 28px;" class="shortcut-label"></span></div></a>
                    <a style="background: #A597FF; border-radius: 50%; width: auto; height:auto; padding:0px;" class="shortcut"><div title="Target mencapai 100%%" style="display: table-cell; text-align: center; vertical-align: middle;width:100px;height:100px;"><span id="kendali4" style="font-size: 28px;" class="shortcut-label"></span></div></a>
                  </div>
                  <div style="padding-left: 10px;">
                  	Target :
                  	<i class="icon-sign-blank" style="color: #D46A6A;"><span style="padding-left:5px;" class="value">< 60%</span></i>
                  	<i class="icon-sign-blank" style="color: #D4CF6A;"><span style="padding-left:5px;" class="value">60% - 79%</span></i>
                  	<i class="icon-sign-blank" style="color: #82B582;"><span style="padding-left:5px;" class="value">90% - 99%</span></i>
                  	<i class="icon-sign-blank" style="color: #A597FF;"><span style="padding-left:5px;" class="value">Mencapai 100%</span></i>
                  </div>
				  <a style="float: right; margin-right: 10px;" id="showKendali" href="javascript:;"><i class="icon-list-alt"></i> Lihat Semua . . .</a>
                  <!--<div style="padding: 10px; display: table; margin: auto;">
                    <div style="padding: 10px; display: table-cell;">
                      <div style="border-radius: 50%;width: 100px;height: 100px;background: yellow;border: 3px solid red;">
                      </div>
                    </div>
                    <div style="padding: 10px; display: table-cell;">
                      <div style="border-radius: 50%;width: 100px;height: 100px;background: yellow;border: 3px solid red;">
                      </div>
                    </div>
                    <div style="padding: 10px; display: table-cell;">
                      <div style="border-radius: 50%;width: 100px;height: 100px;background: yellow;border: 3px solid red;">
                      </div>
                    </div>
                    <div style="padding: 10px; display: table-cell;">
                      <div style="border-radius: 50%;width: 100px;height: 100px;background: yellow;border: 3px solid red;">
                      </div>
                    </div>
                  </div>
                  -->
                </div>
                <!-- /widget-content -->
          </div>
          <div class="widget">
            <div class="widget-header"> <i class="icon-signal"></i>
              <h3> Realisasi Serapan Anggaran</h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content">
              <canvas id="area-chart" class="chart-holder" height="200" width="538"> </canvas>
              <i class="icon-sign-blank" style="color: rgba(220,220,220,1);"><span style="padding-left:5px;" class="value">Anggaran (Rp. M)</span></i> <i class="icon-sign-blank" style="color: rgba(151,187,205,1);"><span style="padding-left:5px;" class="value">Realisasi (Rp. M) </span></i>
              <!-- /area-chart -->
            </div>
            <!-- /widget-content -->
          </div>
          <div class="widget">
            <div class="widget-header">
                <i class="icon-bar-chart"></i>
                <h3>Kinerja & Serapan Dana Program Kegiatan RKPD</h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content">
                <h6 id="title_capaian" class="bigstats" style="padding:0;margin:0;text-align:center;"></h6>
                <canvas id="capaian-chart" class="chart-holder" width="538" height="180">
                </canvas>
                <i class="icon-sign-blank" style="color: rgba(199, 96, 76, 1);"><span style="padding-left:5px;" class="value">Input</span></i> <i class="icon-sign-blank" style="color: rgba(105, 210, 231, 1);"><span style="padding-left:5px;" class="value">Output</span></i>
                <a style="float: right; margin-right: 10px;" id="showkinerja" href="javascript:;"><i class="icon-list-alt"></i> Lihat Semua . . .</a>
                <!-- /bar-chart -->
            </div>
            <!-- /widget-content -->
          </div>
          <div class="widget">
            <div class="widget-header">
                <i class="icon-adjust"></i>
                <h3>Perbandingan Program Kegiatan Per Bidang Urusan</h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content" style="text-align: center;">
                <h6 id="title_perbandingan" class="bigstats" style="padding:0;margin:0;text-align:center;"></h6>
                <div style="margin: auto; display: table;">
                    <div style="display:table-cell;">
                        <h6 style="padding:0;margin:0;text-align:center;">Program</h6>
                        <canvas id="pie-chart" class="chart-holder" width="250" height="180">
                        </canvas>
                    </div>
                    <div style="display:table-cell;">
                        <h6 style="padding:0;margin:0;text-align:center;">Kegiatan</h6>
                        <canvas id="pie-chart2" class="chart-holder" width="250" height="180">
                        </canvas>
                    </div>
                </div>
                <!-- /pie-chart -->
                <i class="icon-sign-blank" style="color: #4D5360;"><span style="padding-left:5px;" class="value">Ada pada RKPD dan APBD</span></i> <i class="icon-sign-blank" style="color: #3D77FF;"><span style="padding-left:5px;" class="value">Ada pada RKPD </span></i> <i class="icon-sign-blank" style="color: #FFB300;"><span style="padding-left:5px;" class="value">Ada pada APBD</span></i>
                <a style="float: right; margin-right: 10px;" id="showPerbandingan" href="javascript:;"><i class="icon-list-alt"></i> Lihat Semua . . .</a>
            </div>
            <!-- /widget-content -->
          </div>
        </div>
        <!-- /span6 -->
        <div class="span6">
          <!-- /widget -->
            <div class="widget widget-nopad">
                <div class="widget-header"> <i class="icon-calendar"></i>
                  <h3> Agenda </h3>
                </div>
                <!-- /widget-header -->
                <div class="widget-content">
                  <div id='calendar'>
                  </div>
                </div>
                <!-- /widget-content -->
            </div>
            <div class="widget widget-nopad">
                <div class="widget-header"> <i class="icon-list-alt"></i>
                  <h3> Berita</h3>
                </div>
                <!-- /widget-header -->
                <div class="widget-content">
                    <ul class="news-items">
                    <?php
                        foreach ($berita as $row) {
                ?>
                        <li>
                            <div class="news-item-date"> <span class="news-item-day"><?php echo date_format(date_create($row->date_cru), 'd'); ?></span> <span class="news-item-month"><?php echo date_format(date_create($row->date_cru), 'M'); ?></span> </div>
                            <div class="news-item-detail"> <a class="detNews" idN="<?php echo $row->id; ?>" href="javascript:;" class="news-item-title"><?php echo $row->title; ?></a>
                            <p class="news-item-preview"><?php echo $this->alat->cutNews($row->content,125); ?><a class="detNews" idN="<?php echo $row->id; ?>" href="javascript:;"> <i class="icon-search"></i>lihat</a></p>
                            </div>
                        </li>
                <?php
                        }
                    ?>
                    <li style="float: right;">
                       <a id="showNews" href="javascript:;"><i class="icon-list-alt"></i> Lihat Semua . . .</a>
                    </li>
                  </ul>
                </div>
                <!-- /widget-content -->
            </div>
            <div class="widget widget-table action-table">
                <div class="widget-header"> <i class="icon-th-list"></i>
                  <h3>Unduh Dokumen</h3>
                </div>
                <!-- /widget-header -->
                <div class="widget-content">
                  <table class="table table-striped table-bordered">
                    <tbody>
                <?php
                    foreach ($files as $row) {
                ?>
                        <tr>
                            <td> <?php echo $row->title; ?> </td>
                            <td class="td-actions">
                                <a class="bdet-file" idF="<?php echo $row->id; ?>" href="javascript:;"><i class="idownload icon-search"> </i></a>
                                <a target="_blank" href="<?php echo site_url($row->nama_file); ?>"><i class="idownload icon-download"> </i></a>
                            </td>
                        </tr>
                <?php
                    }
                ?>
                      <tr>
                        <td colspan="3"><a id="showFiles" href="javascript:;" style="float: right;"><i class="icon-list-alt"></i> Lihat Semua . . .</a></td>
                      </tr>

                    </tbody>
                  </table>
                </div>
                <!-- /widget-content -->
            </div>
            <div class="widget widget-table action-table">
                <div class="widget-header"> <i class="icon-th-list"></i>
                  <h3>Dokumen SKPD</h3>
                </div>
                <!-- /widget-header -->
                <div class="widget-content">
                <?php
                    if (!empty($skpd_files)) {
                ?>
                  <h6 id="title_dokumen_skpd" class="bigstats" style="padding:0;margin:5px 15px;text-align:center;">Perhubungan</h6>
                <?php
                    }
                ?>
                  <table class="table table-striped table-bordered">
                    <tbody>
                <?php
                    if (!empty($skpd_files)) {
                      foreach ($skpd_files as $row) {
                ?>
                        <tr>
                            <td> <?php echo $row->title; ?> </td>
                            <td class="td-actions">
                                <a class="bdet-file" idF="<?php echo $row->id; ?>" href="javascript:;"><i class="idownload icon-search"> </i></a>
                                <a target="_blank" href="<?php echo site_url($row->nama_file); ?>"><i class="idownload icon-download"> </i></a>
                            </td>
                        </tr>
                <?php
                      }
                ?>
                      <tr>
                        <td colspan="3"><a id="showFiles" href="javascript:;" style="float: right;"><i class="icon-list-alt"></i> Lihat Semua . . .</a></td>
                      </tr>
                <?php
                    }else{
                ?>
                      <tr>
                        <td colspan="3"><i>Data belum tersedia.</i></td>
                      </tr>
                <?php
                    }
                ?>
                    </tbody>
                  </table>
                </div>
                <!-- /widget-content -->
            </div>
          <!--
          <div class="widget">
            <div class="widget-header"> <i class="icon-file"></i>
              <h3> Content</h3>
            </div>
            <div class="widget-content">
              <ul class="messages_layout">
                <li class="from_user left"> <a href="#" class="avatar"><img src="img/message_avatar1.png"/></a>
                  <div class="message_wrap"> <span class="arrow"></span>
                    <div class="info"> <a class="name">John Smith</a> <span class="time">1 hour ago</span>
                      <div class="options_arrow">
                        <div class="dropdown pull-right"> <a class="dropdown-toggle " id="dLabel" role="button" data-toggle="dropdown" data-target="#" href="#"> <i class=" icon-caret-down"></i> </a>
                          <ul class="dropdown-menu " role="menu" aria-labelledby="dLabel">
                            <li><a href="#"><i class=" icon-share-alt icon-large"></i> Reply</a></li>
                            <li><a href="#"><i class=" icon-trash icon-large"></i> Delete</a></li>
                            <li><a href="#"><i class=" icon-share icon-large"></i> Share</a></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <div class="text"> As an interesting side note, as a head without a body, I envy the dead. There's one way and only one way to determine if an animal is intelligent. Dissect its brain! Man, I'm sore all over. I feel like I just went ten rounds with mighty Thor. </div>
                  </div>
                </li>
                <li class="by_myself right"> <a href="#" class="avatar"><img src="img/message_avatar2.png"/></a>
                  <div class="message_wrap"> <span class="arrow"></span>
                    <div class="info"> <a class="name">Bender (myself) </a> <span class="time">4 hours ago</span>
                      <div class="options_arrow">
                        <div class="dropdown pull-right"> <a class="dropdown-toggle " id="dLabel" role="button" data-toggle="dropdown" data-target="#" href="#"> <i class=" icon-caret-down"></i> </a>
                          <ul class="dropdown-menu " role="menu" aria-labelledby="dLabel">
                            <li><a href="#"><i class=" icon-share-alt icon-large"></i> Reply</a></li>
                            <li><a href="#"><i class=" icon-trash icon-large"></i> Delete</a></li>
                            <li><a href="#"><i class=" icon-share icon-large"></i> Share</a></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <div class="text"> All I want is to be a monkey of moderate intelligence who wears a suit… that's why I'm transferring to business school! I had more, but you go ahead. Man, I'm sore all over. I feel like I just went ten rounds with mighty Thor. File not found. </div>
                  </div>
                </li>
                <li class="from_user left"> <a href="#" class="avatar"><img src="img/message_avatar1.png"/></a>
                  <div class="message_wrap"> <span class="arrow"></span>
                    <div class="info"> <a class="name">Celeste Holm </a> <span class="time">1 Day ago</span>
                      <div class="options_arrow">
                        <div class="dropdown pull-right"> <a class="dropdown-toggle " id="dLabel" role="button" data-toggle="dropdown" data-target="#" href="#"> <i class=" icon-caret-down"></i> </a>
                          <ul class="dropdown-menu " role="menu" aria-labelledby="dLabel">
                            <li><a href="#"><i class=" icon-share-alt icon-large"></i> Reply</a></li>
                            <li><a href="#"><i class=" icon-trash icon-large"></i> Delete</a></li>
                            <li><a href="#"><i class=" icon-share icon-large"></i> Share</a></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <div class="text"> And I'd do it again! And perhaps a third time! But that would be it. Are you crazy? I can't swallow that. And I'm his friend Jesus. No, I'm Santa Claus! And from now on you're all named Bender Jr. </div>
                  </div>
                </li>
                <li class="from_user left"> <a href="#" class="avatar"><img src="img/message_avatar2.png"/></a>
                  <div class="message_wrap"> <span class="arrow"></span>
                    <div class="info"> <a class="name">Mark Jobs </a> <span class="time">2 Days ago</span>
                      <div class="options_arrow">
                        <div class="dropdown pull-right"> <a class="dropdown-toggle " id="dLabel" role="button" data-toggle="dropdown" data-target="#" href="#"> <i class=" icon-caret-down"></i> </a>
                          <ul class="dropdown-menu " role="menu" aria-labelledby="dLabel">
                            <li><a href="#"><i class=" icon-share-alt icon-large"></i> Reply</a></li>
                            <li><a href="#"><i class=" icon-trash icon-large"></i> Delete</a></li>
                            <li><a href="#"><i class=" icon-share icon-large"></i> Share</a></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <div class="text"> That's the ONLY thing about being a slave. Now, now. Perfectly symmetrical violence never solved anything. Uh, is the puppy mechanical in any way? As an interesting side note, as a head without a body, I envy the dead. </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>
          -->
        </div>
      </div>
      <!-- /row -->
    </div>
    <!-- /container -->
  </div>
  <!-- /main-inner -->
</div>

<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel"></h3>
  </div>
  <div id="myModalBody" class="modal-body">
  </div>
  <div class="modal-footer">
    <i style="float: right;">SIRENBANGDA</i>
  </div>
</div>

<script>
    function getBilanganRandom(min, max) {
      return Math.floor(Math.random() * (max - min)) + min;
    }

    function see_schedule(id){
        $("#myModal .modal-header").hide();
        $("#myModal #myModalBody").html("<center>Mohon tunggu . . .<BR><img src='<?php echo site_url('asset/images/loader.gif'); ?>'></center>");
        $("#myModal").modal();
        $.ajax({
            type: "POST",
            url: '<?php echo site_url("guest/home/get_det_schedule"); ?>',
            data: {id:id},
            dataType: 'json',
            success: function(msg){
                if (msg!="") {
                    $("#myModal .modal-header").fadeIn();
                    $("#myModal #myModalLabel").html(msg.title);
                    $("#myModal #myModalBody").html("<p>"+msg.information+"<BR><BR>Tanggal Mulai: "+msg.date_start+"<BR>"+"Tanggal Berakhir: "+msg.date_end+"</p>");
                    $("#myModal").modal();
                };
            }
        });
    }

    var lineChartData = {
        labels: ["", "Jan", "Peb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agst", "Sept", "Okt", "Nop", "Des"],
        datasets: [
            {
                fillColor: "rgba(220,220,220,0.5)",
                strokeColor: "rgba(220,220,220,1)",
                pointColor: "rgba(220,220,220,1)",
                pointStrokeColor: "#fff",
                data: <?php echo $anggaran; ?>
            },
            {
                fillColor: "rgba(151,187,205,0.5)",
                strokeColor: "rgba(151,187,205,1)",
                pointColor: "rgba(151,187,205,1)",
                pointStrokeColor: "#fff",
                data: <?php echo $realisasi; ?>
            }
        ]

    }

    var myLine = new Chart(document.getElementById("area-chart").getContext("2d")).Line(lineChartData);

    var myPie
    var myPie1;
    var bidang_urusan = <?php echo $bidang_urusan; ?>;
    function chartperbandingan(){
      index_array = getBilanganRandom(0, (bidang_urusan.length-1));

      $.ajax({
          type: "POST",
          url: '<?php echo site_url("guest/home/get_perbandingan"); ?>',
          data: {kd_bidang: bidang_urusan[index_array].id, kd_urusan: bidang_urusan[index_array].id_urusan},
          dataType: 'json',
          success: function(msg){
            if (msg.status != 0) {
              proDataDet = [
                  {
                      value: msg.pro_rkpd,
                      color: "#3D77FF"
                  },
                  {
                      value: msg.pro_apbd,
                      color: "#FFB300"
                  },
                  {
                      value: msg.pro_rkpd_apbd,
                      color: "#4D5360"
                  }

              ];

              kegDataDet = [
                  {
                      value: msg.keg_rkpd,
                      color: "#3D77FF"
                  },
                  {
                      value: msg.keg_apbd,
                      color: "#FFB300"
                  },
                  {
                      value: msg.keg_rkpd_apbd,
                      color: "#4D5360"
                  }

              ];
              try{
                myPie.destroy();
                myPie1.destroy();
              }catch(err) {
              }

              $("h6#title_perbandingan").hide();
              $("h6#title_perbandingan").html(msg.title);

              myPie = new Chart(document.getElementById("pie-chart").getContext("2d")).Doughnut(proDataDet);
              myPie1 = new Chart(document.getElementById("pie-chart2").getContext("2d")).Doughnut(kegDataDet);
              $("h6#title_perbandingan").fadeIn();
            }else{
              chartperbandingan();
            }
          }
      });
    }
    chartperbandingan();

    var myBar;
    function chartCapaian(){
      index_array = getBilanganRandom(0, (bidang_urusan.length-1));

      $.ajax({
          type: "POST",
          url: '<?php echo site_url("guest/home/get_kinerja"); ?>',
          data: {kd_bidang: bidang_urusan[index_array].id, kd_urusan: bidang_urusan[index_array].id_urusan},
          dataType: 'json',
          success: function(msg){
            $("h6#title_capaian").hide();
            $("h6#title_capaian").html(msg.title);
            var barChartData = {
                labels: ["Sangat Rendah", "Rendah", "Sedang", "Tinggi", "Sangat Tinggi"],
                datasets: [
                    {
                        fillColor: "rgba(199, 96, 76, 0.5)",
                        strokeColor: "rgba(199, 96, 76, 1)",
                        data: msg.input
                    },
                    {
                        fillColor: "rgba(105, 210, 231, 0.5)",
                        strokeColor: "rgba(105, 210, 231, 1)",
                        data: msg.output
                    }
                ]

            }

            myBar = new Chart(document.getElementById("capaian-chart").getContext("2d")).Bar(barChartData);
            $("h6#title_capaian").fadeIn();
          }
      });
    }
    chartCapaian();

    function circleKendali(){
      index_array = getBilanganRandom(0, (bidang_urusan.length-1));
    	$.ajax({
            type: "POST",
            url: '<?php echo site_url("guest/home/circle_kendali"); ?>',
            dataType: 'json',
            data: {kd_bidang: bidang_urusan[index_array].id, kd_urusan: bidang_urusan[index_array].id_urusan, triwulan: '<?php echo $triwulan; ?>'},
            success: function(msg){
                if (msg!="") {
                	$("div#kendali .kendali").fadeOut(function(){
                		$("div#kendali #title_kendali").html(msg.title);
	                    $("div#kendali #kendali1").html(msg.kendali1);
	                    $("div#kendali #kendali2").html(msg.kendali2);
	                    $("div#kendali #kendali3").html(msg.kendali3);
	                    $("div#kendali #kendali4").html(msg.kendali4);
                	});
                	$("div#kendali .kendali").fadeIn();
                };
            }
        });
    }
    circleKendali();

    function piePerbandingan(){
    	var proDataDetPerb = [
			{
			  value: "<?php echo ((!empty($perbandingan_jml_prokeg->program_rkpd_1))?$perbandingan_jml_prokeg->program_rkpd_1:0)+((!empty($perbandingan_jml_prokeg->program_rkpd_2))?$perbandingan_jml_prokeg->program_rkpd_2:0); ?>",
			  color: "#3D77FF"
			},
			{
			  value: "<?php echo ((!empty($perbandingan_jml_prokeg->program_apbd_1))?$perbandingan_jml_prokeg->program_apbd_1:0)+((!empty($perbandingan_jml_prokeg->program_apbd_2))?$perbandingan_jml_prokeg->program_apbd_2:0); ?>",
			  color: "#FFB300"
			}
		];

		var kegDataDetPerb = [
			{
			  value: "<?php echo ((!empty($perbandingan_jml_prokeg->kegiatan_rkpd_1))?$perbandingan_jml_prokeg->kegiatan_rkpd_1:0)+((!empty($perbandingan_jml_prokeg->kegiatan_rkpd_2))?$perbandingan_jml_prokeg->kegiatan_rkpd_2:0); ?>",
			  color: "#3D77FF"
			},
			{
			  value: "<?php echo ((!empty($perbandingan_jml_prokeg->kegiatan_apbd_1))?$perbandingan_jml_prokeg->kegiatan_apbd_1:0)+((!empty($perbandingan_jml_prokeg->kegiatan_apbd_2))?$perbandingan_jml_prokeg->kegiatan_apbd_2:0); ?>",
			  color: "#FFB300"
			}
		];
		var perbandinganPie = new Chart(document.getElementById("pie-perbandingan").getContext("2d")).Doughnut(proDataDetPerb);
		var perbandinganPie1 = new Chart(document.getElementById("pie-perbandingan2").getContext("2d")).Doughnut(kegDataDetPerb);
    }
    piePerbandingan();

    setInterval(function(){
    	circleKendali();
    	myPie.destroy();
    	myPie1.destroy();
    	myBar.destroy();
        chartperbandingan();
        chartCapaian();
    }, 60000);

    $(document).ready(function() {
        var calendar = $('#calendar').fullCalendar({
          header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month'
          },
          selectable: false,
          selectHelper: true,
          select: function(start, end, allDay) {
            var title = prompt('Event Title:');
            if (title) {
              calendar.fullCalendar('renderEvent',
                {
                  title: title,
                  start: start,
                  end: end,
                  allDay: allDay
                },
                true // make the event "stick"
              );
            }
            calendar.fullCalendar('unselect');
          },
          editable: true,
          events: <?php echo $schedule; ?>
        });

        $(document).on("click", ".detNews", function(){
            $("#myModal").addClass("large");
            $("#myModal .modal-header").hide();
            $("#myModal #myModalBody").html("<center>Mohon tunggu . . .<BR><img src='<?php echo site_url('asset/images/loader.gif'); ?>'></center>");
            $("#myModal").modal();
            $.ajax({
                type: "POST",
                url: '<?php echo site_url("guest/home/get_det_news"); ?>',
                data: {id:$(this).attr("idN")},
                dataType: 'json',
                success: function(msg){
                    if (msg!="") {
                        $("#myModal .modal-header").fadeIn();
                        $("#myModal #myModalLabel").html(msg.title);
                        $("#myModal #myModalBody").html(msg.content);
                        $("#myModal").modal();
                    };
                }
            });
        });

        $(document).on("click", ".bdet-file", function(){
            reset_modal();
            $("#myModal .modal-header").hide();
            $("#myModal #myModalBody").html("<center>Mohon tunggu . . .<BR><img src='<?php echo site_url('asset/images/loader.gif'); ?>'></center>");
            $("#myModal").modal();
            $.ajax({
                type: "POST",
                url: '<?php echo site_url("guest/home/get_det_file"); ?>',
                data: {id:$(this).attr("idF")},
                dataType: 'json',
                success: function(msg){
                    if (msg!="") {
                        $("#myModal .modal-header").fadeIn();
                        $("#myModal #myModalLabel").html(msg.title);
                        $("#myModal #myModalBody").html(msg.content);
                        $("#myModal").modal();
                    };
                }
            });
        });

        $("#showNews").click(function(){
            $("#myModal").addClass("extra-large");
            $("#myModal .modal-header").hide();
            $("#myModal #myModalBody").html("<center>Mohon tunggu . . .<BR><img src='<?php echo site_url('asset/images/loader.gif'); ?>'></center>");
            $("#myModal").modal();
            $.ajax({
                url: '<?php echo site_url("guest/home/news"); ?>',
                success: function(msg){
                    if (msg!="") {
                        $("#myModal .modal-header").fadeIn();
                        $("#myModal #myModalLabel").html("Berita");
                        $("#myModal #myModalBody").html(msg);
                        $("#myModal").modal();
                    };
                }
            });
        });

        $("#showFiles").click(function(){
            $("#myModal").addClass("extra-large");
            $("#myModal .modal-header").hide();
            $("#myModal #myModalBody").html("<center>Mohon tunggu . . .<BR><img src='<?php echo site_url('asset/images/loader.gif'); ?>'></center>");
            $("#myModal").modal();
            $.ajax({
                url: '<?php echo site_url("guest/home/files"); ?>',
                success: function(msg){
                    if (msg!="") {
                        $("#myModal .modal-header").fadeIn();
                        $("#myModal #myModalLabel").html("Unduh Dokumen");
                        $("#myModal #myModalBody").html(msg);
                        $("#myModal").modal();
                    };
                }
            });
        });

        $("#showKendali").click(function(){
        	$("#myModal").addClass("medium");
            $("#myModal .modal-header").hide();
            $("#myModal #myModalBody").html("<center>Mohon tunggu . . .<BR><img src='<?php echo site_url('asset/images/loader.gif'); ?>'></center>");
            $("#myModal").modal();
            $.ajax({
                url: '<?php echo site_url("guest/home/kendali"); ?>',
                success: function(msg){
                    if (msg!="") {
                        $("#myModal .modal-header").fadeIn();
                        $("#myModal #myModalLabel").html("Kendali Pelaksanaan Kegiatan");
                        $("#myModal #myModalBody").html(msg);
                        $("#myModal").modal();
                    };
                }
            });
        });

        $("#showkinerja").click(function(){
        	$("#myModal").addClass("medium");
            $("#myModal .modal-header").hide();
            $("#myModal #myModalBody").html("<center>Mohon tunggu . . .<BR><img src='<?php echo site_url('asset/images/loader.gif'); ?>'></center>");
            $("#myModal").modal();
            $.ajax({
                url: '<?php echo site_url("guest/home/kinerja"); ?>',
                success: function(msg){
                    if (msg!="") {
                        $("#myModal .modal-header").fadeIn();
                        $("#myModal #myModalLabel").html("Kinerja & Serapan Dana Program Kegiatan RKPD");
                        $("#myModal #myModalBody").html(msg);
                        $("#myModal").modal();
                    };
                }
            });
        });

        $("#showPerbandingan").click(function(){
        	$("#myModal").addClass("medium");
            $("#myModal .modal-header").hide();
            $("#myModal #myModalBody").html("<center>Mohon tunggu . . .<BR><img src='<?php echo site_url('asset/images/loader.gif'); ?>'></center>");
            $("#myModal").modal();
            $.ajax({
                url: '<?php echo site_url("guest/home/perbandingan"); ?>',
                success: function(msg){
                    if (msg!="") {
                        $("#myModal .modal-header").fadeIn();
                        $("#myModal #myModalLabel").html("Perbandingan Program Kegiatan Per Bidang Urusan");
                        $("#myModal #myModalBody").html(msg);
                        $("#myModal").modal();
                    };
                }
            });
        });
  });
</script><!-- /Calendar -->
