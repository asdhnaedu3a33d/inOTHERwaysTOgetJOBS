<script type="text/javascript">
</script>
<article class="module width_full">
 	<header>
 		<h3>
		<!--<?php
			if (!empty($usulanpro)){
			    echo "Edit Data Usulan";
			} else {
			    echo "Input Data Usulan";
			}
		?>-->
		</h3>
 	</header>
 	<div class="module_content">
 		<!--<form action="<?php echo site_url('usulanpro/save');?>" method="POST" name="usulanpro" id="usulanpro" accept-charset="UTF-8" enctype="multipart/form-data" >
 			<input type="hidden" name="id_usulanpro" value="<?php if(!empty($usulanpro->id_musrenbang)){echo $usulanpro->id_musrenbang;} ?>" />-->
 			<table class="fcari" width="100%">
 				<tbody>
 					<tr>
 						<td width="20%">Group</td>
					</tr> 					
					<tr>
						<td>SKPD Tujuan</td>
						<td>
						</td>
					</tr>
					<tr style="background-color: white;">
						<td colspan="2"><hr></td>
					</tr>
					<tr>
						<td>Kecamatan Sasaran</td>
						<td>
						</td>
					</tr>
					<tr>
						<td>Nama Urusan</td>
						<td>
						</td>
					</tr>
					<tr>
						<td>Nama Bidang</td>
						<td>
						</td>
					</tr>
					<tr>
						<td>Nama Program</td>
						<td>
						</td>
					</tr>
					<tr>
						<td>Nama Kegiatan</td>
						<td>
						</td>
					</tr>
					<tr>
						<td>Jenis Pekerjaan</td>
						<td><input class="common" type="text" name="jenis_pekerjaan" </td>
					</tr>
					<tr>
						<td>Volume</td>
						<td><input class="common" type="text" name="volume" </td>
					</tr>
					<tr>
						<td>Satuan</td>
						<td><input class="common" type="text" name="satuan" </td>
					</tr>
					<tr>
						<td>Lokasi</td>
						<td><input class="common" type="text" name="lokasi" </td>
					</tr>
					<tr>
						<td>Sumber Dana</td>
						<td>
						</td>
					</tr>
					<tr>
						<td>Catatan</td>
						<td>
							<textarea class="common" name="catatan"></textarea>
						</td>
					</tr>					
 				</tbody>
 			</table>
 		</form> 		
 	</div> 	
 	<footer>
		<div class="submit_link">  
  			<input type='button' id="simpan" name="simpan" value='Simpan' />
  			<input type="button" value="Keluar" onclick="window.location.href='<?php echo site_url('usulanpro'); ?>'" />
		</div> 
	</footer>
</article>