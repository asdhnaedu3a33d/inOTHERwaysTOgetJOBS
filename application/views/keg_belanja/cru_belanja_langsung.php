<div style="width: 900px">
	<header>
		<h3>
	<?php
		if (!empty($program)){
		    echo "Edit Data Program";
		} else{
		    echo "Input Data Program";
		}
	?>
	</h3>
	</header>
	<div class="module_content">
		<form action="<?php echo site_url('renstra/save_program');?>" method="POST" name="program" id="program" accept-charset="UTF-8" enctype="multipart/form-data" >
			<input type="hidden" id="nama_prog_or_keg" name="nama_prog_or_keg" value="<?php echo (!empty($program->nama_prog_or_keg))?$program->nama_prog_or_keg:''; ?>" />

			<table class="fcari" width="100%">
				<tbody>
					<tr>
						<td>Jenis Belanja Langsung</td>
						<td id="cmb-bel-lang">
							<?php echo $; ?>
						</td>
					</tr>
					<tr>
						<td>Realisasi <a id="tambah_realisasi" class="icon-plus-sign" href="javascript:void(0)"></a></td>
						<td id="Realisasi_frame" key="<?php echo (!empty($realisasi))?$realisasi->num_rows():'1'; ?>">
							<?php
								if (!empty($realisasi)) {
									$i=0;
									foreach ($realisasi->result() as $row) {
										$i++;
							?>
							<input type="hidden" name="id_indikator_program[<?php echo $i; ?>]" value="<?php echo $row->id; ?>">
							<div style="width: 100%; margin-top: 10px;">
								<div style="width: 100%;">
									<textarea class="common indikator_val" name="indikator_kinerja[<?php echo $i; ?>]"><?php if(!empty($row->indikator)){echo $row->indikator;} ?></textarea>
							<?php
								if ($i != 1) {
							?>
								<a class="icon-remove hapus_indikator_program" href="javascript:void(0)" style="vertical-align: top;"></a>
							<?php
								}
							?>
								</div>
								<div style="width: 100%;">
									<table class="table-common" width="100%">
										<tr>
											<td colspan="2">Satuan</td>
											<td colspan="5"><?php echo form_dropdown('satuan_target['. $i .']', $satuan, $row->satuan_target, 'class="common indikator_val" id="satuan_target"'); ?></td>
										</tr>
										<tr>
											<th>Kondisi Awal</th>
											<th>Target 1</th>
											<th>Target 2</th>
											<th>Target 3</th>
											<th>Target 4</th>
											<th>Target 5</th>
											<th>Kondisi Akhir</th>
										</tr>
										<tr>
											<td width="14%"><input style="width: 100%;" type="text" class="target" name="kondisi_awal[<?php echo $i; ?>]" value="<?php echo (!empty($row->kondisi_awal))?$row->kondisi_awal:''; ?>"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" name="target_1[<?php echo $i; ?>]" value="<?php echo (!empty($row->target_1))?$row->target_1:''; ?>"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" name="target_2[<?php echo $i; ?>]" value="<?php echo (!empty($row->target_2))?$row->target_2:''; ?>"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" name="target_3[<?php echo $i; ?>]" value="<?php echo (!empty($row->target_3))?$row->target_3:''; ?>"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" name="target_4[<?php echo $i; ?>]" value="<?php echo (!empty($row->target_4))?$row->target_4:''; ?>"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" name="target_5[<?php echo $i; ?>]" value="<?php echo (!empty($row->target_5))?$row->target_5:''; ?>"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" name="target_kondisi_akhir[<?php echo $i; ?>]" value="<?php echo (!empty($row->target_kondisi_akhir))?$row->target_kondisi_akhir:''; ?>"></td>
										</tr>
									</table>
								</div>
							</div>
							<?php
									}
								}else{
							?>
							<div style="width: 100%; margin-top: 10px;">
								<div style="width: 100%;">
									<textarea class="common indikator_val" name="indikator_kinerja[1]"></textarea>
								</div>
								<div style="width: 100%;">
									<table class="table-common" width="100%">
										<tr>
											<td colspan="2">Satuan</td>
											<td colspan="5"><?php echo form_dropdown('satuan_target[1]', $satuan, '', 'class="common indikator_val" id="satuan_target"'); ?></td>
										</tr>
										<tr>
											<th>Kondisi Awal</th>
											<th>Target 1</th>
											<th>Target 2</th>
											<th>Target 3</th>
											<th>Target 4</th>
											<th>Target 5</th>
											<th>Kondisi Akhir</th>
										</tr>
										<tr>
											<td width="14%"><input style="width: 100%;" type="text" class="target" name="kondisi_awal[1]"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" name="target_1[1]"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" name="target_2[1]"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" name="target_3[1]"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" name="target_4[1]"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" name="target_5[1]"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" name="target_kondisi_akhir[1]"></td>
										</tr>
									</table>
								</div>
							</div>
							<?php
								}
							?>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
	<footer>
		<div class="submit_link">
  			<input id="simpan" type="button" value="Simpan">
		</div>
	</footer>
</div>
<div style="display: none" id="indikator_box_program">
	<div style="width: 100%; margin-top: 15px;">
		<hr>
		<div style="width: 100%;">
			<textarea class="common indikator_val" name="indikator_kinerja[]"></textarea>
			<a class="icon-remove hapus_indikator_program" href="javascript:void(0)" style="vertical-align: top;"></a>
		</div>
		<div style="width: 100%;">
			<table class="table-common" width="100%">
				<tr>
					<td colspan="2">Satuan</td>
					<td colspan="5"><?php echo form_dropdown('satuan_target[1]', $satuan, '', 'class="common indikator_val" id="satuan_target"'); ?></td>
				</tr>
				<tr>
					<th>Kondisi Awal</th>
					<th>Target 1</th>
					<th>Target 2</th>
					<th>Target 3</th>
					<th>Target 4</th>
					<th>Target 5</th>
					<th>Kondisi Akhir</th>
				</tr>
				<tr>
					<td width="14%"><input style="width: 100%;" type="text" class="target" id="target_aw" name="kondisi_awal[1]"></td>
					<td width="14%"><input style="width: 100%;" type="text" class="target" id="target_1" name="target_1[1]"></td>
					<td width="14%"><input style="width: 100%;" type="text" class="target" id="target_2" name="target_2[1]"></td>
					<td width="14%"><input style="width: 100%;" type="text" class="target" id="target_3" name="target_3[1]"></td>
					<td width="14%"><input style="width: 100%;" type="text" class="target" id="target_4" name="target_4[1]"></td>
					<td width="14%"><input style="width: 100%;" type="text" class="target" id="target_5" name="target_5[1]"></td>
					<td width="14%"><input style="width: 100%;" type="text" class="target" id="target_ah" name="target_kondisi_akhir[1]"></td>
				</tr>
			</table>
		</div>
	</div>
</div>
