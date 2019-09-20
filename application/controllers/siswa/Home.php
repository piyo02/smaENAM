<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends Siswa_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_ulangan');
	}
	public function index()
	{
		$data_param['kelas_id'] = $this->session->userdata('class_id');
		$this->data['rows'] = $this->m_ulangan->get_ulangan($data_param)->result();
		$this->data["page_title"] = "Beranda";
		$this->render("siswa/dashboard/content");
	}
}
