<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/** 
 * @brief Library yang merupakan kumpulan fungsi yang digunakan secara belulang dalam SIAKU 
 * 
 * Library yang merupakan kumpulan fungsi yang digunakan secara belulang dalam SIAKU.
 * 
 * @author Putu Praba Santika
 */
class Alat {
	var $CI = NULL;
	function __construct(){
		$this->CI =& get_instance();
	}
    /**
     * Menambah history berbentuk xml untuk suatu transaksi.
     * @param tabel string nama tabel
     * @param kolom string nama kolom tempat history
     * @param kolom_batas string imbuhan nama untuk id dan th pada tabel
     * @param id integer id baris yang akan ditambah komentar
     * @param kodeStatus integer kode status sesuai tabel m_lov, atau string status khusus
     * @param komentar string komentar  dari baris yang diupdate
     */
	function history_tambah($tabel, $kolom, $id, $kodeStatus, $komentar){
        $kolom_riwayat  ="riwayat_".$kolom;
        $id_riwayat     ="id_".$kolom;
        
        
        $this->CI->db->select( $kolom_riwayat);
        $this->CI->db->from($tabel);
        $this->CI->db->where($id_riwayat, $id);
        $result = $this->CI->db->get();
        $row=$result->row_array();
        if (isset($result)){
            if(isset($row[$kolom_riwayat])){
                $xml= new SimpleXMLElement($row[$kolom_riwayat]);
                $lastNode=$xml->revision[count($xml->revision) - 1];
                $revMax = $lastNode['rev']+1;
            }else{
                $xml= new SimpleXMLElement('<history></history>');
                $revMax=1;
            }
            
            $this->CI->db->select('nama_value');
            $this->CI->db->from('m_lov');
            $this->CI->db->where("kode_value", $kodeStatus);
            $result = $this->CI->db->get();
            $row=$result->row();
            if($row && isset($row->nama_value) ){
                $status=$row->nama_value;
            }else{
                $status=$kodeStatus;
            }
            
            
            date_default_timezone_set('Etc/GMT-8');
            $statusDate=date('Y-m-d H:i:s');
            $statusBy=$this->CI->session->userdata('username');
            
            $rev=$xml->addChild('revision');
        	$rev->addAttribute('rev', $revMax);
            $rev->addChild('date',$statusDate);
        	$rev->addChild('status',$status);
        	$rev->addChild('coment',$komentar);
        	$rev->addChild('by',$statusBy);
                        
            //echo htmlentities($xml->asXML()) ;
            
            $this->CI->db->where($id_riwayat, $id);
            
            $this->CI->db->set($kolom_riwayat,$xml->asXML());
            
            $this->CI->db->update($tabel);
            
            
            //echo $this->CI->db->last_query();
            //exit;
            
        }else{
            //kolom atau tabel salah
        }
	}
    
    /**
     * update jumlah pencetakan suatu transaksi.
     * @param tabel string nama tabel
     * @param kolom string nama kolom tempat history
     * @param kolom_batas string imbuhan nama untuk id dan th pada tabel
     * @param th integer tahun baris yang akan ditambah komentar
     * @param id integer id baris yang akan ditambah komentar
     * @param kodeStatus integer kode status sesuai tabel m_lov, atau string status khusus
     * @param komentar string komentar  dari baris yang diupdate
     */
	function update_print($tabel, $kolom, $id){
        $kolom_print  ="print_".$kolom;
        $id_print     ="id_".$kolom;
        
        
        $this->CI->db->select($kolom_print);
        $this->CI->db->from($tabel);
        $this->CI->db->where($id_print, $id);
        $result = $this->CI->db->get();
        $row=$result->row_array();
        if (isset($result)){
            if(isset($row[$kolom_print])){
                $banyak = $row[$kolom_print]+1;
            }else{
                $banyak=1;
            }
            
            $this->CI->db->where($id_print, $id);
            
            $this->CI->db->set($kolom_print,$banyak);
            
            $this->CI->db->update($tabel);
            
            //echo $this->CI->db->last_query();
            //exit;
            
        }else{
            //kolom atau tabel salah
        }
	}
	
    function cutNews($string, $length) {
        $string = strip_tags($string);
        $tail   = max(0, $length - 10);
        $trunk  = substr($string, 0, $tail);
        $trunk .= strrev(preg_replace('~^..+?[\s,:]\b|^...~', '...', strrev(substr($string, $tail, $length - $tail))));
        return $trunk;
    }
}
