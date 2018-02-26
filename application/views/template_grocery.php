<?php
    if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>SIRENBANGDA</title>

    <link rel="stylesheet" href="<?php echo base_url();?>asset/new-theme/bootstrap/css/bootstrap.min.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url();?>asset/new-theme/font-awesome-4.6.3/css/font-awesome.min.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url();?>asset/new-theme/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>asset/new-theme/skin-green-light.min.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url();?>asset/new-theme/custom.css" type="text/css" media="screen" />

	  <!-- jquery start -->
    <style type="text/css">@import url("<?php echo base_url(); ?>asset/jquery/css/jquery-ui.css");</style>
    <style>label.error {margin-left: 2px;width: auto;display: block;color:#F00;}</style>

    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>asset/css/sprites.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>asset/css/sprites-famfam.css">

    <!-- Chosen -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>asset/new-theme/toastr/toastr.css">
    <script src="<?php echo base_url(); ?>asset/new-theme/toastr/toastr.js" type="text/javascript"></script>
</head>
<body class="hold-transition skin-green-light sidebar-mini">
  <div class="wrapper">
    <header class="main-header">
      <!-- Logo -->
      <a href="index2.html" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><img style="padding-top:5px; height: 45px;" src="<?php echo base_url().'asset/themes/modify-style/'; ?>images/template/klk.png"/></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><img style="padding-top:2px; padding-bottom: 5px; height: 45px;" src="<?php echo base_url().'asset/themes/modify-style/'; ?>images/template/klk.png"/> <b>SI</b>RENBANGDA</span>
      </a>
      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>

        <?php
          $user = $this->auth->get_user();
          $t_anggaran = $this->m_settings->get_tahun_anggaran_db();
        ?>
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <li class="dropdown tasks-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i style="font-size: 18px;" class="fa fa-calendar"></i> <?= $this->session->userdata('t_anggaran_aktif') ?>
              </a>
              <ul class="dropdown-menu">
                <li class="header">
                  <select id='cmb_ta' class="form-control">
                    <?php
                    foreach ($t_anggaran as $row) {
                    ?>
                        <option <?php if($this->session->userdata('t_anggaran_aktif')==$row->tahun_anggaran){echo "selected";} ?> value='<?php echo $row->tahun_anggaran ?>'><?php echo $row->tahun_anggaran; ?></option>
                    <?php
                    }
                    ?>
                  </select>
                </li>
              </ul>
            </li>
            <li class="dropdown tasks-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i style="font-size: 18px;" class="fa fa-sliders"></i> <?= $user->active_role ?>
              </a>
              <ul class="dropdown-menu">
                <li class="header">
                  <select id='roles' class="form-control">
                    <?php
                      foreach($user->roles as $rolename=>$roleobj) {
                        $selected = '';
                        if($user->active_role == $rolename) {
                          $selected = 'selected';
                        }
                        echo "<option value='$rolename' $selected>$rolename</option>";
                      }
                    ?>
                  </select>
                </li>
              </ul>
            </li>
            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="<?= base_url('asset/new-theme/img/user.png') ?>" class="user-image" alt="User Image">
                <span class="hidden-xs"><?= $this->session->userdata('username') ?></span>
              </a>
              <ul class="dropdown-menu">
                <!-- User image -->
                <li class="user-header">
                  <img src="<?= base_url('asset/new-theme/img/user.png') ?>" class="img-circle" alt="User Image">

                  <p>
                    <?= $this->session->userdata('nama_p') ?>
                    <small><?= $this->session->userdata('nama_skpd') ?></small>
                  </p>
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-left">
                    <?php
                      $id_skpd = $this->session->userdata('id_skpd');
                      $id_desa = $this->session->userdata('id_desa');
                      if (!empty($id_desa)){
                    ?>
                        <a href="<?= base_url('users/edit_desa/edit/'.$id_desa) ?>" class="btn btn-default btn-flat"><i class="fa fa-cog"></i> Edit Data Desa</a>
                    <?php
                      }elseif (!empty($id_skpd)){
                    ?>
                        <a href="<?= base_url('users/edit_skpd/edit/'.$id_skpd) ?>" class="btn btn-default btn-flat"><i class="fa fa-cog"></i> Edit Data SKPD</a>
                    <?php
                      }
                    ?>
                  </div>
                  <div class="pull-right">
                    <a href="<?php echo  site_url('authenticate/logout');?>" class="btn btn-default btn-flat"><i class="fa fa-sign-out"></i> Sign out</a>
                  </div>
                </li>
              </ul>
            </li>
            <!-- Control Sidebar Toggle Button -->
            <li>
              <a style="height: 50px" href="#" onclick="fullScreenMode(this)" data-toggle="control-sidebar"><i class="fa fa-arrows-alt"></i></a>
            </li>
          </ul>
        </div>
      </nav>
    </header>
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
          <div class="pull-left image">
            <img src="<?= base_url('asset/new-theme/img/user.png') ?>" class="img-circle" alt="User Image">
          </div>
          <div class="pull-left info">
            <p><?= $this->session->userdata('username') ?></p>
            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
          </div>
        </div>
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
          <div class="input-group">
            <input disabled type="text" name="q" class="form-control" value="<?= date('l - M d, Y') ?>">
                <span class="input-group-btn">
                  <button type="button" disabled name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-calendar"></i>
                  </button>
                </span>
          </div>
        </form>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <?= $this->auth->create_menu($this->session->userdata('active_menu')); ?>
      </section>
      <!-- /.sidebar -->
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section style="padding: 0; padding-bottom: 5px; background: #ffffff; border-bottom: 3px solid #61B38E;" class="content-header">
        <div style="padding-top: 6px; padding-left: 10px;">
          <i class="fa fa-clock-o" aria-hidden="true"></i> <font id="clock_show"><i>loading...</i></font>
        </div>
        <ol style="top:0;" class="breadcrumb">
        <?php
          $nama_skpd=$this->session->userdata('nama_skpd');
          if(!empty($nama_skpd)){
        ?>
          <li><a href="#"><i class="fa fa-bullhorn"></i> SKPD</a></li>
          <li class="active"><?= $nama_skpd ?></li>
        <?php
          }

          $nama_desa=$this->session->userdata('nama_desa');
          if(!empty($nama_desa)){
        ?>
          <li><a href="#"><i class="fa fa-university"></i> Desa</a></li>
          <li class="active"><?= $nama_skpd ?></li>
        <?php
          }
        ?>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        <?php echo $contents; ?>
      </section>
      <!-- /.content -->
      <a href="#" id="to-top">
  			Ke atas <i class="fa fa-chevron-circle-up" aria-hidden="true"></i>
  		</a>
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
      <div class="pull-right hidden-xs">
        <b>Elapsed-Time</b> {elapsed_time}
        <b>| Memory-Usage</b> {memory_usage}
      </div>
      <strong>Bappeda &copy; Since 2015 </strong> Klungkung - Bali
    </footer>
  </div>
  <script type="text/javascript" src="<?php echo base_url(); ?>asset/jquery/jquery.blockUI.js"></script>
  <script type="text/javascript" src="<?php echo base_url();?>asset/new-theme/bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>asset/js/common.js"></script>
  <script type="text/javascript" src="<?php echo base_url();?>asset/new-theme/dist/js/app.min.js"></script>


  <script src="<?php echo base_url(); ?>asset/new-theme/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script>
  <script src="<?php echo base_url(); ?>asset/new-theme/datatables/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
  <script type="text/javascript">
    var site_url4kc_finder = '<?php echo site_url(); ?>';

    $(document).ready(function(){
      $(document).on('click', '.dataTables_filter input', function(e) {
          $(this).css('width','300px');
      });
      $(document).on('blur', '.dataTables_filter input', function(e) {
          $(this).css('width','149px');
      });
    });

    jQuery.curCSS = function(element, prop, val) {
        return jQuery(element).css(prop, val);
    };
  </script>
  <script type="text/javascript">
    $(document).ready(function(){
      $('#cmb_ta').change(function() {
          $.blockUI({
              message: 'Perubahan Tahun Renja, mohon ditunggu ...',
              css: window._css,
              overlayCSS: window._ovcss
          });

          $.ajax({
              type: "POST",
              url : "<?php echo site_url('setting/change_ta')?>",
              data: {ta:$('#cmb_ta').val()},
              success: function(msg){

                  if(msg =='success'){
                      window.location = "<?php echo site_url('home')?>";
                  }else{
                      console.log('System error....');
                      alert('System error....');
                  }
                  //console.log($('#cmb_ta').val());

              }
          });
      });

      $('#roles').change(function() {
        //block page
        $.blockUI({
          message: 'Perubahan role, mohon ditunggu ...',
          css: window._css,
          overlayCSS: window._ovcss
        });

        $.post('<?php echo site_url('authenticate/change_role');?>',
         { rolename: $(this).val() },
         function(data) {
        	try {
        		var response = $.parseJSON(data);

        		if(response.errno != 0){
        			$.blockUI({
        				message: response.message,
        				timeout: 1500,
        				css: window._css,
        				overlayCSS: window._ovcss
        			});
        		} else {
        			//redirect
        			window.location = response.message;
        		}
        	} catch(e) {
        		//log error, misal format json salah
        		console.log('System error : "' + e + '"');
        		$.unblockUI();
        	}
         });
        });

        var offset = 300,
        offset_opacity = 1200,
        scroll_top_duration = 700,
        $back_to_top = $('#to-top');
        $(window).scroll(function(){
            ( $(this).scrollTop() > offset ) ? $back_to_top.addClass('cd-is-visible') : $back_to_top.removeClass('cd-is-visible cd-fade-out');
            if( $(this).scrollTop() > offset_opacity ) {
                $back_to_top.addClass('cd-fade-out');
            }
        });
        $back_to_top.on('click', function(event){
            event.preventDefault();
            $('body,html').animate({
                scrollTop: 0 ,
                }, scroll_top_duration
            );
        });

        $("h4.msg").delay(10000).fadeOut();

        $("ul.sidebar-menu li.active").parents("li").addClass("active");
        setInterval('updateClock()', 1000);        
    });

    function fullScreenMode(ele) {
      if ((document.fullScreenElement && document.fullScreenElement !== null) || (!document.mozFullScreen && !document.webkitIsFullScreen)) {
          if (document.documentElement.requestFullScreen) {
              document.documentElement.requestFullScreen();
          }
          else if (document.documentElement.mozRequestFullScreen) {
              document.documentElement.mozRequestFullScreen();
          }
          else if (document.documentElement.webkitRequestFullScreen) {
              document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
          }
          ele.find("i").removeClass('fa-expand fa-compress');
          ele.find("i").addClass('fa-compress');
      }
      else {
          if (document.cancelFullScreen) {
              document.cancelFullScreen();
          }
          else if (document.mozCancelFullScreen) {
              document.mozCancelFullScreen();
          }
          else if (document.webkitCancelFullScreen) {
              document.webkitCancelFullScreen();
          }
          ele.find("i").removeClass('fa-expand fa-compress');
          ele.find("i").addClass('fa-expand');
      }
    }

    function updateClock ( ){
  	  var currentTime = new Date ( );
      var currentHours = currentTime.getHours ( );
      var currentMinutes = currentTime.getMinutes ( );
      var currentSeconds = currentTime.getSeconds ( );
      // Pad the minutes and seconds with leading zeros, if required
      currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
      currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;
      // Choose either "AM" or "PM" as appropriate
      var timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";
      // Convert the hours component to 12-hour format if needed
      currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;
      // Convert an hours component of "0" to "12"
      currentHours = ( currentHours == 0 ) ? 12 : currentHours;
      // Compose the string for display
      var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay;
      $("#clock_show").html(currentTimeString);
    }
  </script>
</body>
</html>
