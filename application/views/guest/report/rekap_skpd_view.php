<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div style="overflow: auto; height: 500px; margin-top: 10px; margin-right: 25px;">
  <table class="table-common">  
    <?php
    	if(!empty($rekapitulasi1))
    	{
    		echo $rekapitulasi1;
    	}
    	if(!empty($rekapitulasi2))
    	{
    		echo $rekapitulasi2;
    	}
    	if(!empty($rekapitulasi3))
    	{
    		echo $rekapitulasi3;
    	}
    ?>
  </table>
</div>