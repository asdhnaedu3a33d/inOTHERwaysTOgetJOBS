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
				url: "<?php echo site_url('cik/do_veri'); ?>",
				data: {id: $(this).attr("id-r"), bulan: $(this).attr("id-b"), action: 'keg'},
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
				url: "<?php echo site_url('cik/do_veri'); ?>",
				data: {id: $(this).attr("id-r"), bulan: $(this).attr("id-b"), action: 'pro'},
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
			$(location).attr('href', '<?php echo site_url("cik/veri_view"); ?>')
		});

		$("#disapprove_cik").click(function(){
			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});

	    	$.ajax({
				type: "POST",
				url: "<?php echo site_url('cik/disapprove_cik'); ?>",
				data: {id:$(this).attr("id-r"),bulan:$(this).attr("id-b")},
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
<article class="module width_full" style="width: 100%;">
	<header>
	  <h3>Preview Verifikasi CIK</h3>
	</header>
    <div class="module_content">
	<input type="hidden" name="id_skpd" id="id_skpd" value="<?php echo $id_skpd; ?>" />
    <input type="hidden" name="bulan" id="bulan" value="<?php echo $bulan; ?>" />
    <table id="tabel_cik" class="table-common" width="99.9%">
    	<thead>
            <tr>
                <th rowspan="2" colspan="4">KODE</th>
                <th rowspan="2">PROGRAM DAN KEGIATAN</th>
                <th colspan="3">ANGGARAN</th>
                <th colspan="4">KELOMPOK INDIKATOR KINERJA PROGRAM (OUTCOME) / INDIKATOR KINERJA KEGIATAN (OUTPUT)</th>
                <th rowspan="2">KET.</th>
                <th rowspan="2" colspan="2">ACTION</th>
            </tr>
            <tr>
                <th>RENCANA (Rp.)</th>
                <th>REALISASI (Rp.)</th>
                <th>CAPAIAN IK</th>
                <th>INDIKATOR/SATUAN</th>
                <th>RENCANA</th>
                <th>REALISASI</th>
                <th>CAPAIAN IK</th>
            </tr>
          </thead>
        <tbody id="preview_cik">
        		<?php
				$max_col_keg=1;
				$bulan = $this->uri->segment(4);
				$tot_rencana=0; $tot_realisasi=0;
				$realisasi = "realisasi_".$bulan;
				$capaian = "capaian_".$bulan;
				$status = "status_".$bulan;
				foreach($urusan as $row_urusan){
						$bidang = $this->m_cik->get_data_bidang_cik_readonly($row_urusan->kd_urusan,$id_skpd,$bulan);
					foreach($bidang as $row_bidang){
						$program = $this->m_cik->get_data_program_cik_readonly($row_urusan->kd_urusan,$row_bidang->kd_bidang,$id_skpd,$bulan);
					foreach($program as $row)
					{
						$tot_rencana += $row->sum_rencana;
						$tot_realisasi += $row->sum_realisasi;
						$kegiatan = $this->m_cik->get_data_kegiatan_cik_readonly($row->id_skpd,$bulan,$row_urusan->kd_urusan,$row_bidang->kd_bidang,$row->kd_program);
						$cik_pro_keg = (empty($row->sum_realisasi))?0:round(($row->sum_realisasi/$row->sum_rencana)*100,2);
						$indikator_program = $this->m_cik->get_indikator_prog_keg_preview($row->id, $bulan, FALSE, TRUE);
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
                    	<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->kd_urusan; ?></td>
                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->kd_bidang; ?></td>
                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->kd_program; ?></td>
                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->kd_kegiatan; ?></td>
                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->nama_prog_or_keg; ?></td>
                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right"><?php echo Formatting::currency($row->sum_rencana,2); ?>
                        </td>
                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right"><?php echo Formatting::currency($row->sum_realisasi,2); ?>
                        </td>
                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right"><?php echo $cik_pro_keg; ?></td>
                        <td>
							<?php
                                echo $temp[0]->indikator;
                            ?>
                        </td>
                        <td align="right">
                            <?php
                                echo $temp[0]->target;
                            ?>
                        </td>
                        <td align="right">
                            <?php
                                echo (empty($temp[0]->realisasi)) ? 0 :$temp[0]->realisasi;
                            ?>
                        </td>
                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right">
                                    <?php
                                        echo $row->$capaian;
                                    ?>
                                </td>
                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="center">-</td>
                        <td align="center" colspan="2" style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>">
                        <?php
						//echo $row->is_prog_or_keg;
								if ($row->$status == 2) {
						?>
									<i style="color:blue;">Baru Dikirim</i>
						<?php
								}elseif ($row->$status == 3) {
						?>
									<i style="color:red;">Tidak Disetujui</i>
						<?php
								}elseif ($row->$status == 4) {
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
                            <td align="right">
                                <?php
                                    echo $temp[$i]->target;
                                ?>
                            </td>
                            <td align="right">
                                <?php
                                    echo $temp[$i]->realisasi;
                                ?>
                            </td>
                            <td style="border-top: 0;border-bottom: 0;"></td>
                            <td style="border-top: 0;border-bottom: 0;"></td>
                            <td style="border-top: 0;border-bottom: 0;" colspan="2"></td>
                        </tr>
                    <?php
							}
                        }
						?>
                <?php
					foreach($kegiatan as $row_kegiatan)
					{
						//$kegiatan = $this->m_cik->get_data_kegiatan_cik($row->id_skpd,$bulan,$row->id);
						$cik_pro_keg = (empty($row_kegiatan->$realisasi))?0:round(($row_kegiatan->$realisasi/$row_kegiatan->rencana)*100,2);
						$indikator_program = $this->m_cik->get_indikator_prog_keg_preview($row_kegiatan->id, $bulan, FALSE, TRUE);
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
                    	<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row_kegiatan->kd_urusan; ?></td>
                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row_kegiatan->kd_bidang; ?></td>
                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row_kegiatan->kd_program; ?></td>
                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row_kegiatan->kd_kegiatan; ?></td>
                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row_kegiatan->nama_prog_or_keg; ?></td>
                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right"><?php echo Formatting::currency($row_kegiatan->rencana,2); ?>
                        </td>
                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right"><?php echo Formatting::currency($row_kegiatan->$realisasi,2); ?>
                        </td>
                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right"><?php echo $cik_pro_keg; ?></td>
                        <td>
							<?php
                                echo $temp[0]->indikator;
                            ?>
                        </td>
                        <td align="right">
                            <?php
                                echo $temp[0]->target;
                            ?>
                        </td>
                        <td align="right">
                            <?php
                                echo (empty($temp[0]->realisasi)) ? 0 :$temp[0]->realisasi;
                            ?>
                        </td>
                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right">
                                    <?php
                                        echo $row_kegiatan->$capaian;
                                    ?>
                                </td>
                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="center">-</td>
                        <td align="center" colspan="2" style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>">
                        <?php
						//echo $row->is_prog_or_keg;
								if ($row_kegiatan->$status == 2) {
						?>
                        			<i style="color:blue;">Baru Dikirim</i>
						<?php
								}elseif ($row_kegiatan->$status == 3) {
						?>
									<i style="color:red;">Tidak Disetujui</i>
						<?php
								}elseif ($row_kegiatan->$status == 4) {
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
                            <td align="right">
                                <?php
                                    echo $temp[$i]->target;
                                ?>
                            </td>
                            <td align="right">
                                <?php
                                    echo $temp[$i]->realisasi;
                                ?>
                            </td>
                            <td style="border-top: 0;border-bottom: 0;"></td>
                            <td style="border-top: 0;border-bottom: 0;"></td>
                            <td style="border-top: 0;border-bottom: 0;" colspan="2"></td>
                        </tr>
                    <?php
							}
                        }
						?>
                <?php
					}
					}
					}
				}
				?>
				<tr>
		    	<td colspan="4">&nbsp;</td>
		        <td align="right">JUMLAH&nbsp;&nbsp;&nbsp;</td>
		        <td align="right"><?php echo Formatting::currency($tot_rencana); ?></td>
		        <td align="right"><?php echo Formatting::currency($tot_realisasi); ?></td>
		        <td align="right"><?php echo (empty($tot_realisasi)) ? 0 :round(($tot_realisasi/$tot_rencana)*100,2); ?></td>
		        <td colspan="3" align="right">Rata-rata Capaian Program</td>
		        <td align="right"><?php echo round($tot_prog,2); ?></td>
		        <td colspan="3">&nbsp;</td>
		    </tr>
		    <?php $sisa = $tot_rencana-$tot_realisasi; ?>
		    <tr>
		    	<td colspan="4">&nbsp;</td>
		        <td align="right">SISA&nbsp;&nbsp;&nbsp;</td>
		        <td colspan="2" align="right"><?php echo Formatting::currency($sisa); ?></td>
		        <td align="right"><?php echo (empty($sisa)) ? 0 :round(($sisa/$tot_rencana)*100,2); ?></td>
		        <td colspan="3" align="right">Rata-rata Capaian Kegiatan</td>
		        <td align="right"><?php echo round($tot_keg,2); ?></td>
		        <td colspan="3">&nbsp;</td>
		    </tr>
         </tbody>
	</table>
    </div>
	<footer>
		<div class="submit_link">
			<input type="button" value="Kembali" onclick="history.go(-1)">
		</div>
	</footer>
</article>
