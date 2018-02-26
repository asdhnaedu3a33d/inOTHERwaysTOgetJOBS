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
     $NamaBidang =   $tahun1[0]->nama_bidang;

    $UrusanPemerintah = $tahun1[0]->kode_urusan;
     $KodeBidang =  $UrusanPemerintah." . ". $tahun1[0]->kode_bidang;
    $Organisasi = $KodeBidang." . ".$UrusanPemerintah.".".$this->session->userdata('id_skpd');
    $Program = $Organisasi." . ".$tahun1[0]->kode_program;
    $Kegiatan =  $Program." . ". $tahun1[0]->kode_kegiatan;

    function terbilang1($x){
        $abil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        if ($x < 12)
        return " " . $abil[$x];
        elseif ($x < 20)
        return terbilang1($x - 10) . "belas";
        elseif ($x < 100)
        return terbilang1($x / 10) . " puluh" . terbilang1($x % 10);
        elseif ($x < 200)
        return " seratus" . terbilang1($x - 100);
        elseif ($x < 1000)
        return terbilang1($x / 100) . " ratus" . terbilang1($x % 100);
        elseif ($x < 2000)
        return " seribu" . terbilang1($x - 1000);
        elseif ($x < 1000000)
        return terbilang1($x / 1000) . " ribu" . terbilang1($x % 1000);
        elseif ($x < 1000000000)
        return terbilang1($x / 1000000) . " juta" . terbilang1($x % 1000000);
    };


    $ta =  $tahun1[0]->tahun_anggaran;

    $ta_min = $ta-1;
     $ta_plus = $ta+1;

     if ($nominal[0]->nominal_min == 0.00 ){
      $terbilang_n_min1 = "-";
      $Nominal_n_min =  "-";
      $terbilang_n_min = "-";
      $terbilang_n_min_tulis = "-";
     }else{
      $terbilang_n_min1 = $nominal[0]->nominal_min;
      $Nominal_n_min =  Formatting::currency($nominal[0]->nominal_min);
      $terbilang_n_min = terbilang1($terbilang_n_min1);
      $terbilang_n_min_tulis = "( ". $terbilang_n_min." "."rupiah"." ".")";
     }


     if ($nominal[0]->nominal_thndpn == 0.00 ){
      $terbilang_n_plus1 = "-";
      $Nominal_n_plus = "-" ;
     $terbilang_n_plus = "-";
      $terbilang_n_plus_tulis ="-" ;
     }else{
      $terbilang_n_plus1 = $nominal[0]->nominal_thndpn;
      $Nominal_n_plus =  Formatting::currency($nominal[0]->nominal_thndpn) ;
     $terbilang_n_plus = terbilang1( $terbilang_n_plus1);
     $terbilang_n_plus_tulis = "( ". $terbilang_n_plus." "."rupiah"." ".")";
     }


     if ($nominal[0]->nominal == 0.00 ){
      $terbilang_n1 = "-";
      $Nominal_n =  "-";
      $terbilang_n = "-";
      $terbilang_n_tulis = "-";
     }else{
      $terbilang_n1 = $nominal[0]->nominal;
      $Nominal_n =  Formatting::currency($nominal[0]->nominal);
     $terbilang_n = terbilang1($terbilang_n1);
     $terbilang_n_tulis = "( ". $terbilang_n." "."rupiah"." ".")";
     }










    ?>

    <tr>
      <td id="header3" colspan="3" width="7%" style="border-right:none"  ><strong>Urusan Pemerintahan  <br> Bidang  <br> Organisasi <br> Program <br> Kegiatan</strong></td>
      <td id="header3" width="20%" style="border-left:none ; border-right:none ; padding-left:10px"   >  : <?php echo $UrusanPemerintah   ;?> <br> :  <?php echo $KodeBidang ;?> <br> : <?php echo  $Organisasi ?>  <br>  : <?= $Program ?> <br>  : <?= $Kegiatan ?></td>
      <td id="header3" width="60%" style="border-left:none ; border-right:none"  > <?php echo $tahun1[0]->nama_urusan;?>  <br> <?php echo $NamaBidang ;?>  <br> <?= $this->session->userdata('nama_skpd') ?>  <br> <?= $tahun1[0]->nama_program ;?> <br> <?= $tahun1[0]->nama_kegiatan ;?></td>
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
      <td id="header3" style="border-left:none ;" colspan="6"> <?php echo $Nominal_n_min; ?> &nbsp; <em><?php echo $terbilang_n_min_tulis; ?></em>
        <br>  <?php echo $Nominal_n; ?>  &nbsp; <em><?php echo  $terbilang_n_tulis ?> </em>  <br> <?php echo $Nominal_n_plus; ?> &nbsp; <em><?php echo  $terbilang_n_plus_tulis; ?> </em>
      </td>
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
      <td id="header4"  colspan="3">
        <?php  foreach ($capaian as $cap ) {
           echo $cap->indikator."<br>";

            ?>
            <?php
          }
            ?>
      </td>
      <td id="header4" align="right" colspan="4" style="padding-right:10px">
        <?php  foreach ($capaian as $cap ) {


            echo $cap->target." ".$cap->satuan_target ;


            ?>
            <?php
          }
            ?>
      </td>

    </tr>
    <tr>
      <td id="header4" colspan="3"><strong>MASUKAN</strong></td>
      <td id="header4" colspan="3">Uang</td>
      <td id="header4" align="right" colspan="4" style="padding-right:10px" >
      <?php

            echo "Rp."." ". Formatting::currency($nominal[0]->nominal)  ;


            ?>

      </td>

    </tr>
    <tr>
      <td id="header4" colspan="3"><strong>KELUARAN</strong></td>
      <td id="header4" colspan="3">
        <?php  foreach ($keluaran as $kel ) {
           echo $kel->indikator."<br>";

            ?>
            <?php
          }
            ?>
      </td>
      <td id="header4" align="right" colspan="4" style="padding-right:10px">
        <?php  foreach ($keluaran as $kel ) {

            echo $kel->target." ".$kel->satuan_target ;


            ?>
            <?php
          }
            ?>
      </td>

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
                        <td colspan='3' style=' border-bottom:none ; border-top:none ; padding-left:10px ' ></td>
                       <td colspan='3' style=' border-bottom:none ; border-top:none ; padding-left:10px ' ><div style='padding-left: 32px;'> - $rowth1->detil_uraian_belanja </div></td>
                        </div></td>
                        <td align='center' style=' border-bottom:none ; border-top:none  ;' > $volume </td>
                        <td align='center' style=' border-bottom:none ; border-top:none  ;' > $rowth1->satuan </td>
                        <td align='right' style=' border-bottom:none ; border-top:none  ; padding-right:10px' >".Formatting::currency($rowth1->nominal_satuan)."</td>
                        <td align='right' style=' border-bottom:none ; border-top:none  ; padding-right:10px' >".Formatting::currency($rowth1->subtotal)."</td>

                        </tr>";

                                    }else{

    $sumurai = $this->db->query("SELECT sum(subtotal) as sumur FROM t_renja_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_keg = '$idk_ng' AND uraian_belanja = '$rowth1->uraian_belanja' GROUP BY uraian_belanja")->row();

                        $uraianbelanja = $rowth1->uraian_belanja;
                        echo "<tr>
                        <td colspan=3 style=' border-bottom:none ; border-top:none ; padding-left:10px ' ></td>
                        <td colspan='3' style=' border-bottom:none ; border-top:none ' ><div style='padding-left: 40px;'> $uraianbelanja </div></td>
                        <td> </td>
                        <td></td>
                        <td  ></td>
                        <td align='right' style=' padding-right:10px'>".Formatting::currency($sumurai->sumur)."</td>
                        </tr>";
                        $volume = round($rowth1->volume);
                        echo "
                        <tr>
                        <td colspan='3' style=' border-bottom:none ; border-top:none ; padding-left:10px ' ></td>
                        <td colspan='3' style=' border-bottom:none ; border-top:none ; padding-left:10px ' ><div style='padding-left: 32px;'> - $rowth1->detil_uraian_belanja </div></td>
                         </div></td>
                        <td align='center' style=' border-bottom:none ; border-top:none  ; border-top-style:dashed;' > $volume </td>
                        <td align='center' style=' border-bottom:none ; border-top:none  ; border-top-style:dashed;' > $rowth1->satuan </td>
                        <td align='right' style=' border-bottom:none ; border-top:none  ;  border-top-style:dashed; padding-right:10px' >".Formatting::currency($rowth1->nominal_satuan)."</td>
                        <td align='right' style=' border-bottom:none ; border-top:none  ; border-top-style:dashed; padding-right:10px' >".Formatting::currency($rowth1->subtotal)."</td>

                        </tr>";

                                    }
                                }else{

                        $kdbelanja = $rowth1->kode_belanja;
    $sumurai = $this->db->query("SELECT sum(subtotal) as sumur FROM t_renja_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_keg = '$idk_ng' AND kode_belanja = '$rowth1->kode_belanja' GROUP BY kode_belanja")->row();
                        echo "
                        <tr>
                        <td colspan='3' align='left' style=' border-bottom:none ; border-top:none ; padding-left:10px ' width='90'>5 . $jenisText . $kategori . $subkategori . $kdbelanja</td>
                        <td colspan='3'  style=' border-bottom:none ; border-top:none ; padding-left:10px ' ><div style='padding-left: 30px;'> $rowth1->belanja </div></td>
                        <td></td>
                        <td  ></td>
                        <td  ></td>
                        <td align='right' style=' padding-right:10px'>".Formatting::currency($sumurai->sumur)."</td>
                        </tr>";
                        $uraianbelanja = $rowth1->uraian_belanja;
    $sumurai = $this->db->query("SELECT sum(subtotal) as sumur FROM t_renja_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_keg = '$idk_ng' AND uraian_belanja = '$rowth1->uraian_belanja' GROUP BY uraian_belanja")->row();
                        echo "<tr>
                        <td colspan='3' style=' border-bottom:none ; border-top:none ; padding-left:10px ' ></td>
                        <td colspan='3' style=' border-bottom:none ; border-top:none ' ><div style='padding-left: 40px;'> $uraianbelanja</div></td>
                        <td> </td>
                        <td></td>
                        <td  ></td>
                        <td align='right' style=' padding-right:10px'>".Formatting::currency($sumurai->sumur)."</td>
                        </tr>";
                        $volume = round($rowth1->volume);
                        echo "
                        <tr>
                        <td colspan=3 style=' border-bottom:none ; border-top:none ; padding-left:10px ' ></td>
                        <td colspan='3' style=' border-bottom:none ; border-top:none ; padding-left:10px ' ><div style='padding-left: 32px;'> - $rowth1->detil_uraian_belanja </div></td>
                         </div></td>
                        <td align='center' style=' border-bottom:none ; border-top:none ; border-top-style:dashed' > $volume </td>
                        <td align='center' style=' border-bottom:none ; border-top:none ; border-top-style:dashed'> $rowth1->satuan </td>
                        <td align='right' style='padding-right:10px; border-bottom:none ; border-top:none ; border-top-style:dashed' >".Formatting::currency($rowth1->nominal_satuan)."</td>
                        <td align='right' style='padding-right:10px; border-bottom:none ; border-top:none ; border-top-style:dashed' >".Formatting::currency($rowth1->subtotal)."</td>

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
                       <td colspan='3' style=' border-bottom:none ; border-top:none ; padding-left:10px ' ><div style='padding-left: 32px;'> - $rowth1->detil_uraian_belanja </div></td>
                         </div></td>
                        <td align='center' style=' border-bottom:none ; border-top:none ; border-top-style:dashed;' > $volume </td>
                        <td align='center' style=' border-bottom:none ; border-top:none ; border-top-style:dashed;' > $rowth1->satuan </td>
                        <td align='right' style='padding-right:10px; border-bottom:none ; border-top:none ; border-top-style:dashed; ' >".Formatting::currency($rowth1->nominal_satuan)."</td>
                        <td align='right' style='padding-right:10px; border-bottom:none ; border-top:none ; border-top-style:dashed; ' >".Formatting::currency($rowth1->subtotal)."</td>

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
                       <td colspan='3' style=' border-bottom:none ; border-top:none ; padding-left:10px ' ><div style='padding-left: 32px;'> - $rowth1->detil_uraian_belanja </div></td>
                         </div></td>
                        <td align='center' style=' border-bottom:none ; border-top:none ;' > $volume </td>
                        <td align='center' style=' border-bottom:none ; border-top:none ;' > $rowth1->satuan </td>
                        <td align='right' style='padding-right:10px; border-bottom:none ; border-top:none ;' >".Formatting::currency($rowth1->nominal_satuan)."</td>
                        <td align='right' style='padding-right:10px; border-bottom:none ; border-top:none ;' >".Formatting::currency($rowth1->subtotal)."</td>

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
                        <td align='right' style='padding-right:10px ' > ". Formatting::currency($TotalBelanja)." </td>
                        </tr>";

                        $jenis = $rowth1->kode_jenis_belanja;                               
                        $jenisText = substr_replace($jenis,"", 0, -1);
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
                        <td align='right' style='padding-right:10px ' > ". Formatting::currency($TotalJenisBelanja)." </td>

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
                        <td  align='right' style='padding-right:10px ' > ". Formatting::currency($TotalKategoriBelanja)."</td>
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
                         <td align='right' style='padding-right:10px ' > ". Formatting::currency($TotalSubKategoriBelanja)."</td>
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
                        <td align='right' style='padding-right:10px ' > ". Formatting::currency($TotalBelanja1)."</td>
                        </tr>";
                        $uraianbelanja = $rowth1->uraian_belanja;
    $sumurai = $this->db->query("SELECT sum(subtotal) as sumur FROM t_renja_belanja_kegiatan WHERE tahun = '$ta_ng' AND id_keg = '$idk_ng' AND uraian_belanja = '$rowth1->uraian_belanja' GROUP BY uraian_belanja")->row();
                        echo "<tr>
                        <td colspan=3 style=' border-bottom:none ; border-top:none ; padding-left:10px ' ></td>
                        <td colspan='3' style=' border-bottom:none ; border-top:none ' ><div style='padding-left: 40px;'> $uraianbelanja</div></td>
                        <td> </td>
                        <td></td>
                        <td  ></td>
                        <td align='right' style='padding-right:10px'>".Formatting::currency($sumurai->sumur)."</td>
                        </tr>";
                        $volume = round($rowth1->volume);
                        echo "
                        <tr>
                        <td colspan=3 style=' border-bottom:none ; border-top:none ; padding-left:10px ' ></td>
                        <td colspan='3' style=' border-bottom:none ; border-top:none ; padding-left:10px ' ><div style='padding-left: 32px;'> - $rowth1->detil_uraian_belanja </div></td>
                        <td align='center' style='border-bottom:none; border-top:none; border-top-style:dashed;'> $volume </td>
                        <td align='center' style='border-bottom:none; border-top:none; border-top-style:dashed;'> $rowth1->satuan </td>
                        <td align='right' style='border-bottom:none ; border-top:none; border-top-style:dashed; padding-right:10px'>".Formatting::currency($rowth1->nominal_satuan)."</td>
                        <td align='right' style='border-bottom:none ; border-top:none; border-top-style:dashed ; padding-right:10px'  >".Formatting::currency($rowth1->subtotal)."</td>

                        </tr>";
                    }
                }
                ?>






     <tr>
      <td id="footer1" colspan="10" ><strong>Formulir Belanja SKPD - <?= $this->session->userdata('nama_skpd') ?></strong></td>
    </tr>
  </tbody>
</table>

</body>
</html>

