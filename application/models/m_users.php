<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_users extends CI_Model {
	
    public function __construct(){
        parent::__construct();
    }
	
	function get_user_by_credential($uname, $passwd)
	{
		$sql = "
			SELECT *
			FROM m_users 
			WHERE username = ?
				AND password = ?
			LIMIT 1
			";
			
		$query = $this->db->query($sql, array($uname, $passwd));
		if( $query->num_rows() > 0 ) {
			return $query->row();
		}
		
		return FALSE;
	}
	
	function get_user_by_id($id_user)
	{
		$sql = "
			SELECT *
			FROM m_users
			WHERE id_user = ?
			LIMIT 1;
		";
		
		$query = $this->db->query($sql, array($id_user));
		if( $query->num_rows() > 0 ) {
			return $query->row();
		}
		
		return FALSE;		
	}
	
	function get_user_by_id_complete($id_user)
	{
		$sql = "
			SELECT a.*, b.nama_skpd, b.kode_skpd, b.id_bidkoor
			, c.nama_desa, c.kode_desa,c.id_kec
			FROM m_users a
				LEFT JOIN m_skpd b ON a.id_skpd=b.id_skpd
				LEFT JOIN m_desa c ON a.id_desa=c.id_desa
			WHERE id_user = ?
			LIMIT 1;
		";
		
		$query = $this->db->query($sql, array($id_user));
		if( $query->num_rows() > 0 ) {
			return $query->row();
		}
		
		return FALSE;		
	}
}

?>