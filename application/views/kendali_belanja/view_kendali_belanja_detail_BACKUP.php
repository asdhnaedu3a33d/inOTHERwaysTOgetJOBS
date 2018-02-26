<script type="text/javascript">
$(".add-kinerja-triwulan").click(function(){
	var idr = $(this).parent().parent().attr("id-r");

	prepare_facebox();
	$.blockUI({
		css: window._css,
		overlayCSS: window._ovcss
	});
	$.ajax({
		type: "POST",
		url: '<?php echo site_url("kendali/add_kinerja_triwulan"); ?>',
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
	alert('tes');
	var idr = $(this).parent().parent().attr("id-r");

	prepare_facebox();
	$.blockUI({
		css: window._css,
		overlayCSS: window._ovcss
	});
	$.ajax({
		type: "POST",
		url: '<?php echo site_url("kendali/edit_kinerja_triwulan"); ?>',
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
		url: '<?php echo site_url("kendali/show_revisi"); ?>',
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
</script>
<table>
	<thead>
			<tr>
				<th colspan="6" >Kinerja Bulanan</th>
				<th rowspan="3" >Ket.</th>
				<th rowspan="3" >Aksi</th>
				<th rowspan="3" >Status</th>
			</tr>
			<tr>
				<th rowspan="2">Bulanan</th>
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
  						//iterasi data untuk tiap triwulan
  						//parameter id
  						// $id_dpa_prog_keg = $id;

  						for($i=1;$i<=12;$i++){

  							//get data tiap triwulan
  							$sql = "select * from tx_dpa_prog_keg_triwulan where id_dpa_prog_keg='".$id_dpa_prog_keg."' and id_triwulan='".$i."'";
  							$data_triwulan = $this->db->query($sql)->row();

  							//get jumlah data kinerja
  							$sql1 = "
  								select b.* from (select id as id_dpa_prog_keg_triwulan,id_dpa_prog_keg from tx_dpa_prog_keg_triwulan
  								where id_dpa_prog_keg = '".$id_dpa_prog_keg."' and id_triwulan='".$i."') a
  								left join tx_dpa_prog_keg_triwulan_detail b
  								on a.id_dpa_prog_keg_triwulan = b.id_dpa_prog_keg_triwulan";
								$count_output = $this->db->query($sql1)->num_rows();
  							$detail_output = $this->db->query($sql1)->result();
  							$num_row_span = $count_output==0 ? 1 : $count_output;

  					?>
  					<tr>
  						<td rowspan="<?php echo $num_row_span ?>">BLN <?php echo $i?></td>
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
  				?>

				</tbody>
  		</table>
