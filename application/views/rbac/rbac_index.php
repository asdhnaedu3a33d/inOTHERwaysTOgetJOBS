<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Role Based Access Control</title>

<script type="text/javascript" src="<?php echo base_url(); ?>asset/jquery/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>asset/jquery/jquery-ui.min.js"></script>

	
<style type='text/css' media='all'>
body
{
    padding: 0;
    margin: 0;
}

#info {
	overflow: visible;
    width: 100%;
    position: fixed;
    z-index:2;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 10pt;
    font-weight: normal;
	
}
 
#infobar 
{
    display: block;
    float: left;
    width:99%;
    clear: both;
    color: InfoText;    
    padding: .45em .3em .45em .5em;
    border-bottom: .16em outset;
    text-decoration: none;
    cursor: default;
	background: InfoBackground url(icon_warning.gif) no-repeat fixed .3em .3em;
}

#infobar a:hover
{
	color: blue;
}

#host {
   
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 10pt;
    font-weight: bold;
	float:right;
}

#content {
	padding: 10;
}

table {
    border-collapse: collapse;	
}

table td {
	padding:0px 3px;
}

pre.console {
	background-color:black;
	color:white;
	overflow-x:auto;
	padding:5px
}

#content {
	padding-top: 40px;
	padding-left: 10px
}

</style>

</head>
<body>
<div id='info'>
	<div id='infobar'>
		<a href="<?php echo site_url("rbac/rbac_roles"); ?>">RBAC Roles</a> |
		<a href="<?php echo site_url('rbac/rbac_permission'); ?>">RBAC Permission</a> |
		<a href="<?php echo site_url('rbac/rbac_role_permission'); ?>">RBAC Role Permission</a> |
		<a href="<?php echo site_url('rbac/rbac_user_role'); ?>">RBAC User Role</a>
	</div>
</div>

<div id='content'>
	<?php
		echo $contents;
	?>
</div>

</body>
</html>
