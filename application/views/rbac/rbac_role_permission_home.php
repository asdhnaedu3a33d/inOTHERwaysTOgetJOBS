<html>
<head>
</head>
<body>
<?php echo form_open('rbac/rbac_role_permission/edit');?>
<?php $option = array();
foreach($results as $result){
    $option[$result->role_id] = $result->role_name;
}
echo "Role : ";
echo form_dropdown('role_id',$option);
echo form_submit('select','Select');
echo "<br/>";
echo form_close();


?>

</body>
</html>