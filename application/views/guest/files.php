<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<style type="text/css">
  label select, label input {
    display: table-row;
  }

  input, select {
    margin-bottom: 0px;
  }

  .dataTables_wrapper .dataTables_filter {    
    clear: none;
    float: right;
    margin-top: 0px;
  }

  #file td a{
    margin-left: 4px;
  }
</style>
<div class="row">
  <div class="span12">
    <table id="file" class="table table-striped table-bordered">
      <thead>
        <tr>
          <th class="no-sort" width="25px">No</th>      
          <th>Judul</th>         
          <th width="25px">Tanggal</th>
          <th class="no-sort" width="10px">Action</th>
        </tr>       
      </thead>
      <tbody>     
      </tbody>
    </table>
  </div>
</div>
<script type="text/javascript">
  var dt;
  $(document).ready(function(){
      dt = $("#file").DataTable({
        "processing": true,
          "serverSide": true,           
          "aoColumnDefs": [                
                {
                    "bSortable": false,
                    "aTargets": ["no-sort"]  
                }
            ],
            "ajax": {
              "url": "<?php echo site_url('guest/home/get_files'); ?>",
              "type": "POST"
          }         
      });

      $('div.dataTables_filter input').unbind();
      $("div.dataTables_filter input").keyup( function (e) {
        if (e.keyCode == 13) {
            dt.search( this.value ).draw();
        }
    } );    
  });  
</script>