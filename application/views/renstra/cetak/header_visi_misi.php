<tbody class="print-no-border">
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
	
	
</tbody>

