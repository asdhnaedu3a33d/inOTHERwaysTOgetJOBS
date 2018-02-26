<div style="width:800px">
<table class="fcari" width="800px">
	<tbody>
		<tr>
			<td width="20%">SKPD</td>
			<td width="77%"><?php echo $dpa->nama_skpd; ?></td>
		</tr>
		<tr>
			<td>Urusan</td>
			<td><?php echo $dpa->kd_urusan.". ".$dpa->Nm_Urusan; ?></td>
		</tr>
		<tr>
			<td>Bidang Urusan</td>
			<td style="padding-left: 20px;"><?php echo $dpa->kd_bidang.". ".$dpa->Nm_Bidang; ?></td>
		</tr>
		<tr>
			<td>Program</td>
			<td style="padding-left: 43px;"><?php echo $dpa->kd_program.". ".$dpa->Ket_Program; ?></td>
		</tr>
		<tr>
			<td>Kegiatan</td>
			<td style="padding-left: 65px;"><?php echo $dpa->kd_kegiatan.". ".$dpa->nama_prog_or_keg; ?></td>
		</tr>
		<tr>
			<td>Indikator Kinerja</td>
			<td>
				<?php
					$ta=$this->m_settings->get_tahun_anggaran();
					$tahun_n1=0;
					$tahun_n1= $ta+1;
					$i=0;
					foreach ($indikator_kegiatan as $row_kegiatan) {
						$i++;
						echo $i .". ". $row_kegiatan->indikator ."<BR>";
						echo "Kategori indikator : $row_kegiatan->nama_status_indikator | $row_kegiatan->nama_kategori_indikator";
				?>
				<table class="table-common" width="100%">
					<tr>
						<td align="center"><?php echo $row_kegiatan->target." ".$row_kegiatan->nama_value; ?></td>
					</tr>
				</table>
				<hr>
				<?php
					}
				?>
		  </td>
		</tr>
		<tr>
			<td>Penanggung Jawab</td>
			<td><?php echo $dpa->penanggung_jawab; ?></td>
		</tr>
	</tbody>
</table>
<table class="table-common" width="100%">
	<tbody>
		<tr>
				<td colspan="4" align="center"><strong>Anggaran Kas</strong></td>
		</tr>
		<tr bgcolor="#CCCCCC">
			<td align="center" width="25%">Bulan 1</td>
			<td align="center" width="25%">Bulan 2</td>
			<td align="center" width="25%">Bulan 3</td>
			<td align="center" width="25%">Bulan 4</td>
		</tr>
		<tr>
			<td align="right">Rp. <?php echo Formatting::currency($dpa->nominal_1); ?></td>
			<td align="right">Rp. <?php echo Formatting::currency($dpa->nominal_2); ?></td>
      <td align="right">Rp. <?php echo Formatting::currency($dpa->nominal_3); ?></td>
      <td align="right">Rp. <?php echo Formatting::currency($dpa->nominal_4); ?></td>
		</tr>
		<tr bgcolor="#CCCCCC">
			<td align="center" width="25%">Bulan 5</td>
			<td align="center" width="25%">Bulan 6</td>
			<td align="center" width="25%">Bulan 7</td>
			<td align="center" width="25%">Bulan 8</td>
		</tr>
		<tr>
			<td align="right">Rp. <?php echo Formatting::currency($dpa->nominal_5); ?></td>
			<td align="right">Rp. <?php echo Formatting::currency($dpa->nominal_6); ?></td>
      <td align="right">Rp. <?php echo Formatting::currency($dpa->nominal_7); ?></td>
      <td align="right">Rp. <?php echo Formatting::currency($dpa->nominal_8); ?></td>
		</tr>
		<tr bgcolor="#CCCCCC">
			<td align="center" width="25%">Bulan 9</td>
			<td align="center" width="25%">Bulan 10</td>
			<td align="center" width="25%">Bulan 11</td>
			<td align="center" width="25%">Bulan 12</td>
		</tr>
		<tr>
			<td align="right">Rp. <?php echo Formatting::currency($dpa->nominal_9); ?></td>
			<td align="right">Rp. <?php echo Formatting::currency($dpa->nominal_10); ?></td>
      <td align="right">Rp. <?php echo Formatting::currency($dpa->nominal_11); ?></td>
      <td align="right">Rp. <?php echo Formatting::currency($dpa->nominal_12); ?></td>
		</tr>
		<tr>
				<td colspan="4" align="center"><strong>Rencana Aksi</strong></td>
		</tr>
		<tr bgcolor="#e2e2e2" >
			<td colspan="4" align="center">
				<table>
					<thead>
						<tr>
							<th>Bulan</th>
							<th>Aksi</th>
							<th>Bobot (%)</th>
							<th>Target</th>
						</tr>
					</thead>
					<tbody id="rcn_tabel">
						<?php if (!empty($rencana_aksi)) {
							foreach ($rencana_aksi as $row_rcn ) {
								echo "<tr>
									<td>".$row_rcn->bulan."</td>
									<td>".$row_rcn->aksi."</td>
									<td>".$row_rcn->bobot."</td>
									<td>".$row_rcn->target."</td>
								</tr>";
							}
						} ?>
					</tbody>
				</table>
			</td>
		</tr>

        <!-- <tr>
            <td colspan="4" align="center">Ukuran Kinerja Triwulan</td>
        </tr>
		<tr>
			<td align="left"><?php echo $dpa->catatan_1; ?></td>
			<td align="left" bgcolor="#CCCCCC"><?php echo $dpa->catatan_2; ?></td>
            <td align="left"><?php echo $dpa->catatan_3; ?></td>
            <td align="left" bgcolor="#CCCCCC"><?php echo $dpa->catatan_4; ?></td>
		</tr>
        <tr>
            <td colspan="4" align="center">Keterangan</td>
        </tr>
		<tr>
			<td align="left"><?php echo $dpa->ket_1; ?></td>
			<td align="left" bgcolor="#CCCCCC"><?php echo $dpa->ket_2; ?></td>
            <td align="left"><?php echo $dpa->ket_3; ?></td>
            <td align="left" bgcolor="#CCCCCC"><?php echo $dpa->ket_4; ?></td>
		</tr> -->
	</tbody>
</table>

<table style="display: none;">
	<tr>
		<td colspan="3" align="center">Uraian Belanja</td>
	</tr>
			<?php
			$jenis = NULL; $kategori = NULL; $subkategori = NULL; $kdbelanja = NULL; $uraianbelanja = NULL;

			foreach ($tahun1 as $rowth1) {
				if ($rowth1->kode_jenis_belanja == $jenis) {
					if ($rowth1->kode_kategori_belanja == $kategori) {
						if ($rowth1->kode_sub_kategori_belanja == $subkategori) {
							if ($rowth1->kode_belanja == $kdbelanja) {
								if ($rowth1->uraian_belanja == $uraianbelanja) {
									$volume = round($rowth1->volume);
									echo "<tr><td></td><td><div style='padding-left: 32px;'> - $rowth1->detil_uraian_belanja : $volume $rowth1->satuan x Rp. ".Formatting::currency($rowth1->nominal_satuan).
									" </div></td><td align='right' width='90'>".Formatting::currency($rowth1->subtotal)."</td></tr>";

								}else{
									$uraianbelanja = $rowth1->uraian_belanja; 					echo "<tr><td></td><td><div style='padding-left: 40px;'> $uraianbelanja </div></td><td></td></tr>";
									$volume = round($rowth1->volume);
									echo "<tr><td></td><td><div style='padding-left: 32px;'> - $rowth1->detil_uraian_belanja : $volume $rowth1->satuan x Rp. ".Formatting::currency($rowth1->nominal_satuan).
									" </div></td><td align='right' width='90'>".Formatting::currency($rowth1->subtotal)."</td></tr>";
								}
							}else{
								$kdbelanja = $rowth1->kode_belanja;									echo "<tr><td>5 . $jenisText . $kategori . $subkategori . $kdbelanja</td><td><div style='padding-left: 30px;'> $rowth1->belanja </div></td><td></td></tr>";
								$uraianbelanja = $rowth1->uraian_belanja; 					echo "<tr><td></td><td><div style='padding-left: 40px;'> $uraianbelanja </div></td><td></td></tr>";
								$volume = round($rowth1->volume);
								echo "<tr><td></td><td><div style='padding-left: 32px;'> - $rowth1->detil_uraian_belanja : $volume $rowth1->satuan x Rp. ".Formatting::currency($rowth1->nominal_satuan).
								" </div></td><td align='right' width='90'>".Formatting::currency($rowth1->subtotal)."</td></tr>";
							}
						}else{
							$subkategori = $rowth1->kode_sub_kategori_belanja; 	echo "<tr><td>5 . $jenisText . $kategori . $subkategori</td><td><div style='padding-left: 20px;'> $rowth1->subkategori </div></td><td></td></tr>";
							$kdbelanja = $rowth1->kode_belanja;									echo "<tr><td>5 . $jenisText . $kategori . $subkategori . $kdbelanja</td><td><div style='padding-left: 30px;'> $rowth1->belanja </div></td><td></td></tr>";
							$uraianbelanja = $rowth1->uraian_belanja; 					echo "<tr><td></td><td><div style='padding-left: 40px;'> $uraianbelanja </div></td><td></td></tr>";
							$volume = round($rowth1->volume);
							echo "<tr><td></td><td><div style='padding-left: 32px;'> - $rowth1->detil_uraian_belanja : $volume $rowth1->satuan x Rp. ".Formatting::currency($rowth1->nominal_satuan).
							" </div></td><td align='right' width='90'>".Formatting::currency($rowth1->subtotal)."</td></tr>";
						}
					}else{
						$kategori = $rowth1->kode_kategori_belanja; 				echo "<tr><td>5 . $jenisText . $kategori</td><td><b><div style='padding-left: 10px;'> $rowth1->kategori </div></b></td><td></td></tr>";
						$subkategori = $rowth1->kode_sub_kategori_belanja; 	echo "<tr><td>5 . $jenisText . $kategori . $subkategori</td><td><div style='padding-left: 20px;'> $rowth1->subkategori </div></td><td></td></tr>";
						$kdbelanja = $rowth1->kode_belanja;									echo "<tr><td>5 . $jenisText . $kategori . $subkategori . $kdbelanja</td><td><div style='padding-left: 30px;'> $rowth1->belanja </div></td><td></td></tr>";
						$uraianbelanja = $rowth1->uraian_belanja; 					echo "<tr><td></td><td><div style='padding-left: 40px;'> $uraianbelanja </div></td><td></td></tr>";
						$volume = round($rowth1->volume);
						echo "<tr><td></td><td><div style='padding-left: 32px;'> - $rowth1->detil_uraian_belanja : $volume $rowth1->satuan x Rp. ".Formatting::currency($rowth1->nominal_satuan).
						" </div></td><td align='right' width='90'>".Formatting::currency($rowth1->subtotal)."</td></tr>";
					}
				}else {

					$jenis = $rowth1->kode_jenis_belanja;								$jenisText = substr_replace($jenis,"", 0, -1);
					echo "<tr><td width='120px'>5 . $jenisText </td><td><b> $rowth1->jenis </b></td></tr>";
					$kategori = $rowth1->kode_kategori_belanja; 				echo "<tr><td>5 . $jenisText . $kategori</td><td><b><div style='padding-left: 10px;'> $rowth1->kategori </div></b></td><td></td></tr>";
					$subkategori = $rowth1->kode_sub_kategori_belanja; 	echo "<tr><td>5 . $jenisText . $kategori . $subkategori</td><td><div style='padding-left: 20px;'> $rowth1->subkategori </div></td><td></td></tr>";
					$kdbelanja = $rowth1->kode_belanja;									echo "<tr><td>5 . $jenisText . $kategori . $subkategori . $kdbelanja</td><td><div style='padding-left: 30px;'> $rowth1->belanja </div></td><td></td></tr>";
					$uraianbelanja = $rowth1->uraian_belanja; 					echo "<tr><td></td><td><div style='padding-left: 40px;'> $uraianbelanja </div></td><td></td></tr>";
					$volume = round($rowth1->volume);
					echo "<tr><td></td><td><div style='padding-left: 32px;'> - $rowth1->detil_uraian_belanja : $volume $rowth1->satuan x Rp. ".Formatting::currency($rowth1->nominal_satuan).
					" </div></td><td align='right' width='90'>".Formatting::currency($rowth1->subtotal)."</td></tr>";
				}
			}
			?>
</table>
</div>
