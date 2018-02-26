<html>
<head>

</head>
<body>
<?php    
    echo form_open('rbac/rbac_permission/saveEdit');?>
    <?php
        foreach($results as $result){
            echo "Permission id : ";
            echo form_input(array('name'=>'perm_id',
                                    'value' => $result->perm_id,
                                    'readonly' => false));
            echo "<br/>Permission name : ";
            echo form_input('perm_desc',$result->perm_desc);
            echo "<br/>Permission detail : ";
            echo form_input('perm_detail',$result->perm_detail);
            echo "<br/>ID Menu : ";
            echo form_input('id_menu',$result->id_menu);            
            echo "<br>";
        } 
    ?>
 <?php echo form_submit('save','Save');?>
 <br/>
 <?php echo form_close();?>

</body>

</html>