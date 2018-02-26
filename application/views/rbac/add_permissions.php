<html>
<head>

</head>
<body>
<?php    
    echo form_open('rbac/rbac_permission/addPermProcess');?>
    <?php
        echo "<br/>Permission name : ";
        echo form_input('perm_desc',"");
        echo "<br/>Permission detail : ";
        echo form_input('perm_detail',"");
        echo "<br/>ID Menu : ";
        echo form_input('id_menu',"");            
        echo "<br>";
    ?>
 <?php echo form_submit('save','Save');?>
 <br/>
 <?php echo form_close();?>

</body>

</html>