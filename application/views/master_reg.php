        <?php
        //ini crud dari geocery crud 
        if(isset($css_files)){
            foreach($css_files as $file): ?>
                <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
            <?php endforeach; 
        } ?>
        
        <?php 
        if(isset($js_files)){
            foreach($js_files as $file): ?>
                <script src="<?php echo $file; ?>"></script>
            <?php endforeach; 
        } ?>
        
	    <article class="module width_full">
    		<header><h3><?php echo $judul; ?></h3></header>
            <div class="module_content">
                <!-- page content -->
            <?php echo ($output) ; ?>
			<div align="right"><input type="button" value="Kembali" class="ui-input-button ui-button ui-widget ui-state-default ui-corner-all" role="button" aria-disabled="false" onClick="location.href='<?php echo base_url();?>'"></div>   
                
            </div>
            <footer>
			</footer>
        </article>
        <!-- end of styles article --> 
