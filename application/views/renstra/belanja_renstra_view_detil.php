<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>SIRENBANGDA</title>
		<link href="<?php echo base_url('assets/tabtab/css/fixed_table_rc.css');?>" type="text/css" rel="stylesheet" media="all" />
		<script type="text/javascript" src="<?php echo base_url(); ?>asset/new-theme/jquery/jquery-2.2.3.min.js"></script>
		<!-- <script src="https://code.jquery.com/jquery.min.js" type="text/javascript"></script> -->
		<script src="<?php echo base_url('assets/tabtab/js/sortable_table.js');?>" type="text/javascript"></script>
		<script src="<?php echo base_url('assets/tabtab/js/fixed_table_rc.js');?>" type="text/javascript"></script>
	</head>

	<h3 align="center" style="margin-left : 15px ;  "> Detil Belanja Kegiatan</h3>

	    <table style="margin-left : 15px ;" >

	     <?php

	      	$namaprogram =   $renstra->nama_prog_or_keg;
	      	$namaskpd  = $renstra->nama_skpd;
					$th_anggaran = $this->m_settings->get_tahun_anggaran_db();


             ?>

             <tr style="margin-left : 15px ;">
        	 <td style="margin-left : 15px ;" > <strong> Program / Kegiatan </strong > </td>
        	<td> <?php echo ": ".  $namaprogram ?> </td>
			</tr>
			<tr style="margin-left : 15px ;">
        	 <td style="margin-left : 15px ;"> <strong> Nama SKPD </strong > </td>
        	<td> <?php echo ": ". $namaskpd ?> </td>
        	</tr>


         </table>



	<body>

<div class="container">
		<div class="dwrapper">
<table id="fixed_hdr_test28" style="width:1200px ; ">
		  <thead>
		    <tr>
		    <th style =  'border: 1px solid black;' colspan="4" ></th>
		    <th style =  'border: 1px solid black;' colspan="5" >Tahun 1</th>
		    <th style =  'border: 1px solid black;' colspan="5" >Tahun 2</th>
		    <th style =  'border: 1px solid black;' colspan="5" >Tahun 3</th>
		    <th style =  'border: 1px solid black;' colspan="5" >Tahun 4</th>
		    <th style =  'border: 1px solid black;' colspan="5"  >Tahun 5</th>


		  </tr>
		    <tr>
		    <th  style="width:41px; border: 1px solid black; "  >No</th>
		    <th style="width:71px; border: 1px solid black; "  >Kode Belanja</th>
		    <th  style="width:200px; border: 1px solid black; " >Belanja</th>
		    <th  style="width:250px; border: 1px solid black; " > Detail Uraian</th>


		    <th style="width:71px; border: 1px solid black; " >Sumber Dana</th>
		    <th style="width:71px; border: 1px solid black; " >Volume</th>
		    <th style="width:71px; border: 1px solid black; " >Satuan</th>
		    <th style="width:71px; border: 1px solid black; " >Nominal</th>
		    <th style="width:71px; border: 1px solid black; " >Subtotal</th>

		     <th style="width:71px; border: 1px solid black; " >Sumber Dana</th>
		    <th style="width:71px; border: 1px solid black; " >Volume</th>
		    <th style="width:71px; border: 1px solid black; " >Satuan</th>
		    <th style="width:71px; border: 1px solid black; " >Nominal</th>
		    <th style="width:71px; border: 1px solid black; " >Subtotal</th>

		     <th style="width:71px; border: 1px solid black;" >Sumber Dana</th>
		    <th style="width:71px; border: 1px solid black;" >Volume</th>
		    <th style="width:71px; border: 1px solid black;" >Satuan</th>
		    <th style="width:71px; border: 1px solid black;" >Nominal</th>
		    <th style="width:71px; border: 1px solid black;" >Subtotal</th>

		      <th style="width:71px; border: 1px solid black;" >Sumber Dana</th>
		    <th style="width:71px; border: 1px solid black; " >Volume</th>
		    <th style="width:71px; border: 1px solid black; " >Satuan</th>
		    <th style="width:71px; border: 1px solid black; " >Nominal</th>
		    <th style="width:71px; border: 1px solid black; " >Subtotal</th>

		     <th style="width:71px; border: 1px solid black; " >Sumber Dana</th>
		    <th style="width:71px; border: 1px solid black; " >Volume</th>
		    <th style="width:71px; border: 1px solid black; " >Satuan</th>
		    <th style="width:71px; border: 1px solid black; " >Nominal</th>
		    <th style="width:71px; border: 1px solid black; " >Subtotal</th>
		   </tr>
		  </thead>
		  <tbody>

		  <?php
		  $no= 1;
		  $kode = "";

		  foreach ($detil_kegiatan as $detil) {


		  	$jenis = $detil->kode_jenis_belanja;
		  	$kategori = $detil->kode_kategori_belanja;
		  	$subkategori = $detil->kode_sub_kategori_belanja;
		  	$kdbelanja = $detil->kode_belanja;
		  	$belanja = $detil->belanja;
		  	$uraianbelanja = $detil->uraian_belanja;
		  	$detiluraianbelanja = $detil->detil_uraian_belanja;
		  	$kode_sumberdana = $detil->kode_sumber_dana;
		  	$id_kegiatan = $detil->id_kegiatan;
		  	//$Sumberdana = $detil->Sumber_dana;
		  	//$volume  =  $detil->volume;
		  	//$satuan  =  $detil->satuan;
		  	//$nominalsatuan  =  $detil->nominal_satuan;
		  	//$subtotal  =  $detil->subtotal;
		  	//$tahun  =  $detil->tahun;


		  	//$kode = $jenis.".".$kategori.".".$subkategori.".".$kdbelanja;
		  	$kodeBaru = $jenis.".".$kategori.".".$subkategori.".".$kdbelanja;


		  	?>

 			<?php
		  	echo "

			<tr >
			<td style =  'border: 1px solid black;' > $no </td>
			 <td style =  'border: 1px solid black;'>$kodeBaru </td>
			 <td style =  'border: 1px solid black;'> $uraianbelanja </td>
			 <td style =  'border: 1px solid black;'>  $detiluraianbelanja </td>";




			 	$databelanja = $this->m_renstra_trx->get_nominal_detail_uraian_semuatahun
				($kode_sumberdana, $jenis,$kategori,$subkategori,$kdbelanja,$th_anggaran[0]->tahun_anggaran,$detiluraianbelanja,$id_kegiatan );

			 	if (empty($databelanja)){
			 		echo "<td bgcolor='#99FFCC' style =  'border: 1px solid black;' > - </td>
			 				<td bgcolor='#99FFCC' style =  'border: 1px solid black;' > - </td>
			 				<td bgcolor='#99FFCC' style =  'border: 1px solid black;' > - </td>
			 				<td bgcolor='#99FFCC' style =  'border: 1px solid black;' > - </td>
			 				<td bgcolor='#99FFCC' style =  'border: 1px solid black;' > - </td>";

			 	}else{
			 	foreach ($databelanja as $belanja) {
			 			$subtotal = Formatting::currency($belanja->subtotal);
			 			$nominal_satuan = Formatting::currency($belanja->nominal_satuan);



			 	if($belanja->tahun == $th_anggaran[0]->tahun_anggaran){
			 			echo "
			 			<td bgcolor='#99FFCC' style =  'border: 1px solid black;'> $belanja->sumberdana </td>
			 			<td bgcolor='#99FFCC' style =  'border: 1px solid black;'> $belanja->volume </td>
			 			<td bgcolor='#99FFCC' style =  'border: 1px solid black;'> $belanja->satuan </td>
			 			<td bgcolor='#99FFCC' style =  'border: 1px solid black;'> $nominal_satuan</td>
			 			<td bgcolor='#99FFCC' style =  'border: 1px solid black;'> $subtotal </td>

			 			";
			 	   }
			    }
			}







			 $databelanja2 = $this->m_renstra_trx->get_nominal_detail_uraian_semuatahun
			 	($kode_sumberdana, $jenis,$kategori,$subkategori,$kdbelanja,$th_anggaran[1]->tahun_anggaran,$detiluraianbelanja,$id_kegiatan);
			 	if (empty($databelanja2)){
			 		echo "<td bgcolor='#2E94E0'  style =  'border: 1px solid black;'> - </td>
			 				<td  bgcolor='#2E94E0'  style =  'border: 1px solid black;'> - </td>
			 				<td bgcolor='#2E94E0'  style =  'border: 1px solid black;'> - </td>
			 				<td bgcolor='#2E94E0'  style =  'border: 1px solid black;'> - </td>
			 				<td bgcolor='#2E94E0'  style =  'border: 1px solid black;'> - </td>";

			 	}else{
			 	foreach ($databelanja2 as $belanja2) {
			 			$subtotal2 = Formatting::currency($belanja2->subtotal);
			 			$nominal_satuan2 = Formatting::currency($belanja2->nominal_satuan);



			 	if($belanja2->tahun == $th_anggaran[1]->tahun_anggaran){
			 			echo "
			 			<td bgcolor='#2E94E0' style =  'border: 1px solid black;'> $belanja2->sumberdana </td>
			 			<td bgcolor='#2E94E0' style =  'border: 1px solid black;'> $belanja2->volume </td>
			 			<td bgcolor='#2E94E0' style =  'border: 1px solid black;'> $belanja2->satuan </td>
			 			<td bgcolor='#2E94E0' style =  'border: 1px solid black;'> $nominal_satuan2</td>
			 			<td bgcolor='#2E94E0' style =  'border: 1px solid black;'> $subtotal2 </td>

			 			";
			 	}
			 }


			 }





			 	$databelanja3 = $this->m_renstra_trx->get_nominal_detail_uraian_semuatahun
			 	($kode_sumberdana, $jenis,$kategori,$subkategori,$kdbelanja,$th_anggaran[2]->tahun_anggaran,$detiluraianbelanja,$id_kegiatan);
			 	if (empty($databelanja3)){
			 		echo "<td bgcolor='#E0FF2E' style =  'border: 1px solid black;'> - </td>
			 				<td bgcolor='#E0FF2E' style =  'border: 1px solid black;'> - </td>
			 				<td bgcolor='#E0FF2E' style =  'border: 1px solid black;'> - </td>
			 				<td bgcolor='#E0FF2E' style =  'border: 1px solid black;'> - </td>
			 				<td bgcolor='#E0FF2E' style =  'border: 1px solid black;'> - </td>";

			 	}else{
			 	foreach ($databelanja3 as $belanja3) {
			 			$subtotal3 = Formatting::currency($belanja3->subtotal);
			 			$nominal_satuan3 = Formatting::currency($belanja3->nominal_satuan);



			 	if($belanja3->tahun == $th_anggaran[2]->tahun_anggaran){
			 			echo "
			 			<td bgcolor='#E0FF2E' style =  'border: 1px solid black;'> $belanja3->sumberdana </td>
			 			<td bgcolor='#E0FF2E' style =  'border: 1px solid black;'> $belanja3->volume </td>
			 			<td bgcolor='#E0FF2E' style =  'border: 1px solid black;'> $belanja3->satuan </td>
			 			<td bgcolor='#E0FF2E' style =  'border: 1px solid black;'> $nominal_satuan3</td>
			 			<td bgcolor='#E0FF2E' style =  'border: 1px solid black;'> $subtotal3 </td>

			 			";
			 	}
			 }

			 }




			 	$databelanja4 = $this->m_renstra_trx->get_nominal_detail_uraian_semuatahun
			 	($kode_sumberdana, $jenis,$kategori,$subkategori,$kdbelanja,$th_anggaran[3]->tahun_anggaran,$detiluraianbelanja,$id_kegiatan);
			 	if (empty($databelanja4)){
			 		echo "<td bgcolor='#D1B2F0' style =  'border: 1px solid black;'> - </td>
			 				<td bgcolor='#D1B2F0' style =  'border: 1px solid black;'> - </td>
			 				<td bgcolor='#D1B2F0' style =  'border: 1px solid black;'> - </td>
			 				<td bgcolor='#D1B2F0' style =  'border: 1px solid black;'> - </td>
			 				<td bgcolor='#D1B2F0' style =  'border: 1px solid black;'> - </td>";

			 	}else{
			 	foreach ($databelanja4 as $belanja4) {
			 			$subtotal4 = Formatting::currency($belanja4->subtotal);
			 			$nominal_satuan4 = Formatting::currency($belanja4->nominal_satuan);



			 	if($belanja4->tahun == $th_anggaran[3]->tahun_anggaran){
			 			echo "
			 			<td bgcolor='#D1B2F0' style =  'border: 1px solid black;'> $belanja4->sumberdana </td>
			 			<td bgcolor='#D1B2F0'  style =  'border: 1px solid black;'> $belanja4->volume </td>
			 			<td bgcolor='#D1B2F0' style =  'border: 1px solid black;'> $belanja4->satuan </td>
			 			<td bgcolor='#D1B2F0' style =  'border: 1px solid black;'> $nominal_satuan4</td>
			 			<td bgcolor='#D1B2F0' style =  'border: 1px solid black;'> $subtotal4 </td>

			 			";
			 	}
			 }

			 }



			 	$databelanja5 = $this->m_renstra_trx->get_nominal_detail_uraian_semuatahun
			 	($kode_sumberdana, $jenis,$kategori,$subkategori,$kdbelanja,$th_anggaran[4]->tahun_anggaran,$detiluraianbelanja,$id_kegiatan);
			 	if (empty($databelanja5)){
			 		echo "<td bgcolor='#FF8566' style =  'border: 1px solid black;'> - </td>
			 				<td bgcolor='#FF8566' style =  'border: 1px solid black;'> - </td>
			 				<td bgcolor='#FF8566' style =  'border: 1px solid black;'> - </td>
			 				<td bgcolor='#FF8566' style =  'border: 1px solid black;'> - </td>
			 				<td bgcolor='#FF8566' style =  'border: 1px solid black;'> - </td>";

			 	}else{
			 	foreach ($databelanja5 as $belanja5) {
			 			$subtotal5 = Formatting::currency($belanja5->subtotal);
			 			$nominal_satuan5 = Formatting::currency($belanja5->nominal_satuan);



			 	if($belanja5->tahun == $th_anggaran[4]->tahun_anggaran){
			 			echo "
			 			<td bgcolor='#FF8566' style =  'border: 1px solid black;' > $belanja5->sumberdana </td>
			 			<td bgcolor='#FF8566' style =  'border: 1px solid black;'> $belanja5->volume </td>
			 			<td bgcolor='#FF8566' style =  'border: 1px solid black;'> $belanja5->satuan </td>
			 			<td bgcolor='#FF8566' style =  'border: 1px solid black;'> $nominal_satuan5</td>
			 			<td bgcolor='#FF8566' style =  'border: 1px solid black;'> $subtotal5 </td>

			 			";
			 	}
			 }

			 }




			echo "</tr>";



$no++;
}

?>



		  </tbody>
		</table>
		</div>
</div>
	</body>
</html>
<style>
	html, body {
		font-family: Arial,​​sans-serif;
		font-size: 16px;
		margin: 0;
		padding: 0;
		background-color: #FFFFFF;
	}






	div.container {
		padding: 5px 15px;
		width: 1330px;
		margin: 10px auto;
	}

	.ft_container table tr th {
		background-color: #e0e0d1;
	}
</style>
<script>

$(document).ready(function() {
	$('#fixed_hdr_test28').fxdHdrCol({
		fixedCols: 1,
		width:     '100%',
		height:    'auto',
		colModal: [

			 { width: 50, align: 'left' },
			 { width: 50, align: 'left' },
			 { width: 200, align: 'left' },
			 { width: 200, align: 'left' },

			 //tahun1
			 { width: 200, align: 'left' },
			 { width: 50, align: 'right' },
			 { width: 50, align: 'left' },
			 { width: 200, align: 'right' },
			 { width: 200, align: 'center' },


			 //tahun2
			{ width: 200, align: 'left' },
			 { width: 50, align: 'right' },
			 { width: 50, align: 'left' },
			 { width: 200, align: 'right' },
			 { width: 200, align: 'center' },

			 //tahun3
			 { width: 200, align: 'left' },
			 { width: 50, align: 'right' },
			 { width: 50, align: 'left' },
			 { width: 200, align: 'right' },
			 { width: 200, align: 'center' },

			 //tahun4
			{ width: 200, align: 'left' },
			 { width: 50, align: 'right' },
			 { width: 50, align: 'left' },
			 { width: 200, align: 'right' },
			 { width: 200, align: 'center' },

			 //tahun5
			{ width: 200, align: 'left' },
			 { width: 50, align: 'right' },
			 { width: 50, align: 'left' },
			 { width: 200, align: 'right' },
			 { width: 200, align: 'center' }

		]
	});
	});

</script>
