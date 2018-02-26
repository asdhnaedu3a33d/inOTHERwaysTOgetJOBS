<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('url');

		$this->load->library('grocery_CRUD');
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

	function _example_output($output = null,$judul=NULL)
	{
		//$this->load->view('example',$output);
		$output->judul=$judul;
		$this->template->load('template_grocery','master',$output);
	}

	function _example_output_registrasi($output = null,$judul=NULL)
	{

		$output->judul=$judul;
		$this->load->view('master_reg',$output);
		//$this->template->load('template','master',$output);
	}

	function index()
	{
		//$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
	}

	function unit(){
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_unit');
			$crud->set_subject('Unit');
			//$crud->required_fields('nama_unit');
			$crud->fields('kode_unit','nama_unit','nama_unit_singkat','alamat','bank_relasi','rekening','npwp','dekan_nama','dekan_nip','pd2_nama','pd2_nip','bpp_nama','bpp_nip');
			$crud->columns('kode_unit','nama_unit','nama_unit_singkat','alamat','bank_relasi');
            $crud->display_as('nama_unit','Nama Unit');
            $crud->display_as('kode_digit','Kode Unit');
            $crud->display_as('nama_unit_singkat','Nama Singkat');

			$output = $crud->render();

			$this->_example_output($output,'Setting Master Unit');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

    function subunit(){
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_subunit');
			$crud->set_subject('Subunit');
			$crud->columns('kode_subunit','id_unit','nama_subunit','kode_sim_reg');
			$crud->fields('kode_subunit','kode_sim_reg','nama_subunit','pim_jab','pim_nama','pim_nip','id_unit');
            $crud->display_as('kode_sim_reg','Kode Simreg');
            $crud->set_relation('id_unit','m_unit','nama_unit');
            $crud->display_as('pim_jab','Jabatan Pemimpin');
            $crud->display_as('pim_nama','Nama Pimpinan');
            $crud->display_as('pim_nip','NIP Pimpinan');

			$output = $crud->render();

			$this->_example_output($output,'Setting Master Subunit');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	function bidang_koordinasi(){
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_bidkoordinasi');
			$crud->set_subject('Koordinasi');
			$crud->columns('kode_bidkoor','nama_koor','alamat','kakoor_nama','kakoor_nip');
			$crud->fields('kode_bidkoor','nama_koor','alamat','kakoor_nama','kakoor_nip');
            $crud->display_as('kode_bidkoor','Kode Bid. Koordinasi');
			$crud->display_as('nama_koor','Nama Bid. Koordinasi');
			$crud->display_as('kakoor_nama','Nama KaBid. Koordinasi');
			$crud->display_as('kakoor_nip','Nip KaBid. Koordinasi');

			$output = $crud->render();

			$this->_example_output($output,'Setting Master Koordinasi');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	function skpd(){
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_skpd');
			$crud->set_subject('SKPD');
			$crud->columns('id_bidkoor','kode_skpd','nama_skpd','telp_skpd','kaskpd_nama');
			$crud->fields('id_bidkoor','kode_skpd','kodepos_skpd','nama_skpd','alamat','telp_skpd','kaskpd_nama','kaskpd_nip','wkskpd_nama','wkskpd_nip');
			$crud->set_relation('id_bidkoor','m_bidkoordinasi','nama_koor');
            $crud->display_as('kode_skpd','Kode SKPD');
			$crud->display_as('nama_koor','Nama Bid. Koordinasi');
			$crud->display_as('kodepos_skpd','Kode POS');
			$crud->display_as('alamat','Alamat SKPD');
			$crud->display_as('telp_skpd','No Telp SKPD');
			$crud->display_as('kaskpd_nama','Nama Kepala SKPD');
			$crud->display_as('kaskpd_nip','NIP Kepala SKPD');
			$crud->display_as('wkskpd_nama','Nama Waka SKPD');
			$crud->display_as('wkskpd_nip','NIP Waka SKPD');

			$output = $crud->render();

			$this->_example_output($output,'Setting Master Koordinasi');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	function desa(){
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_desa');
			$crud->set_subject('Desa');
			$crud->columns('id_kec','kode_desa','nama_desa','alamat','kodepost_desa');
			$crud->fields('id_kec','kode_desa','nama_desa','alamat','kodepost_desa');
			$crud->set_relation('id_kec','m_kecamatan','nama_kec');
			$crud->display_as('id_kec','ID Kecamatan');
			$crud->display_as('kode_desa','Kode Desa');
			$crud->display_as('nama_desa','Nama Desa');
			$crud->display_as('alamat','Alamat');
			$crud->display_as('kodepost_desa','Kode Post');

			$output = $crud->render();

			$this->_example_output($output,'Setting Master Desa');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	function kecamatan(){
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_kecamatan');
			$crud->set_subject('Kecamatan');
			$crud->columns('kode_kec','kodepos_kec','nama_kec','alamat');
			$crud->fields('kode_kec','kodepos_kec','nama_kec','alamat');
			$crud->display_as('kode_kec','kode kecamatan');
			$crud->display_as('kodepos_kec','Kode POS');
			$crud->display_as('nama_kec','nama kecaatan');
			$crud->display_as('alamat','Alamat');
			$output = $crud->render();

			$this->_example_output($output,'Setting Master Kecamatan');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	function urusan(){
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_urusan');
			$crud->set_subject('Urusan');
			$crud->columns('Kd_Urusan','Nm_Urusan');
			$crud->fields('Kd_Urusan','Nm_Urusan');
			//$crud->set_relation('id_kec','m_kecamatan','nama_kec');
			$crud->display_as('Kd_Urusan','Kode Urusan');
			$crud->display_as('Nm_Urusan','Nama Urusan');

			$output = $crud->render();

			$this->_example_output($output,'Setting Master Urusan');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	function bidang(){
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_bidang');
			$crud->set_subject('Bidang');
			$crud->columns('Kd_Urusan','Kd_Bidang','Nm_Bidang','Kd_Fungsi');
			$crud->fields('Kd_Urusan','Kd_Bidang','Nm_Bidang','Kd_Fungsi');
			$crud->set_relation('Kd_Urusan','m_urusan','Nm_Urusan');
			$crud->set_relation('Kd_Fungsi','m_fungsi','Nm_Fungsi');
			$crud->display_as('Kd_Urusan','Nama Urusan');
			$crud->display_as('Kd_Bidang','Kode Bidang');
			$crud->display_as('Nm_Bidang','Nama Bidang');
			$crud->display_as('Kd_Fungsi','Kode Fungsi');

			$output = $crud->render();

			$this->_example_output($output,'Setting Master Bidang');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	function program(){
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_program');
			$crud->set_subject('Program');
			$crud->columns('Kd_Urusan','Kd_Bidang','Kd_Prog','Ket_Program');
			$crud->fields('Kd_Urusan','Kd_Bidang','Kd_Prog','Ket_Program');
			$crud->set_relation('Kd_Urusan','m_urusan','Nm_Urusan');
			$crud->set_relation('Kd_Bidang','m_bidang','Nm_Bidang');
			$crud->display_as('Kd_Urusan','Nama Urusan');
			$crud->display_as('Kd_Bidang','Nama Bidang');
			$crud->display_as('Kd_Prog','Kode Program');
			$crud->display_as('Ket_Program','Keterangan Program');

			$output = $crud->render();

			$this->_example_output($output,'Setting Master Program');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	function kegiatan(){
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_kegiatan');
			$crud->set_subject('Kegiatan');
			$crud->columns('Kd_Urusan','Kd_Bidang','Kd_Prog','Kd_Keg','Ket_Kegiatan');
			$crud->fields('Kd_Urusan','Kd_Bidang','Kd_Prog','Kd_Keg','Ket_Kegiatan');
			$crud->set_relation('Kd_Urusan','m_urusan','Nm_Urusan');
			$crud->set_relation('Kd_Bidang','m_bidang','Nm_Bidang');
			$crud->set_relation('Kd_Prog','m_program','Ket_Program');
			$crud->display_as('Kd_Urusan','Nama Urusan');
			$crud->display_as('Kd_Bidang','Nama Bidang');
			$crud->display_as('Kd_Prog','Kode Program');
			$crud->display_as('Kd_Keg','Kode Kegiatan');
			$crud->display_as('Ket_Kegiatan','Keterangan Kegiatan');

			$output = $crud->render();

			$this->_example_output($output,'Setting Master Kegiatan');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	function sumber_dana(){
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_sumberdana');
			$crud->set_subject('Sumber Dana');
			$crud->columns('id_sumberdana','nama','keterangan');
			$crud->fields('id_sumberdana','nama','keterangan');
			$crud->display_as('id_sumberdana','ID');
			$crud->display_as('nama','Nama Sumber Dana');
			$crud->display_as('keterangan','Keterangan');

			$output = $crud->render();

			$this->_example_output($output,'Setting Master Sumber Dana');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	function prioritas(){
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_prioritas');
			$crud->set_subject('Prioritas');
			$crud->columns('id_prioritas','nama','keterangan');
			$crud->fields('id_prioritas','nama','keterangan');
			$crud->display_as('id_prioritas','ID');
			$crud->display_as('nama','Nama Prioritas');
			$crud->display_as('keterangan','Keterangan');

			$output = $crud->render();

			$this->_example_output($output,'Setting Master Prioritas');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

		function fungsi(){
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_fungsi');
			$crud->set_subject('Fungsi');
			$crud->columns('Kd_Fungsi','Nm_Fungsi');
			$crud->fields('Kd_Fungsi','Nm_Fungsi');
			$crud->display_as('Kd_Fungsi','Kode Fungsi');
			$crud->display_as('Nm_Fungsi','Nama Fungsi');

			$output = $crud->render();

			$this->_example_output($output,'Setting Master Fungsi');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

		function indikator(){
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_indikator');
			$crud->set_subject('Indikator');
			$crud->columns('Kd_Indikator','Nm_Indikator');
			$crud->fields('Kd_Indikator','Nm_Indikator');
			//$crud->set_relation('id_kec','m_kecamatan','nama_kec');
			$crud->display_as('Kd_Indikator','Kode Indikator');
			$crud->display_as('Nm_Indikator','Nama Indikator');

			$output = $crud->render();

			$this->_example_output($output,'Setting Master Indikator');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	function subunit_input(){
		try {
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_subunit');
			$crud->set_subject('Subunit');
			$crud->columns('id_unit','nama_subunit','pim_jab','pim_nama','pim_nip');
			$crud->fields('kode_subunit','nama_subunit','pim_jab','pim_nama','pim_nip','id_unit');
            $crud->set_relation('id_unit','m_unit','nama_unit');
            $crud->display_as('pim_jab','Jabatan Pemimpin');
            $crud->display_as('pim_nama','Nama Pimpinan');
            $crud->display_as('pim_nip','NIP Pimpinan');

			$output = $crud->render();

			$this->_example_output($output,'Setting Subunit');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}


    function setting(){
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_setting');
			$crud->set_subject('Setting');
			//$crud->required_fields('nama_unit');
			$crud->columns('kode','value');

            $crud->add_fields('value','kode');

            $user = $this->auth->get_user();
			if($user->hasPrivilege('master_edit_setting_kode')){
                $crud->edit_fields('value','kode');
            }else{
                $crud->edit_fields('value');
            }

			$output = $crud->render();

			$this->_example_output($output,'Setting Master Konfigurasi');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

    function user(){
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_users');
			$crud->set_subject('User');
			$crud->fields('username','password','user_active','user_nama','id_desa','id_skpd','user_email','user_phone','user_role');
			$crud->columns('username','user_active','user_nama','id_desa','id_skpd','user_email','user_phone');

            $crud->set_relation('id_skpd','m_skpd','nama_skpd');
            $crud->set_relation('id_desa','m_desa','nama_desa');

            $crud->set_relation_n_n('user_role', 'rbac_user_role', 'rbac_roles', 'id_user', 'role_id', 'role_name');

            $crud->field_type('user_active','true_false');

            $crud->change_field_type('password', 'password');
            $crud->callback_before_insert(array($this,'encrypt_password'));
            $crud->callback_before_update(array($this,'encrypt_password'));
            $crud->callback_edit_field('password',array($this,'kosongin_pass'));
            //TODO: sambungin ke unit sama subunit
            $crud->display_as('user_active','User Aktif');
            $crud->display_as('user_nama','Nama User');
            $crud->display_as('id_desa','Desa');
            $crud->display_as('id_skpd','SKPD');
            $crud->display_as('user_email','Email');
            $crud->display_as('user_phone','Telepon');

			$output = $crud->render();

			$this->_example_output($output,'Setting Master User');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	function registrasi(){
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_users');
			$crud->set_subject('User');
			$crud->fields('username','password','user_nama','id_skpd','id_bidkoor','user_email','user_phone');
			$crud->columns('username','user_nama','id_skpd','id_bidkoor','user_email','user_phone');

            $crud->set_relation('id_unit','m_unit','nama_unit');
            $crud->set_relation('id_subunit','m_subunit','nama_subunit');

            $crud->change_field_type('password', 'password');
            $crud->callback_before_insert(array($this,'encrypt_password'));
            $crud->callback_before_update(array($this,'encrypt_password'));
            $crud->callback_edit_field('password',array($this,'kosongin_pass'));
            //TODO: sambungin ke unit sama subunit
            $crud->display_as('user_nama','Nama User');
            $crud->display_as('id_skpd','SKPD');
            $crud->display_as('id_bidkoor','Bidang Koordinasi');
            $crud->display_as('user_email','Email');
            $crud->display_as('user_phone','Telepon');
			$crud->unset_back_to_list();

			$output = $crud->render();

			$this->_example_output_registrasi($output,'Registrasi User');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

    function encrypt_password($post_array, $primary_key = null){
        if(!empty($post_array['password'])){
            $post_array['password']=  md5($post_array['password']);
        }else{
            //kalo passwordnya dikosongin, jangan update ya...
            unset($post_array['password']);
        }
        return $post_array;
    }

    function kosongin_pass(){
        return "<input type='password' name='password' value='' />";
    }

    function bas(){
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_bas');
			$crud->set_subject('BAS');
			$crud->columns('akun_kode','akun_nama','saldo_normal');

            $crud->fields('akun_kode','akun_nama','tipe','saldo_normal','kegiatan','aktif','level','parent','kode_pen_simper','id_unit','id_subunit','keterangan');

            $crud->set_relation('parent','m_bas','{akun_kode} - {akun_nama}');
            $crud->set_relation('id_unit','m_unit','nama_unit');
            $crud->set_relation('id_subunit','m_subunit','nama_subunit');

            $crud->display_as('id_subunit','Subunit');
            $crud->display_as('id_unit','Unit');

            $crud->field_type('aktif','dropdown', array('TIDAK' => 'Tidak', 'YA' => 'Ya'));
            $crud->field_type('saldo_normal','dropdown', array('D' => 'Debet', 'K' => 'Kredit'));

			$output = $crud->render();

			$this->_example_output($output,'Setting Master Bagan Akun Standar');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	function pegawai(){
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_pegawai_untuk_siaku');
			$crud->set_subject('Pegawai');
			$crud->columns('nama','nip','golongan','kode_unit');
            $crud->fields('nama','nip','golongan','kode_unit');
            //$crud->set_relation('kode_unit','m_unit','nama_unit');
            //$crud->display_as('kode_unit','Unit','nama_unit');

			$output = $crud->render();
			$this->_example_output($output,'Setting Master Data Pegawai');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

    function lov(){
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_lov');
			$crud->set_subject('LOV');
			$crud->columns('kode_app','nama_app','kode_value','nama_value','active');
			$crud->fields('kode_app','nama_app','kode_value','nama_value','active');

			$output = $crud->render();

			$this->_example_output($output,'Setting Master List Of Value');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }



    function dipa(){
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_dipa');
			$crud->set_subject('DIPA');
			$crud->columns('tgl_dipa','nomor_dipa','aktif');
			$crud->fields('tgl_dipa','nomor_dipa','aktif');

            $crud->field_type('aktif','true_false');

			$output = $crud->render();

			$this->_example_output($output,'Setting DIPA');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }

    function tarif_sk(){
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_tarif_sk');
			$crud->set_subject('SK Tarif');
			$crud->columns('tahun_ajaran','sk_penetapan','tanggal_sk');
			$crud->fields('tahun_ajaran','sk_penetapan','tanggal_sk');

			$output = $crud->render();

			$this->_example_output($output,'Setting SK Tarif');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }

    function jeniskwitansi(){
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_jeniskwitansi');
			$crud->set_subject('Jenis Kwitansi');
			$crud->columns('sifat_bayar','kw_type');
			$crud->fields('sifat_bayar','kw_type');

			$output = $crud->render();

			$this->_example_output($output,'Setting Jenis Kwitansi');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }

    function pajak(){
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_pajak');
			$crud->set_subject('Pajak');
			$crud->columns('pph','ppn','ket');
			$crud->fields('pph','ppn','ket');

			$output = $crud->render();

			$this->_example_output($output,'Setting Pajak');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }

    function status_kwitansi(){
        try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_status_kwitansi');
			$crud->set_subject('Staus Kwitansi');
			$crud->columns('id_status_kwitansi','status_kwitansi');
			$crud->fields('id_status_kwitansi','status_kwitansi');

            $crud->display_as('id_status_kwitansi','id Status Kwitansi');
            $crud->display_as('status_kwitansi','Status Kwitansi');

			$output = $crud->render();

			$this->_example_output($output,'Setting Status Kwitansi');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }
    function status_rekap(){
        try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_status_rekap');
			$crud->set_subject('Staus Rekap');
			$crud->columns('id_status_rekap','status_rekap');
			$crud->fields('id_status_rekap','status_rekap');

            $crud->display_as('id_status_rekap','id Status Rekap');
            $crud->display_as('status_rekap','Status Rekap');

			$output = $crud->render();

			$this->_example_output($output,'Setting Status Rekap');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }

    function status_sp2d(){
        try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_status_sp2d');
			$crud->set_subject('Staus SP2D');
			$crud->columns('id_status_sp2d','status_sp2d');
			$crud->fields('id_status_sp2d','status_sp2d');

            $crud->display_as('id_status_sp2d','id Status SP2D');
            $crud->display_as('status_sp2d','Status SP2D');

			$output = $crud->render();

			$this->_example_output($output,'Setting Status SP2D');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }
    function status_spj(){
        try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_status_spj');
			$crud->set_subject('Staus SP2D');
			$crud->columns('id_status_spj','status_spj');
			$crud->fields('id_status_spj','status_spj');

            $crud->display_as('id_status_spj','id Status SPJ');
            $crud->display_as('status_spj','Status SPJ');

			$output = $crud->render();

			$this->_example_output($output,'Setting Status SPJ');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }

    function status_spm(){
        try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_status_spm');
			$crud->set_subject('Staus SPM');
			$crud->columns('id_status_spm','status_spm');
			$crud->fields('id_status_spm','status_spm');

            $crud->display_as('id_status_spm','id Status SPM');
            $crud->display_as('status_spm','Status SPM');

			$output = $crud->render();

			$this->_example_output($output,'Setting Status SPM');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }

    function status_spp(){
        try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_status_spp');
			$crud->set_subject('Staus SPP');
			$crud->columns('id_status_spp','status_spp');
			$crud->fields('id_status_spp','status_spp');

            $crud->display_as('id_status_spp','id Status SPP');
            $crud->display_as('status_spp','Status SPP');

			$output = $crud->render();

			$this->_example_output($output,'Setting Status SPP');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }

    function status_sptb(){
        try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_status_sptb');
			$crud->set_subject('Staus SPTB');
			$crud->columns('id_status_sptb','status_sptb');
			$crud->fields('id_status_sptb','status_sptb');

            $crud->display_as('id_status_sptb','id Status SPTB');
            $crud->display_as('status_sptb','Status SPTB');

			$output = $crud->render();

			$this->_example_output($output,'Setting Status SPTB');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }

    function status_usulan(){
        try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_status_usulan');
			$crud->set_subject('Staus Usulan');
			$crud->columns('id_status_usulan','status_usulan');
			$crud->fields('id_status_usulan','status_usulan');

            $crud->display_as('id_status_usulan','id Status Usulan');
            $crud->display_as('status_usulan','Status Usulan');

			$output = $crud->render();

			$this->_example_output($output,'Setting Status Usulan');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }

    function status_usulan_item(){
        try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_status_usulan_item');
			$crud->set_subject('Staus Usulan Item');
			$crud->columns('id_status_usulan_item','status_usulan_item');
			$crud->fields('id_status_usulan_item','status_usulan_item');

            $crud->display_as('id_status_usulan_item','id Status Usulan Item');
            $crud->display_as('status_usulan_item','Status Usulan Item');

			$output = $crud->render();

			$this->_example_output($output,'Setting Status Usulan Item');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }

    function verifikasi(){
        try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_verifikasi');
			$crud->set_subject('Item Verifikasi');
			$crud->columns('id_jenis','id_persyaratan','required','uraian');
			$crud->fields('id_jenis','id_persyaratan','required','uraian');

            $crud->field_type('id_persyaratan','integer');
            $crud->field_type('required','true_false');

			$output = $crud->render();

			$this->_example_output($output,'Setting Status Usulan Item');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }

    function verunit(){
        try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_verunit');
			$crud->set_subject('Verifikasi Unit');
			$crud->columns('sifat_bayar','jenis_kwitansi','nama');
			$crud->fields('sifat_bayar','jenis_kwitansi','nama');

			$output = $crud->render();

			$this->_example_output($output,'Setting Status Usulan Item');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }
    function rbac_role(){
        try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('rbac_roles');
			$crud->set_subject('RBAC Roles');
			$crud->columns('role_name', 'role_permission');
			$crud->fields('role_name','role_permission');

            $crud->set_primary_key('perm_id','vw_rbac_permissions');
            $crud->set_relation_n_n('role_permission', 'rbac_role_perm', 'vw_rbac_permissions', 'role_id', 'perm_id', '{id_group} - {perm_name}[{perm_desc}]');

			$output = $crud->render();

			$this->_example_output($output,'Setting RBAC Roles');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }
    function rbac_group_permission(){
        try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('rbac_group_permission');
			$crud->set_subject('RBAC Group Permissions');
			$crud->columns('group_name');
			$crud->fields('group_name');

			$output = $crud->render();

			$this->_example_output($output,'Setting RBAC Group Permissions');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }
    function rbac_permission(){
        try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('rbac_permissions');
			$crud->set_subject('RBAC Permissions');
			$crud->columns('perm_name','perm_desc','id_menu','id_group');
			$crud->fields('perm_name','perm_desc','id_menu','id_group');

            $crud->set_relation('id_menu','rbac_menu','{text} [{link}]');
            $crud->set_relation('id_group','rbac_group_permission','group_name');
            $crud->field_type('perm_desc','string');
            $crud->display_as('text','Menu');
            $crud->display_as('link','Link Menu');
			$output = $crud->render();

			$this->_example_output($output,'Setting RBAC Permissions');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }

    function rbac_menu(){
        try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('rbac_menu');
			$crud->set_subject('RBAC Menu');
			$crud->columns('text','link','parent', 'depth', 'weight');
			$crud->fields('text','link','parent', 'depth', 'weight');

            $crud->set_relation('parent','rbac_menu','{text} [{link}]');
            $crud->field_type('perm_desc','string');
			$output = $crud->render();

			$this->_example_output($output,'Setting RBAC Menu');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }

    function template_cetak(){
    	$_SESSION['KCFINDER']['disabled'] = false;
        $_SESSION['KCFINDER']['uploadURL'] = "../../../../../upload";

    	try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('m_template_cetak');
			$crud->set_subject('Template Cetak');
			$crud->columns('kode_setting');
			$crud->fields('template');

      $crud->unset_add();
    	$crud->unset_delete();
      
			$output = $crud->render();

			$this->_example_output($output,'Template Cetak');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
    }
}
