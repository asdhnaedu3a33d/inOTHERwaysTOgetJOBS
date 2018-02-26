<?php
	$tahun_sekarang=$this->session->userdata('t_anggaran_aktif'); 
	$nama_skpd = $this->session->userdata('nama_skpd');
	$max_col_keg=1;
?>
<style type="text/css">	
</style>
<script type="text/javascript">
	$(document).ready(function(){		
		$(".veri_keg").click(function(){
			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
	    	
	    	$.ajax({
				type: "POST",
				url: "<?php echo site_url('kendali_perubahan/do_veri_belanja'); ?>",
				data: {id: $(this).attr("id-r"), action: 'keg'},				
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

		$(".veri_prog").click(function(){
			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
	    	
	    	$.ajax({
				type: "POST",
				url: "<?php echo site_url('kendali_perubahan/do_veri_belanja'); ?>",
				data: {id: $(this).attr("id-r"), action: 'pro'},
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
			$(location).attr('href', '<?php echo site_url("kendali_perubahan/veri_view_belanja"); ?>')
		});

		$("#disapprove_renja").click(function(){
			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
	    	
	    	$.ajax({
				type: "POST",
				url: "<?php echo site_url('kendali_perubahan/disapprove_belanja'); ?>",
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
	  <h3>Verifikasi Kendali Belanja Perubahan</h3>
	</header>
	<div class="module_content";>
		<div style="overflow:auto">			
			<table class="table-common" width="100%">
				<thead>
				<tr>
					<th rowspan="3" colspan="4">Kode</th>
					<th rowspan="3" >Program dan Kegiatan</th>
					<th rowspan="3" >Kriteria Keberhasilan</th>
					<th rowspan="3" >Ukuran Keberhasilan</th>
					<th colspan="6" >Kinerja Triwulan</th>
					<th rowspan="3" >Ket.</th>
                    <th rowspan="3" >Action</th>
				</tr>
				<tr>
					<th rowspan="2">Triwulan</th>
					<th colspan="3">Input</th>
					<th colspan="2">Output</th>
				</tr>
				<tr>
					<th> Pagu </th>
					<th> Realisasi   </th>
					<th> Capaian (%) </th>
					<th> Ukuran Kinerja Triwulan   </th>
					<th> Capaian (%) </th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach ($belanjas as $row) {
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
				<tr>
					<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_urusan; ?></td>
					<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_bidang; ?></td>
					<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_program; ?></td>
					<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_kegiatan; ?></td>
					<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->nama_prog_or_keg; ?></td>
					<td>
						<?php
							echo $temp[0]->indikator;
						?>
					</td>
					<td>
						<?php
							echo $temp[0]->indikator." ".$temp[0]->target." ".$temp[0]->satuan_target;
						?>
					</td>
					<td align="center" style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
                    	TW I <br />
                        TW II <br />
                        TW III <br />
                        TW IV <br />
                    </td>
					<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
                    	<?php echo $row->nominal_1; ?><br />
                        <?php echo $row->nominal_2; ?><br />
                        <?php echo $row->nominal_3; ?><br />
                        <?php echo $row->nominal_4; ?><br />
                    </td>
					<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
                    	0 <br />
                        0 <br />
                        0 <br />
                        0 <br />
                    </td>
					<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
                    	<?php echo (empty($row->realisasi))?0:round($row->realisasi/$row->rencana)*100; ?> <br />
                        <?php echo (empty($row->realisasi))?0:round($row->realisasi/$row->rencana)*100; ?> <br />
                        <?php echo (empty($row->realisasi))?0:round($row->realisasi/$row->rencana)*100; ?> <br />
                        <?php echo (empty($row->realisasi))?0:round($row->realisasi/$row->rencana)*100; ?> <br />
                    </td>
					<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
                    	<?php echo $row->catatan_1; ?><br />
                        <?php echo $row->catatan_2; ?><br />
                        <?php echo $row->catatan_3; ?><br />
                        <?php echo $row->catatan_4; ?><br />
                    </td>
					<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
                    	<?php echo $row->capaian_1; ?><br />
                        <?php echo $row->capaian_2; ?><br />
                        <?php echo $row->capaian_3; ?><br />
                        <?php echo $row->capaian_4; ?><br />
                    </td>
					<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
                    	<?php echo $row->ket_1; ?><br />
                        <?php echo $row->ket_2; ?><br />
                        <?php echo $row->ket_3; ?><br />
                        <?php echo $row->ket_4; ?><br />
                    </td>
                    <td style="border-bottom: 0;" align="center">
				<?php
						if ($row->id_status == 2) {
				?>
							<a href="javascript:void(0)" class="<?php echo ($row->is_prog_or_keg==1)?'icon-th-large veri_prog':'icon-list veri_keg';?>" id-r="<?php echo $row->id; ?>"></a>
				<?php				
						}elseif ($row->id_status == 3) {
				?>
							<i style="color:red;">Tidak Disetujui</i>
				<?php
						}elseif ($row->id_status == 4) {
				?>
							<i style="color:black;">Disetujui</i>
				<?php
						}
				?>									
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
									echo $temp[$i]->indikator;
								?>
							</td>
							<td>
								<?php
									echo $temp[$i]->indikator." ".$temp[0]->target." ".$temp[0]->satuan_target;
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
					<?php
							}
					?>
						</tr>
					<?php
						}
					}
					foreach ($kegiatan as $row) {						
							$indikator_kegiatan = $this->m_kendali_perubahan->get_indikator_prog_keg_dpa($row->id, FALSE, TRUE);
							$temp = $indikator_kegiatan->result();			
							$total_temp = $indikator_kegiatan->num_rows();

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
								echo $temp[0]->indikator;
							?>
						</td>
						<td>
							<?php
								echo $temp[0]->indikator." ".$temp[0]->target." ".$temp[0]->satuan_target;
							?>
						</td>
						<td align="center" style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
                            TW I <br />
                            TW II <br />
                            TW III <br />
                            TW IV <br />
                    	</td>
						<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
                    	<?php echo $row->nominal_1; ?><br />
                        <?php echo $row->nominal_2; ?><br />
                        <?php echo $row->nominal_3; ?><br />
                        <?php echo $row->nominal_4; ?><br />
                    </td>
					<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
                    	0 <br />
                        0 <br />
                        0 <br />
                        0 <br />
                    </td>
					<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
                    	<?php echo (empty($row->realisasi))?0:round($row->realisasi/$row->rencana)*100; ?> <br />
                        <?php echo (empty($row->realisasi))?0:round($row->realisasi/$row->rencana)*100; ?> <br />
                        <?php echo (empty($row->realisasi))?0:round($row->realisasi/$row->rencana)*100; ?> <br />
                        <?php echo (empty($row->realisasi))?0:round($row->realisasi/$row->rencana)*100; ?> <br />
                    </td>
					<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
                    	<?php echo $row->catatan_1; ?><br />
                        <?php echo $row->catatan_2; ?><br />
                        <?php echo $row->catatan_3; ?><br />
                        <?php echo $row->catatan_4; ?><br />
                    </td>
					<td align="right" style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
                    	<?php echo $row->capaian_1; ?><br />
                        <?php echo $row->capaian_2; ?><br />
                        <?php echo $row->capaian_3; ?><br />
                        <?php echo $row->capaian_4; ?><br />
                    </td>
					<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
                    	<?php echo $row->ket_1; ?><br />
                        <?php echo $row->ket_2; ?><br />
                        <?php echo $row->ket_3; ?><br />
                        <?php echo $row->ket_4; ?><br />
                    </td>
                    <td style="border-bottom: 0;" align="center">
				<?php
						if ($row->id_status == 2) {
				?>
							<a href="javascript:void(0)" class="<?php echo ($row->is_prog_or_keg==1)?'icon-th-large veri_keg':'icon-list veri_keg';?>" id-r="<?php echo $row->id; ?>"></a>
				<?php				
						}elseif ($row->id_status == 3) {
				?>
							<i style="color:red;">Tidak Disetujui</i>
				<?php
						}elseif ($row->id_status == 4) {
				?>
							<i style="color:black;">Disetujui</i>
				<?php
						}
				?>									
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
									echo $temp[$i]->indikator;
								?>
							</td>
							<td>
								<?php
									echo $temp[$i]->indikator." ".$temp[0]->target." ".$temp[0]->satuan_target;
								?>
							</td>
							<td style="border-top: 0;border-bottom: 0;" ></td>
							<td style="border-top: 0;border-bottom: 0;" ></td>
							<td style="border-top: 0;border-bottom: 0;" ></td>
							<td style="border-top: 0;border-bottom: 0;" ></td>
							<td style="border-top: 0;border-bottom: 0;" ></td>
							<td style="border-top: 0;border-bottom: 0;" ></td>
							<td style="border-top: 0;border-bottom: 0;" ></td>
                            <td style="border-top: 0;border-bottom: 0;" ></td>
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
			<input id-r="<?php echo $id_skpd; ?>" type="button" id="disapprove_renja" value="Tidak Setujui Kendali Belanja Perubahan">
			<input type="button" id="kembali_all" value="Kembali">
		</div>
	</footer>
</article>