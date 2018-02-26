<?php
    if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<html>
<head>

  <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
<meta content="utf-8" http-equiv="encoding">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>SIRENBANGDA</title>

	  <!-- <link rel="stylesheet" href="<?php echo base_url();?>asset/css/layout.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url();?>asset/css/style-portal-content.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url();?>asset/css/style-portal-table.css" type="text/css" media="screen" />
	  <link rel="stylesheet" href="<?php echo base_url();?>asset/css/tabs.css" type="text/css" media="screen" /> -->
    <link rel="stylesheet" href="<?php echo base_url();?>asset/new-theme/bootstrap/css/bootstrap.min.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url();?>asset/new-theme/font-awesome-4.6.3/css/font-awesome.min.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url();?>asset/new-theme/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>asset/new-theme/skin-green-light.min.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url();?>asset/new-theme/custom.css" type="text/css" media="screen" />

	  <!-- jquery start -->
    <style type="text/css">@import url("<?php echo base_url(); ?>asset/jquery/css/jquery-ui.css");</style>
    <style>label.error {margin-left: 2px;width: auto;display: block;color:#F00;}</style>
    <script type="text/javascript" src="<?php echo base_url(); ?>asset/new-theme/jquery/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>asset/jquery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>asset/jquery/jquery.validate.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>asset/jquery/jquery.blockUI.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>asset/jquery/autoNumeric.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>asset/new-theme/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>asset/js/accounting.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>asset/js/common.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>asset/new-theme/dist/js/app.min.js"></script>
    <script src="<?php echo base_url('asset/new-plugin/js/jquery-ui.min.js') ?>" type="text/javascript"></script>

    <!-- begin jquery chosen-->
    <script type="text/javascript" src="<?php echo base_url(); ?>asset/js/chosen/chosen.jquery.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url();?>asset/js/chosen/chosen.min.css" type="text/css" media="screen" />
    <script language="javascript">
    var site_url4kc_finder = '<?php echo site_url(); ?>';
    function kombocari(){
        $(".kombocari").chosen({no_results_text: "Tidak Ada yang Sesuai...",
            search_contains: true});
        $(".kombocari").trigger("liszt:updated");
        $("select").trigger("liszt:updated");
    }

    function prepare_chosen(){
        /* For Chosen */
        var config = {
          '.chosen-select'           : {},
          '.chosen-select-deselect'  : {allow_single_deselect:true},
          '.chosen-select-no-single' : {disable_search_threshold:10},
          '.chosen-select-no-results': {no_results_text:'Oops, Tidak ada data!'}
        }
        for (var selector in config) {
          $(selector).chosen(config[selector]);
        }
    }

    function build_chosen(element){
      /* For Chosen */
      var config = {disable_search_threshold:10, no_results_text:'Maaf, Tidak ada data!',  placeholder_text_single: 'Mohon pilih option ini'};

      $(element).chosen(config);
      $(element).bind( "change", function() {
        try {
            $(this).valid();
        } catch (e) {

        }
      });
    }
    </script>
    <!-- end jquery chosen-->

    <script type="text/javascript">
        //auto numeric untuk semua class uang persen
        /*$( function() {
            $(".uang").autoNumeric(window.numOptions);
            $(".persen").autoNumeric(window.numOptionsPersen);
        });*/
        //date picker untuk semua class tanggal
        $(function() {
            $( ".tanggal").datepicker({
                dateFormat:"yy-mm-dd",
                changeMonth: true,
                changeYear: true,
            });
        });
    </script>

    <script type="text/javascript">
		function Start(page) {
			OpenWin = this.open(page, "CtrlWindow", "toolbar=no,menubar=no,location=center,scrollbars=yes,resizable=yes,width=800,height=650");
		}
    </script>

    <script type="text/JavaScript">
    function Start2(page) {
        OpenWin = this.open(page, "CtrlWindow", "toolbar=no,menubar=no,location=center,scrollbars=yes,resizable=yes,width=500,height=350");
    }
    </script>
    <script type="text/JavaScript">
    function Start3(page) {
        OpenWin = this.open(page, "CtrlWindow", "toolbar=no,menubar=no,location=center,scrollbars=yes,resizable=yes,width=900,height=650");
    }
    </script>

    <!-- fancybox & facebox -->
    <script src="<?php echo base_url(); ?>asset/facebox/facebox.js" type="text/javascript"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>asset/facebox/facebox.css" media="screen" type="text/css"/>

    <script type="text/javascript" src="<?php echo base_url(); ?>asset/fancybox/jquery.fancybox.pack.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>asset/fancybox/jquery.mousewheel-3.0.6.pack.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>asset/fancybox/jquery.fancybox.css" type="text/css" media="screen" />
    <!-- end fancybox & facebox-->

    <script type="text/javascript">
        function prepare_facebox()
        {
            console.log('prepare facebox ...');

            $(function() {
                $.facebox.settings.closeImage = "<?php echo base_url('asset/images/closelabel.png') ?>";
                $.facebox.settings.loadingImage = "<?php echo base_url('asset/images/loading2.gif') ?>";
                $('a[rel*=facebox]').facebox();
            });
        }

        $(document).bind('beforeReveal.facebox', function() {
          $("body").css("overflow-y", "hidden");
        });
        $(document).bind('afterClose.facebox', function() {
          $("body").css("overflow-y", "auto");
        })
    </script>

    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>asset/css/sprites.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>asset/css/sprites-famfam.css">

    <!-- Chosen -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>asset/new-theme/toastr/toastr.css">
    <script src="<?php echo base_url(); ?>asset/new-theme/toastr/toastr.js" type="text/javascript"></script>

    <!-- Datatables -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>asset/new-theme/datatables/css/dataTables.bootstrap.min.css">
    <script src="<?php echo base_url(); ?>asset/new-theme/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>asset/new-theme/datatables/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
    <script type="text/javascript">
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

        <div  class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <li style="margin-top:10px" class="dropdown tasks-menu">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i style="font-size: 18px;" class="fa fa-calendar-check-o"></i>
               <?php echo $this->session->userdata('t_anggaran_aktif')?>
               </a>



            <ul class="dropdown-menu">
              <li class="header">Pilih Tahun Anggaran...</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- start message -->


                    <?php
                    foreach ($t_anggaran as $row) {
                    ?>


                    <a href="" >

                      <h4  >
                      <li href="" onclick="klikTahun(this)"> <?php echo $row->tahun_anggaran; ?> </li>

                      </h4>

                    </a>
                    <?php
                    }
                    ?>

                  </li>
                  <!-- end message -->
                  </li>
                </ul>
              </li>

            </ul>
          </li>


                  <div  class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <li style="margin-top:10px" class="dropdown tasks-menu">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i style="font-size: 18px;" class="fa fa-sliders"></i>
               <?= $user->active_role ?>
               </a>



            <ul class="dropdown-menu">
              <li class="header">Pilih Role...</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- start message -->



                    <?php
                      foreach($user->roles as $rolename=>$roleobj) {
                    ?>



                    <a href="" >

                      <h4>
                       <li href="" onclick="role(this)"><?php echo $rolename ?>  </li>
                      </h4>

                    </a>
                    <?php
                    }
                    ?>

                  </li>
                  <!-- end message -->
                  </li>
                </ul>
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
        <?=
         $this->auth->create_menu($this->session->userdata('active_menu'));
         ?>
      </section>
      <!-- /.sidebar -->
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="min-height : 550px;">
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

  <script type="text/javascript">
        function klikTahun(obj) {
          var tahun_anggaran1 = obj.textContent;

          $.blockUI({
              message: 'Perubahan Tahun Renja, mohon ditunggu ...',
              css: window._css,
              overlayCSS: window._ovcss
          });

          $.ajax({
              type: "POST",
              url : "<?php echo site_url('setting/change_ta')?>",
              data: {ta:tahun_anggaran1},
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
      };
</script>

  <script type="text/javascript">
        function role(obj) {
        var role = obj.textContent;
        var role1 = role.split(' ').join('');
          // alert(role1);
        //block page
        $.blockUI({
          message: 'Perubahan role, mohon ditunggu ...',
          css: window._css,
          overlayCSS: window._ovcss
        });
//$(this).val()
        $.post('<?php echo site_url('authenticate/change_role');?>',
         { rolename:  role1 },
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
        var a =  $(this).val();
       alert(a);
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

<script type="text/javascript">
  function tahunanggaran_function(selectObj){
    $.blockUI({
              message: 'Perubahan Tahun Renja, mohon ditunggu ...',
              css: window._css,
              overlayCSS: window._ovcss
          });
  }
</script>




</body>
</html>
