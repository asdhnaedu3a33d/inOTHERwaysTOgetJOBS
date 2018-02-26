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
</head>
<body>
   <?php include_once('inc/judul.php');
    include_once('inc/menu.php');

    ?>
    <section id="main" >  
      <article class="module width_3,5_quarter">
        <header><h3 class="tabs_involved">Edit Pengumuman</h3>
          
        </header>

     <div class="tab_container">
            <div id="tab1" class="tab_content">
            <?php echo form_open_multipart('admin/admin_updatepengumuman');?> 
            <table class="tablesorter" width="950" bgcolor="#ccc" cellpadding="2" cellspacing="1" class="widget-small"> 
             <?php foreach($ep->result_array() as $row){
                $id=$row["id_pengumuman"]; 
                $judul=$row["judul_pengumuman"]; 
                $isi=$row["isi"]; 
                $tgl=$row["tanggal"];
                } 
             ?> 
                <input type="hidden" size="80px" name="id_pengumuman" value="<?php echo"$id";?>"> </input>
                <tr><td>Judul : <input type="text" size="80px" name="judul" value="<?php echo"$judul";?>"> </input>  </td></tr>
                <tr><td>Isi Berita :<br />
                <textarea name="isi">  <?php echo $isi;  ?>  </textarea></td> 
                </tr>
                <tr><td>Tanggal Posting : <input type="text" name="tgl" value="<?php echo"$tgl";?>"></input></td><tr>
                <tr><td><input type="submit"  value="Simpan Data" class="input"/></td>    </tr>
            </table>        
        </div><!-- end of #tab2 -->
            
        </div><!-- end of .tab_container -->  
        </article><!-- end of content manager article -->


        
    </section>;


</body>
</html>
