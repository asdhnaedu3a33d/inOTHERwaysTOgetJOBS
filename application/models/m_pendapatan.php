<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_pendapatan extends CI_Model
{
	var $table_pendapatan = 't_pendapatan';
	var $table_jenis_pendapatan = 'm_pendapatan';
	var $table_asal_pendapatan = 'm_asal_pendapatan';
	var $primary_pendapatan = 'id_pendapatan';


	function get_jenis_pendapatan(){
		$this->db->select('kd_jenis AS id, nama_jenis_pendapatan AS nama');
		$result = $this->db->get($this->table_jenis_pendapatan);
		return $result->result();
	}

	function get_asal_pendapatan($kd_jenis=NULL){
		$this->db->select('kd_asal AS id, asal_pendapatan AS nama');
		if (!empty($kd_jenis)) {
			$this->db->where('kd_jenis', $kd_jenis);
		}
		$result = $this->db->get($this->table_asal_pendapatan);
		return $result->result();
	}

	function get_data($data,$table){
        $this->db->where($data);
        $query = $this->db->get($this->$table);
        return $query->row();
    }

    function insert($data,$table) {
        $this->db->insert($this->$table,$data);
        return $this->db->insert_id();
    }

    function update($id,$data,$table,$primary) {
        $this->db->where($this->$primary,$id);
        return $this->db->update($this->$table,$data);
    }

		function hard_delete($data,$table){
        return $this->db->delete($this->$table, $data);
    }

	function get_data_table($search, $start, $length, $order)
	{
		$order_arr = array('id_pendapatan');
		$sql="
			SELECT p.id_pendapatan,p.jenis_pendapatan,p.kd_asal,p.kd_jenis,
			p.realisasi_2011,p.realisasi_2012,p.proyeksi_2013,p.proyeksi_2014,p.proyeksi_2015,
			j.nama_jenis_pendapatan,a.asal_pendapatan
			 FROM ".$this->table_pendapatan." AS p
			LEFT JOIN ".$this->table_jenis_pendapatan." AS j ON p.kd_jenis=j.kd_jenis
			LEFT JOIN ".$this->table_asal_pendapatan." AS a ON p.kd_jenis=a.kd_jenis AND p.kd_asal=a.kd_asal
			WHERE jenis_pendapatan LIKE '%".$search['value']."%'

		";
		$this->db->limit($length, $start);
		$this->db->order_by($order_arr[$order["column"]], $order["dir"]);

		$result = $this->db->query($sql);
		return $result->result();
	}

	function count_data_table($search, $start, $length, $order)
	{
		$this->db->from($this->table_pendapatan);

		$this->db->like("jenis_pendapatan", $search['value']);

		$result = $this->db->count_all_results();
		return $result;
	}

	function get_data_with_rincian($id_pendapatan)
	{
		$sql="
		select a.*,p1.nama as nama_jenis_pendapatan,p2.nama as nama_parent_jenis_pendapatan from (select * from t_pendapatan where id_pendapatan = ? ) as a
		left join m_pendapatan as p1
		on a.id_jenis_pendapatan = p1.id
		left join m_pendapatan as p2
		on a.id_parent_jenis_pendapatan = p2.id

		";

		$query = $this->db->query($sql, array($id_pendapatan));

		if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}

			return NULL;
	}

	function count_total_per_asal(){
		$sql="
			SELECT SUM(realisasi_2011) as r1,
				SUM(realisasi_2012) as r2,
				SUM(proyeksi_2013) as r3,
				SUM(proyeksi_2014) as r4,
				SUM(proyeksi_2015) as r5
				from t_pendapatan where kd_asal = ?

		";
		$result = $this->db->query($sql);
		return $result->result();
	}

	function count_total_all(){
		$sql="
		SELECT (SUM(realisasi_2011) + SUM(realisasi_2012) +
		SUM(proyeksi_2013) + SUM(proyeksi_2014) + SUM(proyeksi_2015))
		from t_pendapatan;

		";

		$result = $this->db->query($sql);
		return $result->result();
	}

	function simpan_pend($data_pend)
	{
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$this->db->set($data_pend);
    	$this->db->insert('t_pendapatan');

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function get_value_autocomplete_jenis_pendapatan($search){
		$sql = "SELECT id,nama as label from m_pendapatan where parent = '0' and (kode like '%$search%' or nama like '%$search%')";
		return $this->db->query($sql)->result();
	}

	function get_value_autocomplete_sub_jenis_pendapatan($search,$parent){
		$sql = "SELECT id,nama as label from m_pendapatan where parent = '$parent' and (kode like '%$search%' or nama like '%$search%')";
		return $this->db->query($sql)->result();
		return $result->result();
	}

	function formatRupiah($rupiah)
	{
			return "Rp".number_format(sprintf('%0.2f', preg_replace("/[^0-9.]/", "", $rupiah)),2);
	}

	function get_pendapatan(){
		$output = '';
		$sql = "select a.*,p.nama,p.kode,p.id from (
			SELECT
				id_parent_jenis_pendapatan,
				sum(realisasi_1) as realisasi1,
				sum(realisasi_2) as realisasi2,
				sum(proyeksi_3) as proyeksi3,
				sum(proyeksi_4) as proyeksi4,
				sum(proyeksi_5) as proyeksi5
			FROM
				t_pendapatan AS p
			GROUP BY id_parent_jenis_pendapatan ) as a
			left join m_pendapatan as p
			on a.id_parent_jenis_pendapatan = p.id
			order by p.weight asc";
		$data_parents = $this->db->query($sql)->result();
		foreach ($data_parents as $data_parent) {
			$output .='<tr style="font-weight:bold">';
			$output .="<td>";
			$output .=$data_parent->kode;
			$output .="</td><td>";
			$output .=$data_parent->nama;
			$output .="</td><td>";
			$output .=$this->formatRupiah($data_parent->realisasi1);
			$output .="</td><td>";
			$output .=$this->formatRupiah($data_parent->realisasi2);
			$output .="</td><td>";
			$output .=$this->formatRupiah($data_parent->proyeksi3);
			$output .="</td><td>";
			$output .=$this->formatRupiah($data_parent->proyeksi4);
			$output .="</td><td>";
			$output .=$this->formatRupiah($data_parent->proyeksi5);
			$output .="</td><td>";
			$output .="</td></tr>";

			//load data bedasarkan id parent
			$sql1 = "
				select a.*,p.nama,p.kode from (select * from t_pendapatan where id_parent_jenis_pendapatan = '$data_parent->id') as a
				left join m_pendapatan as p
				on a.id_jenis_pendapatan = p.id";
			$data_details = $this->db->query($sql1)->result();
			foreach ($data_details as $data_detail) {
				$output .="<tr>";
				$output .="<td>";
				$output .=$data_detail->kode;
				$output .="</td><td>";
				$output .=$data_detail->nama;
				$output .="</td><td>";
				$output .=$this->formatRupiah($data_detail->realisasi_1);
				$output .="</td><td>";
				$output .=$this->formatRupiah($data_detail->realisasi_2);
				$output .="</td><td>";
				$output .=$this->formatRupiah($data_detail->proyeksi_3);
				$output .="</td><td>";
				$output .=$this->formatRupiah($data_detail->proyeksi_4);
				$output .="</td><td>";
				$output .=$this->formatRupiah($data_detail->proyeksi_5);
				$output .="</td><td>";
				$output .='<a href="javascript:void(0)" onclick="edit_pendapatan('. $data_detail->id_pendapatan .')" class="icon2-page_white_edit" title="Edit Data Pendapatan"/>
				<a href="javascript:void(0)" onclick="delete_pendapatan('. $data_detail->id_pendapatan .')" class="icon2-delete" title="Hapus Data Pendapatan"/>';
				$output .="</td></tr>";
			}
		}
		return $output;
	}
}
?>
