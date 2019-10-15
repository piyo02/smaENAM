<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Classes extends Siswa_Controller
{
	private $services = null;
	private $name = null;
	private $parent_page = 'siswa';
	private $current_page = 'siswa/classes/';


	public function __construct()
	{
		parent::__construct();
		$this->load->library('services/Student_services');
		$this->services = new Student_services;
		$this->load->model(array(
			'm_class',
			'm_ulangan',
			'm_student_class'
		));
	}
	public function index()
	{
		$param['student_class.user_id'] = $this->session->userdata('user_id');
		$table = $this->services->groups_table_config($this->current_page);

		$table["rows_soal"] = $this->m_student_class->get_student_class($param)->result();
		$table = $this->load->view('templates/tables/plain_table_soal', $table, true);
		$this->data["contents"] = $table;
		$add_class = array(
			"name" => "Tambah Kelas",
			"modal_id" => "add_class",
			"button_color" => "primary",
			"url" => site_url($this->current_page . "add/"),
			"form_data" => array(
				'code' => array(
					'type' => 'text',
					'label' => "Kode Kelas",
				),
				'data' => NULL
			),
		);

		$add_class = $this->load->view('templates/actions/modal_form', $add_class, true);
		$this->data["header_button"] =  $add_class;
		#################################################################3
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Daftar Kelas";
		$this->data["header"] = "Daftar Kelas";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		$this->render("siswa/class/content");
	}

	public function add()
	{
		if (!($_POST)) redirect(site_url($this->current_page));

		// echo var_dump( $data );return;
		$this->form_validation->set_rules($this->services->validation_config());
		if ($this->form_validation->run() === TRUE) {
			$param['code'] = $this->input->post('code');
			$class = $this->m_class->get_classes($param);
			var_dump($class);
			die;
			if ($this->m_student_class->create($param)) {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->m_group->messages()));
			} else {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->m_group->errors()));
			}
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_account->errors() ? $this->m_group->errors() : $this->session->flashdata('message')));
			if (validation_errors() || $this->m_group->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));
		}

		redirect(site_url($this->current_page));
	}
}
