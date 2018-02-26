<?php 
echo form_open('rbac/rbac_user_role/view');
$option = array();
foreach($results as $result){
    $option[$result->id_user] = $result->username;
}
echo "User : ";
echo form_dropdown('id_user',$option,$id_user);
echo form_submit('select','Select');
echo form_close();
?>

<?php 
echo form_open('rbac/rbac_user_role/add');
echo "Role : ";
$option1 = array();
foreach($all_roles as $a){
    $option1[$a->role_id] = $a->role_name;
}
foreach($all_roles as $a){
    foreach($user_role as $b){
        if($a->role_id == $b->role_id){
            unset($option1[$a->role_id]);
            break;
        }
    }
}


echo form_dropdown('role_id',$option1);
echo form_hidden('id_user',$id_user);
echo form_submit('add','Add');
echo form_close();
echo "<br/>Assigned Roles:<br/>";

echo form_open('rbac/rbac_user_role/delete', array('id' => 'delRoleForm'));
echo form_hidden('id_user',$id_user);
echo form_hidden('role_id');
?>
<script type="text/javascript">
    function deleteRole(param) {
        if (confirm('Are you sure to delete this record ?')) {
            $('input[name="role_id"]').val(param);
            $('#delRoleForm').submit();
        }
    }
</script>

<table border="1">
  <tr>
    <th >Role Name</th>
    <th >Action</th>
  </tr>

<?php
  
  foreach($user_role as $role){
    
     $delete_url = "<a href='#' title='Delete role'
            onClick='deleteRole($role->role_id)'>X</a>";
     
     $a = "<tr>
            <td>".$role->role_name."</td>
            <td align='center'>".$delete_url."</td>
           </tr>";
     echo $a;
  } 
  ?>  
</table>

<?php
echo form_close();
?>

</table>
