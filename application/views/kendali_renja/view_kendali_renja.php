<script>prepare_facebox();</script>
<?php
    if (!empty($jendela_kontrol->baru) || !empty($jendela_kontrol->revisi) || (empty($jendela_kontrol->baru)
	    && empty($jendela_kontrol->revisi) && empty($jendela_kontrol->kirim))) {
		$enable_kirim = TRUE;
		$enable_edit = TRUE;
		$enable_delete = TRUE;
	}else{
		$enable_kirim = FALSE;
		$enable_edit = FALSE;
		$enable_delete = FALSE;
	}

	$tahun_sekarang=$this->session->userdata('t_anggaran_aktif');
	$nama_skpd = $this->session->userdata('nama_skpd');
	$max_col_keg=1;
?>
<script>
$(document).on("click", "#kirim_renja", function(){
	$.facebox({div: '<?php echo site_url("kendali/kirim_kendali_renja"); ?>'});
});

function lihat_file(id){
    $.ajax({
      type: "POST",
      url: "<?php echo site_url('kendali/preview_history_renja')?>",
      data: {id: id},
      success: function(msg){
        $.facebox(msg);
      }
    });
  }

$(document).ready(function(){

	$(".edit-kegiatan").click(function(){
		var idr = $(this).parent().parent().attr("id-r");

		prepare_facebox();
		$.blockUI({
			css: window._css,
			overlayCSS: window._ovcss
		});
		$.ajax({
			type: "POST",
			url: '<?php echo site_url("kendali/cru_kendali_renja"); ?>',
			data: { id: $(this).attr("idP")},
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
	  <h3>Tabel pengendalian pelaksanaan renja skpd</h3>
	</header>
	<div class="module_content" style="overflow:auto">
    <div style='float:right'>
      <?php
  		if (!empty($jendela_kontrol->baru) || !empty($jendela_kontrol->revisi)){
  	?>
      	<input id="kirim_renja" style="margin: 3px 10px 0px 0px; float: right;" type="button" value="Kirim Kendali Renja" /></a>
      <?php
  		}
  	?>
  		<a href="<?php echo site_url('kendali/do_cetak_kendali_renja') ?>"><input style="margin: 3px 10px 0px 0px; float: right;" type="button" value="Cetak Kendali Renja" /></a>
      	<a href="<?php echo site_url('kendali/preview_kendali_renja') ?>"><input style="margin: 3px 10px 0px 0px; float: right;" type="button" value="Lihat Kendali Renja" /></a>
  	</div>
    <table class="fcari">
      <tr>
        <td class="col-sm-3">PROVINSI/KABUPATEN</td>
        <td width="10">:</td>
        <td>BALI/KLUNGKUNG</td>
      </tr>
      <tr>
        <td>SKPD</td>
        <td>:</td>
        <td><?php echo $nama_skpd;?></td>
      </tr>
      <tr>
        <td>PERIODE RENJA SKPD</td>
        <td>:</td>
        <td><?php echo $tahun_sekarang;?></td>
      </tr>
      <tr>
        <td>PERIODE RKA SKPD</td>
        <td>:</td>
        <td><?php echo $tahun_sekarang;?></td>
      </tr>
    </table>
    <div class="scroll">
  		<table id="kendali_skpd" class="table-common tablesorter" style="width:100%" >
  			<thead>
  				<tr>
  					<th rowspan="3" colspan="4">KODE</th>
  					<th rowspan="3" >PROGRAM DAN KEGIATAN</th>
  					<th rowspan="2" colspan="2">INDIKATOR KINERJA PROGRAM / KEGIATAN</th>
  					<th colspan="6">RENCANA TAHUN (<?php echo $tahun_sekarang;?>)</th>
  					<th colspan="4">PRAKIRAAN RENCANA TAHUN (<?php echo $tahun_sekarang+1;?>)</th>
  					<th rowspan="3">KESESUAIAN</th>
  					<th rowspan="3">HASIL PENGENDALIAN</th>
  					<th rowspan="3">TINDAK LANJUT</th>
  					<th rowspan="3">HASIL TINDAK LANJUT</th>
                      <th rowspan="3">AKSI</th>
                      <th rowspan="3">STATUS</th>
  				</tr>
  				<tr>
  					<th colspan = "2">LOKASI</th>
  					<th colspan = "2">TARGET CAPAIAN</th>
  					<th colspan = "2">DANA</th>
  					<th colspan = "2">TARGET CAPAIAN KINERJA</th>
  					<th colspan = "2">DANA</th>
  				</tr>
  				<tr>
  					<th> RENJA </th>
  					<th> RKA   </th>
  					<th> RENJA </th>
  					<th> RKA   </th>
  					<th> RENJA </th>
  					<th> RKA   </th>
  					<th> RENJA </th>
  					<th> RKA   </th>
  					<th> RENJA </th>
  					<th> RKA   </th>
  					<th> RENJA </th>
  					<th> RKA   </th>
  				</tr>
  			</thead>
  			<tbody>
  				<?php
  					foreach ($program as $row) {
  						$result = $this->m_kendali->get_kegiatan_rka_4_cetak($row->id,$tahun_sekarang);
  						$kegiatan = $result->result();
  						//echo $this->db->last_query();
  						//$indikator_program = $this->m_kendali->get_indikator_prog_keg($row->id, FALSE, TRUE);
  						$get_id_renja = $this->m_kendali->get_id_renja($row->id_skpd, $row->tahun, $row->kd_urusan,
  						                     $row->kd_bidang, $row->kd_program);
  						//echo "renja".$this->db->last_query()."<BR>";
  						$get_id_rka = $this->m_kendali->get_id_rka($row->id_skpd, $row->tahun, $row->kd_urusan,
  						                     $row->kd_bidang, $row->kd_program);
  					    //echo "rka".$this->db->last_query()."<BR>";
  						$indikator_program_renja = $this->m_kendali->get_indikator_renja($get_id_renja);
  						$indikator_program_rka = $this->m_kendali->get_indikator_rka($get_id_rka);
  						//echo "data ".$this->db->last_query()."<BR>";
  						$temp = $indikator_program_rka->result();
  						$temp1 = $indikator_program_renja->result();
  						$total_temp = $indikator_program_rka->num_rows();
  						$total_temp1 = $indikator_program_renja->num_rows();

  						$col_indikator=1;
  						$go_2_keg = FALSE;
  						$total_for_iteration = $total_temp;
  						if($total_temp > $max_col_keg){
  							$total_temp = $max_col_keg;
  							$go_2_keg = TRUE;
  						}
  				?>
  					<tr>
  						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_urusan; ?></td>
  						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_bidang; ?></td>
  						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_program; ?></td>
  						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_kegiatan; ?></td>
  						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->nama_prog_or_keg; ?></td>
  						<td>
  							<?php
  								echo $temp1[0]->indikator;
  							?>
  						</td>
  						<td>
  							<?php
  								echo $temp[0]->indikator;
  							?>
  						</td>
  						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">-</td>
  						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">-</td>
  						<td align="center">
  							<?php echo $temp1[0]->target." ".$temp1[0]->satuan_target;?>
  						</td>
  						<td align="center">
  							<?php echo $temp[0]->target." ".$temp[0]->satuan_target;?>
  						</td>
  						<td style="border-bottom: 0;" align="right" rowspan="<?php echo $total_temp; ?>">Rp. <?php echo Formatting::currency($row->sum_nomrenja);?></td>
  						<td style="border-bottom: 0;" align="right" rowspan="<?php echo $total_temp; ?>">Rp. <?php echo Formatting::currency($row->sum_nominal);?></td>
  						<td align="center">
  							<?php echo $temp1[0]->target_thndpn." ".$temp1[0]->satuan_target;?>
  						</td>
  						<td align="center">
  							<?php echo $temp[0]->target_thndpn." ".$temp[0]->satuan_target;?>
  						</td>
  						<td style="border-bottom: 0;" align="right" rowspan="<?php echo $total_temp; ?>">Rp. <?php echo Formatting::currency($row->sum_nomrenja_thndpn);?></td>
  						<td style="border-bottom: 0;" align="right" rowspan="<?php echo $total_temp; ?>">Rp. <?php echo Formatting::currency($row->sum_nominal_thndpn);?></td>
  						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="center">
  						<?php if($row->kesesuaian == "1") { ?>
                          	<img src='<?php echo base_url();?>asset/images/right-check.png'>
                          <?php } else if ($row->kesesuaian == "2"){ ?>
                          	<img src='<?php echo base_url();?>asset/images/wrong-check.png'>
  						<?php } ?>
  						</td>
  						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->hasil_kendali;?></td>
  						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->tindak_lanjut;?></td>
  						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->hasil_tl;?></td>
                          <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="center">
                          <?php if($row->id_status != "2") { ?>
                          <a href="javascript:void(0)" idP="<?php echo $row->id;?>" class="icon-pencil edit-kegiatan" title="Kendali Kegiatan"/>
                          <?php } ?>
                          </td>
                          <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
                          <a href="#" onclick="lihat_file('<?php echo $row->id; ?>')" title="lihat history">
                          <?php if($row->id_status == "1") { ?>
                          	<font color="#000000">baru</font>
                          <?php } else if($row->id_status == "2") { ?>
                          	<font color="#0000FF">terkirim</font>
                          <?php } else if($row->id_status == "3") { ?>
                          	<font color="#FF0000">revisi/ tidak disetujui</font>
                          <?php } else if($row->id_status == "4") { ?>
                          	<font color="#00FF00">disetujui</font>
                          <?php } ?>
                          </a>
                          </td>
  					</tr>
  					<?php
  						if ($total_for_iteration > 1) {
  						for ($i=1; $i < $total_for_iteration; $i++) {
  							$col_indikator++;
  					?>
  						<tr>
  							<?php
  								if ($go_2_keg && $col_indikator > $max_col_keg) {
  							?>
  								<td style="border-top: 0;border-bottom: 0;" ></td>
  								<td style="border-top: 0;border-bottom: 0;" ></td>
  								<td style="border-top: 0;border-bottom: 0;" ></td>
  								<td style="border-top: 0;border-bottom: 0;" ></td>
  								<td style="border-top: 0;border-bottom: 0;" ></td>
  							<?php
  								}
  							?>
  							<td>
                              	<?php
  									echo $temp1[$i]->indikator;
  								?>
                </td>
  							<td>
  								<?php
  									echo $temp[$i]->indikator;
  								?>
  							</td>
  							<?php
  								if ($go_2_keg && $col_indikator > $max_col_keg) {
  							?>
  								<td style="border-top: 0;border-bottom: 0;" ></td>
                  <td style="border-top: 0;border-bottom: 0;" ></td>
  							<?php
  								}
  							?>
  							<td align="center">
  								<?php
  									echo $temp[$i]->target." ".$temp[$i]->satuan_target;
  								?>
  							</td>
  							<td align="center">
  								<?php
  									echo $temp[$i]->target_thndpn." ".$temp[$i]->satuan_target_thndpn;
  								?>
  							</td>
  								<?php
  									if ($go_2_keg && $col_indikator > $max_col_keg) {
  								?>
                  <td style="border-top: 0;border-bottom: 0;" ></td>
                  <td style="border-top: 0;border-bottom: 0;" ></td>
                              <td style="border-top: 0;border-bottom: 0;" ></td>
                              <td style="border-top: 0;border-bottom: 0;" ></td>
                              <td style="border-top: 0;border-bottom: 0;" ></td>
                              <td style="border-top: 0;border-bottom: 0;" ></td>
                              <td style="border-top: 0;border-bottom: 0;" ></td>
                              <td style="border-top: 0;border-bottom: 0;" ></td>
                              <td style="border-top: 0;border-bottom: 0;" ></td>
                              <td style="border-top: 0;border-bottom: 0;" ></td>
                              <td style="border-top: 0;border-bottom: 0;" ></td>
                              <td style="border-top: 0;border-bottom: 0;" ></td>
  						<?php
  											}
  						?>
  						</tr>
  					<?php
  							}
  						}

  						foreach ($kegiatan as $row) {
  						//$indikator_kegiatan = $this->m_kendali->get_indikator_prog_keg($row->id, FALSE, TRUE);
  						//$indikator_kegiatan = $this->m_kendali->get_indikator_kegiatan($row->id_skpd, $row->tahun, $row->kd_urusan,
  											  //$row->kd_bidang, $row->kd_program, $row->kd_kegiatan);
  						$get_id_renja1 = $this->m_kendali->get_id_renja1($row->id_skpd, $row->tahun, $row->kd_urusan,
  										 $row->kd_bidang, $row->kd_program , $row->kd_kegiatan );
  						//echo "renja".$this->db->last_query()."<BR>";
  						$get_id_rka1 = $this->m_kendali->get_id_rka1($row->id_skpd, $row->tahun, $row->kd_urusan,
  										 $row->kd_bidang, $row->kd_program, $row->kd_kegiatan);
  						//echo "rka".$this->db->last_query()."<BR>";
  						$indikator_kegiatan_renja = $this->m_kendali->get_indikator_renja($get_id_renja1);
  						$indikator_kegiatan_rka = $this->m_kendali->get_indikator_rka($get_id_rka1);

  						$temp = $indikator_kegiatan_rka->result();
  						$temp1 = $indikator_kegiatan_renja->result();
  						$total_temp = $indikator_kegiatan_rka->num_rows();
  						$total_temp1 = $indikator_kegiatan_renja->num_rows();
  						//echo $this->db->last_query()."<BR>";

  						$go_2_keg = FALSE;
  						$col_indikator_keg=1;
  						$total_for_iteration = $total_temp;
  						if ($total_temp > $max_col_keg) {
  							$total_temp = $max_col_keg;
  							$go_2_keg = TRUE;
  						}
  					?>
  					<tr>
  						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_urusan; ?></td>
  						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_bidang; ?></td>
  						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_program; ?></td>
  						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_kegiatan; ?></td>
  						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->nama_prog_or_keg; ?></td>
  						<td>
  							<?php
  								echo $temp1[0]->indikator;
  							?>
  						</td>
  						<td>
  							<?php
  								echo $temp[0]->indikator;
  							?>
  						</td>
  						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->lokasirenja;?></td>
  						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->lokasi;?></td>
  						<td align="center">
  							<?php
  								echo $temp1[0]->target." ".$temp1[0]->satuan_target;
  							?>
  						</td>
  						<td align="center">
  							<?php
  								echo $temp[0]->target." ".$temp[0]->satuan_target;
  							?>
  						</td>
  						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right" >Rp. <?php echo Formatting::currency($row->nomrenja);?></td>
  						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right" >Rp. <?php echo Formatting::currency($row->nominal);?></td>
  						<td align="center">
  							<?php
  								echo $temp1[0]->target_thndpn." ".$temp1[0]->satuan_target_thndpn;
  							?>
  						</td>
  						<td align="center">
  							<?php
  								echo $temp[0]->target_thndpn." ".$temp[0]->satuan_target_thndpn;
  							?>
  						</td>
  						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right" >Rp. <?php echo Formatting::currency($row->nomrenja_thndpn);?></td>
  						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right" >Rp. <?php echo Formatting::currency($row->nominal_thndpn);?></td>
  						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="center">
  						<?php if($row->kesesuaian == "1") { ?>
                          	<img src='<?php echo base_url();?>asset/images/right-check.png'>
                          <?php } else if ($row->kesesuaian == "2"){ ?>
                          	<img src='<?php echo base_url();?>asset/images/wrong-check.png'>
  						<?php } ?>
  						</td>
  						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->hasil_kendali;?></td>
  						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->tindak_lanjut;?></td>
  						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->hasil_tl;?></td>
                          <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="center">
                          <?php if($row->id_status != "2") { ?>
                          <a href="javascript:void(0)" idP="<?php echo $row->id;?>" class="icon-pencil edit-kegiatan" title="Kendali Kegiatan"/>
                          <?php } ?>
                          </td>
                          <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
                          <a href="#" onclick="lihat_file('<?php echo $row->id; ?>')" title="lihat history">
                          <?php if($row->id_status == "1") { ?>
                          	<font color="#000000">baru</font>
                          <?php } else if($row->id_status == "2") { ?>
                          	<font color="#0000FF">terkirim</font>
                          <?php } else if($row->id_status == "3") { ?>
                          	<font color="#FF0000">revisi/ tidak disetujui</font>
                          <?php } else if($row->id_status == "4") { ?>
                          	<font color="#00FF00">disetujui</font>
                          <?php } ?>
                          </a>
                          </td>
  					</tr>
  					<?php
  					if ($total_for_iteration > 1) {
  						for ($i=1; $i < $total_for_iteration; $i++) {
  							$col_indikator_keg++;
  					?>
  						<tr>
  					<?php
  						if ($go_2_keg && $col_indikator_keg > $max_col_keg) {
  					?>
  						<td style="border-top: 0;border-bottom: 0;" ></td>
  						<td style="border-top: 0;border-bottom: 0;" ></td>
  						<td style="border-top: 0;border-bottom: 0;" ></td>
  						<td style="border-top: 0;border-bottom: 0;" ></td>
  						<td style="border-top: 0;border-bottom: 0;" ></td>
  					<?php
  						}
  					?>
  					<td>
                      	<?php
  							echo $temp1[$i]->indikator;
  						?>
                      </td>
  					<td>
  						<?php
  							echo $temp[$i]->indikator;
  						?>
  					</td>
  					<?php
  						if ($go_2_keg && $col_indikator_keg > $max_col_keg) {
  					?>
  							<td style="border-top: 0;border-bottom: 0;" ></td>
  							<td style="border-top: 0;border-bottom: 0;" ></td>
  					<?php
  						}
  					?>
  					<td align="center">
                      	<?php
  							echo $temp1[$i]->target." ".$temp1[$i]->satuan_target;
  						?>
                      </td>
  					<td align="center">
  						<?php
  							echo $temp[$i]->target." ".$temp[$i]->satuan_target;
  						?>
  					</td>
  					<?php
  						if ($go_2_keg && $col_indikator_keg > $max_col_keg) {
  					?>
  							<td style="border-top: 0;border-bottom: 0;" ></td>
  							<td style="border-top: 0;border-bottom: 0;" ></td>
  					<?php
  						}
  					?>
  					<td align="center">
                      	<?php
  							echo $temp1[$i]->target_thndpn." ".$temp1[$i]->satuan_target_thndpn;
  						?>
                      </td>
  					<td align="center">
  						<?php
  							echo $temp[$i]->target_thndpn." ".$temp[$i]->satuan_target_thndpn;
  						?>
  					</td>
  					<?php
  						if ($go_2_keg && $col_indikator_keg > $max_col_keg) {
  					?>
  							<td style="border-top: 0;border-bottom: 0;" ></td>
  							<td style="border-top: 0;border-bottom: 0;" ></td>
  							<td style="border-top: 0;border-bottom: 0;" ></td>
  							<td style="border-top: 0;border-bottom: 0;" ></td>
  							<td style="border-top: 0;border-bottom: 0;" ></td>
  							<td style="border-top: 0;border-bottom: 0;" ></td>
                              <td style="border-top: 0;border-bottom: 0;" ></td>
                              <td style="border-top: 0;border-bottom: 0;" ></td>
  					<?php
  						}
  					?>
  					</tr>
  			<?php
  					}
  				}
  			}
  		}
  			?>
  			</tbody>
  		</table>
    </div>
	</div>
</article>
