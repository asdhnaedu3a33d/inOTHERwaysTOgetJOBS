<?php
	$ta=$this->session->userdata('t_anggaran_aktif');
	$tahun_sekarang=$this->session->userdata('t_anggaran_aktif'); 
	$nama_skpd = $this->session->userdata('nama_skpd');
?>
<script>
	$(document).ready(function(){
		$(".edit-test").click(function(){
			//var idr = $(this).parent().parent().attr("id-r");
			
			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("evaluasi_renja/cru_evaluasi_renja"); ?>',
				success: function(msg){
					if (msg!="") {						
						$.facebox(msg);
						$.blockUI({							
							timeout: 2000,
							css: window._css,
							overlayCSS: window._ovcss
						});
					};	
				}
			});			
		});
	});
</script>

<article class="module width_full" style="width: 130%; margin-left: -15%;">
	<header>
	  <h3>Evaluasi Renja BKD Kabupaten Klungkung</h3>
	</header>
    <div style='float:right'>
    	<a href="<?php echo site_url('evaluasi_renja/preview_evaluasi_renja') ?>"><input style="margin: 3px 10px 0px 0px; float: right;" type="button" value="Preivew" /></a>
    </div><br>
    <div class="module_content" style="overflow:auto">
    	<table id="kendali_skpd" class="table-common tablesorter" style="width:100%" >
			<thead>
				<tr>
					<th colspan="4">KODE</th>
					<th >PROGRAM DAN KEGIATAN</th>
					<th colspan="2">INDIKATOR KINERJA PROGRAM / KEGIATAN</th>
					<th >TW 1</th>
                    <th >TW 2</th>
                    <th >TW 3</th>
                    <th >TW 4</th>
				</tr>
			</thead>
            <tbody>
            	<tr>
                	<td>1</td>
                    <td>2</td>
                    <td>3</td>
                    <td>4</td>
                    <td>5</td>
                    <td>6</td>
                    <td>7</td>
                    <td>8</td>
                    <td>9</td>
                    <td>10</td>
                    <td>
                    	<a href="javascript:void(0)" class="icon-pencil edit-test" title="Test"/>
                    </td>
                </tr>
            </tbody>
		</table>
    </div>
</article>