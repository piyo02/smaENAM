<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends Siswa_Controller
{
	private $services = null;
	private $name = null;
	private $parent_page = 'siswa';
	private $current_page = 'siswa/home/';


	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_ulangan');
	}
	public function index()
	{
		$data_param['class_id'] = $this->session->userdata('class_id');

		$alert = $this->session->flashdata('alert');
		$this->data['rows'] = $this->m_ulangan->get_ulangan($data_param, $this->session->userdata('user_id'))->result();
		$this->data["alert"] = (isset($alert)) ? $alert : NULL;
		$this->data["page_title"] = "Beranda";
		$this->render("siswa/dashboard/content");
	}
}
