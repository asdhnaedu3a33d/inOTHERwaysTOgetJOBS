<script type="text/javascript">
</script>

<article class="module width_full">
	<div class="module_content">
		<table class="fcari" width="100%">
			<tbody>
 					<tr>
 						<td width="20%">SKPD</td>
 						<td width="80%"><!--<?php echo $skpd->nama_skpd; ?></td>-->
					</tr>
					<tr>
 						<td>Bidang Koordinasi</td>
 						<td><!--<?php echo $skpd->nama_koor; ?></td>-->
					</tr>
					<tr>
						<td>Kode Urusan</td>
						<td>
						</td>
					</tr>
					<tr>
						<td>Kode Bidang Urusan</td>
						<td>
						</td>
					</tr>
					<tr>
						<td>Kode Program</td>
						<td>
						</td>
					</tr>
					<tr>
						<td>Kode Kegiatan</td>
						<td>
						</td>
					</tr>
					<tr>
						<td>Lokasi</td>
						<td></td>
					</tr>
					<tr>
						<td>Volume</td>
						<td></td>
					</tr>
					<tr>
						<td>Nominal 1</td>
						<td></td>
					</tr>
					<tr>
						<td>Tri Wulan 1</td>
						<td></td>
					</tr>
					<tr>
						<td>Nominal 2</td>
						<td></td>
					</tr>
					<tr>
						<td>Tri Wulan 2</td>
						<td></td>
					</tr>
					<tr>
						<td>Nominal 3</td>
						<td></td>
					</tr>
					<tr>
						<td>Tri Wulan 3</td>
						<td></td>
					</tr><tr>
						<td>Nominal 4</td>
						<td></td>
					</tr>
					<tr>
						<td>Tri Wulan 4</td>
						<td></td>
					</tr>
					<tr>
						<input type="radio" name="id_status"
							<?php if (isset($id_status) && $id_status=="1") echo "checked";?>
						value="1">Terverifikasi
					</tr>
 				</tbody>
		</table>
	</div>
	<footer>
		<div class="submit_link">  
  			<input type='button' id="keluar" name="keluar" value='Keluar' />
		</div> 
	</footer>
</article>