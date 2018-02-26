<style media="screen">
	tr.tr-click:hover{
		background-color: pink;
	}
	td.td-click{
		cursor: pointer;
	}
</style>
<script type="text/javascript">
var element;
var tab_element;
$(document).ready(function(){
	$("td.td-click").click(function(){
		if($(this).parent().next().is(":visible")){
			$(this).parent().next().fadeOut();
			return false;
		};

		$("tr.tr-frame").hide();
		$.blockUI({
			css: window._css,
			overlayCSS: window._ovcss
		});

		element = $(this).parent();
		var idk = element.attr("id-k");
    var is_prog_or_keg = element.attr("is_prog_or_keg");
		var this_element = $(this);
		$.ajax({
			type: "POST",
			url: '<?php echo site_url("kendali_perubahan/get_kendali_langsung_bulanan"); ?>',
			data: { idk: idk, is_prog_or_keg : is_prog_or_keg},
			success: function(msg){
				if (msg!="") {
					element.next().children().html(msg);
					element.next().fadeIn();
					element = this_element;
					tab_element = "";
					$.blockUI({
						timeout: 1000,
						css: window._css,
						overlayCSS: window._ovcss
					});
				};
			}
		});
	});


});

$(document).on("click", "#cetak_aksi", function(){
  // prepare_facebox();
  // var link = '<?php //echo site_url("dpa/cetak_form_aksi"); ?>/1';
  // $.facebox({div: link});

  var id = '';
  var aksi = 'capaian_prog';
  var id_skpd = '<?php echo $this->session->userdata("id_skpd"); ?>';
  var t_anggaran = '<?php echo $this->session->userdata("t_anggaran_aktif"); ?>';
  var link = '<?php echo site_url('dpa_perubahan/cetak_aksi'); ?>/'+id_skpd+'/'+t_anggaran+'/'+aksi+'/'+id;
  
  // alert(link);
  // $.blockUI({
  //  css: window._css,
  //  overlayCSS: window._ovcss
  // });

  window.open(link, '_blank');

});

</script>



<?php
	$tahun_sekarang=$this->session->userdata('t_anggaran_aktif');
	$nama_skpd = $this->session->userdata('nama_skpd');
	$max_col_keg=1;
?>
<article class="module width_full" style="width: 130%; margin-left: -15%;">
	<header>
		<h3>Tabel Kendali Pelaksanaan Kegiatan Belanja Langsung Perubahan</h3>
	</header>
    <div class="module_content" style="overflow:auto">
	<div class="form-group text-right">
		<a href="<?php echo site_url('kendali_perubahan/kirim_veri') ?>"><input style="margin: 3px 10px 0px 0px;" type="button" value="Kirim Kendali Belanja" /></a>
    <a href="javascript:void(0)" id="cetak_aksi"><input style="margin: 3px 10px 0px 0px;" type="button" value="Cetak Realisasi Aksi" /></a>
		<a href="<?php echo site_url('kendali_perubahan/do_cetak_kendali_belanja') ?>"><input style="margin: 3px 10px 0px 0px;" type="button" value="Cetak Kendali Belanja" /></a>
    	<a href="<?php echo site_url('kendali_perubahan/preview_kendali_belanja') ?>"><input style="margin: 3px 10px 0px 0px;" type="button" value="Lihat Kendali Belanja" /></a>
	</div>
  <div class="scroll">
  	<table id="kendali_skpd" class="table-common tablesorter" style="width:99.7%" >
  		<thead>
  				<tr>
  					<th colspan="4">Kode</th>
  					<th >Program dan Kegiatan</th>
  					<th >Kriteria Keberhasilan</th>
  					<th >Ukuran Keberhasilan</th>
  				</tr>
  			</thead>
  			<tbody>
  				<?php
  					foreach ($program as $row) {
  						$result = $this->m_kendali_perubahan->get_kegiatan_dpa_4_cetak($row->id,$tahun_sekarang);
  						$kegiatan = $result->result();
  						$indikator_program = $this->m_kendali_perubahan->get_indikator_prog_keg_dpa($row->id, FALSE, TRUE);
  						$temp = $indikator_program->result();
  						$total_temp = $indikator_program->num_rows();

  						$col_indikator=1;
  						$go_2_keg = FALSE;
  						$total_for_iteration = $total_temp;
  						if($total_temp > $max_col_keg){
  							$total_temp = $max_col_keg;
  							$go_2_keg = TRUE;
  						}

  				?>
  				<tr bgcolor="#ddd" class="tr-click" id-k="<?php echo $row->id; ?>" is_prog_or_keg="1">
  					<td class="td-click"  style="border-bottom: 0;"><?php echo $row->kd_urusan; ?></td>
  					<td class="td-click" style="border-bottom: 0;"><?php echo $row->kd_bidang; ?></td>
  					<td class="td-click" style="border-bottom: 0;"><?php echo $row->kd_program; ?></td>
  					<td class="td-click" style="border-bottom: 0;"><?php echo $row->kd_kegiatan; ?></td>
  					<td class="td-click" style="border-bottom: 0;"><?php echo $row->nama_prog_or_keg; ?></td>
  					<td class="td-click">
              <?php
                  for ($i=0; $i < $total_for_iteration; $i++){
                    echo $temp[$i]->indikator;
                    echo '</br></br>';
                  }
              ?>
  					</td>
  					<td class="td-click">
              <?php
                  for ($i=0; $i < $total_for_iteration; $i++){
                    echo $temp[$i]->indikator." ".$temp[$i]->target." ".$temp[$i]->satuan_target;
                    echo '</br></br>';
                  }
                ?>
  					</td>
  				</tr>
          <tr class="tr-frame" style="display: none">
            <td colspan="7"></td>
          </tr>
  				<?php
  					foreach ($kegiatan as $row) {

  							$indikator_kegiatan = $this->m_kendali_perubahan->get_indikator_prog_keg_dpa($row->id, FALSE, TRUE);
  							$temp = $indikator_kegiatan->result();
  							$total_temp = $indikator_kegiatan->num_rows();

  							$total_for_iteration = $total_temp;



  					?>
  					<tr class="tr-click" id-k="<?php echo $row->id; ?>" is_prog_or_keg="2">
  						<td class="td-click" style="border-bottom: 0;"><?php echo $row->kd_urusan; ?></td>
  						<td class="td-click" style="border-bottom: 0;"><?php echo $row->kd_bidang; ?></td>
  						<td class="td-click" style="border-bottom: 0;"><?php echo $row->kd_program; ?></td>
  						<td class="td-click" style="border-bottom: 0;"><?php echo $row->kd_kegiatan; ?></td>
  						<td class="td-click" style="border-bottom: 0;"><?php echo $row->nama_prog_or_keg; ?></td>
  						<td class="td-click" style="border-bottom: 0;">
  							<?php

  								for ($i=0; $i < $total_for_iteration; $i++){
  									echo $temp[$i]->indikator;
  									echo '</br></br>';
  								}


  							?>
  						</td>
  						<td class="td-click" style="border-bottom: 0;">
  							<?php
  								for ($i=0; $i < $total_for_iteration; $i++){
  									echo $temp[$i]->indikator." ".$temp[$i]->target." ".$temp[$i]->satuan_target;
  									echo '</br></br>';
  								}
  							?>
  						</td>

  					</tr>
						<tr class="tr-frame" style="display: none">
							<td colspan="7"></td>
						</tr>


  					<?php

  					}
  				}
  				?>
  			</tbody>
  		</table>
    </div>
	</div>
</article>
