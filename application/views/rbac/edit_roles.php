<html>
<head>

</head>
<body>
<?php    
    echo form_open('rbac/rbac_roles/saveEdit');?>
    <?php
        foreach($results as $result){
            echo "Role id : ";
            echo form_input(array('name'=>'role_id',
                                    'value' => $result->role_id,
                                    'readonly' => false));
            echo "<br/>Role name : ";
            echo form_input('role_name',$result->role_name);
            echo "<br>";
        } 
    ?>
 <?php echo form_submit('save','Save');?>
 <br/>
 <?php echo form_close();?>

</body>

</html>