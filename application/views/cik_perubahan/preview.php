<table class="fcari" width="800px">
	<tbody>
		<tr>
			<td width="20%">SKPD</td>
			<td width="77%"><?php echo $cik->nama_skpd; ?></td>
		</tr>
		<tr>
			<td>Urusan</td>
			<td><?php echo $cik->kd_urusan.". ".$cik->Nm_Urusan; ?></td>
		</tr>		
		<tr>
			<td>Bidang Urusan</td>
			<td style="padding-left: 20px;"><?php echo $cik->kd_bidang.". ".$cik->Nm_Bidang; ?></td>
		</tr>
		<tr>
			<td>Program</td>
			<td style="padding-left: 43px;"><?php echo $cik->kd_program.". ".$cik->Ket_Program; ?></td>
		</tr>
		<tr>
			<td>Kegiatan</td>
			<td style="padding-left: 65px;"><?php echo $cik->kd_kegiatan.". ".$cik->nama_prog_or_keg; ?></td>
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
				?>
				<table class="table-common" width="100%">
					<tr>
						<th width="14%">Target</th>
					</tr>
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
			<td>Rencana Anggaran</td>
			<td>Rp. <?php echo Formatting::currency($cik->rencana); ?></td>
		</tr>
	</tbody>
</table>