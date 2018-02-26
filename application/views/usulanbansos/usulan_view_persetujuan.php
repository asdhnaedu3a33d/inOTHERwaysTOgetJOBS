<script type="text/javascript">
	var dt;
	$(document).ready(function(){
		//$('#det_uraian').autoNumeric('set',value);
	    dt = $("#usulan_table").DataTable({
	    	"processing": true,
			"stateSave": true,
        	"serverSide": true,
        	"aoColumnDefs": [
                {
                    "bSortable": false,
                    "aTargets": ["no-sort"]
                }
            ],
            "ajax": {
	            "url": "<?php echo $url_load_data; ?>",
	            "type": "POST"
	        }
	    });

	    $('div.dataTables_filter input').unbind();
	    $("div.dataTables_filter input").keyup( function (e) {
		    if (e.keyCode == 13) {
		        dt.search( this.value ).draw();
		    }
		} );

	$("#simpan").click(function(){
		    var valid = $("form#persetujuanusulanbansos").valid();

				var idfile;
				var iduserfile;
				try {
					idfile=document.getElementById('userfile').value
					iduserfile=document.getElementById('id_file').value
				//				alert(iduserfile)
				} catch (e) {
					idfile='';
					iduserfile='';

				} finally {

				}
				if(idfile != '' || iduserfile !='' ){
					if (valid) {
				    	//$("#det_uraian").val($("#det_uraian").autoNumeric('get'));

				    	$("form#persetujuanusulanbansos").submit();
				    };
				}else{
					alert('Persetujuan Hibah/Bansos harus disertai dokumen pendukung!')
				}

	});

	});
	$(document).on("click", "#cetak", function(){
			$.blockUI({
				message: 'Cetak dokumen sedang di proses, mohon ditunggu hingga file terunduh secara otomatis ...',
				css: window._css,
				timeout: 2000,
				overlayCSS: window._ovcss
			});
			var link = '<?php echo site_url("usulanbansos/cetakpersetujuanusulanbansos"); ?>';
			$(location).attr('href',link);
		});

	function edit_usulan_table(id){
		window.location = '<?php echo $url_edit_data;?>/' + id;
	}

	function kup(nama){
		var idnama = nama.name;
		$(nama).autoNumeric(numOptions);
		// var x = document.getElementById("det_uraian");
   // alert('jos');
    	//x.autoNumeric(numOptions);
	}
</script>
<article class="module width_full">
	<header>
	  <h3>Tabel Data Persetujuan Usulan Hibah/Bansos </h3>
	</header>

	<div class="module_content"; style="overflow:auto">
   <form action="<?php echo site_url('usulanbansos/savepersetujuan');?>" method="POST" name="persetujuanusulanbansos" id="persetujuanusulanbansos" accept-charset="UTF-8" enctype="multipart/form-data" >

		<table id="usulan_table" class="table-common tablesorter" style="width:100%">
			<thead>
				<tr>
					<th class="no-sort">No</th>
					<th>Pengusul</th>
					<th>Jenis Pekerjaan</th>
					<th>Jumlah Dana (Rp)</th>
					<th>SKPD</th>
					<th class="no-sort" width='15%'>Status RKPD</th>
					<th class="no-sort" width='20%'>Nominal Anggaran</th>
					<th class="no-sort">No. rekomendasi</th>
					<th class="no-sort">Tgl. rekomendasi</th>


				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	<table class="fcari" width="50%">
		<tbody>
			<tr >
        	<td>Keterangan</td>
        	</tr>
        	<tr>
        	<td>
        		<input type = "text" class="commmon" name="keterangan" id="keterangan" value="<?php if(!empty($keterangan_rapat)){echo $keterangan_rapat;} ?>">
			</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			</tr>

			<tr>
				<td>
                   <?php
        		    include_once("file_upload.php");
         			?>
	            </td>
			</tr>
			<tr>
				<td></td>
			</tr>
		</tbody>
    </table>
    <input type='button' id="simpan" name="simpan" value='Simpan' />
		<input type="button" class="button-action" id="cetak" value="Cetak" />

	</div>
</article>

</form>
