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
    <button id="btn-search-perbandingan" class="btn" type="button">Search!</button>
</div>      
<div class="row">
  <div class="span4">
    <h6 style="padding:0;margin:0;text-align:center;">Program</h6>
    <canvas id="perbandingan-chart" class="chart-holder" width="250" height="180">
    </canvas>
  </div>
  <div class="span4">
    <h6 style="padding:0;margin:0;text-align:center;">Kegiatan</h6>
    <canvas id="perbandingan-chart2" class="chart-holder" width="250" height="180">
    </canvas>
  </div>
</div>
<font id="perbandinganTitle"></font> | <i class="icon-sign-blank" style="color: #4D5360;"><span style="padding-left:5px;" class="value">Ada pada RKPD dan APBD</span></i> <i class="icon-sign-blank" style="color: #3D77FF;"><span style="padding-left:5px;" class="value">Ada pada RKPD </span></i> <i class="icon-sign-blank" style="color: #FFB300;"><span style="padding-left:5px;" class="value">Ada pada APBD</span></i>
<script type="text/javascript">  
  var myPerbandingan;
  var myPerbandingan1;
  var kegDataDet;
  var proDataDet;
  $(document).ready(function(){
    $("#btn-search-perbandingan").click(function(){
      reload_perbandingan();
    });

    $('#myModal').on('shown', function (e) {
      try{
        reload_perbandingan();
      }catch(err) {    
      }    
    });    
  });

  function reload_perbandingan(){
    $.ajax({
        type: "POST",
        url: '<?php echo site_url("guest/home/get_perbandingan"); ?>',
        data: {kd_bidang: $("#bidang_urusan").val(), kd_urusan: $("#bidang_urusan option:selected").attr("kdU")},
        dataType: 'json',
        success: function(msg){
            if (msg!="") {              
              proDataDet = [
                  {
                      value: msg.pro_rkpd,
                      color: "#3D77FF"
                  },
                  {
                      value: msg.pro_apbd,
                      color: "#FFB300"
                  },
                  {
                      value: msg.pro_rkpd_apbd,
                      color: "#4D5360"
                  }

              ];

              kegDataDet = [
                  {
                      value: msg.keg_rkpd,
                      color: "#3D77FF"
                  },
                  {
                      value: msg.keg_apbd,
                      color: "#FFB300"
                  },
                  {
                      value: msg.keg_rkpd_apbd,
                      color: "#4D5360"
                  }

              ];
              try{
                myPerbandingan.destroy();
                myPerbandingan1.destroy();
              }catch(err) {
              }
              $("#perbandinganTitle").html(msg.title);
              myPerbandingan = new Chart(document.getElementById("perbandingan-chart").getContext("2d")).Doughnut(proDataDet);
              myPerbandingan1 = new Chart(document.getElementById("perbandingan-chart2").getContext("2d")).Doughnut(kegDataDet);
            };                
        }
    });
  }
</script>