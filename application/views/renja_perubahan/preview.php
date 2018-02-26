<div style="width:1200">
<table class="fcari" width="800px">
	<tbody>
		<tr>
			<td width="20%">SKPD</td>
			<td width="77%"><?php echo $renja->nama_skpd; ?></td>
		</tr>
		<tr>
			<td>Urusan</td>
			<td><?php echo $renja->kd_urusan.". ".$renja->Nm_Urusan; ?></td>
		</tr>
		<tr>
			<td>Bidang Urusan</td>
			<td style="padding-left: 20px;"><?php echo $renja->kd_bidang.". ".$renja->Nm_Bidang; ?></td>
		</tr>
		<tr>
			<td>Program</td>
			<td style="padding-left: 43px;"><?php echo $renja->kd_program.". ".$renja->Ket_Program; ?></td>
		</tr>
		<tr>
			<td>Kegiatan</td>
			<td style="padding-left: 65px;"><?php echo $renja->kd_kegiatan.". ".$renja->nama_prog_or_keg; ?></td>
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
						echo "Kategori indikator : $row_kegiatan->nama_status_indikator | $row_kegiatan->nama_kategori_indikator  <BR>";
				?>
				<table class="table-common" width="100%">
					<tr>
						<th width="14%">Target <?php echo $ta?></th>
						<th width="14%">Target <?php echo $tahun_n1?></th>
					</tr>
					<tr>
						<td align="center"><?php echo $row_kegiatan->target." ".$row_kegiatan->nama_value; ?></td>
						<td align="center"><?php echo $row_kegiatan->target_thndpn." ".$row_kegiatan->nama_value; ?></td>
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
			<td><?php echo $renja->penanggung_jawab; ?></td>
		</tr>
	</tbody>
</table>
<table class="table-common" width="100%">
	<tbody>
		<tr>
			<th width="50%">Pagu Indikatif <?php echo $ta?></th>
			<th>Pagu Indikatif <?php echo $tahun_n1?></th>
		</tr>
		<tr>
			<td align="right">Rp. <?php echo Formatting::currency($renja->nominal); ?></td>
			<td align="right">Rp. <?php echo Formatting::currency($renja->nominal_thndpn); ?></td>
		</tr>
		<tr>
			<th>Lokasi <?php echo $ta?></th>
			<th>Lokasi <?php echo $tahun_n1?></th>
		</tr>
		<tr>
			<td align="left"><?php echo $renja->lokasi; ?></td>
			<td align="left"><?php echo $renja->lokasi_thndpn; ?></td>
		</tr>
		<tr>
			<th>Uraian Kegiatan <?php echo $ta?></th>
			<th>Uraian Kegiatan <?php echo $tahun_n1?></th>
		</tr>
		<tr>
			<td align="left"><?php echo $renja->catatan; ?></td>
			<td align="left"><?php echo $renja->catatan_thndpn; ?></td>
		</tr>
        <tr>
        	<th colspan="2">Keterangan Perubahan </th>
        </tr>
        <tr>
        	<td colspan="2"  align="left"><?php echo $renja->keterangan;?></td>
        </tr>
        <tr>
			<th>Uraian Belanja <?php echo $ta?></th>
			<th>Uraian Belanja <?php echo $tahun_n1?></th>
		</tr>
    <tr >
			<td align="left">
				<table>
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
										" </div></td><td align='right' width='90'>Rp. ".Formatting::currency($rowth1->subtotal)."</td></tr>";

									}else{
										$uraianbelanja = $rowth1->uraian_belanja; 					echo "<tr><td></td><td><div style='padding-left: 40px;'> $uraianbelanja </div></td><td></td></tr>";
										$volume = round($rowth1->volume);
										echo "<tr><td></td><td><div style='padding-left: 32px;'> - $rowth1->detil_uraian_belanja : $volume $rowth1->satuan x Rp. ".Formatting::currency($rowth1->nominal_satuan).
										" </div></td><td align='right' width='90'>Rp. ".Formatting::currency($rowth1->subtotal)."</td></tr>";
									}
								}else{
									$kdbelanja = $rowth1->kode_belanja;									echo "<tr><td>5 . $jenisText . $kategori . $subkategori . $kdbelanja</td><td><div style='padding-left: 30px;'> $rowth1->belanja </div></td><td></td></tr>";
									$uraianbelanja = $rowth1->uraian_belanja; 					echo "<tr><td></td><td><div style='padding-left: 40px;'> $uraianbelanja </div></td><td></td></tr>";
									$volume = round($rowth1->volume);
									echo "<tr><td></td><td><div style='padding-left: 32px;'> - $rowth1->detil_uraian_belanja : $volume $rowth1->satuan x Rp. ".Formatting::currency($rowth1->nominal_satuan).
									" </div></td><td align='right' width='90'>Rp. ".Formatting::currency($rowth1->subtotal)."</td></tr>";
								}
							}else{
								$subkategori = $rowth1->kode_sub_kategori_belanja; 	echo "<tr><td>5 . $jenisText . $kategori . $subkategori</td><td><div style='padding-left: 20px;'> $rowth1->subkategori </div></td><td></td></tr>";
								$kdbelanja = $rowth1->kode_belanja;									echo "<tr><td>5 . $jenisText . $kategori . $subkategori . $kdbelanja</td><td><div style='padding-left: 30px;'> $rowth1->belanja </div></td><td></td></tr>";
								$uraianbelanja = $rowth1->uraian_belanja; 					echo "<tr><td></td><td><div style='padding-left: 40px;'> $uraianbelanja </div></td><td></td></tr>";
								$volume = round($rowth1->volume);
								echo "<tr><td></td><td><div style='padding-left: 32px;'> - $rowth1->detil_uraian_belanja : $volume $rowth1->satuan x Rp. ".Formatting::currency($rowth1->nominal_satuan).
								" </div></td><td align='right' width='90'>Rp. ".Formatting::currency($rowth1->subtotal)."</td></tr>";
							}
						}else{
							$kategori = $rowth1->kode_kategori_belanja; 				echo "<tr><td>5 . $jenisText . $kategori</td><td><b><div style='padding-left: 10px;'> $rowth1->kategori </div></b></td><td></td></tr>";
							$subkategori = $rowth1->kode_sub_kategori_belanja; 	echo "<tr><td>5 . $jenisText . $kategori . $subkategori</td><td><div style='padding-left: 20px;'> $rowth1->subkategori </div></td><td></td></tr>";
							$kdbelanja = $rowth1->kode_belanja;									echo "<tr><td>5 . $jenisText . $kategori . $subkategori . $kdbelanja</td><td><div style='padding-left: 30px;'> $rowth1->belanja </div></td><td></td></tr>";
							$uraianbelanja = $rowth1->uraian_belanja; 					echo "<tr><td></td><td><div style='padding-left: 40px;'> $uraianbelanja </div></td><td></td></tr>";
							$volume = round($rowth1->volume);
							echo "<tr><td></td><td><div style='padding-left: 32px;'> - $rowth1->detil_uraian_belanja : $volume $rowth1->satuan x Rp. ".Formatting::currency($rowth1->nominal_satuan).
							" </div></td><td align='right' width='90'>Rp. ".Formatting::currency($rowth1->subtotal)."</td></tr>";
						}
					}else {


						$jenis = $rowth1->kode_jenis_belanja;								$jenisText = substr_replace($jenis,"", 0, -1);
						echo "<tr><td width='90'>5 . $jenisText </td><td><b> $rowth1->jenis </b></td></tr>";
						$kategori = $rowth1->kode_kategori_belanja; 				echo "<tr><td>5 . $jenisText . $kategori</td><td><b><div style='padding-left: 10px;'> $rowth1->kategori </div></b></td><td></td></tr>";
						$subkategori = $rowth1->kode_sub_kategori_belanja; 	echo "<tr><td>5 . $jenisText . $kategori . $subkategori</td><td><div style='padding-left: 20px;'> $rowth1->subkategori </div></td><td></td></tr>";
						$kdbelanja = $rowth1->kode_belanja;									echo "<tr><td>5 . $jenisText . $kategori . $subkategori . $kdbelanja</td><td><div style='padding-left: 30px;'> $rowth1->belanja </div></td><td></td></tr>";
						$uraianbelanja = $rowth1->uraian_belanja; 					echo "<tr><td></td><td><div style='padding-left: 40px;'> $uraianbelanja </div></td><td></td></tr>";
						$volume = round($rowth1->volume);
						echo "<tr><td></td><td><div style='padding-left: 32px;'> - $rowth1->detil_uraian_belanja : $volume $rowth1->satuan x Rp. ".Formatting::currency($rowth1->nominal_satuan).
						" </div></td><td align='right' width='90'>Rp. ".Formatting::currency($rowth1->subtotal)."</td></tr>";
					}
				}
			 	?>
		 	 	</table>
		 	</td>
			<td align="left">
				<table>
				<?php
				$jenis = NULL; $kategori = NULL; $subkategori = NULL; $kdbelanja = NULL; $uraianbelanja = NULL;

				foreach ($tahun2 as $rowth2) {
					if ($rowth2->kode_jenis_belanja == $jenis) {
						if ($rowth2->kode_kategori_belanja == $kategori) {
							if ($rowth2->kode_sub_kategori_belanja == $subkategori) {
								if ($rowth2->kode_belanja == $kdbelanja) {
									if ($rowth2->uraian_belanja == $uraianbelanja) {
										$volume = round($rowth2->volume);
										echo "<tr><td></td><td><div style='padding-left: 32px;'> - $rowth2->detil_uraian_belanja : $volume $rowth2->satuan x Rp. ".Formatting::currency($rowth2->nominal_satuan).
										" </div></td><td align='right' width='90'>Rp. ".Formatting::currency($rowth2->subtotal)."</td></tr>";

									}else{
										$uraianbelanja = $rowth2->uraian_belanja; 					echo "<tr><td></td><td><div style='padding-left: 40px;'> $uraianbelanja </div></td><td></td></tr>";
										$volume = round($rowth2->volume);
										echo "<tr><td></td><td><div style='padding-left: 32px;'> - $rowth2->detil_uraian_belanja : $volume $rowth2->satuan x Rp. ".Formatting::currency($rowth2->nominal_satuan).
										" </div></td><td align='right' width='90'>Rp. ".Formatting::currency($rowth2->subtotal)."</td></tr>";
									}
								}else{
									$kdbelanja = $rowth2->kode_belanja;									echo "<tr><td>5 . $jenisText . $kategori . $subkategori . $kdbelanja</td><td><div style='padding-left: 30px;'> $rowth2->belanja </div></td><td></td></tr>";
									$uraianbelanja = $rowth2->uraian_belanja; 					echo "<tr><td></td><td><div style='padding-left: 40px;'> $uraianbelanja </div></td><td></td></tr>";
									$volume = round($rowth2->volume);
									echo "<tr><td></td><td><div style='padding-left: 32px;'> - $rowth2->detil_uraian_belanja : $volume $rowth2->satuan x Rp. ".Formatting::currency($rowth2->nominal_satuan).
									" </div></td><td align='right' width='90'>Rp. ".Formatting::currency($rowth2->subtotal)."</td></tr>";
								}
							}else{
								$subkategori = $rowth2->kode_sub_kategori_belanja; 	echo "<tr><td>5 . $jenisText . $kategori . $subkategori</td><td><div style='padding-left: 20px;'> $rowth2->subkategori </div></td><td></td></tr>";
								$kdbelanja = $rowth2->kode_belanja;									echo "<tr><td>5 . $jenisText . $kategori . $subkategori . $kdbelanja</td><td><div style='padding-left: 30px;'> $rowth2->belanja </div></td><td></td></tr>";
								$uraianbelanja = $rowth2->uraian_belanja; 					echo "<tr><td></td><td><div style='padding-left: 40px;'> $uraianbelanja </div></td><td></td></tr>";
								$volume = round($rowth2->volume);
								echo "<tr><td></td><td><div style='padding-left: 32px;'> - $rowth2->detil_uraian_belanja : $volume $rowth2->satuan x Rp. ".Formatting::currency($rowth2->nominal_satuan).
								" </div></td><td align='right' width='90'>Rp. ".Formatting::currency($rowth2->subtotal)."</td></tr>";
							}
						}else{
							$kategori = $rowth2->kode_kategori_belanja; 				echo "<tr><td>5 . $jenisText . $kategori</td><td><b><div style='padding-left: 10px;'> $rowth2->kategori </div></b></td><td></td></tr>";
							$subkategori = $rowth2->kode_sub_kategori_belanja; 	echo "<tr><td>5 . $jenisText . $kategori . $subkategori</td><td><div style='padding-left: 20px;'> $rowth2->subkategori </div></td><td></td></tr>";
							$kdbelanja = $rowth2->kode_belanja;									echo "<tr><td>5 . $jenisText . $kategori . $subkategori . $kdbelanja</td><td><div style='padding-left: 30px;'> $rowth2->belanja </div></td><td></td></tr>";
							$uraianbelanja = $rowth2->uraian_belanja; 					echo "<tr><td></td><td><div style='padding-left: 40px;'> $uraianbelanja </div></td><td></td></tr>";
							$volume = round($rowth2->volume);
							echo "<tr><td></td><td><div style='padding-left: 32px;'> - $rowth2->detil_uraian_belanja : $volume $rowth2->satuan x Rp. ".Formatting::currency($rowth2->nominal_satuan).
							" </div></td><td align='right' width='90'>Rp. ".Formatting::currency($rowth2->subtotal)."</td></tr>";
						}
					}else {


						$jenis = $rowth2->kode_jenis_belanja;								$jenisText = substr_replace($jenis,"", 0, -1);
						echo "<tr><td width='90'>5 . $jenisText </td><td><b> $rowth2->jenis </b></td></tr>";
						$kategori = $rowth2->kode_kategori_belanja; 				echo "<tr><td>5 . $jenisText . $kategori</td><td><b><div style='padding-left: 10px;'> $rowth2->kategori </div></b></td><td></td></tr>";
						$subkategori = $rowth2->kode_sub_kategori_belanja; 	echo "<tr><td>5 . $jenisText . $kategori . $subkategori</td><td><div style='padding-left: 20px;'> $rowth2->subkategori </div></td><td></td></tr>";
						$kdbelanja = $rowth2->kode_belanja;									echo "<tr><td>5 . $jenisText . $kategori . $subkategori . $kdbelanja</td><td><div style='padding-left: 30px;'> $rowth2->belanja </div></td><td></td></tr>";
						$uraianbelanja = $rowth2->uraian_belanja; 					echo "<tr><td></td><td><div style='padding-left: 40px;'> $uraianbelanja </div></td><td></td></tr>";
						$volume = round($rowth2->volume);
						echo "<tr><td></td><td><div style='padding-left: 32px;'> - $rowth2->detil_uraian_belanja : $volume $rowth2->satuan x Rp. ".Formatting::currency($rowth2->nominal_satuan).
						" </div></td><td align='right' width='90'>Rp. ".Formatting::currency($rowth2->subtotal)."</td></tr>";
					}
				}
			 	?>
		 	 	</table>
			</td>
		</tr>
	</tbody>
</table>
</div>
