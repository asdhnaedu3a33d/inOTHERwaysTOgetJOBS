<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="main">
  <div class="main-inner">
    <div class="container">
      <div class="row">
        <div class="span6">
          <div class="widget">
            <div class="widget-header"> <i class="icon-bookmark"></i>
              <h3>Report List</h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content">
              <?php echo $shortcut; ?>
              <!-- /shortcuts --> 
            </div>
            <!-- /widget-content --> 
          </div>          
        </div>
        <div class="span6">
          <div class="info-box">
            <div class="row-fluid stats-box">
              <div class="span12">
                <div class="stats-box-title"><i class="icon-bar-chart" style="color:#3C3"></i> Program Kegiatan per Bidang Koordinasi</div>                
                <div class="wrap-chart" style="text-align: center;">
                  <div id="user-stat" class="chart" style="padding: 0px; position: relative;"><canvas id="bar-chart-renstra" class="chart-holder span8" height="150" width="800"></canvas></div>
                </div>
              </div>
            </div>
          </div>
        </div>        
      </div>
    <?php
      if (!empty($konten) && !empty($title)) {    
    ?>    
      <div class="row">
        <div class="span12">
          <div class="widget">
            <div class="widget-header">
              <i class="icon-list-alt"></i>
              <h3><?php echo $title; ?></h3>
            </div>
            <div class="widget-content">
              <?php echo $konten; ?>
            </div>
          </div>
        </div>
      </div>
    <?php
      }
    ?>
    </div>
  </div>
</div>
<script>
    var barChartData = {
        labels: <?php echo $skpd; ?>,
        datasets: [        
          {
              fillColor: "rgba(151,187,205,0.5)",
              strokeColor: "rgba(151,187,205,1)",
              data: <?php echo $jumlah; ?>
          }
        ]
    }    
    var myLine = new Chart(document.getElementById("bar-chart-renstra").getContext("2d")).Bar(barChartData);    
</script>