<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= $title ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?php echo base_url();?>asset/new-theme/bootstrap/css/bootstrap.min.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="<?php echo base_url();?>asset/new-theme/font-awesome-4.6.3/css/font-awesome.min.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="<?php echo base_url();?>asset/new-theme/dist/css/AdminLTE.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <style type="text/css">
  	.backgroup-login {
  	  background: url(<?php echo base_url();?>asset/images/backgroup-login.jpg) no-repeat center center fixed;
  	  -webkit-background-size: cover;
  	  -moz-background-size: cover;
  	  -o-background-size: cover;
  	  background-size: cover;
  	  border-right: 1px solid #d2d6de;
  	  opacity: 0.8;
  	  position: absolute;
  	  height:100%;
  	}

  	.login-page{
  		webkit-transition: -webkit-transform .3s ease-in-out,width .3s ease-in-out;
  	    -moz-transition: -moz-transform .3s ease-in-out,width .3s ease-in-out;
  	    -o-transition: -o-transform .3s ease-in-out,width .3s ease-in-out;
	    transition: transform .3s ease-in-out,width .3s ease-in-out;
	    background-color: #f9fafc;
  	}

    .login-box-body{
      background-color: #f9fafc;
    }

  	.login-logo{
  		padding: 12% 0 10px;
  		margin-bottom:0;
  		border-bottom: solid 1px #d2d6de;
  	}

  	.backgroup-login-text h1{
  		font-size: 75px;
  		margin-top: 25%;
  	}

  	@media(max-width:767px){
  		.backgroup-login-text, .backgroup-login{
  			display: none;
  		}

  		.login-logo{
  			padding-top: 10px;
  		}
  	}

  	@media(min-width:768px){
  		.backgroup-login {
  			width: 70%;
  		}

  		.backgroup-login-text h1{
  			margin-top: 45%;
  		}
  	}
  	@media(min-width:992px){
  		.backgroup-login {
  			width: 77%;
  		}

  		.backgroup-login-text h1{
  			margin-top: 25%;
  		}
  	}

    .alert{
      margin-top: 10px;
    }
  </style>
</head>
<body class="hold-transition login-page">
	<div class="row">
		<div class="backgroup-login"></div>
		<div class="backgroup-login-text col-sm-8 col-md-9 text-center" style="color: #fff">
			<h1><b>SI</b>RENBANGDA</h1>
		</div>
		<div class="col-xs-12 col-sm-4 col-md-3">
			<div class="login-logo">
				<a href="<?= base_url('home') ?>"><img src="<?php echo base_url().'asset/themes/modify-style/'; ?>images/template/klk.png" alt="Klungkung"></a>
			</div>
			<div class="login-box-body">
				<form action="<?php echo site_url('authenticate/login'); ?>" method="post" id="login_form">
				  <div class="form-group has-feedback">
				    <input type="text" name="username" class="form-control" required placeholder="Username">
				    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
				  </div>
				  <div class="form-group has-feedback">
				    <input type="password" name="password" class="form-control" required placeholder="Password">
				    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
				  </div>
          <div class="form-group">
            <select class="form-control" name="periode">
              <!-- <option value="production">2014 - 2018</option> -->
              <!-- <option value="production_2">2019 - 2023</option> -->
              <option value="default">2014 - 2018</option>
              <option value="group_2">2019 - 2023</option>
            </select>
          </div>
				  <div class="row">
				    <div class="col-xs-12 col-sm-12 col-md-6">
				      <a href="#"><span class="glyphicon glyphicon-home"></span> Halaman Utama</a>
				    </div>
				    <div class="col-xs-12 col-sm-12 col-md-6 text-right">
				      <button type="submit" class="btn-block btn btn-primary btn-flat"><i class="fa fa-sign-in"></i> Sign In</button>
				    </div>
            <div class="col-xs-12">
              <div id="form_result" style="display:none" class="alert alert-success"></div>
            </div>
				  </div>
				</form>

				<div class="social-auth-links text-center" style="margin-top: 30px">
				  <p>Badan Perencanaan Pembangunan Daerah Kabupaten Klungkung</p>
				</div>
			</div>
		</div>
	</div>
  <script type="text/javascript" src="<?php echo base_url(); ?>asset/new-theme/jquery/jquery-2.2.3.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url();?>asset/new-theme/bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function(){
    		$('#login_form').submit(function() {
      		$("#form_result").removeClass().addClass("alert alert-info");
      		$("#form_result").html("<i class='fa fa-hourglass-start'></i> Validasi user ...");
      		$("#form_result").slideDown("fast");

      		$.ajax({
      			type: 'POST',
      			url: $("#login_form").attr('action'),
      			data: $("#login_form").serialize(),
            dataType: "json",
      			success: function(data_) {
      			  var pesan = data_.errno;

      				if(pesan=='0'){
      					$("#form_result").removeClass().addClass("alert alert-success");;
      					$("#form_result").html("<i class='fa fa-unlock'></i> Login berhasil ...");
      					document.location=data_.message;
      				}else if(pesan=='1')
      				{
      					$("#form_result").removeClass().addClass("alert alert-warning");
      					$('#form_result').html("<i class='fa fa-warning'></i> Username dan Password salah!");
      					$('input[type=text]').val("");
      					$('input[type=password]').val("");
      				}

      			}
      		})
      		return false;
      	});
    });
  </script>
</body>
</html>
