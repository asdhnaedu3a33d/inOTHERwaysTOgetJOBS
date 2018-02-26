<style type="text/css">
	form{
		color: black;
	}
	.radio-btn{
		width: 97%;
		padding: 10px;
	}
	.radio-btn textarea, .radio-btn .error{
		margin-left: 25px;
		width: 500px;
		height: 100px;
		float: left;
	}
</style>
<script type="text/javascript">
	$(document).ready(function(){
		$("form#veri_renstra").validate({
			rules: {
			  ket : "required"
			}
	    });

		$("#simpan").click(function(){
		    var valid = $("form#veri_renstra").valid();
		    if (valid) {
		    	$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});

		    	$.ajax({
					type: "POST",
					url: $("form#veri_renstra").attr("action"),
					data: $("form#veri_renstra").serialize(),
					dataType: "json",
					success: function(msg){
						if (msg.success==1) {
							$.blockUI({
								message: msg.msg,
								timeout: 2000,
								css: window._css,
								overlayCSS: window._ovcss
							});
							location.reload();
							$.facebox.close();
						};
					}
				});
		    };
		});

		$("#keluar").click(function(){
			$.facebox.close();
		});

		$("input[name=veri]").click(function(){
			$("#simpan").attr("disabled", false);
			if($(this).val()=="tdk_setuju"){
				$("#ket").attr("disabled", false);
			}else{
				$("#ket").val("");
				$("#ket").attr("disabled", true);
			}
		});
	});
</script>
<div style="width: 900px;">
	<header>
 		<h3>
			Verifikasi Data Renstra (KEPALA SKPD)
		</h3>
 	</header>
	<div class="module_content">
		<table class="fcari" width="100%">
			<tbody>
				<tr>
					<td width="22%">SKPD</td>
					<td width="77%"><?php echo $renstra->nama_skpd; ?></td>
				</tr>
				<tr>
					<td>Tujuan</td>
					<td><?php echo $renstra->tujuan; ?></td>
				</tr>
				<tr>
					<td>Sasaran</td>
					<td><?php echo $renstra->sasaran; ?></td>
				</tr>
				<tr>
					<td>Indikator Sasaran</td>
					<td>
						<?php
							$i=0;
							foreach ($indikator_sasaran as $row1) {
								$i++;
								echo $i .". ". $row1->indikator ."<BR>";

							}
						?>
					</td>
				</tr>
				<tr style="background-color: white;">
					<td colspan="2"><hr></td>
				</tr>
				<tr>
					<td>Urusan</td>
					<td><?php echo $renstra->kd_urusan.". ".$renstra->Nm_Urusan; ?></td>
				</tr>
				<tr>
					<td>Bidang Urusan</td>
					<td style="padding-left: 20px;"><?php echo $renstra->kd_bidang.". ".$renstra->Nm_Bidang; ?></td>
				</tr>
				<tr>
					<td>Program</td>
					<td style="padding-left: 43px;"><?php echo $renstra->kd_program.". ".$renstra->Ket_Program; ?></td>
				</tr>
			<?php
				if (empty($program) && !$program) {
			?>
				<tr>
					<td>Kegiatan</td>
					<td style="padding-left: 65px;"><?php echo $renstra->kd_kegiatan.". ".$renstra->nama_prog_or_keg; ?></td>
				</tr>
			<?php
				}
			?>
				<tr>
					<td>Indikator Kinerja</td>
					<td>
						<?php
							$i=0;
							foreach ($indikator as $row_indikator) {
								$i++;
								echo $i .". ". $row_indikator->indikator ."<BR>";
								echo "Kategori Indikator :  $row_indikator->nama_status_indikator | $row_indikator->nama_kategori_indikator <BR>";
						?>
						<table class="table-common" width="100%">
							<tr>
								<th width="14%">Kondisi Awal</th>
								<th width="14%">Target 1</th>
								<th width="14%">Target 2</th>
								<th width="14%">Target 3</th>
								<th width="14%">Target 4</th>
								<th width="14%">Target 5</th>
								<th width="14%">Kondisi Akhir</th>
							</tr>
							<tr>
								<td align="center"><?php echo $row_indikator->kondisi_awal." ".$row_indikator->nama_value; ?></td>
								<td align="center"><?php echo $row_indikator->target_1." ".$row_indikator->nama_value; ?></td>
								<td align="center"><?php echo $row_indikator->target_2." ".$row_indikator->nama_value; ?></td>
								<td align="center"><?php echo $row_indikator->target_3." ".$row_indikator->nama_value; ?></td>
								<td align="center"><?php echo $row_indikator->target_4." ".$row_indikator->nama_value; ?></td>
								<td align="center"><?php echo $row_indikator->target_5." ".$row_indikator->nama_value; ?></td>
								<td align="center"><?php echo $row_indikator->target_kondisi_akhir." ".$row_indikator->nama_value; ?></td>
							</tr>
						</table>
						<hr>
						<?php
							}
						?>
					</td>
				</tr>
			<?php
				if (empty($program) && !$program) {
			?>
				<tr>
					<td>Penanggung Jawab</td>
					<td><?php echo $renstra->penanggung_jawab; ?></td>
				</tr>
				<tr>
					<td>Lokasi</td>
					<td><?php echo $renstra->lokasi; ?></td>
				</tr>
				<tr style="background-color: white;">
					<td colspan="2"><hr></td>
				</tr>
			<?php
				}
			?>
			</tbody>
		</table>
		<?php
			if (empty($program) && !$program) {
				$th_anggaran = $this->m_settings->get_tahun_anggaran_db();
		?>

		<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tahun1" data-toggle="tab"><b>Tahun 1</b></a></li>
					<li><a href="#tahun2" data-toggle="tab"><b>Tahun 2</b></a></li>
					<li><a href="#tahun3" data-toggle="tab"><b>Tahun 3</b></a></li>
					<li><a href="#tahun4" data-toggle="tab"><b>Tahun 4</b></a></li>
					<li><a href="#tahun5" data-toggle="tab"><b>Tahun 5</b></a></li>
				</ul>
				<div class="tab-content">
					<!-- /.tab-pane -->
					<div class="active tab-pane" id="tahun1">
						<!-- /.tab-tahun 1 -->
						<div class="tab-pane" id="tahun1">
							<table class="fcari">
								<tbody>
									<tr>
										<td width="22%">Lokasi Tahun 1</th>
										<td align="left"><?php echo $renstra->lokasi_1; ?></td>
									</tr>
									<tr>
										<td>Uaraian Kegiatan Tahun 1</td>
										<td align="left"><?php echo $renstra->uraian_kegiatan_1; ?></td>
									</tr>
									<tr>
										<td width="22%">Nominal Tahun 1</td>
										<td>Rp. <?php echo Formatting::currency($renstra->nominal_1); ?></td>
									</tr>
									<tr>
										<table id="listbelanja_1">
											<tr>
												<th>No</th>
												<th>Sumber Dana</th>
												<th>Jenis Belanja</th>
												<th>Kategori Belanja</th>
												<th>Sub Kategori Belanja</th>
												<th>Belanja</th>
												<th>Uraian</th>
												<th>Detil</th>

												<th>Volume</th>
												<th>Satuan</th>
												<th>Nominal</th>
												<th>Sub Total</th>
											</tr>
								      <?php if(!empty($detil_kegiatan)){
								              $gIndex_1 = 1;
								              foreach ($detil_kegiatan as $row) {
								                if ($row->tahun == $th_anggaran[0]->tahun_anggaran) {
								                  $vol = Formatting::currency($row->volume);
								                  $nom = Formatting::currency($row->nominal_satuan);
								                  $sub = Formatting::currency($row->subtotal);
								      ?>
								      <tr id="<?php echo $gIndex_1 ?>">
								        <td> <?php echo $gIndex_1 ?> </td>
								        <td> <?php echo $row->Sumber_dana ?> </td>
								        <td> <?php echo $row->kode_jenis_belanja.". ".$row->jenis_belanja ?> </td>
								        <td> <?php echo $row->kode_kategori_belanja.". ".$row->kategori_belanja ?> </td>
								        <td> <?php echo $row->kode_sub_kategori_belanja.". ".$row->sub_kategori_belanja ?> </td>
								        <td> <?php echo $row->kode_belanja.". ".$row->belanja ?> </td>
								        <td> <?php echo $row->uraian_belanja ?> </td>
								        <td> <?php echo $row->detil_uraian_belanja ?> </td>
								        <td> <?php echo $vol; ?> </td>
								        <td> <?php echo $row->satuan ?> </td>
								        <td> <?php echo $nom; ?> </td>
								        <td> <?php echo $sub; ?> </td>
								      </tr>
								      <?php $gIndex_1++; }}} ?>
										</table>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<!-- /.tab-tahun 2 -->
					<div class="tab-pane" id="tahun2">
						<table class="fcari">
							<tbody>
								<tr>
									<td width="22%">Lokasi Tahun 2</th>
									<td align="left"><?php echo $renstra->lokasi_2; ?></td>
								</tr>
								<tr>
									<td>Uaraian Kegiatan Tahun 2</td>
									<td align="left"><?php echo $renstra->uraian_kegiatan_2; ?></td>
								</tr>
								<tr>
									<td width="22%">Nominal Tahun 2</td>
									<td>Rp. <?php echo Formatting::currency($renstra->nominal_2); ?></td>
								</tr>
								<tr>
									<table id="listbelanja_2">
										<tr>
											<th>No</th>
											<th>Sumber Dana</th>
											<th>Jenis Belanja</th>
											<th>Kategori Belanja</th>
											<th>Sub Kategori Belanja</th>
											<th>Belanja</th>
											<th>Uraian</th>
											<th>Detil</th>

											<th>Volume</th>
											<th>Satuan</th>
											<th>Nominal</th>
											<th>Sub Total</th>
										</tr>
										<?php if(!empty($detil_kegiatan)){
														$gIndex_2 = 1;
														foreach ($detil_kegiatan as $row) {
															if ($row->tahun == $th_anggaran[1]->tahun_anggaran) {
																$vol = Formatting::currency($row->volume);
																$nom = Formatting::currency($row->nominal_satuan);
																$sub = Formatting::currency($row->subtotal);
										?>
										<tr id="<?php echo $gIndex_2 ?>">
											<td> <?php echo $gIndex_2 ?> </td>
											<td> <?php echo $row->Sumber_dana ?> </td>
											<td> <?php echo $row->kode_jenis_belanja.". ".$row->jenis_belanja ?> </td>
											<td> <?php echo $row->kode_kategori_belanja.". ".$row->kategori_belanja ?> </td>
											<td> <?php echo $row->kode_sub_kategori_belanja.". ".$row->sub_kategori_belanja ?> </td>
											<td> <?php echo $row->kode_belanja.". ".$row->belanja ?> </td>
											<td> <?php echo $row->uraian_belanja ?> </td>
											<td> <?php echo $row->detil_uraian_belanja ?> </td>
											<td> <?php echo $vol; ?> </td>
											<td> <?php echo $row->satuan ?> </td>
											<td> <?php echo $nom; ?> </td>
											<td> <?php echo $sub; ?> </td>
										</tr>
										<?php $gIndex_2++; }}} ?>
									</table>
								</tr>
							</tbody>
						</table>
					</div>
					<!-- /.tab-tahun 3 -->
					<div class="tab-pane" id="tahun3">
						<table class="fcari">
							<tbody>
								<tr>
									<td width="22%">Lokasi Tahun 3</th>
									<td align="left"><?php echo $renstra->lokasi_3; ?></td>
								</tr>
								<tr>
									<td>Uaraian Kegiatan Tahun 3</td>
									<td align="left"><?php echo $renstra->uraian_kegiatan_3; ?></td>
								</tr>
								<tr>
									<td width="22%">Nominal Tahun 3</td>
									<td>Rp. <?php echo Formatting::currency($renstra->nominal_3); ?></td>
								</tr>
								<tr>
									<table id="listbelanja_3">
										<tr>
											<th>No</th>
											<th>Sumber Dana</th>
											<th>Jenis Belanja</th>
											<th>Kategori Belanja</th>
											<th>Sub Kategori Belanja</th>
											<th>Belanja</th>
											<th>Uraian</th>
											<th>Detil</th>

											<th>Volume</th>
											<th>Satuan</th>
											<th>Nominal</th>
											<th>Sub Total</th>
										</tr>
										<?php if(!empty($detil_kegiatan)){
														$gIndex_3 = 1;
														foreach ($detil_kegiatan as $row) {
															if ($row->tahun == $th_anggaran[2]->tahun_anggaran) {
																$vol = Formatting::currency($row->volume);
																$nom = Formatting::currency($row->nominal_satuan);
																$sub = Formatting::currency($row->subtotal);
										?>
										<tr id="<?php echo $gIndex_3 ?>">
											<td> <?php echo $gIndex_3 ?> </td>
											<td> <?php echo $row->Sumber_dana ?> </td>
											<td> <?php echo $row->kode_jenis_belanja.". ".$row->jenis_belanja ?> </td>
											<td> <?php echo $row->kode_kategori_belanja.". ".$row->kategori_belanja ?> </td>
											<td> <?php echo $row->kode_sub_kategori_belanja.". ".$row->sub_kategori_belanja ?> </td>
											<td> <?php echo $row->kode_belanja.". ".$row->belanja ?> </td>
											<td> <?php echo $row->uraian_belanja ?> </td>
											<td> <?php echo $row->detil_uraian_belanja ?> </td>
											<td> <?php echo $vol; ?> </td>
											<td> <?php echo $row->satuan ?> </td>
											<td> <?php echo $nom; ?> </td>
											<td> <?php echo $sub; ?> </td>
										</tr>
										<?php $gIndex_3++; }}} ?>
									</table>
								</tr>
							</tbody>
						</table>
					</div>
					<!-- /.tab-tahun 4 -->
					<div class="tab-pane" id="tahun4">
						<table class="fcari">
							<tbody>
								<tr>
									<td width="22%">Lokasi Tahun 4</th>
									<td align="left"><?php echo $renstra->lokasi_4; ?></td>
								</tr>
								<tr>
									<td>Uaraian Kegiatan Tahun 4</td>
									<td align="left"><?php echo $renstra->uraian_kegiatan_4; ?></td>
								</tr>
								<tr>
									<td width="22%">Nominal Tahun 4</td>
									<td>Rp. <?php echo Formatting::currency($renstra->nominal_4); ?></td>
								</tr>
								<tr>
									<table id="listbelanja_4">
										<tr>
											<th>No</th>
											<th>Sumber Dana</th>
											<th>Jenis Belanja</th>
											<th>Kategori Belanja</th>
											<th>Sub Kategori Belanja</th>
											<th>Belanja</th>
											<th>Uraian</th>
											<th>Detil</th>

											<th>Volume</th>
											<th>Satuan</th>
											<th>Nominal</th>
											<th>Sub Total</th>
										</tr>
										<?php if(!empty($detil_kegiatan)){
														$gIndex_4 = 1;
														foreach ($detil_kegiatan as $row) {
															if ($row->tahun == $th_anggaran[3]->tahun_anggaran) {
																$vol = Formatting::currency($row->volume);
																$nom = Formatting::currency($row->nominal_satuan);
																$sub = Formatting::currency($row->subtotal);
										?>
										<tr id="<?php echo $gIndex_4 ?>">
											<td> <?php echo $gIndex_4 ?> </td>
											<td> <?php echo $row->Sumber_dana ?> </td>
											<td> <?php echo $row->kode_jenis_belanja.". ".$row->jenis_belanja ?> </td>
											<td> <?php echo $row->kode_kategori_belanja.". ".$row->kategori_belanja ?> </td>
											<td> <?php echo $row->kode_sub_kategori_belanja.". ".$row->sub_kategori_belanja ?> </td>
											<td> <?php echo $row->kode_belanja.". ".$row->belanja ?> </td>
											<td> <?php echo $row->uraian_belanja ?> </td>
											<td> <?php echo $row->detil_uraian_belanja ?> </td>
											<td> <?php echo $vol; ?> </td>
											<td> <?php echo $row->satuan ?> </td>
											<td> <?php echo $nom; ?> </td>
											<td> <?php echo $sub; ?> </td>
										</tr>
										<?php $gIndex_4++; }}} ?>
									</table>
								</tr>
							</tbody>
						</table>
					</div>
					<!-- /.tab-tahun 5 -->
					<div class="tab-pane" id="tahun5">
						<table class="fcari">
							<tbody>
								<tr>
									<td width="22%">Lokasi Tahun 5</th>
									<td align="left"><?php echo $renstra->lokasi_5; ?></td>
								</tr>
								<tr>
									<td>Uaraian Kegiatan Tahun 5</td>
									<td align="left"><?php echo $renstra->uraian_kegiatan_5; ?></td>
								</tr>
								<tr>
									<td width="22%">Nominal Tahun 5</td>
									<td>Rp. <?php echo Formatting::currency($renstra->nominal_5); ?></td>
								</tr>
								<tr>
									<table id="listbelanja_5">
										<tr>
											<th>No</th>
											<th>Sumber Dana</th>
											<th>Jenis Belanja</th>
											<th>Kategori Belanja</th>
											<th>Sub Kategori Belanja</th>
											<th>Belanja</th>
											<th>Uraian</th>
											<th>Detil</th>

											<th>Volume</th>
											<th>Satuan</th>
											<th>Nominal</th>
											<th>Sub Total</th>
										</tr>
										<?php if(!empty($detil_kegiatan)){
														$gIndex_5 = 1;
														foreach ($detil_kegiatan as $row) {
															if ($row->tahun == $th_anggaran[4]->tahun_anggaran) {
																$vol = Formatting::currency($row->volume);
																$nom = Formatting::currency($row->nominal_satuan);
																$sub = Formatting::currency($row->subtotal);
										?>
										<tr id="<?php echo $gIndex_5 ?>">
											<td> <?php echo $gIndex_5 ?> </td>
											<td> <?php echo $row->Sumber_dana ?> </td>
											<td> <?php echo $row->kode_jenis_belanja.". ".$row->jenis_belanja ?> </td>
											<td> <?php echo $row->kode_kategori_belanja.". ".$row->kategori_belanja ?> </td>
											<td> <?php echo $row->kode_sub_kategori_belanja.". ".$row->sub_kategori_belanja ?> </td>
											<td> <?php echo $row->kode_belanja.". ".$row->belanja ?> </td>
											<td> <?php echo $row->uraian_belanja ?> </td>
											<td> <?php echo $row->detil_uraian_belanja ?> </td>
											<td> <?php echo $vol; ?> </td>
											<td> <?php echo $row->satuan ?> </td>
											<td> <?php echo $nom; ?> </td>
											<td> <?php echo $sub; ?> </td>
										</tr>
										<?php $gIndex_5++; }}} ?>
									</table>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
		</div>
		<?php
			}
		?>
		<form id="veri_renstra" name="veri_renstra" method="POST" accept-charset="UTF-8" action="<?php echo site_url('renstra/save_veri_kepala_skpd'); ?>">
			<input type="hidden" name="id" value="<?php echo $renstra->id; ?>">
			<input type="hidden" name="id_renstra" value="<?php echo $renstra->id_renstra; ?>">
			<div class="radio">
        <label>
				      <input type="radio" name="veri" value="setuju"> Disetujui
        </label>
			</div>
			<div class="radio">
        <label>
				      <input type="radio" name="veri" value="tdk_setuju"> Tidak Disetujui
        </label>
			</div>
			<div class="form-group">
				<textarea class="form-control" disabled id="ket" name="ket"></textarea>
			</div>
		</form>
	</div>
	<footer>
		<div class="submit_link">
			<input disabled type='button' id="simpan" name="simpan" value='Simpan' />
  			<input type='button' id="keluar" name="keluar" value='Keluar' />
		</div>
	</footer>
</div>
