<style type="text/css">
	.r-ranwal{
		background-color: #D0BE2D;
		padding-left: 5px;
	}

	.r-akhir{
		background-color: #96CC3F;
		padding-left: 5px;
	}
</style>
<header>
	<h3>
	Jendela Kontrol Renstra
</h3>
</header>
<div class="module_content">
	<table class="fcari" width="100%">
		<tbody>
			<tr>
				<td align="center" colspan="6">Proses</td>
			</tr>
			<tr align="center">
				<td colspan="3" class="r-ranwal">
					Rancangan Awal
				</td>
				<td colspan="3" class="r-akhir">
					Renstra Akhir (Setelah RPJMD)
				</td>
			</tr>
			<tr>
				<td width="25%" class="r-ranwal">Program & Kegiatan Baru</td>
				<td colspan="2" width="25%" class="r-ranwal"><?php echo $jendela_kontrol->baru; ?> Data</td>
				<td width="25%" class="r-akhir">Program & Kegiatan Baru</td>
				<td colspan="2" width="25%" class="r-akhir"><?php echo $jendela_kontrol->baru2; ?> Data</td>
			</tr>
			<tr>
				<td class="r-ranwal">Program & Kegiatan Telah dikirim</td>
				<td colspan="2" class="r-ranwal"><?php echo $jendela_kontrol->kirim; ?> Data</td>
				<td class="r-akhir">Program & Kegiatan Telah dikirim</td>
				<td colspan="2" class="r-akhir"><?php echo $jendela_kontrol->kirim2; ?> Data</td>
			</tr>
			<tr>
				<td class="r-ranwal">Program & Kegiatan Diproses</td>
				<td colspan="2" class="r-ranwal"><?php echo $jendela_kontrol->proses; ?> Data</td>
				<td class="r-akhir">Program & Kegiatan Diproses</td>
				<td colspan="2" class="r-akhir"><?php echo $jendela_kontrol->proses2; ?> Data</td>
			</tr>
			<tr>
				<td class="r-ranwal"></td>
				<td width="7%" class="r-ranwal">Revisi</td>
				<td class="r-ranwal">
					<?php echo $jendela_kontrol->revisi; ?> Data
					<?php
						if(!empty($jendela_kontrol->revisi)){
					?>
						<a class="revisi" id-r="<?php echo $renstra->id; ?>" idR="revisi_ranwal" href="#" style="font-style: italic;">Lihat</a>
					<?php
						}
					?>
				</td>

				<td class="r-akhir"></td>
				<td width="7%" class="r-akhir">Revisi</td>
				<td class="r-akhir">
					<?php echo $jendela_kontrol->revisi2; ?> Data
					<?php
						if (!empty($jendela_kontrol->revisi2)) {
					?>
						<a class="revisi" id-r="<?php echo $renstra->id; ?>" idR="revisi_akhir" href="#" style="font-style: italic;">Lihat</a>
					<?php
						}
					?>
				</td>
			</tr>
			<tr>
				<td class="r-ranwal"></td>
				<td class="r-ranwal">Diterima</td>
				<td class="r-ranwal"><?php echo $jendela_kontrol->veri; ?> Data</td>

				<td class="r-akhir"></td>
				<td class="r-akhir">Diterima</td>
				<td class="r-akhir"><?php echo $jendela_kontrol->veri2; ?> Data</td>
			</tr>
		<?php
			if (!empty($log_revisi)) {
		?>
			<tr>
				<td align="center" colspan="6">&nbsp;</td>
			</tr>
			<tr>
				<td align="center" colspan="6">Log Revisi</td>
			</tr>
		<?php
				foreach ($log_revisi as $row) {
		?>
			<tr>
				<td align="center" colspan="4"><?php echo $row->keterangan; ?></td>
				<td align="center" colspan="2">
		<?php
					if ($row->status == 2) {
						echo "<font style='color: red;'>Tidak Disetujui</font>";
					}elseif ($row->status == 3) {
						echo "Disetujui";
					}elseif ($row->status == 4) {
						echo "<font style='color: orange;'>Revisi dari Pusat</font>";
					}
		?>
				</td>
			</tr>
		<?php
				}
			}
		?>
		</tbody>
	</table>
	<table style="font-style: italic; color: #666;">
		<tr>
			<td colspan="2">*Keterangan:</td>
		</tr>
		<tr>
			<td valign="top">1. </td>
			<td>Apabila renstra telah dikirim, maka fitur manajemen renstra seperti edit akan di non-aktiifkan.</td>
		</tr>
		<tr>
			<td valign="top">2. </td>
			<td>Fitur manajemen renstra seperti edit akan aktif kembali apabila renstra telah diproses baik tidak disetujui maupun disetujui.</td>
		</tr>
		<tr>
			<td valign="top">2. </td>
			<td>Fitur manajemen renstra seperti edit akan aktif pula apabila terdapat revisi RPJMD sehingga renstra unit yang bersangkutan juga harus direvisi/dirubah.</td>
		</tr>
	</table>
</div>
<footer>
	<div class="submit_link">
		<a href="<?php echo site_url('renstra/preview_renstra'); ?>"><input type="button" value="Lihat Renstra" /></a>
	<?php
		if (!empty($jendela_kontrol->veri2)) {
	?>
		<input type="button" class="button-action" id="p_revisi" value="Pengajuan Revisi" />
	<?php
		}

	?>
		<input type="button" class="button-action" id="cetak" value="Cetak" />
	<?php

		if (!empty($jendela_kontrol->revisi) || (empty($jendela_kontrol->kirim) && empty($jendela_kontrol->veri))){
			if ($kode_unit == $this->session->userdata("id_skpd")) {

	?>
		<a href="<?php echo site_url('renstra/edit_renstra_skpd'); ?>"><input type="button" value="Edit Renstra" /></a>
	<?php
		}}

		if (!empty($jendela_kontrol->baru) || !empty($jendela_kontrol->baru2)){
			if ($is_verifikasi->id == 0){
				if ($kode_unit == $this->session->userdata("id_skpd")) {
	?>
		<input type="button" id="kirim_renstra" value="Kirim Renstra" />
	<?php
		}}}
	?>
		<input type="button" value="Back" onclick="history.go(-1)" />
	</div>
</footer>
