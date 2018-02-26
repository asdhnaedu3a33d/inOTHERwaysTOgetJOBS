<!doctype html>
<head>

</head>
<body>
<?php echo "<a href=\"".site_url("rbac/rbac_permission/addPerms")."\">Add</a>";?>
<table width="550" height="121" border="1">
  <tr>
    <th width="100">Permission ID</th>
    <th width="100">Permission Name</th>
    <th width="200">Permission Detail</th>
    <th width="50">ID Menu</th>
    <th width="100">Action</th>
  </tr>
<?php
  
  foreach($results as $result){
     $save = "rbac/rbac_permission/edit/".$result->perm_id;
     $delete = "rbac/rbac_permission/delete/".$result->perm_id;
     $save_url = "<a href=\"".site_url($save)."\">Edit</a>";
     $delete_url = "<a href=\"".site_url($delete)."\">Delete</a>";
     
     $a = "<tr>
            <td>".$result->perm_id."</td>
            <td>".$result->perm_name."</td>
            <td>".$result->perm_desc."</td>
            <td>".$result->id_menu."</td>
            <td>".$save_url." ".$delete_url."</td>
           </tr>";
     echo $a;
  } 
?>
  
</table>

  
</body>

</html>