<script type="text/javascript">
</script>

<article class="module width_full">
	<header>
 		<h3>
		<?php
			if (isset($isEdit) && $isEdit){
			    echo "Edit Data RKPD";
			} else{
			    echo "Input Data RKPD";
			}
		?>
		</h3>
 	</header>
 	<div class="module_content">
 		<form action="<?php echo site_url('rkpd/save');?>" method="POST" name="rkpd" id="rkpd" accept-charset="UTF-8" enctype="multipart/form-data" >
 			<input type="hidden" name="id_rkpd" value="<?php if(!empty($rkpd->id)){echo $rkpd->id;} ?>" />
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
							<input type="text" id="Kd_Urusan_autocomplete" name="Kd_Urusan_autocomplete" class="common" value="<?php if(!empty($rkpd->nm_urusan)){echo $rkpd->nm_urusan;} ?>" />
	            			<input type="hidden" id="Kd_Urusan" name="kd_urusan" value="<?php if(!empty($rkpd->kd_urusan)){echo $rkpd->kd_urusan;} ?>"/>            
            			</td>
					</tr>
					<tr>
						<td>Kode Bidang Urusan</td>
						<td>
							<input type="text" id="Kd_Bidang_autocomplete" name="Kd_Bidang_autocomplete" class="common" value="<?php if(!empty($rkpd->nm_bidang)){echo $rkpd->nm_bidang;} ?>" />
          					<input type="hidden" id="Kd_Bidang" name="kd_bidang" value="<?php if(!empty($rkpd->kd_bidang)){echo $rkpd->kd_bidang;} ?>"/>
						</td>
					</tr>
					<tr>
						<td>Kode Program</td>
						<td>
							<input type="text" id="Kd_Prog_autocomplete" name="Kd_Prog_autocomplete" class="common" value="<?php if(!empty($rkpd->ket_program)){echo $rkpd->ket_program;} ?>"/>
          					<input type="hidden" id="Kd_Prog" name="kd_program" value="<?php if(!empty($rkpd->kd_program)){echo $rkpd->kd_program;} ?>"/>
						</td>
					</tr>
					<tr>
						<td>Kode Kegiatan</td>
						<td>
							<input type="text" id="Kd_Keg_autocomplete" name="Kd_Keg_autocomplete" class="common" value="<?php if(!empty($rkpd->ket_kegiatan)){echo $rkpd->ket_kegiatan;} ?>"/>
          					<input type="hidden" id="Kd_Keg" name="kd_kegiatan" value="<?php if(!empty($rkpd->kd_kegiatan)){echo $rkpd->kd_kegiatan;} ?>"/>            
						</td>
					</tr>
					<tr>
						<td>Lokasi</td>
						<td><input class="common" type="text" name="lokasi" /></td>
					</tr>
					<tr>
						<td>Volume</td>
						<td><input class="common" type="text" name="volume" /></td>
					</tr>
					<tr>
						<td>Nominal 1</td>
						<td><input class="common" type="text" name="nominal_1" /></td>
					</tr>
					<tr>
						<td>Tri Wulan 1</td>
						<td><input class="common" type="text" name="tw_1" /></td>
					</tr>
					<tr>
						<td>Nominal 2</td>
						<td><input class="common" type="text" name="nominal_2" /></td>
					</tr>
					<tr>
						<td>Tri Wulan 2</td>
						<td><input class="common" type="text" name="tw_2" /></td>
					</tr>
					<tr>
						<td>Nominal 3</td>
						<td><input class="common" type="text" name="nominal_3" /></td>
					</tr>
					<tr>
						<td>Tri Wulan 3</td>
						<td><input class="common" type="text" name="tw_3" /></td>
					</tr><tr>
						<td>Nominal 4</td>
						<td><input class="common" type="text" name="nominal_4" /></td>
					</tr>
					<tr>
						<td>Tri Wulan 4</td>
						<td><input class="common" type="text" name="tw_4" /></td>
					</tr>
					<tr>
						<input type="radio" name="id_status"
							<?php if (isset($id_status) && $id_status=="1") echo "checked";?>
						value="1">Terverifikasi
					</tr>
 				</tbody>
 			</table>
 		</form>
 	</div>
 	<footer>
		<div class="submit_link">  
  			<input type='button' id="simpan" name="simpan" value='Simpan' />
  			<input type="button" value="Keluar" onclick="window.location.href='<?php echo site_url('rkpd'); ?>'" />
		</div> 
	</footer>
</article>