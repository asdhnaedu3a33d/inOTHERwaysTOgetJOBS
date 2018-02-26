<script type="text/javascript">
var element_program;
$(document).ready(function(){

	$(".add-kinerja-triwulan").click(function(){
		var idr = $(this).parent().parent().attr("id-r");

		prepare_facebox();
		$.blockUI({
			css: window._css,
			overlayCSS: window._ovcss
		});
		$.ajax({
			type: "POST",
			url: '<?php echo site_url("kendali_perubahan/add_kinerja_triwulan"); ?>',
			data: { id_dpa_prog_keg: $(this).attr("idP"),id_triwulan: $(this).attr("idT")},
			success: function(msg){
				if (msg!="") {
					$.facebox(msg);
					$.blockUI({
						timeout: 2000,
						css: window._css,
						overlayCSS: window._ovcss
					});
				};
			}
		});
	});

	$(".edit-kinerja-triwulan").click(function(){
		var idr = $(this).parent().parent().attr("id-r");

		prepare_facebox();
		$.blockUI({
			css: window._css,
			overlayCSS: window._ovcss
		});
		$.ajax({
			type: "POST",
			url: '<?php echo site_url("kendali_perubahan/edit_kinerja_triwulan"); ?>',
			data: { id_dpa_prog_keg: $(this).attr("idP"),id_triwulan: $(this).attr("idT")},
			success: function(msg){
				if (msg!="") {
					$.facebox(msg);
					$.blockUI({
						timeout: 2000,
						css: window._css,
						overlayCSS: window._ovcss
					});
				};
			}
		});
	});

	$(".see_revisi").click(function(){
		var id = $(this).attr("id-d");

		prepare_facebox();
		$.blockUI({
			css: window._css,
			overlayCSS: window._ovcss
		});
		$.ajax({
			type: "POST",
			url: '<?php echo site_url("kendali_perubahan/show_revisi"); ?>',
			data: {id: id},
			success: function(msg){
				if (msg!="") {
					$.facebox(msg);
					$.blockUI({
						timeout: 2000,
						css: window._css,
						overlayCSS: window._ovcss
					});
				};
			}
		});
	});
});

</script>



<?php
	$tahun_sekarang=$this->session->userdata('t_anggaran_aktif');
	$nama_skpd = $this->session->userdata('nama_skpd');
	$max_col_keg=1;
?>
<article class="module width_full" style="width: 130%; margin-left: -15%;">
	<header>
		<h3>Tabel Kendali Pelaksanaan Kegiatan Belanja Langsung Perubahan</h3>
	</header>
    <div class="module_content" style="overflow:auto">
	<div class="form-group text-right">
		<a href="<?php echo site_url('kendali_perubahan/kirim_veri') ?>"><input style="margin: 3px 10px 0px 0px;" type="button" value="Kirim Kendali Belanja" /></a>
		<a href="<?php echo site_url('kendali_perubahan/do_cetak_kendali_belanja') ?>"><input style="margin: 3px 10px 0px 0px;" type="button" value="Cetak Kendali Belanja" /></a>
    	<a href="<?php echo site_url('kendali_perubahan/preview_kendali_belanja') ?>"><input style="margin: 3px 10px 0px 0px;" type="button" value="Lihat Kendali Belanja" /></a>
	</div>
  <div class="scroll">
  	<table id="kendali_skpd" class="table-display tablesorter" style="width:99.7%" >
  		<thead>
  				<tr>
  					<th rowspan="3" colspan="4">Kode</th>
  					<th rowspan="3" >Program dan Kegiatan</th>
  					<th rowspan="3" >Kriteria Keberhasilan</th>
  					<th rowspan="3" >Ukuran Keberhasilan</th>
  					<th colspan="6" >Kinerja Triwulan</th>
  					<th rowspan="3" >Ket.</th>
                      <th rowspan="3" >Aksi</th>
                      <th rowspan="3" >Status</th>
  				</tr>
  				<tr>
  					<th rowspan="2">Triwulan</th>
  					<th colspan="3">Input</th>
  					<th colspan="2">Output</th>
  				</tr>
  				<tr>
  					<th> Pagu </th>
  					<th> Realisasi   </th>
  					<th> Capaian (%) </th>
  					<th> Ukuran Kinerja Triwulan   </th>
  					<th> Capaian (%) </th>
  				</tr>
  			</thead>
  			<tbody>
  				<?php
  					foreach ($program as $row) {
  						$result = $this->m_kendali_perubahan->get_kegiatan_dpa_4_cetak($row->id,$tahun_sekarang);
  						$kegiatan = $result->result();
  						$indikator_program = $this->m_kendali_perubahan->get_indikator_prog_keg_dpa($row->id, FALSE, TRUE);
  						$temp = $indikator_program->result();
  						$total_temp = $indikator_program->num_rows();

  						$col_indikator=1;
  						$go_2_keg = FALSE;
  						$total_for_iteration = $total_temp;
  						if($total_temp > $max_col_keg){
  							$total_temp = $max_col_keg;
  							$go_2_keg = TRUE;
  						}

  				?>
  				<tr>
  					<td style="border-bottom: 0;"><?php echo $row->kd_urusan; ?></td>
  					<td style="border-bottom: 0;"><?php echo $row->kd_bidang; ?></td>
  					<td style="border-bottom: 0;"><?php echo $row->kd_program; ?></td>
  					<td style="border-bottom: 0;"><?php echo $row->kd_kegiatan; ?></td>
  					<td style="border-bottom: 0;"><?php echo $row->nama_prog_or_keg; ?></td>
  					<td>
  					</td>
  					<td>
  					</td>
  					<td align="center" style="border-bottom: 0;" >
                      </td>
  					<td align="right" style="border-bottom: 0;" >
                      </td>
  					<td align="right" style="border-bottom: 0;" >
                      </td>
  					<td align="right" style="border-bottom: 0;" >
                      </td>
  					<td style="border-bottom: 0;" >
                      </td>
  					<td align="right" style="border-bottom: 0;" >
                      </td>
  					<td style="border-bottom: 0;">
                      </td>
                      <td style="border-bottom: 0;">
                      </td>
                      <td style="border-bottom: 0;">
                      </td>
  				</tr>
  				<?php
  					foreach ($kegiatan as $row) {
  							$indikator_kegiatan = $this->m_kendali_perubahan->get_indikator_prog_keg_dpa($row->id, FALSE, TRUE);
  							$temp = $indikator_kegiatan->result();
  							$total_temp = $indikator_kegiatan->num_rows();

  							$total_for_iteration = $total_temp;



  							//total row min adalah jumlah triwulan (4)
  							//total row maks adalah jumlah capaian semua triwulan()
  							$min_row = 4;
  							$row_count = $this->db->query("
  								select * from (select id,id_dpa_prog_keg_perubahan from tx_dpa_prog_keg_triwulan_perubahan
  								where id_dpa_prog_keg = '".$row->id."') a
  								left join tx_dpa_prog_keg_triwulan_detail_perubahan b
  								on a.id = b.id_dpa_prog_keg_triwulan"
  							)->num_rows();

  							if($row_count > $min_row){
  								//gak tau kenapa harus ditambah +1 4head
  								$min_row = $row_count+1;
  							}else{
  								$min_row++;
  							}


  					?>
  					<tr>
  						<td style="border-bottom: 0;" rowspan="<?php echo $min_row; ?>"><?php echo $row->kd_urusan; ?></td>
  						<td style="border-bottom: 0;" rowspan="<?php echo $min_row; ?>"><?php echo $row->kd_bidang; ?></td>
  						<td style="border-bottom: 0;" rowspan="<?php echo $min_row; ?>"><?php echo $row->kd_program; ?></td>
  						<td style="border-bottom: 0;" rowspan="<?php echo $min_row; ?>"><?php echo $row->kd_kegiatan; ?></td>
  						<td style="border-bottom: 0;" rowspan="<?php echo $min_row; ?>"><?php echo $row->nama_prog_or_keg; ?></td>
  						<td style="border-bottom: 0;" rowspan="<?php echo $min_row; ?>">
  							<?php

  								for ($i=0; $i < $total_for_iteration; $i++){
  									echo $temp[$i]->indikator;
  									echo '</br></br>';
  								}


  							?>
  						</td>
  						<td style="border-bottom: 0;" rowspan="<?php echo $min_row; ?>">
  							<?php
  								for ($i=0; $i < $total_for_iteration; $i++){
  									echo $temp[$i]->indikator." ".$temp[$i]->target." ".$temp[$i]->satuan_target;
  									echo '</br></br>';
  								}

  							?>
  						</td>

  					</tr>

  					<?php
  						//iterasi data untuk tiap triwulan
  						//parameter id
  						$id_dpa_prog_keg = $row->id;

  						for($i=1;$i<=4;$i++){

  							//get data tiap triwulan
  							$sql = "select * from tx_dpa_prog_keg_triwulan_perubahan where id_dpa_prog_keg='".$id_dpa_prog_keg."' and id_triwulan='".$i."'";
  							$data_triwulan = $this->db->query($sql)->row();

  							//get jumlah data kinerja
  							$sql1 = "
  								select b.* from (select id as id_dpa_prog_keg_triwulan,id_dpa_prog_keg from tx_dpa_prog_keg_triwulan_perubahan
  								where id_dpa_prog_keg = '".$id_dpa_prog_keg."' and id_triwulan='".$i."') a
  								left join tx_dpa_prog_keg_triwulan_detail_perubahan b
  								on a.id_dpa_prog_keg_triwulan = b.id_dpa_prog_keg_triwulan";
  							$count_output = $this->db->query($sql1)->num_rows();
  							$detail_output = $this->db->query($sql1)->result();
  							$num_row_span = $count_output==0 ? 1 : $count_output;
  					?>
  					<tr>
  						<td rowspan="<?php echo $num_row_span ?>">TW <?php echo $i?></td>
  						<td rowspan="<?php echo $num_row_span ?>" align="right"><?php echo Formatting::currency(@$data_triwulan->anggaran) ?></td>
  						<td rowspan="<?php echo $num_row_span ?>" align="right"><?php echo Formatting::currency(@$data_triwulan->realisasi) ?></td>
  						<td rowspan="<?php echo $num_row_span ?>"><?php echo @$data_triwulan->capaian ?></td>
  						<td ><?php echo @$detail_output[0]->catatan?></td>
  						<td ><?php echo @$detail_output[0]->capaian?></td>
  						<td ><?php echo @$detail_output[0]->keterangan?></td>
  						<td align="center" rowspan="<?php echo $num_row_span ?>">
  						<?php
  							if (@$data_triwulan->status_kendali != 3) {
  						?>
  							<a href="javascript:void(0)" idP="<?php echo $id_dpa_prog_keg;?>" idT = "<?php echo $i?>" class="icon-plus-sign add-kinerja-triwulan" title="Tambah Capaian Triwulan <?php echo $i;?>"/></a><br />
  							<a href="javascript:void(0)" idP="<?php echo $id_dpa_prog_keg;?>" idT = "<?php echo $i?>" class="icon-pencil edit-kinerja-triwulan" title="Kendali Triwulan <?php echo $i;?>"/></a><br />
  						<?php
  							}else{
  						?>
  								-
  						<?php
  							}
  						?>
  						</td>
  						<td align="center" rowspan="<?php echo $num_row_span ?>">
  						<?php
  							if (@$data_triwulan->status_kendali == 0) {
  								echo "Baru";
  							}elseif (@$data_triwulan->status_kendali == 1) {
  								echo "Proses Verifikasi";
  							}elseif (@$data_triwulan->status_kendali == 2) {
  								echo '<a class="see_revisi" id-d="'. $data_triwulan->id .'" href="javascript:void(0)" style="font-style: italic; color:red;">Revisi</a>';
  							}elseif (@$data_triwulan->status_kendali == 3) {
  								echo '<a class="see_revisi" id-d="'. $data_triwulan->id .'" href="javascript:void(0)" style="font-style: italic; color:green;">Disetujui</a>';
  							}else{
  								echo "-";
  							}
  						?>
  						</td>
  					</tr>

  					<?php
  							if($num_row_span > 1){
  								for($j=1;$j<$num_row_span;$j++){

  					?>
  					<tr>
  						<td ><?php echo @$detail_output[$j]->catatan?></td>
  						<td ><?php echo @$detail_output[$j]->capaian?></td>
  						<td ><?php echo @$detail_output[$j]->keterangan?></td>

  					</tr>

  					<?php
  								}
  							}

  						}

  					}
  				}
  				?>
  			</tbody>
  		</table>
    </div>
	</div>
</article>
