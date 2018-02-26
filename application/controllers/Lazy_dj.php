<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class Lazy_dj extends CI_Controller
{
	var $CI = NULL;
	public function __construct()
	{
		$this->CI =& get_instance();
        parent::__construct();
        //$this->load->helper(array('form','url', 'text_helper','date'));
        $this->load->database();
        //$this->load->model('m_berita','',TRUE);
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

	//RKA TO DPA DJ
	function rka_to_dpa()
	{
        $query = $this->db->query("SELECT d.id AS id_dpa_indikator_prog_keg,
		 d.id_indikator_rka,
		    i.*,
		    r.id as id_indikator_rka_source,
		 r.indikator AS indikator_rka,s.`nama_skpd` AS nama_skpd FROM (
		SELECT
		i.id_indikator_dpa,
		i.kd_urusan ,
		i.kd_bidang ,
		i.kd_program ,
		i.kd_kegiatan ,
		i.id_skpd,
		i.tahun,
		i.status,
		i.nama_indikator_dpa,
		i.status_indikator_rka,
		r.id,
		i.valid

		 FROM import_rka_to_dpa_dj i
		LEFT JOIN tx_rka_prog_keg r
		ON i.kd_urusan = r.kd_urusan
		AND i.kd_bidang = r.kd_bidang
		AND i.kd_program = r.kd_program
		AND i.kd_kegiatan = r.kd_kegiatan
		AND i.id_skpd = r.id_skpd
		AND i.tahun = r.tahun
		WHERE i.kd_kegiatan <> ''

		ORDER BY i.kd_urusan ASC,i.kd_bidang ASC,i.kd_program ASC,i.kd_kegiatan ASC
		) i
		LEFT JOIN tx_rka_indikator_prog_keg  r
		ON i.id = r.id_prog_keg
		LEFT JOIN tx_dpa_indikator_prog_keg d
		ON i.id_indikator_dpa  = d.id
		LEFT JOIN m_skpd s
		ON i.id_skpd = s.`id_skpd`
		order by id_skpd asc,id_indikator_rka_source asc");
		$html =  "<style>table, th, td {
			    border: 1px solid black;
			}</style>";
		$html .= "<table>";
		$html .= "
		<tr>
			<th>id dpa</th>
			<th>id indikator rka</th>
			<th>id indikator rka source</th>
			<th>id skpd</th>
			<th>nama skpd</th>
			<th>status indikator rka</th>
			<th>status</th>
			<th>action</th>
			<th>valid</th>
		</tr>";

		foreach ($query->result() as $kegiatan) {
			if($kegiatan->valid==0){
				$color = "#FF0000";
			}
			else if($kegiatan->valid==1){
				$color = "##00FF00";
			}
			else{
				$color = "#FFFF00";
			}
			$html .= "<tr bgcolor=\"".$color."\"><td>".$kegiatan->id_dpa_indikator_prog_keg."</td>";
			$html .= "<td>".$kegiatan->id_indikator_rka."</td>";
			$html .= "<td>".$kegiatan->id_indikator_rka_source."</td>";
			$html .= "<td>".$kegiatan->id_skpd."</td>";
			$html .= "<td>".$kegiatan->nama_skpd."</td>";
			$html .= "<td>".$kegiatan->status_indikator_rka."</td>";
			$html .= "<td>".$kegiatan->status."</td>";
			if($kegiatan->id_indikator_rka_source!=''){
				$html .= "<td><a href=".site_url('lazy_dj/edit_rka_to_dpa/'.$kegiatan->id_dpa_indikator_prog_keg.'/'.$kegiatan->id_indikator_rka_source).">Edit</a></td>";
			}
			else{
				$html .= "<td></td>";
			}
			$html .= "<td>".($kegiatan->valid=='0'?"Blum":($kegiatan->valid=='1'?"Benar":"Do Nothing"))."</td></tr>";

		}

		echo $html;
	}

	function edit_rka_to_dpa($id_dpa_indikator_prog_keg,$id_indikator_rka_source){
		$query = $this->db->query("
		select
			".$id_dpa_indikator_prog_keg.",
			(select indikator as indikator_dpa from tx_dpa_indikator_prog_keg where id = ".$id_dpa_indikator_prog_keg.") as indikator_dpa,
			(select concat(target,' ',satuan_target) as target_dpa from tx_dpa_indikator_prog_keg where id = ".$id_dpa_indikator_prog_keg.") target_dpa,
			(select indikator as indikator_rka from tx_rka_indikator_prog_keg where id  =".$id_indikator_rka_source.") indikator_rka,
			(select indikator_thndpn as indikator_thndpn_rka from tx_rka_indikator_prog_keg where id  =".$id_indikator_rka_source.") indikator_thndpn_rka,
			(select concat(target,' ',satuan_target) as target_rka from tx_rka_indikator_prog_keg where id  =".$id_indikator_rka_source.") target_rka
		");


		$hasil = $query->row();
		$html =  "<style>table, th, td {
			    border: 1px solid black;
			}</style>";
		$html .= "<table style=\"border: 1px solid black;\">";
		$html .= "
		<tr>
			<th>TARGET DPA</th>
			<th>INDIKATOR DPA</th>
			<th>indikator_rka</th>
			<th>indikator_thndpn_rka</th>
			<th>target_rka</th>
		</tr>
		<tr>
			<td>".$hasil->indikator_dpa."</td>
			<td>".$hasil->target_dpa."</td>
			<td>".$hasil->indikator_rka."</td>
			<td>".$hasil->indikator_thndpn_rka."</td>
			<td>".$hasil->target_rka."</td>
		</tr></table><br />"
		;

		$query_1 = "select * from import_rka_to_dpa_dj where id_indikator_dpa =".$id_dpa_indikator_prog_keg;

		$html .= "STATUS =".$this->db->query($query_1)->row()->status_indikator_rka;

		$case1 = "<td><a href=".site_url('lazy_dj/case_rka_to_dpa/1/'.$id_dpa_indikator_prog_keg.'/'.$id_indikator_rka_source).">Copy indikator DPA to indikator RKA</a></td></tr>";
		$case2 = "<td><a href=".site_url('lazy_dj/case_rka_to_dpa/2/'.$id_dpa_indikator_prog_keg.'/'.$id_indikator_rka_source).">Copy indikator RKA to indikator DPA</a></td></tr>";
		$case3 = "<td><a href=".site_url('lazy_dj/case_rka_to_dpa/3/'.$id_dpa_indikator_prog_keg.'/'.$id_indikator_rka_source).">Copy Status ke indikator RKA dan indikator DPA</a></td></tr>";
		$case4 = "<td><a href=".site_url('lazy_dj/case_rka_to_dpa/4/'.$id_dpa_indikator_prog_keg.'/'.$id_indikator_rka_source).">DO NOTHING</a></td></tr>";
		$html .= "<br />".$case1."<br /><br />".$case2."<br /><br />".$case3."<br /><br />".$case4;
		echo $html;

	}

	function case_rka_to_dpa($case,$id_dpa_indikator_prog_keg,$id_indikator_rka_source){
		$query = $this->db->query("select * from tx_dpa_indikator_prog_keg where id=".$id_dpa_indikator_prog_keg);
		$dpa = $query->row();

		$query = $this->db->query("select * from tx_rka_indikator_prog_keg where id=".$id_indikator_rka_source);
		$rka = $query->row();

		if($case==1){
			//copy indikator dpa to rka
			$sql_1 = "update tx_rka_indikator_prog_keg set indikator ='".$dpa->indikator."', indikator_thndpn = '".$dpa->indikator."' where id = '".$rka->id."'";
			$sql_2 = "update tx_dpa_indikator_prog_keg set id_indikator_rka = '".$rka->id."' where id = '".$dpa->id."'";
			$sql_3 = "update import_rka_to_dpa_dj set valid = 1 where id_indikator_dpa = '".$id_dpa_indikator_prog_keg."' ";

			$this->db->query($sql_1);
			$this->db->query($sql_2);
			$this->db->query($sql_3);

		}
		else if($case==2){
			//copy indikator rka to dpa
			//$sql_1 = "update tx_rka_indikator_prog_keg set indikator ='".$dpa->indikator."', indikator_thndpn = '".$dpa->indikator."' where id = '".$rka->id."'";
			$sql_2 = "update tx_dpa_indikator_prog_keg set id_indikator_rka = '".$rka->id."',indikator='".$rka->indikator."' where id = '".$dpa->id."'";
			$sql_3 = "update import_rka_to_dpa_dj set valid = 1 where id_indikator_dpa = '".$id_dpa_indikator_prog_keg."' ";

			//$this->db->query($sql_1);
			$this->db->query($sql_2);
			$this->db->query($sql_3);
		}
		else if($case ==3){
			//get status di
			$sql_1 = "update tx_rka_indikator_prog_keg set indikator =(select status_indikator_rka from `import_rka_to_dpa_dj` where id_indikator_dpa = '".$id_dpa_indikator_prog_keg."'),
			indikator_thndpn = (select status_indikator_rka from `import_rka_to_dpa_dj` where id_indikator_dpa = '".$id_dpa_indikator_prog_keg."') where id = '".$rka->id."'";

			$sql_2 = "update tx_dpa_indikator_prog_keg set id_indikator_rka = '".$rka->id."',indikator=(select status_indikator_rka from `import_rka_to_dpa_dj` where id_indikator_dpa = '".$id_dpa_indikator_prog_keg."') where id = '".$dpa->id."'";
			$sql_3 = "update import_rka_to_dpa_dj set valid = 1 where id_indikator_dpa = '".$id_dpa_indikator_prog_keg."' ";



			$this->db->query($sql_1);
			$this->db->query($sql_2);
			$this->db->query($sql_3);


		}
		else if($case ==4){
			//do nothing
			$sql_3 = "update import_rka_to_dpa_dj set valid = 2 where id_indikator_dpa = '".$id_dpa_indikator_prog_keg."' ";
			$this->db->query($sql_3);
		}


		redirect('/lazy_dj/rka_to_dpa');

	}


	//DPA TO CIK DJ
	function dpa_to_cik()
	{
        $query = $this->db->query("SELECT d.id AS id_cik_indikator_prog_keg,
		 d.id_indikator_dpa,
		    i.*,
		    r.id as id_indikator_dpa_source,
		 r.indikator AS indikator_dpa,s.`nama_skpd` AS nama_skpd FROM (
		SELECT
		i.id_indikator_cik,
		i.kd_urusan ,
		i.kd_bidang ,
		i.kd_program ,
		i.kd_kegiatan ,
		i.id_skpd,
		i.tahun,
		i.status,
		i.nama_indikator_cik,
		i.status_indikator_dpa,
		r.id,
		i.valid

		 FROM import_dpa_to_cik_dj i
		LEFT JOIN tx_dpa_prog_keg r
		ON i.kd_urusan = r.kd_urusan
		AND i.kd_bidang = r.kd_bidang
		AND i.kd_program = r.kd_program
		AND i.kd_kegiatan = r.kd_kegiatan
		AND i.id_skpd = r.id_skpd
		AND i.tahun = r.tahun
		WHERE i.kd_kegiatan <> ''

		ORDER BY i.kd_urusan ASC,i.kd_bidang ASC,i.kd_program ASC,i.kd_kegiatan ASC
		) i
		LEFT JOIN tx_dpa_indikator_prog_keg  r
		ON i.id = r.id_prog_keg
		LEFT JOIN tx_cik_indikator_prog_keg d
		ON i.id_indikator_cik  = d.id
		LEFT JOIN m_skpd s
		ON i.id_skpd = s.`id_skpd`
		order by id_skpd asc,id_indikator_dpa_source asc");
		$html =  "<style>table, th, td {
			    border: 1px solid black;
			}</style>";
		$html .= "<table>";
		$html .= "
		<tr>
			<th>id cik</th>
			<th>id indikator dpa</th>
			<th>id indikator dpa source</th>
			<th>id skpd</th>
			<th>nama skpd</th>
			<th>status indikator dpa</th>
			<th>status</th>
			<th>action</th>
			<th>valid</th>
		</tr>";

		foreach ($query->result() as $kegiatan) {
			if($kegiatan->valid==0){
				$color = "#FF0000";
			}
			else if($kegiatan->valid==1){
				$color = "##00FF00";
			}
			else{
				$color = "#FFFF00";
			}
			$html .= "<tr bgcolor=\"".$color."\"><td>".$kegiatan->id_cik_indikator_prog_keg."</td>";
			$html .= "<td>".$kegiatan->id_indikator_dpa."</td>";
			$html .= "<td>".$kegiatan->id_indikator_dpa_source."</td>";
			$html .= "<td>".$kegiatan->id_skpd."</td>";
			$html .= "<td>".$kegiatan->nama_skpd."</td>";
			$html .= "<td>".$kegiatan->status_indikator_dpa."</td>";
			$html .= "<td>".$kegiatan->status."</td>";
			if($kegiatan->id_indikator_dpa_source!=''){
				$html .= "<td><a href=".site_url('lazy_dj/edit_dpa_to_cik/'.$kegiatan->id_cik_indikator_prog_keg.'/'.$kegiatan->id_indikator_dpa_source).">Edit</a></td>";
			}
			else{
				$html .= "<td></td>";
			}
			$html .= "<td>".($kegiatan->valid=='0'?"Blum":($kegiatan->valid=='1'?"Benar":"Do Nothing"))."</td></tr>";

		}

		echo $html;
	}

	function edit_dpa_to_cik($id_cik_indikator_prog_keg,$id_indikator_dpa_source){
		$query = $this->db->query("
		select
			".$id_cik_indikator_prog_keg.",
			(select indikator as indikator_cik from tx_cik_indikator_prog_keg where id = ".$id_cik_indikator_prog_keg.") as indikator_cik,
			(select concat(target,' ',satuan_target) as target_cik from tx_cik_indikator_prog_keg where id = ".$id_cik_indikator_prog_keg.") target_cik,
			(select indikator as indikator_dpa from tx_dpa_indikator_prog_keg where id  =".$id_indikator_dpa_source.") indikator_dpa,
			(select concat(target,' ',satuan_target) as target_dpa from tx_dpa_indikator_prog_keg where id  =".$id_indikator_dpa_source.") target_dpa
		");


		$hasil = $query->row();
		$html =  "<style>table, th, td {
			    border: 1px solid black;
			}</style>";
		$html .= "<table style=\"border: 1px solid black;\">";
		$html .= "
		<tr>
			<th>TARGET cik</th>
			<th>INDIKATOR cik</th>
			<th>indikator_dpa</th>
			<th>indikator_thndpn_dpa</th>
			<th>target_dpa</th>
		</tr>
		<tr>
			<td>".$hasil->indikator_cik."</td>
			<td>".$hasil->target_cik."</td>
			<td>".$hasil->indikator_dpa."</td>
			<td></td>
			<td>".$hasil->target_dpa."</td>
		</tr></table><br />"
		;

		$query_1 = "select * from import_dpa_to_cik_dj where id_indikator_cik =".$id_cik_indikator_prog_keg;

		$html .= "STATUS =".$this->db->query($query_1)->row()->status_indikator_dpa;

		$case1 = "<td><a href=".site_url('lazy_dj/case_dpa_to_cik/1/'.$id_cik_indikator_prog_keg.'/'.$id_indikator_dpa_source).">Copy indikator cik to indikator dpa</a></td></tr>";
		$case2 = "<td><a href=".site_url('lazy_dj/case_dpa_to_cik/2/'.$id_cik_indikator_prog_keg.'/'.$id_indikator_dpa_source).">Copy indikator dpa to indikator cik</a></td></tr>";
		$case3 = "<td><a href=".site_url('lazy_dj/case_dpa_to_cik/3/'.$id_cik_indikator_prog_keg.'/'.$id_indikator_dpa_source).">Copy Status ke indikator dpa dan indikator cik</a></td></tr>";
		$case4 = "<td><a href=".site_url('lazy_dj/case_dpa_to_cik/4/'.$id_cik_indikator_prog_keg.'/'.$id_indikator_dpa_source).">DO NOTHING</a></td></tr>";
		$html .= "<br />".$case1."<br /><br />".$case2."<br /><br />".$case3."<br /><br />".$case4;
		echo $html;

	}

	function case_dpa_to_cik($case,$id_cik_indikator_prog_keg,$id_indikator_dpa_source){
		$query = $this->db->query("select * from tx_cik_indikator_prog_keg where id=".$id_cik_indikator_prog_keg);
		$cik = $query->row();

		$query = $this->db->query("select * from tx_dpa_indikator_prog_keg where id=".$id_indikator_dpa_source);
		$dpa = $query->row();

		if($case==1){
			//copy indikator cik to dpa
			$sql_1 = "update tx_dpa_indikator_prog_keg set indikator ='".$cik->indikator."' where id = '".$dpa->id."'";
			$sql_2 = "update tx_cik_indikator_prog_keg set id_indikator_dpa = '".$dpa->id."' where id = '".$cik->id."'";
			$sql_3 = "update import_dpa_to_cik_dj set valid = 1 where id_indikator_cik = '".$id_cik_indikator_prog_keg."' ";

			$this->db->query($sql_1);
			$this->db->query($sql_2);
			$this->db->query($sql_3);

		}
		else if($case==2){
			//copy indikator dpa to cik
			//$sql_1 = "update tx_dpa_indikator_prog_keg set indikator ='".$cik->indikator."', indikator_thndpn = '".$cik->indikator."' where id = '".$dpa->id."'";
			$sql_2 = "update tx_cik_indikator_prog_keg set id_indikator_dpa = '".$dpa->id."',indikator='".$dpa->indikator."' where id = '".$cik->id."'";
			$sql_3 = "update import_dpa_to_cik_dj set valid = 1 where id_indikator_cik = '".$id_cik_indikator_prog_keg."' ";

			//$this->db->query($sql_1);
			$this->db->query($sql_2);
			$this->db->query($sql_3);
		}
		else if($case ==3){
			//get status di
			$sql_1 = "update tx_dpa_indikator_prog_keg set indikator =(select status_indikator_dpa from `import_dpa_to_cik_dj` where id_indikator_cik = '".$id_cik_indikator_prog_keg."') where id = '".$dpa->id."'";

			$sql_2 = "update tx_cik_indikator_prog_keg set id_indikator_dpa = '".$dpa->id."',indikator=(select status_indikator_dpa from `import_dpa_to_cik_dj` where id_indikator_cik = '".$id_cik_indikator_prog_keg."') where id = '".$cik->id."'";
			$sql_3 = "update import_dpa_to_cik_dj set valid = 1 where id_indikator_cik = '".$id_cik_indikator_prog_keg."' ";



			$this->db->query($sql_1);
			$this->db->query($sql_2);
			$this->db->query($sql_3);


		}
		else if($case ==4){
			//do nothing
			$sql_3 = "update import_dpa_to_cik_dj set valid = 2 where id_indikator_cik = '".$id_cik_indikator_prog_keg."' ";
			$this->db->query($sql_3);
		}


		redirect('/lazy_dj/dpa_to_cik');

	}

	#renja to rka DJ
	function renja_to_rka()
	{
        $query = $this->db->query("SELECT d.id AS id_rka_indikator_prog_keg,
		 d.id_indikator_renja,
		    i.*,
		    r.id as id_indikator_renja_source,
		 r.indikator AS indikator_renja,s.`nama_skpd` AS nama_skpd FROM (
		SELECT
		i.id_indikator_rka,
		i.kd_urusan ,
		i.kd_bidang ,
		i.kd_program ,
		i.kd_kegiatan ,
		i.id_skpd,
		i.tahun,
		i.status,
		i.nama_indikator_rka,
		i.status_indikator_renja,
		r.id,
		i.valid

		 FROM import_renja_to_rka_dj i
		LEFT JOIN t_renja_prog_keg r
		ON i.kd_urusan = r.kd_urusan
		AND i.kd_bidang = r.kd_bidang
		AND i.kd_program = r.kd_program
		AND i.kd_kegiatan = r.kd_kegiatan
		AND i.id_skpd = r.id_skpd
		AND i.tahun = r.tahun
		WHERE i.kd_kegiatan <> ''

		ORDER BY i.kd_urusan ASC,i.kd_bidang ASC,i.kd_program ASC,i.kd_kegiatan ASC
		) i
		LEFT JOIN t_renja_indikator_prog_keg  r
		ON i.id = r.id_prog_keg
		LEFT JOIN tx_rka_indikator_prog_keg d
		ON i.id_indikator_rka  = d.id
		LEFT JOIN m_skpd s
		ON i.id_skpd = s.`id_skpd`
		order by id_skpd asc,id_indikator_renja_source asc");
		$html =  "<style>table, th, td {
			    border: 1px solid black;
			}</style>";
		$html .= "<table>";
		$html .= "
		<tr>
			<th>id rka</th>
			<th>id indikator renja</th>
			<th>id indikator renja source</th>
			<th>id skpd</th>
			<th>nama skpd</th>
			<th>status indikator renja</th>
			<th>status</th>
			<th>action</th>
			<th>valid</th>
		</tr>";

		foreach ($query->result() as $kegiatan) {
			if($kegiatan->valid==0){
				$color = "#FF0000";
			}
			else if($kegiatan->valid==1){
				$color = "##00FF00";
			}
			else{
				$color = "#FFFF00";
			}
			$html .= "<tr bgcolor=\"".$color."\"><td>".$kegiatan->id_rka_indikator_prog_keg."</td>";
			$html .= "<td>".$kegiatan->id_indikator_renja."</td>";
			$html .= "<td>".$kegiatan->id_indikator_renja_source."</td>";
			$html .= "<td>".$kegiatan->id_skpd."</td>";
			$html .= "<td>".$kegiatan->nama_skpd."</td>";
			$html .= "<td>".$kegiatan->status_indikator_renja."</td>";
			$html .= "<td>".$kegiatan->status."</td>";
			if($kegiatan->id_indikator_renja_source!=''){
				$html .= "<td><a href=".site_url('lazy_dj/edit_renja_to_rka/'.$kegiatan->id_rka_indikator_prog_keg.'/'.$kegiatan->id_indikator_renja_source).">Edit</a></td>";
			}
			else{
				$html .= "<td></td>";
			}
			$html .= "<td>".($kegiatan->valid=='0'?"Blum":($kegiatan->valid=='1'?"Benar":"Do Nothing"))."</td></tr>";

		}

		echo $html;
	}

	function edit_renja_to_rka($id_rka_indikator_prog_keg,$id_indikator_renja_source){
		$query = $this->db->query("
		select
			".$id_rka_indikator_prog_keg.",
			(select indikator as indikator_rka from tx_rka_indikator_prog_keg where id = ".$id_rka_indikator_prog_keg.") as indikator_rka,
			(select concat(target,' ',satuan_target) as target_rka from tx_rka_indikator_prog_keg where id = ".$id_rka_indikator_prog_keg.") target_rka,
			(select indikator as indikator_renja from t_renja_indikator_prog_keg where id  =".$id_indikator_renja_source.") indikator_renja,
			(select indikator_thndpn as indikator_thndpn_renja from t_renja_indikator_prog_keg where id  =".$id_indikator_renja_source.") indikator_thndpn_renja,
			(select concat(target,' ',satuan_target) as target_renja from t_renja_indikator_prog_keg where id  =".$id_indikator_renja_source.") target_renja
		");


		$hasil = $query->row();
		$html =  "<style>table, th, td {
			    border: 1px solid black;
			}</style>";
		$html .= "<table style=\"border: 1px solid black;\">";
		$html .= "
		<tr>
			<th>TARGET rka</th>
			<th>INDIKATOR rka</th>
			<th>indikator_renja</th>
			<th>indikator_thndpn_renja</th>
			<th>target_renja</th>
		</tr>
		<tr>
			<td>".$hasil->indikator_rka."</td>
			<td>".$hasil->target_rka."</td>
			<td>".$hasil->indikator_renja."</td>
			<td>".$hasil->indikator_thndpn_renja."</td>
			<td>".$hasil->target_renja."</td>
		</tr></table><br />"
		;

		$query_1 = "select * from import_renja_to_rka_dj where id_indikator_rka =".$id_rka_indikator_prog_keg;

		$html .= "STATUS =".$this->db->query($query_1)->row()->status_indikator_renja;

		$case1 = "<td><a href=".site_url('lazy_dj/case_renja_to_rka/1/'.$id_rka_indikator_prog_keg.'/'.$id_indikator_renja_source).">Copy indikator rka to indikator renja</a></td></tr>";
		$case2 = "<td><a href=".site_url('lazy_dj/case_renja_to_rka/2/'.$id_rka_indikator_prog_keg.'/'.$id_indikator_renja_source).">Copy indikator renja to indikator rka</a></td></tr>";
		$case3 = "<td><a href=".site_url('lazy_dj/case_renja_to_rka/3/'.$id_rka_indikator_prog_keg.'/'.$id_indikator_renja_source).">Copy Status ke indikator renja dan indikator rka</a></td></tr>";
		$case4 = "<td><a href=".site_url('lazy_dj/case_renja_to_rka/4/'.$id_rka_indikator_prog_keg.'/'.$id_indikator_renja_source).">DO NOTHING</a></td></tr>";
		$html .= "<br />".$case1."<br /><br />".$case2."<br /><br />".$case3."<br /><br />".$case4;
		echo $html;

	}

	function case_renja_to_rka($case,$id_rka_indikator_prog_keg,$id_indikator_renja_source){
		$query = $this->db->query("select * from tx_rka_indikator_prog_keg where id=".$id_rka_indikator_prog_keg);
		$rka = $query->row();

		$query = $this->db->query("select * from t_renja_indikator_prog_keg where id=".$id_indikator_renja_source);
		$renja = $query->row();

		if($case==1){
			//copy indikator rka to renja
			$sql_1 = "update t_renja_indikator_prog_keg set indikator ='".$rka->indikator."', indikator_thndpn = '".$rka->indikator."' where id = '".$renja->id."'";
			$sql_2 = "update tx_rka_indikator_prog_keg set id_indikator_renja = '".$renja->id."' where id = '".$rka->id."'";
			$sql_3 = "update import_renja_to_rka_dj set valid = 1 where id_indikator_rka = '".$id_rka_indikator_prog_keg."' ";

			$this->db->query($sql_1);
			$this->db->query($sql_2);
			$this->db->query($sql_3);

		}
		else if($case==2){
			//copy indikator renja to rka
			//$sql_1 = "update t_renja_indikator_prog_keg set indikator ='".$rka->indikator."', indikator_thndpn = '".$rka->indikator."' where id = '".$renja->id."'";
			$sql_2 = "update tx_rka_indikator_prog_keg set id_indikator_renja = '".$renja->id."',indikator='".$renja->indikator."' where id = '".$rka->id."'";
			$sql_3 = "update import_renja_to_rka_dj set valid = 1 where id_indikator_rka = '".$id_rka_indikator_prog_keg."' ";

			//$this->db->query($sql_1);
			$this->db->query($sql_2);
			$this->db->query($sql_3);
		}
		else if($case ==3){
			//get status di
			$sql_1 = "update t_renja_indikator_prog_keg set indikator =(select status_indikator_renja from `import_renja_to_rka_dj` where id_indikator_rka = '".$id_rka_indikator_prog_keg."'),
			indikator_thndpn = (select status_indikator_renja from `import_renja_to_rka_dj` where id_indikator_rka = '".$id_rka_indikator_prog_keg."') where id = '".$renja->id."'";

			$sql_2 = "update tx_rka_indikator_prog_keg set id_indikator_renja = '".$renja->id."',indikator=(select status_indikator_renja from `import_renja_to_rka_dj` where id_indikator_rka = '".$id_rka_indikator_prog_keg."') where id = '".$rka->id."'";
			$sql_3 = "update import_renja_to_rka_dj set valid = 1 where id_indikator_rka = '".$id_rka_indikator_prog_keg."' ";



			$this->db->query($sql_1);
			$this->db->query($sql_2);
			$this->db->query($sql_3);


		}
		else if($case ==4){
			//do nothing
			$sql_3 = "update import_renja_to_rka_dj set valid = 2 where id_indikator_rka = '".$id_rka_indikator_prog_keg."' ";
			$this->db->query($sql_3);
		}


		redirect('/lazy_dj/renja_to_rka');

	}


}
