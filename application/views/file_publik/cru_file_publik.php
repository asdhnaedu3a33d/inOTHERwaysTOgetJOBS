<script type="text/javascript">

$(function() {

        $("#input_file_publik").validate({
            rules: {
                title : "required",
                keterangan : "required",
                //nama_file : "required"
            },
            messages: {
                title : "Mohon diisi terlebih dahulu",
                keterangan : "Mohon diisi terlebih dahulu"//,
                //nama_file : "Mohon diisi terlebih dahulu"
            },
      submitHandler: function(form){
        form.submit();
      }
        });
    });

</script>

<article class="module width_full">
 	<header>
 		<h3>
		<?php
			if (isset($isEdit) && $isEdit){
			    echo "Edit File Publik";
			} else{
			    echo "Input File Publik";
			}
		?>
		</h3>
 	</header>
  <form method="post" name='input_file_publik' id='input_file_publik' action="<?php echo site_url('file_publik/save_file_publik')?>" enctype="multipart/form-data" >
 	  <div class="module_content">
      <input name='call_from' type='hidden' id="call_from" value='<?php echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '' ?>'/>
   	  <input type="hidden" name="id" value="<?php if(!empty($id)){echo $id;} ?>" />
        <table id="file_publik_input" class="fcari" width="100%">
              <tr>
                  <td style="width:20%">Judul</td>
                  <td style="width:80%">
                      <input type="text" name="title" id="title" placeholder="Judul File"
                      value="<?php echo isset($title) ? $title : ''; ?>" />
                  </td>
              </tr>
              <tr>
                <td>Keterangan</td>
                <td>
                      <input type="text" name="keterangan" id="keterangan" placeholder="Keterangan" value="<?php echo isset($keterangan) ? $keterangan : ''; ?>"/>
                  </td>
              </tr>
              <tr>
                <td>File :</td>
                <td>
                      <input type="file" name="file_publik" id="file_publik"/>
                  </td>
              </tr>
         </table>
      </div>
      <footer>
          <div class="submit_link">
    			<input type="submit" name="simpan"  id="simpan" value='Simpan'/>
    			<input type="button" value="Keluar" onclick="window.location.href='<?php echo site_url('file_publik'); ?>'" />          
      </footer>
   </form>
</article>
