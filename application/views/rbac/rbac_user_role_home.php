<html>
<head>
</head>
<body>
<?php echo form_open('rbac/rbac_user_role/view');?>
<?php $option = array();
foreach($results as $result){
    $option[$result->id_user] = $result->username;
}
echo "User : ";
echo form_dropdown('id_user',$option);
echo form_submit('select','Select');
echo "<br/>";
echo form_close();


?>
</body>
</html>