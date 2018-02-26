<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*! Library untuk membuat penomoran database */
/** 
 * @brief Library untuk membuat penomoran ketika insert database
 * 
 * Library untuk membuat penomoran ketika insert database 
 * 
 * @author Putu Praba Santika
 */
class Nomer {
	var $CI = NULL;
	function __construct(){
		$this->CI =& get_instance();
	}
    
    /**
     * Pembuatan id dan nomer kwitansi untuk insert tabel
     * @param tgl_kwitansi berisi tanggal witansi
     * @param idsu berisi id subunit
     * @param sifat_bayar berisi sifat pembayaran sesuai lib constant
     * @return array berisi id_kwitansi, no_kwitansi, id_unit
     */
     
    function gen_no_kwitansi($tgl_kwitansi, $idsu, $sifat_bayar){
        //TODO: ada kemungkinan nomor duplicate, jika direquest bersamaan
        $ta=date('Y',strtotime($tgl_kwitansi));
        $bln=date('m',strtotime($tgl_kwitansi));
        $this->CI->load->model('m_umum');
        $su_det=$this->CI->m_umum->subunit_detail($idsu,Constant::JR_ID);
        if (isset($su_det->id_unit)){
            $id_unit=$su_det->id_unit;
        }else{
            $id_unit='';
        }
            
        $sql = "
                SELECT IFNULL(MAX(
                    CAST(SUBSTR(no_kwitansi,-4) AS SIGNED)
                    ),0) + 1 AS gede
                FROM t_kwitansi
                WHERE YEAR(tgl_kwitansi)='$ta' 
                    AND MONTH(tgl_kwitansi)='$bln'
                    AND id_unit=$id_unit
            ";
        $result = $this->CI->db->query($sql);    
        $row = $result->row();
        
        $no_baru = sprintf("%04d", $row->gede);
        $bulan= sprintf("%02d", $bln);
        $idsubunit= sprintf("%03d", $idsu);
        
        $row_baru['id_unit']=$id_unit;
        $row_baru['id_subunit']=$idsu;
        $row_baru['tgl_kwitansi']=$tgl_kwitansi;
        $row_baru['no_kwitansi']="KW/".$sifat_bayar."/".$idsubunit."/".$ta."/".$bulan."/".$no_baru;
        $row_baru['sifat_bayar']=$sifat_bayar;
        
        $this->CI->db->insert('t_kwitansi', $row_baru);
        $row_baru['id_kwitansi']= $this->CI->db->insert_id();
        return $row_baru;
    }
    
    /**
     * Pembuatan id dan nomer sptb untuk insert tabel
     * @param tgl_sptb berisi tanggal sptb
     * @param idu berisi id unit
     * @param sifat_bayar berisi sifat pembayaran sesuai lib constant
     * @return array berisi id_sptb, no_sptb, id_unit
     */
    
    function gen_no_sptb($tgl_sptb, $idu, $sifat_bayar){
        $ta=date('Y',strtotime($tgl_sptb));
        $bln=date('m',strtotime($tgl_sptb));
        $sql = "
            SELECT IFNULL(MAX(
                CAST(SUBSTR(no_sptb,-4) AS SIGNED)
                ),0) + 1 AS gede
            FROM t_sptb
            WHERE YEAR(tgl_sptb)='$ta'
                AND id_unit=$idu
        ";
        $result = $this->CI->db->query($sql);    
        $row = $result->row();
        
        $no_baru = sprintf("%04d", $row->gede);
        $id_unit= sprintf("%02d", $idu);
        $bulan= sprintf("%02d", $bln);
        
        $row_baru['id_unit']=$id_unit;
        $row_baru['tgl_sptb']=$tgl_sptb;
        $row_baru['no_sptb']="SPTB/".$sifat_bayar."/".$id_unit."/".$ta."/".$bulan."/".$no_baru;
        $row_baru['sifat_bayar']=$sifat_bayar;
        
        $this->CI->db->insert('t_sptb', $row_baru);
        $row_baru['id_sptb']= $this->CI->db->insert_id();
        return $row_baru;
    }
    
    /**
     * Pembuatan id dan nomer spp untuk insert tabel
     * @param tgl_spp berisi tanggal sptb
     * @param idu berisi id unit
     * @param sifat_bayar berisi sifat pembayaran sesuai lib constant
     * @return array berisi id_unit, no_sptb, id_unit
     */
    
    function gen_no_spp($tgl_spp, $idu, $sifat_bayar){
        $ta=date('Y',strtotime($tgl_spp));
        $bln=date('m',strtotime($tgl_spp));
        $sql = "
            SELECT IFNULL(MAX(
                CAST(SUBSTR(no_spp,-4) AS SIGNED)
                ),0) + 1 AS gede
            FROM t_spp
            WHERE YEAR(tgl_spp)='$ta'
                AND id_unit=$idu
        ";
        $result = $this->CI->db->query($sql);    
        $row = $result->row();
        
        $no_baru = sprintf("%04d", $row->gede);
        $id_unit= sprintf("%02d", $idu);
        $bulan= sprintf("%02d", $bln);
        
        $row_baru['id_unit']=$id_unit;
        $row_baru['tgl_spp']=$tgl_spp;
        $row_baru['no_spp']="SPP/".$sifat_bayar."/".$id_unit."/".$ta."/".$bulan."/".$no_baru;
        $row_baru['sifat_bayar']=$sifat_bayar;
        
        $this->CI->db->insert('t_spp', $row_baru);
        $row_baru['id_spp']= $this->CI->db->insert_id();
        return $row_baru;
    }
    
    /**
     * Pembuatan id dan nomer spm untuk insert tabel
     * @param tgl_spm berisi tanggal spm
     * @param idu berisi id unit
     * @param sifat_bayar berisi sifat pembayaran sesuai lib constant
     * @return array berisi id_spm, no_spm, id_unit
     */
    
    function gen_no_spm($tgl_spm, $idu, $sifat_bayar){
        $ta=date('Y',strtotime($tgl_spm));
        $bln=date('m',strtotime($tgl_spm));
        $sql = "
            SELECT IFNULL(MAX(
                CAST(SUBSTR(no_spm,-4) AS SIGNED)
                ),0) + 1 AS gede
            FROM t_spm
            WHERE YEAR(tgl_spm)='$ta'
                AND id_unit=$idu
        ";
        $result = $this->CI->db->query($sql);    
        $row = $result->row();
        
        $no_baru = sprintf("%04d", $row->gede);
        $id_unit= sprintf("%02d", $idu);
        $bulan= sprintf("%02d", $bln);
        
        $row_baru['id_unit']=$id_unit;
        $row_baru['tgl_spm']=$tgl_spm;
        $row_baru['no_spm']="SPM/".$sifat_bayar."/".$id_unit."/".$ta."/".$bulan."/".$no_baru;
        $row_baru['sifat_bayar']=$sifat_bayar;
        
        $this->CI->db->insert('t_spm', $row_baru);
        $row_baru['id_spm']= $this->CI->db->insert_id();
        return $row_baru;
    }
    
    /**
     * Pembuatan id dan nomer spm untuk insert tabel
     * @param tgl_sp2d berisi tanggal sp2d
     * @param sifat_bayar berisi sifat pembayaran sesuai lib constant
     * @return array berisi id_sp2d, no_sp2d, id_unit
     */
    
    function gen_no_sp2d($tgl_sp2d, $sifat_bayar){
        $ta=date('Y',strtotime($tgl_sp2d));
        $sql = "
            SELECT IFNULL(MAX(
                CAST(SUBSTR(no_sp2d,-4) AS SIGNED)
                ),0) + 1 AS gede
            FROM t_sp2d
            WHERE YEAR(tgl_sp2d)='$ta'
        ";
        $result = $this->CI->db->query($sql);    
        $row = $result->row();
        
        $no_baru = sprintf("%04d", $row->gede);
        
        $row_baru['tgl_sp2d']=$tgl_sp2d;
        $row_baru['no_sp2d']="SP2D/".$sifat_bayar."/".$ta."/".$no_baru;
        $row_baru['sifat_bayar']=$sifat_bayar;
        
        $this->CI->db->insert('t_sp2d', $row_baru);
        $row_baru['id_sp2d']= $this->CI->db->insert_id();
        return $row_baru;
    }
    
    //Ini no. bukti berdasarkan unit
    function gen_no_bukti($tgl_bukti, $idsu, $jenis_bukti,$bank_kas,$idu){
        //TODO: ada kemungkinan nomor duplicate, jika direquest bersamaan
        $ta=date('Y',strtotime($tgl_bukti));
        $bln=date('m',strtotime($tgl_bukti));
        $this->CI->load->model('m_umum');
        $u_det=$this->CI->m_umum->unit_detail($idu,Constant::JR_ID);
        if (isset($u_det->id_unit)){
            $id_unit=$u_det->id_unit;
        }else{
            $id_unit='';
        }
            
        $sql = "
                SELECT IFNULL(MAX(
                    CAST(SUBSTR(no_bukti,-4) AS SIGNED)
                    ),0) + 1 AS gede
                FROM t_bukti_akuntansi
                JOIN m_unit ON m_unit.id_unit=t_bukti_akuntansi.id_unit
                WHERE YEAR(tgl_bukti)='$ta' 
                    AND MONTH(tgl_bukti)='$bln'
                    AND m_unit.id_unit='$id_unit'
                    AND jenis_bukti='$jenis_bukti'
            ";
        $result = $this->CI->db->query($sql);    
        $row = $result->row();
        
        $no_baru = sprintf("%04d", $row->gede);
        $bulan= sprintf("%02d", $bln);
        $idsubunit= sprintf("%03d", $idsu);
        $id_unit= sprintf("%03d", $id_unit);
        
        $row_baru['id_subunit']=$idsu;
        $row_baru['tgl_bukti']=$tgl_bukti;
        $row_baru['jenis_bukti']=$jenis_bukti;
        $row_baru['no_bukti']=$bank_kas."/".$id_unit."/".$ta."/".$bulan."/".$no_baru;
        
        $this->CI->db->insert('t_bukti_akuntansi', $row_baru);
        $row_baru['id_bukti']= $this->CI->db->insert_id();
        return $row_baru;
    }

    /*
    Ini No bukti berdasarkan sub-unit
     function gen_no_bukti($tgl_bukti, $idsu, $jenis_bukti,$bank_kas){
        //TODO: ada kemungkinan nomor duplicate, jika direquest bersamaan
        $ta=date('Y',strtotime($tgl_bukti));
        $bln=date('m',strtotime($tgl_bukti));
        $this->CI->load->model('m_umum');
        $su_det=$this->CI->m_umum->subunit_detail($idsu,Constant::JR_ID);
        if (isset($su_det->id_unit)){
            $id_unit=$su_det->id_unit;
        }else{
            $id_unit='';
        }
            
        $sql = "
                SELECT IFNULL(MAX(
                    CAST(SUBSTR(no_bukti,-4) AS SIGNED)
                    ),0) + 1 AS gede
                FROM t_bukti_akuntansi
                JOIN m_subunit ON m_subunit.id_subunit=t_bukti_akuntansi.id_subunit
                WHERE YEAR(tgl_bukti)='$ta' 
                    AND MONTH(tgl_bukti)='$bln'
                    AND m_subunit.id_unit='$id_unit'
                    AND jenis_bukti='$jenis_bukti'
            ";
        $result = $this->CI->db->query($sql);    
        $row = $result->row();
        
        $no_baru = sprintf("%04d", $row->gede);
        $bulan= sprintf("%02d", $bln);
        $idsubunit= sprintf("%03d", $idsu);
        
        $row_baru['id_subunit']=$idsu;
        $row_baru['tgl_bukti']=$tgl_bukti;
        $row_baru['jenis_bukti']=$jenis_bukti;
        $row_baru['no_bukti']=$bank_kas."/".$idsubunit."/".$ta."/".$bulan."/".$no_baru;
        
        $this->CI->db->insert('t_bukti_akuntansi', $row_baru);
        $row_baru['id_bukti']= $this->CI->db->insert_id();
        return $row_baru;
    }
    */
    
    function gen_no_surat_setor($tgl_surat_setor){
        //TODO: ada kemungkinan nomor duplicate, jika direquest bersamaan
        $ta=date('Y',strtotime($tgl_surat_setor));
        $bln=date('m',strtotime($tgl_surat_setor));
        
        $sql = "
                SELECT IFNULL(MAX(
                    CAST(SUBSTR(no_suratsetor,1,4) AS SIGNED)
                    ),0) + 1 AS gede
                FROM t_surat_setor
                WHERE YEAR(tgl_setor)='$ta' 
                    AND MONTH(tgl_setor)='$bln'
            ";
        $result = $this->CI->db->query($sql);    
        $row = $result->row();
        
        $no_baru = sprintf("%04d", $row->gede);
        $bulan= sprintf("%02d", $bln);
        $tahun= sprintf("%04d", $ta);
        
        $row_baru['tgl_setor']=$tgl_surat_setor;
        $row_baru['no_suratsetor']=$no_baru."/415253/".$bulan."/".$ta;
        
        $this->CI->db->insert('t_surat_setor', $row_baru);
        $row_baru['id_setor']= $this->CI->db->insert_id();
        return $row_baru;
    }

     /**
     * Pembuatan id dan nomer spj untuk insert tabel
     * @param tgl_spm berisi tanggal spj
     * @param idu berisi id unit
     * @param sifat_bayar berisi sifat pembayaran sesuai lib constant
     * @return array berisi id_spj, no_spj, id_unit
     */
    
    function gen_no_spj($created_date, $idu, $jenis_belanja){
        $ta=date('Y',strtotime($created_date));
        $bln=date('m',strtotime($created_date));
        $sql = "
            SELECT IFNULL(MAX(
                CAST(SUBSTR(no_spj,-4) AS SIGNED)
                ),0) + 1 AS gede
            FROM t_spj
            WHERE YEAR(created_date)='$ta'
                AND id_unit=$idu
        ";
        $result = $this->CI->db->query($sql);    
        $row = $result->row();
        
        $no_baru = sprintf("%04d", $row->gede);
        $id_unit= sprintf("%02d", $idu);
        $bulan= sprintf("%02d", $bln);
        
        $row_baru['id_unit']=$id_unit;
        $row_baru['created_date']=$created_date;
        $row_baru['no_spj']="SPJ/".$jenis_belanja."/".$id_unit."/".$ta."/".$bulan."/".$no_baru;
        $row_baru['sifat_bayar']=$jenis_belanja;
        
        $this->CI->db->insert('t_spj', $row_baru);
        $row_baru= $this->CI->db->insert_id();
        return $row_baru;
    }
  
	 /**
     * Pembuatan id dan nomer spj untuk insert tabel
     * @param tgl_spm berisi tanggal spj
     * @param idu berisi id unit
     * @param sifat_bayar berisi sifat pembayaran sesuai lib constant
     * @return array berisi id_spj, no_spj, id_unit
     */
    
    function gen_no_spjsu($created_date, $idSu, $jenis_belanja){
        $ta=date('Y',strtotime($created_date));
        $bln=date('m',strtotime($created_date));
        $sql = "
            SELECT IFNULL(MAX(
                CAST(SUBSTR(no_spj_su,-4) AS SIGNED)
                ),0) + 1 AS gede
            FROM t_spj_su
            WHERE YEAR(created_date)='$ta'
                AND id_subunit=$idSu
        ";
        $result = $this->CI->db->query($sql);    
        $row = $result->row();
        
        $no_baru 	= sprintf("%04d", $row->gede);
        $id_subunit	= sprintf("%02d", $idSu);
        $bulan		= sprintf("%02d", $bln);
        
        $row_baru['id_subunit']=$idSu;
        $row_baru['created_date']=$created_date;
        $row_baru['no_spj_su']="SPJ-SU/".$jenis_belanja."/".$id_subunit."/".$ta."/".$bulan."/".$no_baru;
        $row_baru['sifat_bayar']=$jenis_belanja;
        
        $this->CI->db->insert('t_spj_su', $row_baru);
        $row_baru= $this->CI->db->insert_id();
        return $row_baru;
    }
}
