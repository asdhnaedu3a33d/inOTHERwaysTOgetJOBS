<table class='table-common tablesorter' width="1200px">  
  <thead>
    <tr>
      <th >No</th>
      <th >Status</th>
      <th >Keterangan</th>
      <th >Tanggal</th>      
      <th >Aktor</th>    
    </tr>
  </thead>  
  <tbody>
    <?php
      $no=0;
      foreach ($result as $row) {
        $no++;
    ?>
      <tr>
        <td><?php echo $no; ?></td>
        <td><?php echo $row->status; ?></td>
        <td><?php echo $row->keterangan; ?></td>
        <td><?php echo $row->create_date; ?></td>
        <td><?php echo $row->user; ?></td>
      </tr>
    <?php
      }
    ?>
  </tbody>
</table>