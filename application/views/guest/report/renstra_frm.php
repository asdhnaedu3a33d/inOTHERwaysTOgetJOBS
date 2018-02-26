<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="row">
  <div class="span12">
    
      <?php echo $dd_skpd; ?>    
      <button id="btn-search" class="btn" type="button">Search!</button>    
  </div>
</div>
<div class="row">
  <div id="renstra_frame" class="span12">    
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    $("#btn-search").click(function(){      
      $.ajax({
        type: "POST",
        url: '<?php echo site_url("guest/report/get_data_renstra"); ?>',
        data: {skpd: $("select[name=dd_skpd]").val()},
        success: function(msg){
          $("div#renstra_frame").html(msg);
        }
      });
    });
  });
</script>