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
    font-size:  10px;
}


 table, th, td {
    border: 1px solid black;
     
}

    </style>

</head>
<body>

    <?php
     $NamaBidang =   $tahun1[0]->nama_bidang;
    
    $UrusanPemerintah = $tahun1[0]->kode_urusan;
     $KodeBidang =  $UrusanPemerintah." . ". $tahun1[0]->kode_bidang;
    $Organisasi = $KodeBidang." . ".$UrusanPemerintah.".".$this->session->userdata('id_skpd');
    $Program = $Organisasi." . ".$tahun1[0]->kode_program;
    $Kegiatan =  $Program." . ". $tahun1[0]->kode_kegiatan;
    ?>
<div style="margin-bottom:-1px;">
<table width="100%"  style=" font-family:'Droid Sans', Helvetica, Arial, sans-serif;" >
  <tbody>
    <tr>
      <td width="15%" rowspan="2" style="border-right: none; padding-left:10px;"> <?php echo $logo; ?></td>
      <td colspan="9" rowspan="2" align="center" style="border-left: none"> <strong>RENCANA KERJA ANGGARAN PERUBAHAN<br>
      SATUAN KERJA PERANGKAT DAERAH</strong></th>
      <td height="51" colspan="2" align="center"><strong>NOMOR RKAP SKPD</td>
      <td width="9%" rowspan="2" align="center"><strong>Formulir<br>
        RKAP SKPD<br>
      2.2.1</strong></td>
    </tr>
    <tr>
      <td colspan="2" scope="col"><table width="100%" style="border: border: 1px solid black;"><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr></table></td>
    </tr>
    <tr>
      <td colspan="13" align="center" scope="col"><strong>PEMERINTAH KABUPATEN KLUNGKUNG</strong> <br>Tahun Anggaran <?php echo $tahun1[0]->tahun; ?></td>
    </tr>
    <tr>
      <td colspan="2" align="left"><strong>Urusan Pemerintahan</strong></td>
      <td colspan="3"  align="left"><?php echo $UrusanPemerintah ?></td>
      <td colspan="8"  align="left"><?php echo $tahun1[0]->nama_urusan;?></td>
    </tr>
    <tr>
      <td colspan="2" align="left"><strong>Bidang Pemerintahan</strong></td>
      <td colspan="3" align="left"> <?php echo $KodeBidang ;?> </td>
      <td colspan="8" align="left"><?php echo $NamaBidang ;?></td>
    </tr>
    <tr>
      <td colspan="2" align="left"><strong>Unit Organisasi</strong></td>
      <td colspan="3" align="left"><?php echo  $Organisasi ?></td>
      <td colspan="8" align="left"><?= $this->session->userdata('nama_skpd') ?></td>
    </tr>
    <tr>
      <td colspan="2" align="left"><strong>Sub Unit Organisasi</strong></td>
      <td colspan="3" align="left">&nbsp;</td>
      <td colspan="8" align="left">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2"><strong>Program</strong></td>
      <td colspan="3"><?= $Program ?></td>
      <td colspan="8"><?= $tahun1[0]->nama_program ;?></td>
    </tr>
    <tr>
      <td colspan="2"><strong>Kegiatan</strong></td>
      <td colspan="3"><?= $Kegiatan ?></td>
      <td colspan="8"><?= $tahun1[0]->nama_kegiatan ;?></td>
    </tr>
    <tr>
      <td colspan="2"><strong>Lokasi Kegiatan</strong></td>
      <td colspan="11">Baperlitbang Kab. Klungkung</td>
    </tr>
    <tr>
      <td colspan="4" style="border-right: none"><strong>Latar belakang perubahan /dianggarkan dalam<br>
      Perubahan APBD</strong></td>
      <td colspan="9"style="border-left: none">: Penambahan biaya telpon dan pengurangan biaya internet</td>
    </tr>
    <tr>
      <td colspan="13" align="center"><strong> INDIKATOR &amp; TOLOK UKUR KINERJA BELANJA LANGSUNG</strong></td>
    </tr>
    <tr>
      <td rowspan="2" align="center"><strong>INDIKATOR</strong></td>
      <td colspan="6" align="center"><strong>TOLAK UKUR KINERJA</strong></td>
      <td colspan="6" align="center"><strong>TARGET KINERJA</strong></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><strong>SEBELUM PERUBAHAN</strong></td>
      <td colspan="3" align="center"><strong>SETELAH PERUBAHAN</strong></td>
      <td colspan="3" align="center"><strong>SEBELUM PERUBAHAN</strong></td>
      <td colspan="3" align="center"><strong>SETELAH PERUBAHAN</strong></td>
    </tr>
    <tr>
      <td>CAPAIANPROGRAM</td>
      <td colspan="3"><?php  foreach ($capaian as $cap ) {
           echo $cap->indikator."<br>";
          
            ?>
            <?php
          }
            ?></td>
      <td colspan="3"><?php  foreach ($capaian as $cap ) {
           echo $cap->indikator."<br>";
          
            ?>
            <?php
          }
            ?></td>
      <td colspan="3"><?php  foreach ($capaian as $cap ) {
           echo $cap->target." ".$cap->satuan_target ."<br>";
          
            ?>
            <?php
          }
            ?></td>
      <td colspan="3"><?php  foreach ($capaian as $cap ) {
           echo $cap->target_thndpn." ".$cap->satuan_target_thndpn ."<br>";
          
            ?>
            <?php
          }
            ?></td>
    </tr>
    <tr>
      <td>MASUKAN</td>
      <td colspan="3">Uang</td>
      <td colspan="3">Uang</td>
      <td colspan="3"><?php
           
            echo "Rp."." ". Formatting::currency($nominal[0]->nominal)  ;
  
           
            ?></td>
      <td colspan="3"><?php
           
            echo "Rp."." ". Formatting::currency($nominal[0]->nominal_thndpn)  ;
  
           
            ?></td>
    </tr>
    <tr>
      <td>KELUARAN</td>
      <td colspan="3"><?php  foreach ($keluaran as $kel ) {
           echo $kel->indikator."<br>";
          
            ?>
            <?php
          }
            ?></td>
      <td colspan="3"><?php  foreach ($keluaran as $kel ) {
           echo $kel->indikator."<br>";
          
            ?>
            <?php
          }
            ?></td>
      <td colspan="3"><?php  foreach ($keluaran as $kel ) {
           echo $kel->indikator."<br>";
          
            ?>
            <?php
          }
            ?></td>
      <td colspan="3"><?php  foreach ($keluaran as $kel ) {
           echo $kel->indikator_thndpn."<br>";
          
            ?>
            <?php
          }
            ?></td>
    </tr>
    <tr>
      <td colspan="13">Kelompok Sasaran Kegiatan : Empat sambungan telepon, internet dan 6 jenis media cetak</td>
    </tr>
  </tbody>
</table>
</div>

<div style="margin-top:-50px;">
<table  width="100%"  style=" font-family:'Droid Sans', Helvetica, Arial, sans-serif;" style="margin-top:0px;">
  <thead>
  <tr>
    <th colspan="13">RINCIAN PERUBAHAN ANGGARAN BELANJA LANGSUNG PROGRAM DAN PER KEGIATAN SATUAN KERJA PERANGKAT DAERAH</th>
  </tr>
  </thead>
  <tbody>
       <tr>
      <td rowspan="3" align="center"><strong>KODE<br>
REKENING</strong></td>
      <td colspan="2" rowspan="3" align="center"><strong>URAIAN</strong></td>
      <td colspan="4" align="center"><strong>SEBELUM PERUBAHAN</strong></td>
      <td colspan="4" align="center"><strong>SETELAH PERUBAHAN</strong></td>
      <td colspan="2"><strong>Bertambah/</strong><br>
      (Berkurang)</td>
    </tr>
    <tr>
      <td colspan="3" align="center"><strong>RINCIAN PERHITUNGAN</strong></td>
      <td width="6%" rowspan="2" align="center"><strong>Jumlah</strong></td>
      <td colspan="3" align="center"><strong>RINCIAN PERHITUNGAN</strong></td>
      <td width="8%" rowspan="2" align="center"><strong>Jumlah</strong></td>
      <td width="3%" align="center">(Rp)</td>
      <td align="center">(%)</td>
    </tr>
    <tr>
      <td width="13%" align="center"><strong>Volume</strong></td>
      <td width="7%" align="center"><strong>Satuan</strong></td>
      <td width="9%" align="center"><strong>Harga Satuan</strong></td>
      <td width="6%" align="center"><strong>Volume</strong></td>
      <td width="6%" align="center"><strong>Satuan</strong></td>
      <td width="8%" align="center"><strong>Harga Satuan</strong></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">1</strong></td>
      <td colspan="2" align="center">2</strong></td>
      <td align="center"><strong>3</strong></td>
      <td align="center"><strong>4</strong></td>
      <td align="center"><strong>5</strong></td>
      <td align="center"><strong>6=3x5</strong></td>
      <td align="center"><strong>7</strong></td>
      <td align="center"><strong>8</strong></td>
      <td align="center"><strong>9</strong></td>
      <td align="center"><strong>10=7x9</strong></td>
      <td align="center"><strong>11</strong></td>
      <td align="center"><strong>12</strong></td>
    </tr>

     <?php
                $jenis = NULL; $kategori = NULL; $subkategori = NULL; $kdbelanja = NULL; $uraianbelanja = NULL;
                $TotalBelanja = 0; $TotalBelanjaPerubahan = 0; $TotalJenisBelanja=0;$TotalJenisBelanjaPerubahan=0;$TotalKategoriBelanja=0;$TotalSubKategoriBelanja=0;$TotalBelanja1=0; $TotalKategoriBelanjaPerubahan=0;
                $TotalSubKategoriBelanjaPerubahan=0;$TotalBelanja1Perubahan=0;  $selisihNominalDetil=0;
                $i=0;
                foreach ($tahun1 as $rowth1  ) { 

                    if ($rowth1->kode_jenis_belanja == $jenis) {

                        if ($rowth1->kode_kategori_belanja == $kategori) {
                            if ($rowth1->kode_sub_kategori_belanja == $subkategori) {
                                if ($rowth1->kode_belanja == $kdbelanja) {
                                    if ($rowth1->uraian_belanja == $uraianbelanja) {
                                        $volume = round($rowth1->volume);


                        $selisihNominalDetil = $rowth1->subtotal-$rowth1->subtotalperubahan;
                        $selisihNominalDetilPersen =  ($selisihNominalDetil/$rowth1->subtotal)*100;
                        if ( $selisihNominalDetil>0) {
                           $selisihNominalDetil = '('.$selisihNominalDetil.')';
                         }else{$selisihNominalDetil = Formatting::currency(abs($selisihNominalDetil)); }
                        echo "
                        <tr>
                        <td  style=' border-bottom:none ; border-top:none ; padding-left:10px ' ></td>
                        <td colspan='2' style=' border-bottom:none ; border-top:none : padding-left:10px ' ><div style='padding-left: 32px;'> - $rowth1->detil_uraian_belanja </div></td>
                        </div></td>
                        <td align='center' > $volume </td>
                        <td align='center' > $rowth1->satuan </td>
                        <td align='right' style='padding-right:10px ' >".Formatting::currency($rowth1->nominal_satuan)."</td>
                        <td align='right' style='padding-right:10px ' >".Formatting::currency($rowth1->subtotal)."</td>
                        <td> ".Formatting::currency($rowth1->volumeperubahan).".</td>
                        <td> $rowth1->satuanperubahan </td>
                        <td> ".Formatting::currency($rowth1->nominalperubahan).".</td>
                        <td>".Formatting::currency($rowth1->subtotalperubahan)."</td>
                        <td>  $selisihNominalDetil</td>
                        <td> ".abs(round($selisihNominalDetilPersen,2))." </td>
                        </tr>";

                                    }else{

                        $uraianbelanja = $rowth1->uraian_belanja;
                        echo "<tr>
                        <td  style=' border-bottom:none ; border-top:none ; padding-left:10px ' ></td>
                        <td colspan='2' style=' border-bottom:none ; border-top:none ' ><div style='padding-left: 40px;'> $uraianbelanja </div></td>
                        <td> </td>
                        <td></td>
                        <td  ></td>
                        <td  ></td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        </tr>";
                        $volume = round($rowth1->volume);
                         $selisihNominalDetil = $rowth1->subtotal-$rowth1->subtotalperubahan;
                          $selisihNominalDetilPersen =  ($selisihNominalDetil/$rowth1->subtotal)*100;
                         if ( $selisihNominalDetil>0) {
                           $selisihNominalDetil = '('.$selisihNominalDetil.')';
                         }else{ $selisihNominalDetil = Formatting::currency(abs($selisihNominalDetil)); }
                        echo "
                        <tr>
                        <td  style=' border-bottom:none ; border-top:none ; padding-left:10px ' ></td>
                        <td colspan='2' style=' border-bottom:none ; border-top:none : padding-left:10px ' ><div style='padding-left: 32px;'> - $rowth1->detil_uraian_belanja </div></td>
                         </div></td>
                        <td align='center' > $volume </td>
                        <td align='center' > $rowth1->satuan </td>
                        <td align='right' style='padding-right:10px ' >Rp. ".Formatting::currency($rowth1->nominal_satuan)."</td>
                        <td align='right' style='padding-right:10px '  >Rp. ".Formatting::currency($rowth1->subtotal)."</td>
                        <td> </td>
                        <td> </td>
                        <td>".Formatting::currency($rowth1->nominalperubahan)." </td>
                        <td>".Formatting::currency($rowth1->subtotalperubahan)." </td>
                        <td> $selisihNominalDetil </td>
                        <td> ".abs(round($selisihNominalDetilPersen,2))."  </td>
                        </tr>";

                                    }
                                }else{

                        $kdbelanja = $rowth1->kode_belanja;
                        echo "
                        <tr>
                        <td align='left' style=' border-bottom:none ; border-top:none ; padding-left:10px ' width='90'>5 . $jenisText . $kategori . $subkategori . $kdbelanja</td>
                        <td colspan='2'  style=' border-bottom:none ; border-top:none ; padding-left:10px ' ><div style='padding-left: 30px;'> $rowth1->belanja </div></td>
                        <td></td>
                        <td  ></td>
                        <td  ></td>
                        <td></td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        </tr>";
                        $uraianbelanja = $rowth1->uraian_belanja;
                        echo "<tr>
                        <td style=' border-bottom:none ; border-top:none ; padding-left:10px ' ></td>
                        <td colspan='2' style=' border-bottom:none ; border-top:none ' ><div style='padding-left: 40px;'> $uraianbelanja </div></td>
                        <td> </td>
                        <td></td>
                        <td  ></td>
                        <td  ></td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        </tr>";
                        $volume = round($rowth1->volume);
                        $selisihNominalDetil = $rowth1->subtotal-$rowth1->subtotalperubahan;
                        $selisihNominalDetilPersen =  ($selisihNominalDetil/$rowth1->subtotal)*100;
                         if ( $selisihNominalDetil>0) {
                           $selisihNominalDetil = '('.$selisihNominalDetil.')';
                         }else{ $selisihNominalDetil = Formatting::currency(abs($selisihNominalDetil)); }
                        echo "
                        <tr>
                        <td  style=' border-bottom:none ; border-top:none ; padding-left:10px ' ></td>
                        <td colspan='2' style=' border-bottom:none ; border-top:none : padding-left:10px ' ><div style='padding-left: 32px;'> - $rowth1->detil_uraian_belanja </div></td>
                         </div></td>
                        <td align='center' > $volume </td>
                        <td align='center' > $rowth1->satuan </td>
                        <td align='right' style='padding-right:10px ' >".Formatting::currency($rowth1->nominal_satuan)."</td>
                        <td align='right' style='padding-right:10px ' >".Formatting::currency($rowth1->subtotal)."</td>
                        <td> </td>
                        <td> </td>
                        <td> ".Formatting::currency($rowth1->nominalperubahan)."</td>
                        <td> ".Formatting::currency($rowth1->subtotalperubahan)."</td>
                        <td> $selisihNominalDetil </td>
                        <td>".abs(round($selisihNominalDetilPersen,2))."</td>
                        </tr>";
                                }
                            }else{



                        $subkategori = $rowth1->kode_sub_kategori_belanja;
                         echo "
                         <tr>
                         <td  align='left' style=' border-bottom:none ; border-top:none ; padding-left:10px ' width='90'>5 . $jenisText . $kategori . $subkategori</td>
                         <td colspan='2' style=' border-bottom:none ; border-top:none ; padding-left:10px ' ><div style='padding-left: 20px;'> $rowth1->subkategori </div></td>
                         <td></td>
                         <td ></td>
                         <td  ></td>
                         <td></td>
                         <td> </td>
                         <td> </td>
                         <td> </td>
                         <td> </td>
                         <td> </td>
                         <td> </td>
                         </tr>";
                        $kdbelanja = $rowth1->kode_belanja;
                        echo "
                        <tr>
                        <td  align='left' style=' border-bottom:none ; border-top:none ; padding-left:10px ' width='90'>5 . $jenisText . $kategori . $subkategori . $kdbelanja</td>
                        <td colspan='2'  style=' border-bottom:none ; border-top:none ; padding-left:10px ' ><div style='padding-left: 30px;'> $rowth1->belanja </div></td>
                        <td></td>
                        <td  ></td>
                        <td  ></td>
                        <td></td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        </tr>";
                        $uraianbelanja = $rowth1->uraian_belanja;
                        echo "<tr>
                        <td  style=' border-bottom:none ; border-top:none ; padding-left:10px ' ></td>
                        <td colspan='2' style=' border-bottom:none ; border-top:none ' ><div style='padding-left: 40px;'> $uraianbelanja </div></td>
                        <td> </td>
                        <td></td>
                        <td  ></td>
                        <td  ></td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        </tr>";
                        $volume = round($rowth1->volume);
                        $selisihNominalDetil = $rowth1->subtotal-$rowth1->subtotalperubahan;
                        $selisihNominalDetilPersen =  ($selisihNominalDetil/$rowth1->subtotal)*100;
                         if ( $selisihNominalDetil>0) {
                           $selisihNominalDetil = '('.$selisihNominalDetil.')';
                         }else{ $selisihNominalDetil = Formatting::currency(abs($selisihNominalDetil)); }
                        echo "
                        <tr>
                        <td 
                        style=' border-bottom:none ; border-top:none ; padding-left:10px ' ></td>
                       <td colspan='2' style=' border-bottom:none ; border-top:none : padding-left:10px ' ><div style='padding-left: 32px;'> - $rowth1->detil_uraian_belanja </div></td>
                         </div></td>
                        <td align='center' > $volume </td>
                        <td align='center' > $rowth1->satuan </td>
                        <td align='right' style='padding-right:10px ' > ".Formatting::currency($rowth1->nominal_satuan)."</td>
                        <td align='right' style='padding-right:10px ' > ".Formatting::currency($rowth1->subtotal)."</td>
                        <td> $rowth1->volumeperubahan </td>
                        <td> </td>
                        <td>".Formatting::currency($rowth1->nominalperubahan)." </td>
                        <td>".Formatting::currency($rowth1->subtotalperubahan)." </td>
                        <td> $selisihNominalDetil</td>
                        <td>".abs(round($selisihNominalDetilPersen,2))." </td>
                        </tr>";
                            }
                        }else{


                        $kategori = $rowth1->kode_kategori_belanja;
                        echo "
                        <tr>
                        <td  align='left' style=' border-bottom:none ; border-top:none ; padding-left:10px' width='90'>5 . $jenisText . $kategori</td>
                        <td colspan='2' style=' border-bottom:none ; border-top:none ; padding-left:10px' ><b><div style='padding-left: 10px;'> $rowth1->kategori </div></b></td>
                        <td></td>
                        <td  ></td>
                        <td  ></td>
                        <td></td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        </tr>";
                        $subkategori = $rowth1->kode_sub_kategori_belanja;
                         echo "
                         <tr>
                         <td  align='left' style=' border-bottom:none ; border-top:none ; padding-left:10px ' width='90'>5 . $jenisText . $kategori . $subkategori</td>
                         <td colspan='2' style=' border-bottom:none ; border-top:none ; padding-left:10px ' ><div style='padding-left: 20px;'> $rowth1->subkategori </div></td>
                         <td></td>
                         <td ></td>
                         <td  ></td>
                         <td></td>
                         <td> </td>
                         <td> </td>
                         <td> </td>
                         <td> </td>
                         <td> </td>
                         <td> </td>
                         </tr>";
                        $kdbelanja = $rowth1->kode_belanja;
                        echo "
                        <tr>
                        <td  align='left' style=' border-bottom:none ; border-top:none ; padding-left:10px ' width='90'>5 . $jenisText . $kategori . $subkategori . $kdbelanja</td>
                        <td colspan='2'  style=' border-bottom:none ; border-top:none ; padding-left:10px ' ><div style='padding-left: 30px;'> $rowth1->belanja </div></td>
                        <td></td>
                        <td  ></td>
                        <td  ></td>
                        <td></td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        </tr>";
                        $uraianbelanja = $rowth1->uraian_belanja;
                        echo "<tr>
                        <td  style=' border-bottom:none ; border-top:none ; padding-left:10px ' ></td>
                        <td colspan='2' style=' border-bottom:none ; border-top:none ' ><div style='padding-left: 40px;'> $uraianbelanja </div></td>
                        <td> </td>
                        <td></td>
                        <td  ></td>
                        <td  ></td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        </tr>";
                        $volume = round($rowth1->volume);
                        echo "
                        <tr>
                        <td  style=' border-bottom:none ; border-top:none ; padding-left:10px ' ></td>
                       <td colspan='2' style=' border-bottom:none ; border-top:none : padding-left:10px ' ><div style='padding-left: 32px;'> - $rowth1->detil_uraian_belanja </div></td>
                         </div></td>
                        <td align='center' > $volume </td>
                        <td align='center' > $rowth1->satuan </td>
                        <td align='right' style='padding-right:10px ' >Rp. ".Formatting::currency($rowth1->nominal_satuan)."</td>
                        <td align='right' style='padding-right:10px ' >Rp. ".Formatting::currency($rowth1->subtotal)."</td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td>Rp. ".Formatting::currency($rowth1->subtotalperubahan)."</td>
                        <td> </td>
                        <td> </td>

                        </tr>";
                        }
                    }else {



                        $jumlah = count($tahun1);
                       for ($i = 0; $i < $jumlah; $i++) {
                        $TotalBelanja += $tahun1[$i]->subtotal;
                         $TotalBelanjaPerubahan += $tahun1[$i]->subtotalperubahan;
                        }
                         $selisihbelanja = $TotalBelanja-$TotalBelanjaPerubahan;
                         $selisihbelanjaPersen =  ($selisihbelanja/$TotalBelanja)*100;
                         if ( $selisihbelanja>0) {
                           $selisihbelanja = '('.$selisihbelanja.')';
                         }else{ $selisihbelanja = Formatting::currency(abs($selisihbelanja)); }
                         echo "
                        <tr>
                        <td  align='left' width='90' style=' border-bottom:none ; border-top:none ; padding-left:10px ' >5</td>
                        <td colspan='2' style=' border-bottom:none ; border-top:none ; padding-left:5px' ><b> BELANJA </b></td>
                        <td ></td>
                        <td ></td>
                        <td > </td>
                        <td align='right' style='padding-right:10px ' > ". Formatting::currency($TotalBelanja)." </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> ". Formatting::currency($TotalBelanjaPerubahan)."</td>
                        <td>  $selisihbelanja </td>
                        <td>  ".abs(round($selisihbelanjaPersen,2))." </td>
                        </tr>";

                        $jenis = $rowth1->kode_jenis_belanja;                               $jenisText = substr_replace($jenis,"", 0, -1);
                        $jumlahJB = count($tahun1);
                       for ($i = 0; $i < $jumlahJB; $i++) {
                        if ($jenis == $tahun1[$i]->kode_jenis_belanja){
                            $TotalJenisBelanja += $tahun1[$i]->subtotal;
                            $TotalJenisBelanjaPerubahan += $tahun1[$i]->subtotal;
                            $selisihjenisbelanja = $TotalJenisBelanja-$TotalJenisBelanjaPerubahan;
                            $selisihjenisbelanjaPersen =  ($selisihjenisbelanja/$TotalJenisBelanja)*100;
                               if ( $selisihjenisbelanja>0) {
                                 $selisihjenisbelanja = '('.$selisihjenisbelanja.')';
                               }else{  $selisihjenisbelanja = Formatting::currency(abs($selisihjenisbelanja));  }
                        }
                        }

                        echo "
                        <tr>
                        <td  align='left' style=' border-bottom:none ; border-top:none ; padding-left:10px ' width='90'>5 . $jenisText </td>
                        <td colspan='2' style=' border-bottom:none ; border-top:none ; padding-left:10px' ><b> $rowth1->jenis </b></td>
                        <td></td>
                        <td ></td>
                        <td  ></td>
                        <td align='right' style='padding-right:10px ' > ". Formatting::currency($TotalJenisBelanja)." </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td>". Formatting::currency($TotalJenisBelanjaPerubahan)."  </td>
                        <td>  $selisihjenisbelanja</td>
                        <td> ".abs(round($selisihjenisbelanjaPersen,2))." </td>

                        </tr>";
                        $kategori = $rowth1->kode_kategori_belanja;
                        $jumlahKT = count($tahun1);
                       for ($i = 0; $i < $jumlahKT; $i++) {
                        if ($kategori == $tahun1[$i]->kode_kategori_belanja){
                            $TotalKategoriBelanja += $tahun1[$i]->subtotal;
                            $TotalKategoriBelanjaPerubahan += $tahun1[$i]->subtotal;
                            $selisihketegoribelanja = $TotalKategoriBelanja-$TotalKategoriBelanjaPerubahan;
                            $selisihketegoribelanjaPersen =  ($selisihketegoribelanja/$TotalKategoriBelanja)*100;
                               if ( $selisihketegoribelanja>0) {
                                 $selisihketegoribelanja = '('.$selisihketegoribelanja.')';
                               }else{  $selisihketegoribelanja = Formatting::currency(abs($selisihketegoribelanja)); }
                        }
                        }
                        echo "
                        <tr>
                        <td  align='left' style=' border-bottom:none ; border-top:none ; padding-left:10px' width='90'>5 . $jenisText . $kategori</td>
                        <td colspan='2' style=' border-bottom:none ; border-top:none ; padding-left:10px' ><b><div style='padding-left: 10px;'> $rowth1->kategori </div></b></td>
                        <td></td>
                        <td  ></td>
                        <td  ></td>
                        <td  align='right' style='padding-right:10px ' > ". Formatting::currency($TotalKategoriBelanja)."</td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> ". Formatting::currency($TotalKategoriBelanjaPerubahan)." </td>
                        <td> $selisihketegoribelanja</td>
                        <td> ".abs(round($selisihketegoribelanjaPersen,2))."</td>
                        </tr>";
                        $subkategori = $rowth1->kode_sub_kategori_belanja;
                         $jumlahSKT = count($tahun1);
                       for ($i = 0; $i < $jumlahSKT; $i++) {
                        if ($subkategori == $tahun1[$i]->kode_sub_kategori_belanja){
                            $TotalSubKategoriBelanja += $tahun1[$i]->subtotal;
                            $TotalSubKategoriBelanjaPerubahan += $tahun1[$i]->subtotal;
                            $selisihsubketegoribelanja = $TotalSubKategoriBelanja-$TotalSubKategoriBelanjaPerubahan;
                            $selisihsubketegoribelanjaPersen =  ($selisihsubketegoribelanja/$TotalSubKategoriBelanja)*100;
                               if ( $selisihsubketegoribelanja>0) {
                                 $selisihsubketegoribelanja = '('.$selisihsubketegoribelanja.')';
                               }else{ $selisihsubketegoribelanja = Formatting::currency(abs($selisihsubketegoribelanja));  }
                          }
                        }
                         echo "
                         <tr>
                         <td  align='left' style=' border-bottom:none ; border-top:none ; padding-left:10px ' width='90'>5 . $jenisText . $kategori . $subkategori</td>
                         <td colspan='2' style=' border-bottom:none ; border-top:none ; padding-left:10px ' ><div style='padding-left: 20px;'> $rowth1->subkategori </div></td>
                         <td></td>
                         <td ></td>
                         <td  ></td>
                         <td align='right' style='padding-right:10px ' > ". Formatting::currency($TotalSubKategoriBelanja)."</td>
                         <td> </td>
                         <td> </td>
                         <td> </td>
                         <td> ". Formatting::currency($TotalSubKategoriBelanjaPerubahan)."</td>
                         <td> $selisihsubketegoribelanja</td>
                         <td> ".abs(round($selisihsubketegoribelanjaPersen,2))." </td>
                         </tr>";
                        $kdbelanja = $rowth1->kode_belanja;
                        $jumlahBel = count($tahun1);

                       for ($i = 0; $i < $jumlahBel; $i++) {
                        if ($kdbelanja == $tahun1[$i]->kode_belanja){
                            $TotalBelanja1 += $tahun1[$i]->subtotal;
                            $TotalBelanja1Perubahan+=$tahun1[$i]->subtotal;
                            $selisihbelanja1 = $TotalBelanja1-$TotalBelanja1Perubahan;
                            $selisihbelanja1Persen =  ($selisihbelanja1/$TotalBelanja1)*100;
                               if ( $selisihbelanja1>0) {
                                 $selisihbelanja1 = '('.$selisihbelanja1.')';
                               }else{ $selisihbelanja1 = Formatting::currency(abs($selisihbelanja1)); }
                        }
                        }
                        echo "
                        <tr>
                        <td  align='left' style=' border-bottom:none ; border-top:none ; padding-left:10px ' width='90'>5 . $jenisText . $kategori . $subkategori . $kdbelanja</td>
                        <td colspan='2'  style=' border-bottom:none ; border-top:none ; padding-left:10px ' ><div style='padding-left: 30px;'> $rowth1->belanja </div></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td align='right' style='padding-right:10px ' > ". Formatting::currency($TotalBelanja1)."</td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> ". Formatting::currency($TotalBelanja1Perubahan)."</td>
                        <td>  $selisihbelanja1</td>
                        <td> ".abs(round($selisihbelanja1Persen,2))." </td>
                        </tr>";
                        $uraianbelanja = $rowth1->uraian_belanja;
                        echo "<tr>
                        <td style=' border-bottom:none ; border-top:none ; padding-left:10px ' ></td>
                        <td colspan='2' style=' border-bottom:none ; border-top:none ' ><div style='padding-left: 40px;'> $uraianbelanja </div></td>
                        <td> </td>
                        <td></td>
                        <td  ></td>
                        <td  ></td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        </tr>";
                        $volume = round($rowth1->volume);
                         $selisihNominalDetil = $rowth1->subtotal-$rowth1->subtotalperubahan;
                          $selisihNominalDetilPersen =  ($selisihNominalDetil/$rowth1->subtotal)*100;
                        if ( $selisihNominalDetil>0) {
                           $selisihNominalDetil = '('.$selisihNominalDetil.')';
                         }else{  $selisihNominalDetil = Formatting::currency(abs($selisihNominalDetil));  }
                        echo "
                        <tr>
                        <td  style=' border-bottom:none ; border-top:none ; padding-left:10px ' ></td>
                        <td colspan='2' style=' border-bottom:none ; border-top:none : padding-left:10px ' ><div style='padding-left: 32px;'> - $rowth1->detil_uraian_belanja </div></td>
                        <td align='center' style=' border-bottom:none ;   border-bottom-style:dashed' > $volume </td>
                        <td align='center' style=' border-bottom:none ; border-top:none  ; border-bottom-style:dashed' > $rowth1->satuan </td>
                        <td align='right' style=' border-bottom:none ; border-top:none  ; border-bottom-style:dashed; padding-right:10px;' >".Formatting::currency($rowth1->nominal_satuan)."</td>
                        <td align='right' style=' border-bottom:none ; border-top:none  ; border-bottom-style:dashed; padding-right:10px;' >".Formatting::currency($rowth1->subtotal)."</td>
                        <td> $rowth1->volumeperubahan)</td>
                        <td>$rowth1->satuanperubahan </td>
                        <td  align='right' style=' border-bottom:none ; border-top:none  ; border-bottom-style:dashed; padding-right:10px;'> ".Formatting::currency($rowth1->nominalperubahan)."</td>
                        <td  align='right' style=' border-bottom:none ; border-top:none  ; border-bottom-style:dashed; padding-right:10px;'>".Formatting::currency($rowth1->subtotalperubahan)." </td>
                        <td  align='right' style=' border-bottom:none ; border-top:none  ; border-bottom-style:dashed; padding-right:10px;'>  $selisihNominalDetil</td>
                        <td> ".abs(round($selisihNominalDetilPersen,2))."  </td>
                        </tr>";
                    }
                $i++;}
                ?>
  </tbody>
</table>
</div>

</body>
</html>
