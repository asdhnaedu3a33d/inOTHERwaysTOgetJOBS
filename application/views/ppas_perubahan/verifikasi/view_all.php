<script type="text/javascript">
	function veri_ppas(urusan, bidang, skpd){
		window.location = '<?php echo site_url("ppas_perubahan/veri")?>/' + urusan +'/'+ bidang +'/'+ skpd;
	}
</script>
<article class="module width_full">
	<header>
	  <h3>Verifikasi PPAS Perubahan Untuk Rancangan Awal (Ranwal)</h3>
	</header>
	<div class="module_content"; style="overflow:auto">
		<table id="ppas" class="table-common" style="width:99%" style="text-transform: uppercase;">
			<thead>
				<tr style="text-transform: uppercase;">
					<th colspan="4">Kode</th>
					<th>Urusan / Bidang Urusan / SKPD</th>
					<th>Anggaran <?php echo $th_anggaran; ?></th>
					<th>Pagu Indikatif <?php echo $th_anggaran + 1; ?></th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
		<?php
			foreach ($ppas as $row) {
		?>
				<tr style="font-weight: bold; background-color: #ddd; text-transform: uppercase;">
					<td align="center"><?php echo $row->kd; ?></td>
					<td align="center">&nbsp;</td>
					<td align="center">&nbsp;</td>
					<td align="center">&nbsp;</td>
					<td> <?php echo $row->nama; ?></td>
					<td align="right"><?php echo number_format($row->nom, 2, ',', '.'); ?></td>
					<td align="right"><?php echo number_format($row->nom_thndpn, 2, ',', '.'); ?></td>
					<td align="center"></td>
				</tr>
		<?php
				$ppas2 = $this->m_ppas_perubahan->get_all_bidang_ppas_veri($row->kd);
				foreach ($ppas2 as $row2) {
		?>
					<tr style="background-color: #eee; text-transform: uppercase;">
						<td align="center"><?php echo $row->kd; ?></td>
						<td align="center"><?php echo $row2->kd; ?></td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td><?php echo $row2->nama; ?></td>
						<td align="right"><?php echo number_format($row2->nom, 2, ',', '.'); ?></td>
						<td align="right"><?php echo number_format($row2->nom_thndpn, 2, ',', '.'); ?></td>
						<td align="center"></td>
					</tr>
		<?php
					$ppas3 = $this->m_ppas_perubahan->get_all_skpd_ppas_veri($row->kd, $row2->kd);
					foreach ($ppas3 as $row3) {
		?>
						<tr style=" text-transform: uppercase;">
							<td align="center"><?php echo $row->kd; ?></td>
							<td align="center"><?php echo $row2->kd; ?></td>
							<td align="center"><?php echo $row3->kd; ?></td>
							<td align="center">&nbsp;</td>
							<td><?php echo $row3->nama; ?></td>
							<td align="right"><?php echo number_format($row3->nom, 2, ',', '.'); ?></td>
							<td align="right"><?php echo number_format($row3->nom_thndpn, 2, ',', '.'); ?></td>
							<td align="center"><a href="javascript:void(0)" onclick="veri_ppas('<?php echo $row->kd; ?>', '<?php echo $row2->kd; ?>', '<?php echo $row3->kd; ?>')" class="icon-edit" title="Verifikasi PPAS"/></td>
						</tr>
		<?php
					}
				}
			}
		?>
			</tbody>
		</table>
	</div>
</article>
