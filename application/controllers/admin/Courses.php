<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Courses extends Admin_Controller
{
	private $services = null;
	private $name = null;
	private $parent_page = 'admin';
	private $current_page = 'admin/courses/';

	public function __construct()
	{
		parent::__construct();
		$this->load->library('services/Courses_services');
		$this->services = new Courses_services;
		$this->load->model(array(
			'm_courses',
			'm_teacher',
		));
	}
	public function index()
	{
		#################################################################3
		$table = $this->services->course_admin_table_config($this->current_page);
		$table["rows"] = $this->m_courses->get_courses()->result();
		$table = $this->load->view('templates/tables/plain_table_12', $table, true);
		$this->data["contents"] = $table;
		$add_menu = array(
			"name" => "Tambah Mata Pelajaran",
			"modal_id" => "add_courses_",
			"button_color" => "primary",
			"url" => site_url($this->current_page . "add/"),
			"form_data" => array(
				"edu_ladder_id" => array(
					'type' => 'select',
					'label' => "Jenjang Pendidikan",
					'options' => $this->m_teacher->list_edu_ladder()
				),
				"name" => array(
					'type' => 'text',
					'label' => "Mata Pelajaran",
					'value' => "",
				),
				"description" => array(
					'type' => 'textarea',
					'label' => "Deskripsi",
					'value' => "-",
				),
				'data' => NULL
			),
		);

		$add_menu = $this->load->view('templates/actions/modal_form', $add_menu, true);

		$this->data["header_button"] =  $add_menu;
		// return;
		#################################################################3
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Mata Peljaran";
		$this->data["header"] = "Daftar Mata Peljaran";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		$this->render("templates/contents/plain_content");
	}


	public function add()
	{
		if (!($_POST)) redirect(site_url($this->current_page));

		// echo var_dump( $data );return;
		$this->form_validation->set_rules($this->services->validation_admin_config());
		if ($this->form_validation->run() === TRUE) {
			$data['edu_ladder_id'] = $this->input->post('edu_ladder_id');
			$data['name'] = $this->input->post('name');
			$data['description'] = $this->input->post('description');

			if ($this->m_courses->create($data)) {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->m_courses->messages()));
			} else {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->m_courses->errors()));
			}
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_account->errors() ? $this->m_courses->errors() : $this->session->flashdata('message')));
			if (validation_errors() || $this->m_courses->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));
		}

		redirect(site_url($this->current_page));
	}

	public function edit()
	{
		if (!($_POST)) redirect(site_url($this->current_page));

		// echo var_dump( $data );return;
		$this->form_validation->set_rules($this->services->validation_admin_config());
		if ($this->form_validation->run() === TRUE) {
			$data['name'] = $this->input->post('name');
			$data['description'] = $this->input->post('description');

			$data_param['id'] = $this->input->post('id');

			if ($this->m_courses->update($data, $data_param)) {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->m_courses->messages()));
			} else {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->m_courses->errors()));
			}
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_account->errors() ? $this->m_courses->errors() : $this->session->flashdata('message')));
			if (validation_errors() || $this->m_courses->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));
		}

		redirect(site_url($this->current_page));
	}

	public function delete()
	{
		if (!($_POST)) redirect(site_url($this->current_page));

		$data_param['id'] 	= $this->input->post('id');
		if ($this->m_courses->delete($data_param)) {
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->m_courses->messages()));
		} else {
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->m_courses->errors()));
		}
		redirect(site_url($this->current_page));
	}
}
