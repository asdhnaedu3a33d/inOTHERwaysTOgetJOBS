<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="row">
  <div class="span12">
    
      SKPD&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $dd_skpd; ?>
      <br>
      Tahun&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $t_anggaran; ?>    
      <button id="btn-search" class="btn" type="button">Search!</button>    
  </div>
</div>
<div class="row">
  <div id="renja_frame" class="span12">    
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    $("#btn-search").click(function(){      
      $.ajax({
        type: "POST",
        url: '<?php echo site_url("guest/report/get_data_renja"); ?>',
        data: {skpd: $("select[name=dd_skpd]").val(),ta: $("select[name=t_anggaran]").val()},
        success: function(msg){
          $("div#renja_frame").html(msg);
        }
      });
    });
  });
</script>