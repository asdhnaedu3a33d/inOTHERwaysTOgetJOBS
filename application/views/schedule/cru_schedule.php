<script type="text/javascript">

$(function() {

        $("#input_schedule").validate({
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
			    echo "Edit Jadwal Kegiatan";
			} else{
			    echo "Input Jadwal Kegiatan";
			}
		?>
		</h3>
 	</header>
  <form method="post" name='input_schedule' id='input_schedule' action="<?php echo site_url('schedule/save_schedule')?>" enctype="multipart/form-data" >
 	  <div class="module_content">
      <input name='call_from' type='hidden' id="call_from" value='<?php echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '' ?>'/>
   	  <input type="hidden" name="id" value="<?php if(!empty($id)){echo $id;} ?>" />
        <table id="schedule_input" class="fcari" width="100%">
              <tr>
                  <td style="width:20%">Judul</td>
                  <td style="width:80%">
                      <input type="text" name="title" id="title" placeholder="Judul Kegiatan"
                      value="<?php echo isset($title) ? $title : ''; ?>"/>
                  </td>
              </tr>
              <tr>
                <td>Tanggal Mulai</td>
                <td>
                      <input type="date" name="date_start" id="date_start"
                      value="<?php echo isset($date_start) ? $date_start : ''; ?>"/>
                  </td>
              </tr>
              <tr>
                <td>Tanggal Selesai</td>
                <td>
                      <input type="date" name="date_end" id="date_end"
                      value="<?php echo isset($date_end) ? $date_end : ''; ?>"/>
                  </td>
              </tr>
              <tr>
                <td>Informasi</td>
                <td>
                    <textarea name="information" id="information"><?php if(!empty($information)){echo $information;} ?></textarea>
                  </td>
              </tr>
         </table>
      </div>
      <footer>
          <div class="submit_link">
    			<input type="submit" name="simpan"  id="simpan" value='Simpan'/>
    			<input type="button" value="Keluar" onclick="window.location.href='<?php echo site_url('schedule'); ?>'" />                        
  		    </div>
  	  </footer>
  </form>
</article>
