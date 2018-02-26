<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div id="main-content">   
        <?php $user=$this->session->userdata('session_data'); 
           print_r($user['menu']);
        ?>
</div>