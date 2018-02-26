<table class="fcari" width="800px">
	<tbody>		
		<tr>
			<td width="20%">Bidang Urusan</td>
			<td width="77%"><?php echo $rpjmd->nm_bidang; ?></td>
		</tr>
		<tr>
			<td>SKPD</td>
			<td><?php echo $rpjmd->nama_skpd; ?></td>
		</tr>
		<tr>
			<td>Kode</td>
			<td><?php echo $rpjmd->kd_urusan.". ".$rpjmd->kd_bidang.". ".$rpjmd->kd_program; ?></td>
		</tr>
		<tr>
			<td>Nama Program</td>
			<td><?php echo $rpjmd->nama_prog_or_keg; ?></td>
		</tr>		
		<tr>
			<td>Indikator Kinerja</td>
			<td>
				<?php  
					$i=0;
					foreach ($indikator_program as $row1) {
						$i++;
						echo $i .". ". $row1->indikator ."<BR>";
					}
				?>
			</td>
		</tr>
		<tr>
			<td>Kondisi Awal</td>
			<td><?php echo $rpjmd->kondisi_awal." ".$rpjmd->nama_value; ?></td>
		</tr>
		<tr>
			<td>Target Kondisi Akhir</td>
			<td><?php echo $rpjmd->target_kondisi_akhir." ".$rpjmd->nama_value; ?></td>
		</tr>
		<tr style="background-color: white;">			
			<td colspan="2"><hr></td>
		</tr>		
	</tbody>
</table>
<table class="fcari" width="800px">
	<tbody>	
		<tr>
			<th>Target 1 (<?php echo $rpjmd->nama_value; ?>)</th>
			<th>Target 2 (<?php echo $rpjmd->nama_value; ?>)</th>
			<th>Target 3 (<?php echo $rpjmd->nama_value; ?>)</th>
			<th>Target 4 (<?php echo $rpjmd->nama_value; ?>)</th>
			<th>Target 5 (<?php echo $rpjmd->nama_value; ?>)</th>
		</tr>
		<tr>
			<td align="center"><?php echo $rpjmd->target_1; ?></td>
			<td align="center"><?php echo $rpjmd->target_2; ?></td>
			<td align="center"><?php echo $rpjmd->target_3; ?></td>
			<td align="center"><?php echo $rpjmd->target_4; ?></td>
			<td align="center"><?php echo $rpjmd->target_5; ?></td>
		</tr>
		<tr>
			<th>Nominal 1</th>
			<th>Nominal 2</th>
			<th>Nominal 3</th>
			<th>Nominal 4</th>
			<th>Nominal 5</th>			
		</tr>
		<tr>
			<td align="right">Rp. <?php echo Formatting::currency($rpjmd->nominal_1_pro); ?></td>
			<td align="right">Rp. <?php echo Formatting::currency($rpjmd->nominal_2_pro); ?></td>
			<td align="right">Rp. <?php echo Formatting::currency($rpjmd->nominal_3_pro); ?></td>
			<td align="right">Rp. <?php echo Formatting::currency($rpjmd->nominal_4_pro); ?></td>
			<td align="right">Rp. <?php echo Formatting::currency($rpjmd->nominal_5_pro); ?></td>
		</tr>		
	</tbody>
</table>