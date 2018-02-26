<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="row">
  <div class="span12">
      Desa&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $dd_desa; ?><br>
      Tahun&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $t_anggaran; ?>
      <button id="btn-search" class="btn" type="button">Search!</button>
  </div>
</div>
<div class="row">
  <div id="musrenbangdes_frame" class="span12">    
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    $("#btn-search").click(function(){      
      $.ajax({
        type: "POST",
        url: '<?php echo site_url("guest/report/get_data_musrenbangdes"); ?>',
        data: {id_desa: $("select[name=dd_desa]").val(),ta: $("select[name=t_anggaran]").val()},
        success: function(msg){
          $("div#musrenbangdes_frame").html(msg);
        }
      });
    });
  });
</script>