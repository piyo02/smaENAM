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
		$data_param['kelas_id'] = $this->session->userdata('class_id');
		$this->data['rows'] = array();
		$add_class = array(
			"name" => "Kelas",
			"modal_id" => "add_class",
			"button_color" => "primary",
			"url" => site_url($this->current_page . "create/"),
			"form_data" => array(
				'p' => array(
					'type' => 'text',
					'label' => "Kode Kelas",
				),
				'data' => NULL
			),
		);

		$add_class = $this->load->view('templates/actions/modal_form_large', $add_class, true);
		$this->data['header_button'] = $add_class;
		// $this->data['rows'] = $this->m_ulangan->get_ulangan($data_param, $this->session->userdata('user_id'))->result();
		$this->data["page_title"] = "Beranda";
		$this->render("siswa/dashboard/content");
	}
}
