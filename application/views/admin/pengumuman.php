<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/> 
<title>pengumuman</title>
<link rel="stylesheet" href="<?php echo base_url();?>asset/css/admin.css" type="text/css" media="screen" />
<?php echo $scripttiny_mce; ?>  
    <!--[if lt IE 9]>
    <link rel="stylesheet" href="css/ie.css" type="text/css" media="screen" />
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <script type="text/javascript" src="<?php echo base_url(); ?>asset/jquery/jquery-1.7.1.min.js"></script> 
    <script src="<?php echo base_url();?>asset/js/hideshow.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>asset/js/jquery.tablesorter.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery.equalHeight.js"></script>
    <script type="text/javascript">
    $(document).ready(function() 
        { 
            $(".tablesorter").tablesorter(); 
        } 
    );
    $(document).ready(function() {

    //When page loads...
    $(".tab_content").hide(); //Hide all content
    $("ul.tabs li:first").addClass("active").show(); //Activate first tab
    $(".tab_content:first").show(); //Show first tab content

    //On Click Event
    $("ul.tabs li").click(function() {

        $("ul.tabs li").removeClass("active"); //Remove any "active" class
        $(this).addClass("active"); //Add "active" class to selected tab
        $(".tab_content").hide(); //Hide all tab content

        var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
        $(activeTab).fadeIn(); //Fade in the active ID content
        return false;
    });

});
    </script>
    <script type="text/javascript">
    $(function(){
        $('.column').equalHeight();
    });
</script>
<script type="text/javascript" src="js/boxOver.js"></script>
 

</head>
<body>
   <?php include_once('inc/judul.php');
    include_once('inc/menu.php');

    ?>
    <section id="main" >  
      <article class="module width_3,5_quarter">
        <header><h3 class="tabs_involved">Pengumuman</h3>
        <ul class="tabs">

            <li><a href="#tab1">Daftar Pengumuman</a></li>
            <li><a href="#tab2">Tambah Pengumuman</a></li>
        </ul>
        </header>

        <div class="tab_container">
            <div id="tab1" class="tab_content"> 
            
            <table width="950" bgcolor="#ccc" cellpadding="2" cellspacing="1" class="widget-small"> 
            <tr bgcolor="#FFF" align="center"><td width="30"><strong>No.</strong></td><td width="800"><strong>Judul</strong></td><td width="100" align="center"><strong>Tanggal</strong></td><td width="5px" colspan="2"><strong>Aksi</strong></td></tr> 
            <?php $no = $page+1;  
            foreach($p->result_array() as $row){
            $id=$row["id_pengumuman"]; 
            $judul=$row['judul_pengumuman']; 
            $tgl=$row["tanggal"]; 
            ?>
  
            <tr bgcolor='#D6F3FF'>
                <td align='center'><?php echo"$no";?></td>
                <td><?php echo"$judul";?></td>
                <td align="center"><?php echo"$tgl";?></td>
                <td align="right" width="10px"><a href='<?php echo base_url()."index.php/admin/admin_editpengumuman/".$row['id_pengumuman'];?>' title='Edit Pengumuman'><img src='<?php echo base_url();?>asset/images/icn_edit.png'></a></td>
                <td><a href='<?php echo base_url()."index.php/admin/admin_hapuspengumuman/".$row['id_pengumuman'];?>' onClick="return confirm('Anda yakin ingin menghapus data ini?')" title='Hapus Pengumuman'> <img src='<?php echo base_url();?>asset/images/icn_trash.png'></a></td>

            </tr> 
            <?php $no++; } ?>
            </table><br />
            
            <?php echo $paginator;?> 
            
            </div><!-- end of #tab1 -->
            
            <div id="tab2" class="tab_content">  
            <?php echo form_open_multipart('admin/admin_simpanpengumuman');?>
            <table class="tablesorter" cellspacing="0" height="100%">
                   <tr><td>Judul : <input type="text" size="80px" name="judul"> </input>  </td></tr>
                   <tr><td>Isi Berita :<br /><br />
                   <textarea name="isi">    </textarea></td> 
                   </tr>
                   <tr><td>Tanggal Posting : <input type='text' name="tanggal" ></input></td><tr>
                   <td><input type="submit"  value="Simpan Data" class="input"/></td>    </tr>
            </table><br />  
            
            <?php echo $paginator; ?>

            </div><!-- end of #tab2 -->
            
        </div><!-- end of .tab_container -->     
        
        </article><!-- end of content manager article -->


        
    </section>;
                <?php echo $paginator;?>  

</body>
</html>
