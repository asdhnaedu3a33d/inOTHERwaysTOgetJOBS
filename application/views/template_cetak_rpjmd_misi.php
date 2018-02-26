<?php
    if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<html>
<head>
    <title>Cetak File Sitenbangda</title>
    <style type="text/css">
        #header,
        #footer {
            position: fixed;
            left: 0;
            right: 0;
            color: black;
            font-size: 0.9em;
        }

        #header {
            color: black;
            width: 100%;
            height: 110px;          
            top: 0;
            font-size: 14px;
            font-weight: bold;
            border-bottom: 0.1pt solid #aaa;
        }

        #footer {
            bottom: 0;
            border-top: 0.1pt solid #aaa;
        }        

        .page-number {
            width: 25%;
            float: right;
            text-align: right;
        }

        .page-number:before {
            content: "- " counter(page) " -";
        }

        hr {
            page-break-after: always;
            border: 0;
        }

        .text-center{
            text-align: center;
        }

        .text-right{
            text-align: right;
        }
        body{
            color: black;
            font-size: 12px;
        }
        .full_width{
            width: 100%;
        }
        table.border,.border th,.border td {
           border: 1px solid black;
           border-collapse: collapse;
        }
        table.collapse,.collapse th,.collapse td {           
           border-collapse: collapse;
        }
        td p{
            margin: 0;
        }        
        .page-break{
            page-break-after: always;
        }   
        .page-break:last-of-type{
            page-break-after: auto
        }
    </style>
</head>
<body>
    
    <div id="footer">        
        <div style="margin-top: 5px; display: inline-block; color: black; font-family:'Droid Sans', Helvetica, Arial, sans-serif;">
            <img style="margin-top:-2px; margin-left: -2px;" height="18" width="18" src="<?php echo site_url('asset/images/S_4_sirenbangda_black.png'); ?>"><font size="3">I</font>RENBANGDA
        </div>
        <div class="page-number"></div>
    </div>
    
    <div class="content">
    <?php
        // dynamic content loaded here
        echo $contents;
    ?>
    </div>
</body>
</html>