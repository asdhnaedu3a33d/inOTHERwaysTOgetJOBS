<tbody class="print-no-border">
	<tr>
		<td class="print-no-border">SKPD</td>
		<td class="print-no-border">:</td>
		<td class="print-no-border"><?php echo $skpd_visi->nama_skpd; ?></td>
	</tr>
	<tr>
		<td class="print-no-border">Visi</td>
		<td class="print-no-border">:</td>
		<td class="print-no-border"><?php echo $skpd_visi->visi; ?></td>
	</tr>
	<tr>
		<td class="print-no-border" valign="top">Misi</td>
		<td class="print-no-border" valign="top">:</td>
		<td class="print-no-border">
		<?php
			$i=0;
			foreach ($misi as $row) {
				$i++;
				echo $i.". ".$row->misi."<BR>";
			}
		?>
		</td>
	</tr>
	<tr>
		<td class="print-no-border" valign="top">Tujuan</td>
		<td class="print-no-border" valign="top">:</td>
		<td class="print-no-border">
		<?php
			$i=0;
			foreach ($tujuan as $row) {
				$i++;
				echo $i.". ".$row->tujuan."<BR>";
			}
		?>
		</td>
	</tr>
	<tr>
		<td class="print-no-border" valign="top">Sasaran</td>
		<td class="print-no-border" valign="top">:</td>
		<td>
			<?php echo $sasaran; ?>
		</td>
	</tr>
</tbody>
