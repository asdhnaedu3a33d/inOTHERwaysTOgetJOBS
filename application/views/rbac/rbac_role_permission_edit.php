<html>
<head>
</head>
<body>
<?php echo form_open('rbac/rbac_role_permission/edit');?>
<?php 
$option = array();
foreach($roles as $role){
    $option[$role->role_id] = $role->role_name;
}
echo "Role : ";
echo form_dropdown('role_id',$option,$role_id);
echo form_submit('select','Select');
echo form_close();
echo "<br/>";
?>
<?php
echo form_open('rbac/rbac_role_permission/save');

foreach($groups as $group){

    echo "<div style='margin-bottom: 5px;'>{$group->group}</div>";
    echo "<table border='1'>";
    
    foreach($permissions as $perm){
        $checked = $perm->role_id !== NULL ? 'checked' : '';

        if($group->group == $perm->group){
            ?>
            <tr>
                <td>
                    <input type="checkbox" name="hasil[]" value="<?php echo $perm->perm_id?>"
                    <?php echo $checked?>>
                </td>
                <td style='width: 200px'>
                    <?php echo $perm->perm_name?>
                </td>
                <td style='width: 400px'>
                    <?php echo $perm->perm_desc?>
                </td>
            </tr>
            
            <?php
        }        
    }
    echo "</table><br/>";

}
    
echo form_hidden('role_id',$role_id);
echo form_submit('save','Save');
?>

<?php
echo form_close();


?>