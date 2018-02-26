<?php
	$tahun_sekarang=$this->session->userdata('t_anggaran_aktif');
	$max_col_keg=1;
?>
<style type="text/css">	
</style>
<script type="text/javascript">
function lihat_file(id){
    $.ajax({
      type: "POST",
      url: "<?php echo site_url('kendali_perubahan/preview_history_renja')?>",
      data: {id: id},
      success: function(msg){
        $.facebox(msg);
      }
    });    
  }
  
	$(document).ready(function(){		
		$(".veri").click(function(){
			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
	    	
	    	$.ajax({
				type: "POST",
				url: "<?php echo site_url('kendali_perubahan/do_veri_renja'); ?>",
				data: {id: $(this).attr("idP")},				
				success: function(msg){					
					$.blockUI({
						message: msg.msg,
						timeout: 2000,
						css: window._css,
						overlayCSS: window._ovcss
					});
					$.facebox(msg);
				}
			});
		});

		$("#kembali_all").click(function(){
			$(location).attr('href', '<?php echo site_url("kendali_perubahan/veri_view_renja"); ?>')
		});

		$("#disapprove_renja").click(function(){
			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
	    	
	    	$.ajax({
				type: "POST",
				url: "<?php echo site_url('rka_perubahan/disapprove_renja'); ?>",
				data: {id:$(this).attr("id-r")},				
				success: function(msg){					
					$.blockUI({
						message: msg.msg,
						timeout: 2000,
						css: window._css,
						overlayCSS: window._ovcss
					});
					$.facebox(msg);
				}
			});
		});
	});
</script>
<article class="module width_full" style="width: 138%; margin-left: -19%;">
	<header>
	  <h3>Verifikasi Kendali Renja Perubahan</h3>
	</header>
	<div class="module_content";>
		<div style="overflow:auto">			
			<table class="table-common" width="100%">
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
					foreach ($renjas as $row) {
						$result = $this->m_kendali_perubahan->get_kegiatan_rka_4_cetak($row->id,$tahun_sekarang);
						$kegiatan = $result->result();
						//echo $this->db->last_query();
						//$indikator_program = $this->m_kendali->get_indikator_prog_keg($row->id, FALSE, TRUE);
						$get_id_renja = $this->m_kendali_perubahan->get_id_renja($row->id_skpd, $row->tahun, $row->kd_urusan,
						                     $row->kd_bidang, $row->kd_program);
						//echo "renja".$this->db->last_query()."<BR>";
						$get_id_rka = $this->m_kendali_perubahan->get_id_rka($row->id_skpd, $row->tahun, $row->kd_urusan,
						                     $row->kd_bidang, $row->kd_program);
					    //echo "rka".$this->db->last_query()."<BR>";
						$indikator_program_renja = $this->m_kendali_perubahan->get_indikator_renja($get_id_renja);
						$indikator_program_rka = $this->m_kendali_perubahan->get_indikator_rka($get_id_rka);
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
						<td style="border-bottom: 0;" align="right" rowspan="<?php echo $total_temp; ?>"><?php echo Formatting::currency($row->sum_nomrenja);?></td>
						<td style="border-bottom: 0;" align="right" rowspan="<?php echo $total_temp; ?>"><?php echo Formatting::currency($row->sum_nominal);?></td>
						<td align="center">
							<?php echo $temp1[0]->target_thndpn." ".$temp1[0]->satuan_target;?>
						</td>
						<td align="center">
							<?php echo $temp[0]->target_thndpn." ".$temp[0]->satuan_target;?>
						</td>
						<td style="border-bottom: 0;" align="right" rowspan="<?php echo $total_temp; ?>"><?php echo Formatting::currency($row->sum_nomrenja_thndpn);?></td>
						<td style="border-bottom: 0;" align="right" rowspan="<?php echo $total_temp; ?>"><?php echo Formatting::currency($row->sum_nominal_thndpn);?></td>
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
                        <?php if($row->id_status == "2") { ?>
                        <a href="javascript:void(0)" idP="<?php echo $row->id;?>" class="icon-pencil veri" title="Verifikasi Program"/>
                        <?php }?>
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
							<?php
								}
							?>
							<td align="center">
								<?php
									echo $temp[$i]->target." ".$temp[$i]->satuan_target;
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
						$get_id_renja1 = $this->m_kendali_perubahan->get_id_renja1($row->id_skpd, $row->tahun, $row->kd_urusan,
										 $row->kd_bidang, $row->kd_program , $row->kd_kegiatan );
						//echo "renja".$this->db->last_query()."<BR>";
						$get_id_rka1 = $this->m_kendali_perubahan->get_id_rka1($row->id_skpd, $row->tahun, $row->kd_urusan,
										 $row->kd_bidang, $row->kd_program, $row->kd_kegiatan);
						//echo "rka".$this->db->last_query()."<BR>";
						$indikator_kegiatan_renja = $this->m_kendali_perubahan->get_indikator_renja($get_id_renja1);
						$indikator_kegiatan_rka = $this->m_kendali_perubahan->get_indikator_rka($get_id_rka1);
											  
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
						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right" ><?php echo Formatting::currency($row->nomrenja);?></td>
						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right" ><?php echo Formatting::currency($row->nominal);?></td>
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
						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right" ><?php echo Formatting::currency($row->nomrenja_thndpn);?></td>
						<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right" ><?php echo Formatting::currency($row->nominal_thndpn);?></td>
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
                        <?php if($row->id_status == "2") { ?>
                        <a href="javascript:void(0)" idP="<?php echo $row->id;?>" class="icon-pencil veri" title="Verifikasi Kegiatan"/>
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
	<footer>
		<div class="submit_link">
			<input id-r="<?php echo $id_skpd; ?>" type="button" id="disapprove_renja" value="Tidak Setujui Kendali Renja">
			<input type="button" id="kembali_all" value="Kembali">
		</div>
	</footer>
</article>