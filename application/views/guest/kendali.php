<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="form-actions form-horizontal">
  <?php 
    echo $dd_tw;
  ?>
  <select id="bidang_urusan" class="span4 m-wrap">
  <?php
    foreach ($dd_bidang_urusan as $row) {
  ?>
    <option kdU="<?php echo $row->id_urusan; ?>" value="<?php echo $row->id; ?>">Bidang Urusan <?php echo $row->nama; ?></option>
  <?php
    }
  ?>
  </select>
    <button id="btn-search-kendali" class="btn" type="button">Search!</button>
</div>
<div id="kendaliDet">
  <h6 id="title_kendaliDet" class="kendaliDet bigstats" style="padding:0;margin:0 15px 0 15px;text-align:center;"></h6>
  <div class="kendaliDet shortcuts" style="margin-top: 10px;">
    <a style="background: #D46A6A; border-radius: 50%; width: auto; height:auto; padding:0px;" class="shortcut"><div title="Target dibawah 60%" style="display: table-cell; text-align: center; vertical-align: middle;width:100px;height:100px;"><span id="kendaliDet1" style="font-size: 28px;" class="shortcut-label"></span></div></a>
    <a style="background: #D4CF6A; border-radius: 50%; width: auto; height:auto; padding:0px;" class="shortcut"><div title="Target antara 60% hingga 79%" style="display: table-cell; text-align: center; vertical-align: middle;width:100px;height:100px;"><span id="kendaliDet2" style="font-size: 28px;" class="shortcut-label"></span></div></a>
    <a style="background: #82B582; border-radius: 50%; width: auto; height:auto; padding:0px;" class="shortcut"><div title="Target antara 90% hingga 99%" style="display: table-cell; text-align: center; vertical-align: middle;width:100px;height:100px;"><span id="kendaliDet3" style="font-size: 28px;" class="shortcut-label"></span></div></a>
    <a style="background: #A597FF; border-radius: 50%; width: auto; height:auto; padding:0px;" class="shortcut"><div title="Target mencapai 100%%" style="display: table-cell; text-align: center; vertical-align: middle;width:100px;height:100px;"><span id="kendaliDet4" style="font-size: 28px;" class="shortcut-label"></span></div></a>
  </div>
  <div style="padding-left: 10px;">
    Target :
    <i class="icon-sign-blank" style="color: #D46A6A;"><span style="padding-left:5px;" class="value">< 60%</span></i>
    <i class="icon-sign-blank" style="color: #D4CF6A;"><span style="padding-left:5px;" class="value">60% - 79%</span></i>
    <i class="icon-sign-blank" style="color: #82B582;"><span style="padding-left:5px;" class="value">90% - 99%</span></i>
    <i class="icon-sign-blank" style="color: #A597FF;"><span style="padding-left:5px;" class="value">Mencapai 100%</span></i>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    $("#btn-search-kendali").click(function(){
      reload_kendali();
    });
    reload_kendali();
  });

  function reload_kendali(){
    $.ajax({
        type: "POST",
        url: '<?php echo site_url("guest/home/circle_kendali"); ?>',
        data: {triwulan: $("#triwulan").val(), kd_bidang: $("#bidang_urusan").val(), kd_urusan: $("#bidang_urusan option:selected").attr("kdU")},
        dataType: 'json',
        success: function(msg){
            if (msg!="") {
              $("div#kendaliDet .kendaliDet").fadeOut(function(){
                $("div#kendaliDet #title_kendaliDet").html(msg.title);
                $("div#kendaliDet #kendaliDet1").html(msg.kendali1);
                $("div#kendaliDet #kendaliDet2").html(msg.kendali2);
                $("div#kendaliDet #kendaliDet3").html(msg.kendali3);
                $("div#kendaliDet #kendaliDet4").html(msg.kendali4);
              });
              $("div#kendaliDet .kendaliDet").fadeIn();
            };                
        }
    });
  }
</script>