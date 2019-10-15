<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Schools extends Admin_Controller
{
	private $services = null;
	private $name = null;
	private $parent_page = 'admin';
	private $current_page = 'admin/schools/';

	public function __construct()
	{
		parent::__construct();
		$this->load->library('services/School_services');
		$this->services = new School_services;
		$this->load->model(array(
			'm_school',
			'm_teacher',
		));
	}
	public function index()
	{
		#################################################################3
		$table = $this->services->school_table_config($this->current_page);
		$table["rows"] = $this->m_school->school()->result();
		$table = $this->load->view('templates/tables/plain_table_12', $table, true);
		$this->data["contents"] = $table;
		$add_menu = array(
			"name" => "Tambah Sekolah",
			"modal_id" => "add_school_",
			"button_color" => "primary",
			"url" => site_url($this->current_page . "add/"),
			"form_data" => array(
				"name" => array(
					'type' => 'text',
					'label' => "Nama Sekolah",
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
		$this->data["block_header"] = "Sekolah";
		$this->data["header"] = "Daftar Sekolah";
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

			if ($this->m_school->create($data)) {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->m_school->messages()));
			} else {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->m_school->errors()));
			}
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_account->errors() ? $this->m_school->errors() : $this->session->flashdata('message')));
			if (validation_errors() || $this->m_school->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));
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

			if ($this->m_school->update($data, $data_param)) {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->m_school->messages()));
			} else {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->m_school->errors()));
			}
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_account->errors() ? $this->m_school->errors() : $this->session->flashdata('message')));
			if (validation_errors() || $this->m_school->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));
		}

		redirect(site_url($this->current_page));
	}

	public function delete()
	{
		if (!($_POST)) redirect(site_url($this->current_page));

		$data_param['id'] 	= $this->input->post('id');
		if ($this->m_school->delete($data_param)) {
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->m_school->messages()));
		} else {
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->m_school->errors()));
		}
		redirect(site_url($this->current_page));
	}

	public function detail($school_id)
	{
		$param['teacher_profile.school_id'] = $school_id;
		#################################################################3
		$table = $this->services->teacher_table_config($this->current_page);
		$table['rows'] = $this->m_school->teacher_by_school_id($param)->result();
		$table = $this->load->view('templates/tables/plain_table_12', $table, true);
		$this->data["contents"] = $table;
		$add_teacher =
			array(
				"name" => "Tambah Guru",
				"type" => "link",
				"url" => site_url($this->current_page . "create/" . $school_id),
				"button_color" => "primary",
				'param' => ''
			);

		$add_teacher = $this->load->view('templates/actions/link', $add_teacher, true);

		$this->data["header_button"] =  $add_teacher;
		// return;
		#################################################################3
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Sekolah";
		$this->data["header"] = "Daftar Sekolah";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		$this->render("templates/contents/plain_content");
	}

	public function create($school_id)
	{
		$tables = $this->config->item('tables', 'ion_auth');
		$identity_column = $this->config->item('identity', 'ion_auth');
		$this->form_validation->set_rules($this->ion_auth->get_validation_config());
		$this->form_validation->set_rules('phone', "No Telepon", 'trim|required');

		if ($this->form_validation->run() === TRUE) {
			$group_id = $this->input->post('group_id');

			$email = $this->input->post('email');
			$identity = $email;
			$password = substr($email, 0, strpos($identity, "@"));


			$additional_data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'email' => $this->input->post('email'),
				'phone' => $this->input->post('phone'),
				'class_id' => 2,
			);
			$edu_ladder = $this->input->post('edu_ladder_id');
			$nip = $this->input->post('nip');
		}
		if ($this->form_validation->run() === TRUE && ($user_id =  $this->ion_auth->register($identity, $password, $email, $additional_data, $group_id))) {
			$data = [
				'school_id' => $school_id,
				'user_id' => $user_id,
				'edu_ladder_id' => $edu_ladder,
				'nip' => $nip,
			];
			$this->m_teacher->insert_teacher_profile($data);

			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->ion_auth->messages()));
			redirect(site_url($this->current_page . 'detail/' . $school_id));
		} else {
			$param['id'] = $school_id;
			$school = $this->m_school->school($param)->row();
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
			if (!empty(validation_errors()) || $this->ion_auth->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));

			$alert = $this->session->flashdata('alert');
			$this->data["key"] = $this->input->get('key', FALSE);
			$this->data["alert"] = (isset($alert)) ? $alert : NULL;
			$this->data["current_page"] = $this->current_page;
			$this->data["block_header"] = "Tambah Guru ";
			$this->data["header"] = "Tambah Guru " . $school->name;
			$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';

			$form_data = $this->ion_auth->get_form_data();
			$form_teacher['form_data'] = array(
				"nip" => array(
					'type' => 'text',
					'label' => "NIP",
				),
				"edu_ladder_id" => array(
					'type' => 'select',
					'label' => "Jenjang Pendidikan",
					'options' => $this->m_teacher->list_edu_ladder(),
				),
			);
			$form_data['form_data'] = array_merge($form_data['form_data'], $form_teacher['form_data']);
			$form_data = $this->load->view('templates/form/bsb_form', $form_data, TRUE);

			$this->data["contents"] =  $form_data;

			$this->render("templates/contents/plain_content_form");
		}
	}

	public function edit_teacher()
	{
		if (!($_POST)) redirect(site_url($this->current_page));

		// echo var_dump( $data );return;
		$this->form_validation->set_rules('active', 'Status', 'required');
		if ($this->form_validation->run() === TRUE) {
			$data['active'] = $this->input->post('active');

			$data_param['id'] = $this->input->post('id');

			if ($this->m_teacher->update_teacher($data, $data_param)) {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->m_school->messages()));
			} else {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->m_school->errors()));
			}
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_account->errors() ? $this->m_school->errors() : $this->session->flashdata('message')));
			if (validation_errors() || $this->m_school->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));
		}

		redirect(site_url($this->current_page));
	}

	public function delete_teacher()
	{
		if (!($_POST)) redirect(site_url($this->current_page));

		$data_param['id'] 	= $this->input->post('id');
		if ($this->m_school->delete_teacher($data_param)) {
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->m_school->messages()));
		} else {
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->m_school->errors()));
		}
		redirect(site_url($this->current_page));
	}
}
