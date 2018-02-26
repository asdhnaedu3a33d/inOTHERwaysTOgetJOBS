<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="row" style="margin-bottom: 10px;">
  <div class="span12">
      OPSI&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $laporan; ?>
      <button id="btn-cari" class="btn" type="button">Search!</button>    
  </div>
</div>

<div id="list-frame" class="shortcut">    
</div>

<script type="text/javascript">
  $(document).ready(function(){
    $("#btn-cari").click(function(){      
      $.ajax({
        type: "POST",
        url: '<?php echo site_url("guest/report/get_datalist"); ?>',
        data: {laporan: $("select[name=shortcut]").val()},
        success: function(msg){
          $("div#list-frame").html(msg);
        }
      });  
    });

    $("#btn-cari").trigger("click");
  });
</script>