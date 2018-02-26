<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="form-actions form-horizontal">
  <select id="bidang_urusan" class="span6 m-wrap">
  <?php
    foreach ($dd_bidang_urusan as $row) {
  ?>
    <option kdU="<?php echo $row->id_urusan; ?>" value="<?php echo $row->id; ?>">Bidang Urusan <?php echo $row->nama; ?></option>
  <?php
    }
  ?>
  </select>
    <button id="btn-search-kinerja" class="btn" type="button">Search!</button>
</div>
<div class="row">
  <div class="span8">
    <canvas id="kinerja-chart" class="chart-holder" width="538" height="180">
    </canvas>
    <font id="kinerjaTitle"></font> | <i class="icon-sign-blank" style="color: rgba(199, 96, 76, 1);"><span style="padding-left:5px;" class="value">Input</span></i> <i class="icon-sign-blank" style="color: rgba(105, 210, 231, 1);"><span style="padding-left:5px;" class="value">Output</span></i>
  </div>
</div>
<!-- /perbandingan-chart -->
<script type="text/javascript">
  var kinerjaBar;
  var barChartDataDet;
  $(document).ready(function(){
    $("#btn-search-kinerja").click(function(){
      reload_kinerja();
    });

    reload_kinerja();
  });

  function reload_kinerja(){    
    $.ajax({
        type: "POST",
        url: '<?php echo site_url("guest/home/get_kinerja"); ?>',
        data: {kd_bidang: $("select#bidang_urusan").val(), kd_urusan: $("select#bidang_urusan option:selected").attr("kdU")},
        dataType: 'json',
        success: function(msg){
            if (msg!="") {
              barChartDataDet = {
                labels: ["Sangat Rendah", "Rendah", "Sedang", "Tinggi", "Sangat Tinggi"],
                datasets: [
                    {
                        fillColor: "rgba(199, 96, 76, 0.5)",
                        strokeColor: "rgba(199, 96, 76, 1)",
                        data: msg.input
                    },
                    {
                        fillColor: "rgba(105, 210, 231, 0.5)",
                        strokeColor: "rgba(105, 210, 231, 1)",
                        data: msg.output
                    }
                ]
              }
              try{
                kinerjaBar.destroy();
              }catch(err) {
              }
              $("#kinerjaTitle").html(msg.title);
              kinerjaBar = new Chart(document.getElementById("kinerja-chart").getContext("2d")).Bar(barChartDataDet);
            };
        }
    });
  }
</script>
