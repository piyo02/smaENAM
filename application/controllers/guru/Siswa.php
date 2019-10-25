<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Siswa extends Users_Controller
{
	private $services = null;
	private $name = null;
	private $parent_page = 'guru';
	private $current_page = 'guru/siswa/';

	public function __construct()
	{
		parent::__construct();
		$this->load->library('services/Siswa_services');
		$this->services = new Siswa_services;
		$this->load->model(array(
			'm_group',
			'm_ulangan',
			'm_student_profile',
		));
	}

	public function index()
	{
		$param['creator_id'] = $this->session->userdata('user_id');
		#################################################################3
		$table = $this->services->groups_table_config($this->current_page);
		$table["rows"] = $this->m_ulangan->get_class_teacher($param)->result();
		$table = $this->load->view('templates/tables/plain_table_12', $table, true);
		$this->data["contents"] = $table;

		// return;
		#################################################################3
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Siswa";
		$this->data["header"] = "Daftar Hasil Siswa";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		$this->render("templates/contents/plain_content");
	}

	public function detail($class_id)
	{
		$param = [
			'class_id' => $class_id,
			'creator_id' => $this->session->userdata('user_id')
		];

		#################################################################3
		$table = $this->services->course_table_config($this->current_page, 1, $class_id);
		$table["rows"] = $this->m_ulangan->get_course_from_ulangan($param)->result();
		$table = $this->load->view('templates/tables/plain_table_12', $table, true);
		$this->data["contents"] = $table;

		// return;
		#################################################################3
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Siswa";
		$this->data["header"] = "Daftar Hasil Siswa";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		$this->render("templates/contents/plain_content");
	}

	public function list($course_id)
	{
		$class_id = $this->input->get('class_id');
		$param = [
			'school_id' => $this->session->userdata('school_id'),
			'class_id' => $class_id,
			// 'creator_id' => $this->session->userdata('user_id')
		];

		#################################################################3
		$table = $this->services->lists_table_config($this->current_page, 1, $class_id, $course_id);
		$table["rows"] = $this->m_student_profile->get_student_by_class($param)->result();
		$table = $this->load->view('templates/tables/plain_table_12', $table, true);
		$this->data["contents"] = $table;

		// return;
		#################################################################3
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Siswa";
		$this->data["header"] = "Daftar Hasil Siswa";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		$this->render("templates/contents/plain_content");
	}

	public function add()
	{
		if (!($_POST)) redirect(site_url($this->current_page));

		// echo var_dump( $data );return;
		$this->form_validation->set_rules($this->services->validation_config());
		if ($this->form_validation->run() === TRUE) {
			$data['name'] = $this->input->post('name');
			$data['description'] = $this->input->post('description');

			if ($this->m_group->create($data)) {
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

	public function edit()
	{
		if (!($_POST)) redirect(site_url($this->current_page));

		// echo var_dump( $data );return;
		$this->form_validation->set_rules($this->services->validation_config());
		if ($this->form_validation->run() === TRUE) {
			$data['name'] = $this->input->post('name');
			$data['description'] = $this->input->post('description');

			$data_param['id'] = $this->input->post('id');

			if ($this->m_group->update($data, $data_param)) {
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

	public function delete()
	{
		if (!($_POST)) redirect(site_url($this->current_page));

		$data_param['id'] 	= $this->input->post('id');
		if ($this->m_group->delete($data_param)) {
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->m_group->messages()));
		} else {
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->m_group->errors()));
		}
		redirect(site_url($this->current_page));
	}

	public function report($user_id)
	{
		$data_param = [
			'user_id' => $user_id,
			'course_id' => $this->input->get('course_id'),
		];
	}
}
