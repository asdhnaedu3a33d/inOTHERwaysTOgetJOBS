<style type="text/css">
	.r-ranwal{
		background-color: #D0BE2D;
		padding-left: 5px;
	}

	.r-akhir{
		background-color: #96CC3F;
		padding-left: 5px;
	}
</style>
<header>
	<h3>
	 
  <?php echo "Jendela Kontrol Renja Perubahan ".$nama_skpd; ?></h3>
</header>
<div class="module_content"> 		
<table class="fcari" width="100%">
    <tbody>
        <tr>
            <td align="center" colspan="6">Proses</td>					
        </tr>
        <tr align="center">					
            <td colspan="3" class="r-ranwal">
                Rancangan Awal
            Renja</td>
            <td colspan="3" class="r-akhir">
                Renja Akhir</td>					
        </tr>
        <tr>
            <td width="25%" class="r-ranwal">Program & Kegiatan Baru</td>
            <td colspan="2" width="25%" class="r-ranwal"><?php echo $jendela_kontrol->baru; ?> Data</td>
            <td width="25%" class="r-akhir">Program & Kegiatan Baru</td>
            <td colspan="2" width="25%" class="r-akhir"><?php echo $jendela_kontrol->baru2; ?> Data</td>
        </tr>
        <tr>
            <td class="r-ranwal">Program & Kegiatan Telah dikirim</td>
            <td colspan="2" class="r-ranwal"><?php echo $jendela_kontrol->kirim; ?> Data</td>
            <td class="r-akhir">Program & Kegiatan Telah dikirim</td>
            <td colspan="2" class="r-akhir"><?php echo $jendela_kontrol->kirim2; ?> Data</td>
        </tr>				
        <tr>
            <td class="r-ranwal">Program & Kegiatan Diproses</td>
            <td colspan="2" class="r-ranwal"><?php echo $jendela_kontrol->proses; ?> Data</td>
            <td class="r-akhir">Program & Kegiatan Diproses</td>
            <td colspan="2" class="r-akhir"><?php echo $jendela_kontrol->proses2; ?> Data</td>
        </tr>				
        <tr>
            <td class="r-ranwal"></td>
            <td width="7%" class="r-ranwal">Revisi</td>
            <td class="r-ranwal">
                <?php echo $jendela_kontrol->revisi; ?> Data 
                <?php 
                    if(!empty($jendela_kontrol->revisi)){ 
                ?>
                    <a class="revisi" id-r="<?php echo $renja->id; ?>" idR="revisi_ranwal" href="#" style="font-style: italic;">Lihat</a>
                <?php 
                    }
                ?>							
            </td>
            
            <td class="r-akhir"></td>					
            <td width="7%" class="r-akhir">Revisi</td>
            <td class="r-akhir">
                <?php echo $jendela_kontrol->revisi2; ?> Data 						
                <?php 
                    if (!empty($jendela_kontrol->revisi2)) {
                ?>
                    <a class="revisi" id-r="<?php echo $renja->id; ?>" idR="revisi_akhir" href="#" style="font-style: italic;">Lihat</a>
                <?php 
                    }
                ?>
            </td>
        </tr>
        <tr>
            <td class="r-ranwal"></td>
            <td class="r-ranwal">Diterima</td>
            <td class="r-ranwal"><?php echo $jendela_kontrol->veri; ?> Data</td>
            
            <td class="r-akhir"></td>
            <td class="r-akhir">Diterima</td>
            <td class="r-akhir"><?php echo $jendela_kontrol->veri2; ?> Data</td>
        </tr>			
    </tbody>
</table>
<table style="font-style: italic; color: #666;">
    <tr>
        <td colspan="2">*Keterangan:</td>				
    </tr>
    <tr>
        <td valign="top">1. </td>
        <td>Apabila renja telah dikirim, maka fitur manajemen renja perubahan seperti edit akan di non-aktiifkan.</td>
    </tr>
    <tr>
        <td valign="top">2. </td>
        <td>Fitur manajemen renja seperti edit akan aktif kembali apabila renja perubahan telah diproses baik tidak disetujui maupun disetujui.</td>
    </tr>
    <tr>
        <td valign="top">2. </td>
        <td>Fitur manajemen renja perubahan seperti edit akan aktif pula apabila terdapat revisi sehingga renja perubahan unit yang bersangkutan juga harus direvisi/dirubah.</td>
    </tr>		
</table></div> 	
<footer>
	<div class="submit_link">
    <?php if (!$ada_renja) {?>
    	<input type="button" class="button-action" id="get_renja" value="Ambil Data DPA" />
    <?php }?>
    <a href="<?php echo site_url('renja_perubahan/preview_renja'); ?>"><input type="button" value="Lihat Renja Perubahan" /></a>
	<?php
		if (!empty($jendela_kontrol->baru) || !empty($jendela_kontrol->baru2)){
	?>
		<input type="button" id="kirim_renja" value="Kirim Renja Perubahan" />
	<?php
		}
	?>		
	  	<input type="button" class="button-action" id="cetak" value="Cetak" />
	 	<input type="button" value="Back" onclick="history.go(-1)" />
	</div> 
</footer>