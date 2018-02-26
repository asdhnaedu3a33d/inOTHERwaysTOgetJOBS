<script type="text/javascript">
	function veri_cik(id,bulan){
		window.location = '<?php echo site_url("cik/veri_cik")?>/' + id +'/' + bulan;
	}
</script>
<article class="module width_full">
	<header>
	  <h3>Verifikasi CIK</h3>
	</header>
	<div class="module_content"; style="overflow:auto">
    <fieldset>
    <form>
	<table width="99.9%">
        <td style="vertical-align: middle !important;" width="5%">Bulan:</td>
		    <td width="25%">
          <select name="id_bulan" id="id_bulan">
            <option value="1">Januari</option>
            <option value="2">Februari</option>
            <option value="3">Maret</option>
            <option value="4">April</option>
            <option value="5">Mei</option>
            <option value="6">Juni</option>
            <option value="7">Juli</option>
            <option value="8">Agustus</option>
            <option value="9">September</option>
            <option value="10">Oktober</option>
            <option value="11">November</option>
            <option value="12">Desember</option>
          </select>
        </td>
        <td width="5%"> <input type="button" id="search" class="btn" value="Search"/></td>
        <td width="75%"> &nbsp;</td>
	</table>
    </form>
    </fieldset>
		<table id="cik" class="table-common" style="width:99%">
			<thead>
				<tr>
					<th width="10px">No</th>
					<th>SKPD</th>
					<th>Jumlah Kegiatan</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody id="preview_cik">

			</tbody>
		</table>
	</div>
</article>
<script type="text/javascript">
    $("#search").click(function(){

		var id_bulan = $("#id_bulan").val();
			var data  = {id_bulan:$("#id_bulan").val()};
				$.ajax({
						type: "POST",
						url : "<?php echo site_url('cik/get_veri_cik')?>",
						data: data,
						success: function(msg){
							$('#preview_cik').html(msg);
						}
				});
        });
</script>
