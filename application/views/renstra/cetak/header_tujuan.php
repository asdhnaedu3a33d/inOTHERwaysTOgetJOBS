<thead>

	<tr>
		<th>No</th>
		<th>Tujuan</th>
		<th>Indikator Kinerja</th>
		<th>Kondisi Awal</th>
		<th>Tahun I</th>
		<th>Tahun II</th>
		<th>Tahun III</th>
		<th>Tahun IV</th>
		<th>Tahun V</th>
		<th>Tahun Akhir</th>
	</tr>
</thead>
<tbody>
<?php
	$y=1;
	foreach ($tujuan as $row) {
		?>
	<tr>
		<td align="center"><?php echo $y; ?></td>
		<td><?php echo $row->tujuan; ?></td>
		<?php
		

		$indikator = $this->m_renstra_trx->get_indikator_tujuan_cetak($row->id);


		
		?>
		<td>
		<?php 
		$i=1;
			foreach ($indikator as $row1) {
				

				echo $y .". ". $row1->indikator ."<BR>";
				$i++;
			}

				?>

		</td> 

		<td>
			<?php 
			foreach ($indikator as $row1) {
				 echo number_format($row1->kondisi_awal)." ".$row1->nama_value."<BR>"; 
			}

		?>
		</td>
		<td>
			<?php 
			foreach ($indikator as $row1) {
				 echo number_format($row1->target_1)." ".$row1->nama_value."<BR>"; 
			}

		?>
		</td>
		<td>
			<?php 
			foreach ($indikator as $row1) {
				 echo number_format($row1->target_2)." ".$row1->nama_value."<BR>"; 
			}

		?>
		</td>
		<td>
			<?php 
			foreach ($indikator as $row1) {
				 echo number_format($row1->target_3)." ".$row1->nama_value."<BR>"; 
			}

		?>
		</td>
		<td>
			<?php 
			foreach ($indikator as $row1) {
				 echo number_format($row1->target_4)." ".$row1->nama_value."<BR>"; 
			}

		?>
		</td>
		<td>
			<?php 
			foreach ($indikator as $row1) {
				 echo number_format($row1->target_5)." ".$row1->nama_value."<BR>"; 
			}

		?>
		</td>
		<td>
			<?php 
			foreach ($indikator as $row1) {
				 echo number_format($row1->kondisi_akhir)." ".$row1->nama_value."<BR>"; 
			}

		?>
		</td>
	</tr>

	
<?php
$y++;
	}
	
?>
</tbody>
