<!doctype html>
<head>

</head>
<body>
<?php echo "<a href=\"".site_url("rbac/rbac_roles/addRoles")."\">Add</a>";?>
<table width="600" border="1">
  <tr>
    <th width="80">Role ID</th>
    <th width="*">Role Name</th>
    <th width="100">Action</th>
  </tr>
  <?php
  
  foreach($results as $result){
     $save = "rbac/rbac_roles/edit/".$result->role_id;
     $delete = "rbac/rbac_roles/delete/".$result->role_id;
     $save_url = "<a href=\"".site_url($save)."\">Edit</a>";
     $delete_url = "<a href=\"".site_url($delete)."\">Delete</a>";
     
     $a = "<tr>
            <td>".$result->role_id."</td>
            <td>".$result->role_name."</td>
            <td>".$save_url." ".$delete_url."</td>
           </tr>";
     echo $a;
  } 
  ?>
  
  
</table>

  
</body>

</html>