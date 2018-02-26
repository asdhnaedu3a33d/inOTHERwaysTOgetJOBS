<section id="secondary_bar">
        <div id="menu" style="height: 38px; ">
            <div id="nav">
             <ul>
                <li><a href="<?php echo base_url(); ?>">Beranda</a></li>
             </ul>  
              <ul>
                <li> <a>[<?php echo $this->session->userdata('nama_p');?>]</a> 
                    <ul>
                       <!-- <li> <a href="<?php echo  base_url();?>user/edit_pass">Edit Password</a> </li>   -->
                        <li> <a href="<?php echo  site_url('login/logout');?>">LogOut</a></li>  
                    </ul>
                </li>
            </ul>
            </div>
        </div>
    </section><!-- end of secondary bar -->