<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CODEIGNITER template library
 *
 * @author	JŽr™me Jaglale
 * @url		http://maestric.com/doc/php/codeigniter_template
 */

class Template
{
		var $template_data = array();
		
		function __construct()
		{
			$this->CI =& get_instance();
			$this->set('title', config_item('system_title'));
			$this->CI->load->model('m_settings');			
		}
		
		function set($name, $value)
		{
			$this->template_data[$name] = $value;
		}
		
		function load($template = '', $view = '' , $view_data = array(), $return = FALSE)
		{
			$this->CI =& get_instance();			
			/*$ta = $this->CI->session->userdata('t_anggaran_aktif');
			if (empty($ta)) {
				$ta = $this->CI->m_settings->get_tahun_anggaran_aktif_db();
				$this->CI->session->set_userdata('t_anggaran_aktif', $ta);
			}*/
			/*
			$view_data['t_anggaran'] = $this->CI->m_settings->get_tahun_anggaran_db();
			
			$ta = $this->CI->session->userdata('t_anggaran_aktif');
			if (empty($ta)) {
				$view_data['t_anggaran_aktif'] = $this->CI->m_settings->get_tahun_anggaran_aktif_db();
				$this->CI->session->set_userdata('t_anggaran_aktif', $view_data['t_anggaran_aktif']);
			}else{
				$view_data['t_anggaran_aktif'] = $ta;
			}
			*/
			
			$this->set('contents', $this->CI->load->view($view, $view_data, TRUE));			
			return $this->CI->load->view($template, $this->template_data, $return);
		}
}

/* End of file Template.php */
/* Location: ./application/libraries/Template.php */