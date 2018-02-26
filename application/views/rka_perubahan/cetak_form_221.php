<?php
    if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<html>
<head>
    <title>Cetak File Sitenbangda</title>
    <style type="text/css">
    #header1 {
            color: black;
                     
            top: 0;
            font-size: 14px;
            font-weight: bold;
            border-bottom: 0.1pt solid #aaa;
        }
    #header2 {
            color: black;         
            top: 0;
            font-size: 12px;
            border-bottom: 0.1pt solid #aaa;
            
        }
     #header3 {
            color: black;         
            top: 0;
            font-size: 11px;
            border-bottom: 0.1pt solid #aaa;
            padding-left:10px;
        }
        #footer1 {
            color: black;         
            top: 0;
            font-size: 7px;
            border-bottom: 0.1pt solid #aaa;
            padding-left:10px;
        }
         #header4 {
            color: black;         
            top: 0;
            font-size: 11px;
            border-bottom: 0.1pt solid #aaa;
            padding-left:10px;
        }
         #tabelhead2 {
            color: black;         
            top: 0;
            font-size: 10px;
            border-bottom: 0.1pt solid #aaa;
            padding-left:10px;
        }
 
    
    table {
    border-collapse: collapse;
}

table, th, td {
    border: 1px solid black;
}
    </style>
   
</head>
<body>
    
  <table width="100%" style=" font-family:'Droid Sans', Helvetica, Arial, sans-serif;" >
  <tbody>
    <tr>
      <td width="8%"  rowspan="2" align="center"><?php echo $logo; ?></td>
      <td id="header1" align="center" colspan="8"><strong>RINCIAN KEGIATAN BELANJA RENJA <br>
       SATUAN KERJA PERANGKAT DAERAH</strong></td>
      <td align="center" rowspan="2"> <strong> Formulir <br>
        BELANJA  <br>
      SKPD </strong></td>
    </tr>
    <tr>
      <td  id="header2" align="center" height="30" colspan="8"><strong>PEMERINTAH KABUPATEN KLUNGKUNG</strong><br>
      Tahun Anggaran : <?php echo $tahun1[0]->tahun; ?></td>
    </tr>

    <?php
    $UrusanPemerintah = $tahun1[0]->kode_urusan.".".$tahun1[0]->kode_fungsi;
    $Organisasi = $UrusanPemerintah." . ".$UrusanPemerintah.".".$this->session->userdata('id_skpd');
    $Program = $Organisasi." . ".$tahun1[0]->kode_program;
    $Kegiatan =  $Program." . ". $tahun1[0]->kode_kegiatan;
    ?>

    <tr>
      <td id="header3" colspan="3" width="7%" style="border-right:none"  ><strong>Urusan Pemerintahan  <br> Organisasi <br> Program <br> Kegiatan</strong></td>
      <td id="header3" width="20%" style="border-left:none ; border-right:none ; padding-left:10px"   >  : <?php echo $UrusanPemerintah   ;?> <br> : <?php echo  $Organisasi ?>  <br>  : <?= $Program ?> <br>  : <?= $Kegiatan ?></td>
      <td id="header3" width="60%" style="border-left:none ; border-right:none"  > <?php echo $tahun1[0]->nama_urusan." "."Fungsi ".$tahun1[0]->nama_fungsi ;?>  <br> <?= $this->session->userdata('nama_skpd') ?>  <br> <?= $tahun1[0]->nama_program ;?> <br> <?= $tahun1[0]->nama_kegiatan ;?></td>
      <td id="header3" colspan="5" style="border-left:none">&nbsp;</td>
    </tr>
    <tr>
      <td id="header3" colspan="3" style="border-right:none"><strong>Lokasi Kegiatan</strong></td>
      <td id="header3" colspan="7" style="border-left:none"> <strong>:</strong> Disdik</td>
    </tr>
    <tr>
      <td id="header3" colspan="3" style="border-right:none"> <strong>Jumlah Tahun n-1 <br> Jumlah Tahun n <br> Jumlah Tahun n+1</strong></td>
      <td id="header3" style="border-left:none ; border-right:none"><strong>: </strong>Rp<strong><br> 
        : </strong>Rp<strong><br>  
      :</strong> Rp</td>
      <td id="header3" style="border-left:none ; border-right:none"> 1000 &nbsp; <em>(seribu)</em> <br>  1000 <br> 1000  </td>
        <td  style="border-left:none" colspan="5">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="10" align="center"><strong>INDIKATOR &amp; TOLAK UKUR KINERJA BELANJA LANGSUNG</strong></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><strong>INDIKATOR</strong></td>
      <td colspan="3" align="center"><strong>TOLAK UKUR KINERJA</strong></td>
      <td colspan="4" align="center"><strong>TARGET KINERJA</strong></td>
      
    </tr>
    <tr>
      <td id="header4" colspan="3"><strong>CAPAIAN PROGRAM</strong></td>
      <td id="header4" colspan="3">&nbsp;</td>
      <td id="header4" colspan="4">&nbsp;</td>
      
    </tr>
    <tr>
      <td id="header4" colspan="3"><strong>MASUKAN</strong></td>
      <td id="header4" colspan="3">&nbsp;</td>
      <td id="header4" colspan="4">&nbsp;</td>
      
    </tr>
    <tr>
      <td id="header4" colspan="3"><strong>KELUARAN</strong></td>
      <td id="header4" colspan="3">&nbsp;</td>
      <td id="header4" colspan="4">&nbsp;</td>
     
    </tr>
    <tr>
      <td id="header4" colspan="10"><strong>Kelompok Sasaran Kegiatan : </strong></td>
    </tr>
    <tr>
      <td colspan="10" align="center"><strong>RINCIAN ANGGARAN BELANJA LANGSUNG MENURUT PROGRAM DAN PER KEGIATAN SATUAN KERJA PERANGKAT DAERAH</strong></td>
    </tr>
    <tr>
      <td colspan="3" rowspan="2" align="center"><strong>KODE <br> REKENING</strong></td>
      <td colspan="3" rowspan="2"  align="center"><strong>URAIAN</strong></td>
      <td colspan="3" align="center"> <strong>RINCIAN PERHITUNGAN</strong></td>
      <td  rowspan="2" align="center"> <strong>JUMLAH <br> (Rp)</strong></td>
       
    </tr>
    <tr>
      <td id="tabelhead2" width="7%" height="30" align="center"><strong>Volume</strong></td>
      <td id="tabelhead2" width="10%" align="center"><strong>Satuan</strong></td>
      <td id="tabelhead2" width="8%" width="50" align="center"><strong>Harga Satuan</strong></td>
      
    </tr>
    <tr>
      <td colspan="3" align="center"><strong>1</strong></td>
      <td colspan="3" align="center"><strong>2</strong></td>
      <td align="center"><strong>3</strong></td>
      <td align="center"><strong>4</strong></td>
      <td align="center"><strong>5</strong></td>
      <td align="center"><strong>6</strong></td>
    </tr>

                <?php
                $jenis = NULL; $kategori = NULL; $subkategori = NULL; $kdbelanja = NULL; $uraianbelanja = NULL; 
                $TotalBelanja = 0;$TotalJenisBelanja=0;$TotalKategoriBelanja=0;$TotalSubKategoriBelanja=0;$TotalBelanja1=0; 

                foreach ($tahun1 as $rowth1  ) {
                   
                    if ($rowth1->kode_jenis_belanja == $jenis) {
                     
                        if ($rowth1->kode_kategori_belanja == $kategori) {
                            if ($rowth1->kode_sub_kategori_belanja == $subkategori) {
                                if ($rowth1->kode_belanja == $kdbelanja) {
                                    if ($rowth1->uraian_belanja == $uraianbelanja) {
                                        $volume = round($rowth1->volume);
                        
                        echo "
                        <tr>
                        <td colspan=3 style=' border-bottom:none ; border-top:none ; padding-left:10px ' ></td>
                       <td colspan='3' style=' border-bottom:none ; border-top:none : padding-left:10px ' ><div style='padding-left: 32px;'> - $rowth1->detil_uraian_belanja </div></td>
                        </div></td>
                        <td align='center' > $volume </td>
                        <td align='center' > $rowth1->satuan </td>
                        <td align='right' >".Formatting::currency($rowth1->nominal_satuan)."</td>
                        <td align='right'  >".Formatting::currency($rowth1->subtotal)."</td>

                        </tr>";

                                    }else{

                        $uraianbelanja = $rowth1->uraian_belanja;                   
                        echo "<tr>
                        <td colspan=3 style=' border-bottom:none ; border-top:none ; padding-left:10px ' ></td>
                        <td colspan='3' style=' border-bottom:none ; border-top:none ' ><div style='padding-left: 40px;'> $uraianbelanja </div></td>
                        <td> </td>
                        <td></td>
                        <td  ></td>
                        <td  ></td>
                        </tr>";
                        $volume = round($rowth1->volume);
                        echo "
                        <tr>
                        <td colspan=3 style=' border-bottom:none ; border-top:none ; padding-left:10px ' ></td>
                        <td colspan='3' style=' border-bottom:none ; border-top:none : padding-left:10px ' ><div style='padding-left: 32px;'> - $rowth1->detil_uraian_belanja </div></td>
                         </div></td>
                        <td align='center' > $volume </td>
                        <td align='center' > $rowth1->satuan </td>
                        <td align='right' >Rp. ".Formatting::currency($rowth1->nominal_satuan)."</td>
                        <td align='right'  >Rp. ".Formatting::currency($rowth1->subtotal)."</td>

                        </tr>";
                                    
                                    }
                                }else{

                        $kdbelanja = $rowth1->kode_belanja;                                 
                        echo "
                        <tr>
                        <td colspan='3' align='left' style=' border-bottom:none ; border-top:none ; padding-left:10px ' width='90'>5 . $jenisText . $kategori . $subkategori . $kdbelanja</td>
                        <td colspan='3'  style=' border-bottom:none ; border-top:none ; padding-left:10px ' ><div style='padding-left: 30px;'> $rowth1->belanja </div></td>
                        <td></td>
                        <td  ></td>
                        <td  ></td>
                        <td></td>
                        </tr>";
                        $uraianbelanja = $rowth1->uraian_belanja;                   
                        echo "<tr>
                        <td colspan=3 style=' border-bottom:none ; border-top:none ; padding-left:10px ' ></td>
                        <td colspan='3' style=' border-bottom:none ; border-top:none ' ><div style='padding-left: 40px;'> $uraianbelanja </div></td>
                        <td> </td>
                        <td></td>
                        <td  ></td>
                        <td  ></td>
                        </tr>";
                        $volume = round($rowth1->volume);
                        echo "
                        <tr>
                        <td colspan=3 style=' border-bottom:none ; border-top:none ; padding-left:10px ' ></td>
                        <td colspan='3' style=' border-bottom:none ; border-top:none : padding-left:10px ' ><div style='padding-left: 32px;'> - $rowth1->detil_uraian_belanja </div></td>
                         </div></td>
                        <td align='center' > $volume </td>
                        <td align='center' > $rowth1->satuan </td>
                        <td align='right' >Rp. ".Formatting::currency($rowth1->nominal_satuan)."</td>
                        <td align='right'  >Rp. ".Formatting::currency($rowth1->subtotal)."</td>

                        </tr>";
                                }
                            }else{
                                                        
                        

                        $subkategori = $rowth1->kode_sub_kategori_belanja; 
                         echo "
                         <tr>
                         <td colspan='3' align='left' style=' border-bottom:none ; border-top:none ; padding-left:10px ' width='90'>5 . $jenisText . $kategori . $subkategori</td>
                         <td colspan='3' style=' border-bottom:none ; border-top:none ; padding-left:10px ' ><div style='padding-left: 20px;'> $rowth1->subkategori </div></td>
                         <td></td>
                         <td ></td>
                         <td  ></td>
                         <td></td>
                         </tr>";
                        $kdbelanja = $rowth1->kode_belanja;                                 
                        echo "
                        <tr>
                        <td colspan='3' align='left' style=' border-bottom:none ; border-top:none ; padding-left:10px ' width='90'>5 . $jenisText . $kategori . $subkategori . $kdbelanja</td>
                        <td colspan='3'  style=' border-bottom:none ; border-top:none ; padding-left:10px ' ><div style='padding-left: 30px;'> $rowth1->belanja </div></td>
                        <td></td>
                        <td  ></td>
                        <td  ></td>
                        <td></td>
                        </tr>";
                        $uraianbelanja = $rowth1->uraian_belanja;                   
                        echo "<tr>
                        <td colspan=3 style=' border-bottom:none ; border-top:none ; padding-left:10px ' ></td>
                        <td colspan='3' style=' border-bottom:none ; border-top:none ' ><div style='padding-left: 40px;'> $uraianbelanja </div></td>
                        <td> </td>
                        <td></td>
                        <td  ></td>
                        <td  ></td>
                        </tr>";
                        $volume = round($rowth1->volume);
                        echo "
                        <tr>
                        <td colspan=3 style=' border-bottom:none ; border-top:none ; padding-left:10px ' ></td>
                       <td colspan='3' style=' border-bottom:none ; border-top:none : padding-left:10px ' ><div style='padding-left: 32px;'> - $rowth1->detil_uraian_belanja </div></td>
                         </div></td>
                        <td align='center' > $volume </td>
                        <td align='center' > $rowth1->satuan </td>
                        <td align='right' >Rp. ".Formatting::currency($rowth1->nominal_satuan)."</td>
                        <td align='right'  >Rp. ".Formatting::currency($rowth1->subtotal)."</td>

                        </tr>";
                            }
                        }else{
                                                   
                        
                        $kategori = $rowth1->kode_kategori_belanja;                 
                        echo "
                        <tr>
                        <td colspan='3' align='left' style=' border-bottom:none ; border-top:none ; padding-left:10px' width='90'>5 . $jenisText . $kategori</td>
                        <td colspan='3' style=' border-bottom:none ; border-top:none ; padding-left:10px' ><b><div style='padding-left: 10px;'> $rowth1->kategori </div></b></td>
                        <td></td>
                        <td  ></td>
                        <td  ></td>
                        <td></td>
                        </tr>";
                        $subkategori = $rowth1->kode_sub_kategori_belanja; 
                         echo "
                         <tr>
                         <td colspan='3' align='left' style=' border-bottom:none ; border-top:none ; padding-left:10px ' width='90'>5 . $jenisText . $kategori . $subkategori</td>
                         <td colspan='3' style=' border-bottom:none ; border-top:none ; padding-left:10px ' ><div style='padding-left: 20px;'> $rowth1->subkategori </div></td>
                         <td></td>
                         <td ></td>
                         <td  ></td>
                         <td></td>
                         </tr>";
                        $kdbelanja = $rowth1->kode_belanja;                                 
                        echo "
                        <tr>
                        <td colspan='3' align='left' style=' border-bottom:none ; border-top:none ; padding-left:10px ' width='90'>5 . $jenisText . $kategori . $subkategori . $kdbelanja</td>
                        <td colspan='3'  style=' border-bottom:none ; border-top:none ; padding-left:10px ' ><div style='padding-left: 30px;'> $rowth1->belanja </div></td>
                        <td></td>
                        <td  ></td>
                        <td  ></td>
                        <td></td>
                        </tr>";
                        $uraianbelanja = $rowth1->uraian_belanja;                   
                        echo "<tr>
                        <td colspan=3 style=' border-bottom:none ; border-top:none ; padding-left:10px ' ></td>
                        <td colspan='3' style=' border-bottom:none ; border-top:none ' ><div style='padding-left: 40px;'> $uraianbelanja </div></td>
                        <td> </td>
                        <td></td>
                        <td  ></td>
                        <td  ></td>
                        </tr>";
                        $volume = round($rowth1->volume);
                        echo "
                        <tr>
                        <td colspan=3 style=' border-bottom:none ; border-top:none ; padding-left:10px ' ></td>
                       <td colspan='3' style=' border-bottom:none ; border-top:none : padding-left:10px ' ><div style='padding-left: 32px;'> - $rowth1->detil_uraian_belanja </div></td>
                         </div></td>
                        <td align='center' > $volume </td>
                        <td align='center' > $rowth1->satuan </td>
                        <td align='right' >Rp. ".Formatting::currency($rowth1->nominal_satuan)."</td>
                        <td align='right'  >Rp. ".Formatting::currency($rowth1->subtotal)."</td>

                        </tr>";
                        }
                    }else {

                       

                        $jumlah = count($tahun1);              
                       for ($i = 0; $i < $jumlah; $i++) {
                        $TotalBelanja += $tahun1[$i]->subtotal;
                        }
                         echo "
                        <tr>
                        <td colspan='3' align='left' width='90' style=' border-bottom:none ; border-top:none ; padding-left:10px ' >5</td>
                        <td colspan='3' style=' border-bottom:none ; border-top:none ; padding-left:5px' ><b> BELANJA </b></td>
                        <td ></td>
                        <td ></td>
                        <td > </td>
                        <td align='right' > ". Formatting::currency($TotalBelanja)." </td>
                        </tr>";

                        $jenis = $rowth1->kode_jenis_belanja;                               $jenisText = substr_replace($jenis,"", 0, -1);
                        $jumlahJB = count($tahun1);              
                       for ($i = 0; $i < $jumlahJB; $i++) {
                        if ($jenis == $tahun1[$i]->kode_jenis_belanja){
                            $TotalJenisBelanja += $tahun1[$i]->subtotal;
                        }                       
                        }
                       
                        echo "
                        <tr>
                        <td colspan='3' align='left' style=' border-bottom:none ; border-top:none ; padding-left:10px ' width='90'>5 . $jenisText </td>
                        <td colspan='3' style=' border-bottom:none ; border-top:none ; padding-left:10px' ><b> $rowth1->jenis </b></td>
                        <td></td>
                        <td ></td>
                        <td  ></td>
                        <td align='right' > ". Formatting::currency($TotalJenisBelanja)." </td>

                        </tr>";
                        $kategori = $rowth1->kode_kategori_belanja;
                        $jumlahKT = count($tahun1);              
                       for ($i = 0; $i < $jumlahKT; $i++) {
                        if ($kategori == $tahun1[$i]->kode_kategori_belanja){
                            $TotalKategoriBelanja += $tahun1[$i]->subtotal;
                        }                       
                        }                 
                        echo "
                        <tr>
                        <td colspan='3' align='left' style=' border-bottom:none ; border-top:none ; padding-left:10px' width='90'>5 . $jenisText . $kategori</td>
                        <td colspan='3' style=' border-bottom:none ; border-top:none ; padding-left:10px' ><b><div style='padding-left: 10px;'> $rowth1->kategori </div></b></td>
                        <td></td>
                        <td  ></td>
                        <td  ></td>
                        <td  align='right' > ". Formatting::currency($TotalKategoriBelanja)."</td>
                        </tr>";
                        $subkategori = $rowth1->kode_sub_kategori_belanja;
                         $jumlahSKT = count($tahun1);              
                       for ($i = 0; $i < $jumlahSKT; $i++) {
                        if ($subkategori == $tahun1[$i]->kode_sub_kategori_belanja){
                            $TotalSubKategoriBelanja += $tahun1[$i]->subtotal;
                        }                       
                        }   
                         echo "
                         <tr>
                         <td colspan='3' align='left' style=' border-bottom:none ; border-top:none ; padding-left:10px ' width='90'>5 . $jenisText . $kategori . $subkategori</td>
                         <td colspan='3' style=' border-bottom:none ; border-top:none ; padding-left:10px ' ><div style='padding-left: 20px;'> $rowth1->subkategori </div></td>
                         <td></td>
                         <td ></td>
                         <td  ></td>
                         <td align='right' > ". Formatting::currency($TotalSubKategoriBelanja)."</td>
                         </tr>";
                        $kdbelanja = $rowth1->kode_belanja; 
                        $jumlahBel = count($tahun1); 

                       for ($i = 0; $i < $jumlahBel; $i++) {
                        if ($kdbelanja == $tahun1[$i]->kode_belanja){
                            $TotalBelanja1 += $tahun1[$i]->subtotal;
                        }                       
                        }                                  
                        echo "
                        <tr>
                        <td colspan='3' align='left' style=' border-bottom:none ; border-top:none ; padding-left:10px ' width='90'>5 . $jenisText . $kategori . $subkategori . $kdbelanja</td>
                        <td colspan='3'  style=' border-bottom:none ; border-top:none ; padding-left:10px ' ><div style='padding-left: 30px;'> $rowth1->belanja </div></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td align='right' > ". Formatting::currency($TotalBelanja1)."</td>
                        </tr>";
                        $uraianbelanja = $rowth1->uraian_belanja;                   
                        echo "<tr>
                        <td colspan=3 style=' border-bottom:none ; border-top:none ; padding-left:10px ' ></td>
                        <td colspan='3' style=' border-bottom:none ; border-top:none ' ><div style='padding-left: 40px;'> $uraianbelanja </div></td>
                        <td> </td>
                        <td></td>
                        <td  ></td>
                        <td  ></td>
                        </tr>";
                        $volume = round($rowth1->volume);
                        echo "
                        <tr>
                        <td colspan=3 style=' border-bottom:none ; border-top:none ; padding-left:10px ' ></td>
                        <td colspan='3' style=' border-bottom:none ; border-top:none : padding-left:10px ' ><div style='padding-left: 32px;'> - $rowth1->detil_uraian_belanja </div></td>
                        <td align='center' style=' border-bottom:none ;   border-bottom-style:dashed' > $volume </td>
                        <td align='center' style=' border-bottom:none ; border-top:none  ; border-bottom-style:dashed' > $rowth1->satuan </td>
                        <td align='right' style=' border-bottom:none ; border-top:none  ; border-bottom-style:dashed' >".Formatting::currency($rowth1->nominal_satuan)."</td>
                        <td align='right' style=' border-bottom:none ; border-top:none  ; border-bottom-style:dashed'  >".Formatting::currency($rowth1->subtotal)."</td>

                        </tr>";
                    }
                }
                ?>
    

      


   
     <tr>
      <td id="footer1" colspan="10" ><strong>Formulir RKA SKPD 2.2.1 - Nama Dinas</strong></td>
    </tr>
  </tbody>
</table>
   
</body>
</html>

