<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SIRENBANGDA</title>
	<link href="<?php echo base_url().'asset/themes/modify-style/'; ?>css/login.css" type="text/css" rel="stylesheet">
    <link href="<?php echo base_url().'asset/themes/modify-style/'; ?>css/button.css" type="text/css" rel="stylesheet">
    <link href="<?php echo base_url().'asset/themes/simper-style/'; ?>css/style-loginbox.css" type="text/css" rel="stylesheet">
	<script language="javascript" src="<?php echo base_url().'asset/themes/modify-style/';?>js/jquery.min.js"></script>
    <script language="javascript" src="<?php echo base_url().'asset/themes/modify-style/';?>js/login.js"></script>
	<script>
	
	</script>
	<link rel="icon" href="<?php echo base_url().'asset/themes/modify-style/'; ?>images/template/ico.ico" />
</head>
<body>
	<div id="wrap_login">	
		<div class="photo_frame">
			<div id="photo_wrap">
				<div id="caption_login2">
					<div id="cap_kiri"></div>
					<div id="cap_kanan">
						 <div id="caption_wrap" class="a2"><div id="caption_login" class="a2"></div></div>
						 <p align="center" style="font-size:13px">Sistem Informasi Perencanaan Pembangunan Daerah</p>
					</div>
				</div>
			</div>
		</div>
		<div class="login">
			<div id="caption_wrap" class="a1"><div id="caption_login" class="a1"></div></div>
			<img src="<?php echo base_url().'asset/themes/modify-style/'; ?>images/template/klk.png" alt="Klungkung">
			<form action="<?php echo site_url('authenticate/login'); ?>" method="post" id="login_form">
				<input type="text" name="username" placeholder="Username" required  class="user">
				<input type="password" name="password" placeholder="Password" required >
				<input type="submit" name="login" value="Login" class="btn btn-info">
			</form>
            <!--<div class="link kiri">
                <a href="<?php echo site_url(); ?>">SIRENBANGDA</a>
            </div>
            -->
            <div class="link kanan">
                <a href="<?php echo site_url(); ?>">Halaman Utama</a>
            </div>
            <div id="form_result" style="display:none"></div>
		</div><!--end login-->
		
		<!--end frame-->
	</div><!--end wrap_login-->
	
	<div id="foot_login">
		<a href="#" target="_blank">Badan Perencanaan Pembangunan Daerah</a> <br>
		Kabupaten Klungkung <br>
	</div><!--end foot_login-->

</body>
</html>
