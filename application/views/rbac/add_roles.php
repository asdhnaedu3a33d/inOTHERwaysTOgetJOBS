<html>
<head>

</head>
<body>
<?php    
    echo form_open('rbac/rbac_roles/addRolesProcess');?>
    <?php
        echo "<br/>Role name : ";
        echo form_input('role_name',"");
        echo "<br>";
         
    ?>
 <?php echo form_submit('save','Save');?>
 <br/>
 <?php echo form_close();?>

</body>

</html>