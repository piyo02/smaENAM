<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Soal extends Users_Controller
{
	private $services = null;
	private $name = null;
	private $parent_page = 'guru';
	private $current_page = 'guru/soal/';

	public function __construct()
	{
		parent::__construct();
		$this->load->library('services/Soal_services');
		$this->services = new Soal_services;
		$this->load->model(array(
			'm_bank_soal',
			'm_mapel',
			'm_soal',
			'm_jawaban',
		));
	}
	public function daftar_soal($bank_soal_id = NULL)
	{
		if (!$bank_soal_id)
			redirect('guru/bank_soal');
		$page = ($this->uri->segment(5)) ? ($this->uri->segment(5) - 1) : 0;
		//pagination parameter
		$pagination['base_url'] = base_url($this->current_page) . '/index';
		$pagination['total_records'] = $this->ion_auth->record_count();
		$pagination['limit_per_page'] = 10;
		$pagination['start_record'] = $page * $pagination['limit_per_page'];
		$pagination['uri_segment'] = 4;
		//set pagination
		if ($pagination['total_records'] > 0) $this->data['pagination_links'] = $this->setPagination($pagination);

		$table = $this->services->groups_table_config($this->current_page);

		$table["rows_soal"] = $this->m_soal->get_soal($bank_soal_id, $pagination['limit_per_page'], $pagination['start_record'])->result();
		$table = $this->load->view('templates/tables/plain_table_soal', $table, true);
		$this->data["contents"] = $table;
		$add_menu = array(
			"name" => "Tambah Soal",
			"modal_id" => "add_soal",
			"button_color" => "primary",
			"url" => site_url($this->current_page . "create/"),
			"form_data" => array(
				"s" => array(
					'type' => 'select',
					'label' => "Tipe Soal",
					'options' => array(
						'teks' => 'Teks',
						'gambar' => 'Gambar',
						'audio' => 'Audio',
					),
				),
				"o" => array(
					'type' => 'select',
					'label' => "Tipe Jawaban",
					'options' => array(
						'teks' => 'Teks',
						'gambar' => 'Gambar',
						'isian' => 'Isian Singkat',
						'esai' => 'Esai ',
					),
				),
				"b" => array(
					'type' => 'hidden',
					'label' => "Tipe Jawaban",
					'value' => $bank_soal_id
				),
				'p' => array(
					'type' => 'hidden',
					'label' => "Tipe Jawaban",
					'value' => 1
				),
				'data' => NULL
			),
		);

		$add_menu = $this->load->view('templates/actions/modal_form_get', $add_menu, true);

		//import soal
		$btn_import = array(
			"name" => "Import Soal",
			"modal_id" => "import_soal",
			"button_color" => "success",
			"url" => site_url("guru/excel_import/import/"),
			"form_data" => array(
				"file" => array(
					'type' => 'file',
					'label' => "Pilih File",
				),
				"b" => array(
					'type' => 'hidden',
					'label' => "Tipe Jawaban",
					'value' => $bank_soal_id
				),
				'data' => NULL
			),
		);

		$btn_import = $this->load->view('templates/actions/modal_form_multipart', $btn_import, true);
		$this->data["header_button"] =  $add_menu . $btn_import;

		//set option
		$rows = $this->m_bank_soal->bank_soal($this->session->userdata('user_id'))->result();
		$options[0] = '-- Pilih bank soal --';
		foreach ($rows as $row => $value) {
			$options[$value->id] = $value->nama;
		}
		$this->data["options"] = $options;
		$this->data["selected"] = '';

		#################################################################3
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Daftar Soal";
		$this->data["header"] = "Daftar Soal";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		$this->render("guru/soal/content");
	}


	public function create()
	{
		//validation sesuai opsi
		$opsi = $this->input->get('o');
		$validation = 'validation_' . $opsi;
		$this->form_validation->set_rules($this->services->$validation());


		$bank_soal_id = $this->input->get('b');
		$tipe_soal = $this->input->get('s');
		$tipe_option = $this->input->get('o');

		if ($this->form_validation->run() === TRUE) {
			$id = $this->generate_id();

			$method_soal = 'get_soal_' . $tipe_soal;
			$method_option = 'get_option_' . $tipe_option;

			$data_soal = $this->$method_soal($id);
			$id = $this->m_soal->insert_soal($data_soal);

			$data_option = $this->$method_option($id);
			$this->m_soal->insert_option($data_option);

			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->m_soal->messages()));
			redirect(site_url($this->current_page . 'daftar_soal/' . $bank_soal_id));
		} else {

			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_soal->errors() ? $this->m_soal->errors() : $this->session->flashdata('message')));
			if (!empty(validation_errors()) || $this->m_soal->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));

			$add_menu = array(
				"name" => "Kembali",
				"button_color" => "primary",
				"url" => site_url($this->current_page . "daftar_soal/" . $bank_soal_id),
			);

			$add_menu = $this->load->view('templates/actions/link', $add_menu, true);

			$this->data["header_button"] =  $add_menu;

			$alert = $this->session->flashdata('alert');
			$this->data["key"] = $this->input->get('key', FALSE);
			$this->data["alert"] = (isset($alert)) ? $alert : NULL;
			$this->data["current_page"] = $this->current_page;
			$this->data["block_header"] = "Tambah Soal ";
			$this->data["header"] = "Tambah Soal ";
			$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';


			$form_soal = 'get_form_soal_' . $tipe_soal;
			$form_option = 'get_form_option_' . $tipe_option;

			$form_data_soal = $this->services->$form_soal();
			$form_data_option = $this->services->$form_option();

			$form_data['form_data'] = array_merge($form_data_soal['form_data'], $form_data_option['form_data']);
			$form_data = $this->load->view('templates/form/bsb_form', $form_data, TRUE);

			$this->data["contents"] =  $form_data;

			$this->render("guru/soal/create");
		}
	}




	public function edit($soal_id = NULL)
	{
		$this->form_validation->set_rules($this->services->validation_esai());
		if ($this->form_validation->run() === TRUE) {
			$soal_id = $this->input->post('id');
			$bank_soal_id = $this->input->post('bank_soal_id');

			$data = [
				'text' => $this->input->post('text'),
			];
			if (isset($_FILES['gambar']) && $_FILES['gambar']['name'] != '')
				$data['gambar'] = $this->upload_soal_gambar($soal_id, 1);

			$_data_option = '';
			if (isset($_FILES['jawaban']) && $_FILES['jawaban']['name'] != '')
				$_data_option = $this->get_option_gambar_edit($soal_id);

			//mengambil option yang benar
			if (null !== $this->input->post('type')) {
				$tipe_option = $this->input->post('type');
				$form_option = 'get_option_' . $tipe_option;
				$_data_option = $this->$form_option($soal_id);
			}

			if ($this->m_soal->update($data, $_data_option, $soal_id)) {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->m_soal->messages()));
				redirect(site_url($this->current_page)  . 'daftar_soal/' . $bank_soal_id);
			} else {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->m_soal->errors()));
				redirect(site_url($this->current_page)  . 'daftar_soal/' . $bank_soal_id);
			}
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_soal->errors() ? $this->m_soal->errors() : $this->session->flashdata('message')));
			if (!empty(validation_errors()) || $this->m_soal->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));

			$alert = $this->session->flashdata('alert');
			$this->data["key"] = $this->input->get('key', FALSE);
			$this->data["alert"] = (isset($alert)) ? $alert : NULL;
			$this->data["current_page"] = $this->current_page;
			$this->data["block_header"] = "Edit Soal ";
			$this->data["header"] = "Edit Soal ";
			$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';

			$data_param['id'] = $soal_id;
			$_soal = $this->m_soal->get_soal_by_id($data_param)->row();

			$form_data = 'get_form_data_' . $_soal->type;
			$form_data = $this->services->$form_data($_soal);


			$data_param = [
				'soal_id' => $soal_id
			];
			$_option = $this->m_soal->get_option_by_id($data_param)->result();
			$form_option = 'get_form_option_' . $_option[0]->type;
			$form_data_option = $this->services->$form_option($_option);

			$form_data['form_data'] = array_merge($form_data['form_data'], $form_data_option['form_data']);
			if (isset($form_data_option['data']))
				$form_data['option'] = $form_data_option['data'];
			$form_data = $this->load->view('templates/form/bsb_form', $form_data, TRUE);
			$this->data["contents"] =  $form_data;

			$this->render("guru/soal/edit");
		}
	}




	public function detail($soal_id = NULL)
	{
		$data_param['id'] = $soal_id;
		$_soal = $this->m_soal->get_soal_by_id($data_param)->row();

		$data_param = [
			'soal_id' => $soal_id
		];
		$_option = $this->m_soal->get_option_by_id($data_param)->result();

		$form_data = $this->services->get_form_soal_teks($_soal);

		$form_option = 'get_form_option_' . $_option[0]->type;
		$form_data_option['data'] = array();
		$form_data_option = $this->services->$form_option($_option);

		$form_data['form_data'] = array_merge($form_data['form_data'], $form_data_option['form_data']);

		if (isset($form_data_option['data']))
			$form_data['option'] = $form_data_option['data'];
		$form_data = $this->load->view('templates/form/bsb_form_readonly', $form_data, TRUE);


		$add_menu = array(
			"name" => "Kembali",
			"button_color" => "primary",
			"url" => site_url($this->current_page . "daftar_soal/" . $_soal->bank_soal_id),
		);

		$add_menu = $this->load->view('templates/actions/link', $add_menu, true);

		$this->data["header_button"] =  $add_menu;

		$this->data["contents"] =  $form_data;
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Detail ";
		$this->data["header"] = "Detail Soal ";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';

		$this->render("guru/soal/detail");
	}



	public function delete()
	{
		if (!($_POST)) redirect(site_url($this->current_page . '/daftar_soal'));
		$soal_id = $this->input->post('id');
		$bank_soal_id = $this->input->post('bank_soal_id');
		if ($this->m_soal->delete($soal_id)) {
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->m_soal->messages()));
		} else {
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->m_soal->errors()));
		}
		redirect(site_url($this->current_page . 'daftar_soal/' . $bank_soal_id));
	}


	public function upload_soal_gambar($id, $edit = null)
	{
		$file = $_FILES['gambar'];

		$config['upload_path']          = './uploads/soal/';
		$config['allowed_types']        = 'gif|jpg|png|jpeg';
		$config['overwrite']            = true;
		$config['max_size']             = 1024;


		$_FILES['gambar']['name']     = $id . '_' . time() . '_' . $file['name'];
		$_FILES['gambar']['type']     = $file['type'];
		$_FILES['gambar']['tmp_name'] = $file['tmp_name'];
		$_FILES['gambar']['error']    = $file['error'];
		$_FILES['gambar']['size']     = $file['size'];

		$name = $id . '_' . time() . '_' . $file['name'];
		$this->load->library('upload', $config);
		$this->upload->do_multi_upload('gambar');

		$data =  $name;

		$data_param['id'] = $id;
		$old_file = $this->m_soal->get_soal_by_id($data_param)->row();
		if ($edit)
			$this->remove_photo($old_file->gambar);

		return $data;
	}

	public function remove_photo($file_name)
	{
		$config['upload_path']          = './uploads/soal/';

		return @unlink($config['upload_path'] . $file_name);
	}

	public function upload_soal_audio($id)
	{
		$file = $_FILES['audio'];
		var_dump($file);
		die;
		$config['upload_path']          = './uploads/audio/';
		$config['allowed_types']        = 'mp3';
		$config['overwrite']            = true;
		$config['max_size']             = 1024;


		$_FILES['audio']['name']     = $id . '_' . time() . '_' . $file['name'];
		$_FILES['audio']['type']     = $file['type'];
		$_FILES['audio']['tmp_name'] = $file['tmp_name'];
		$_FILES['audio']['error']    = $file['error'];
		$_FILES['audio']['size']     = $file['size'];

		$name = $id . '_' . time() . '_' . $file['name'];
		$this->load->library('upload', $config);
		$this->upload->do_multi_upload('audio');

		$data =  '<img src="' . base_url('uploads/audio/') . $name . '" width="300px" heigth="300px">';


		return $data;
	}

	public function get_soal_teks($id)
	{
		$data = [
			'kode' => $id,
			'bank_soal_id' => $this->input->get('b'),
			'type' => 'teks',
			'text' => $this->input->post('text'),
		];
		return $data;
	}

	public function get_soal_gambar($id)
	{
		$data = [
			'kode' => $id,
			'bank_soal_id' => $this->input->get('b'),
			'type' => 'gambar',
			'text' => $this->input->post('text'),
		];
		$data['gambar'] = $this->upload_soal_gambar($id);
		return $data;
	}

	public function get_soal_audio($id)
	{
		$data = [
			'kode' => $id,
			'bank_soal_id' => $this->input->get('b'),
			'type' => 'audio',
			'audio' => $this->input->post('text'),
		];
		$data['audio'] = $this->upload_soal_audio($id);
		return $data;
	}

	public function get_option_teks($id)
	{
		for ($i = 0; $i < 5; $i++) {
			$_option['soal_id'] = $id;
			$_option['type']    = 'teks';
			$_option['jawaban'] = $this->input->post('jawaban_' . $i);

			$_option['skor']    = 0;
			if (null !== $this->input->post('jawaban_5') && $this->input->post('jawaban_5') == $i)
				$_option['skor'] = 1;
			if (null !== $this->input->post('data_' . $i))
				$_option['id'] = $this->input->post('data_' . $i);
			$_data_option[] = $_option;
		}
		return $_data_option;
	}

	public function get_option_gambar($id)
	{
		$file = $_FILES['jawaban'];

		$name = [];

		$config['upload_path']          = './uploads/soal/';
		$config['allowed_types']        = 'gif|jpg|png|jpeg';
		$config['overwrite']            = true;
		$config['max_size']             = 1024;

		for ($i = 0; $i < 5; $i++) {

			$_FILES['jawaban']['name']     = $id . '_' . time() . '_' . $file['name'][$i];
			$_FILES['jawaban']['type']     = $file['type'][$i];
			$_FILES['jawaban']['tmp_name'] = $file['tmp_name'][$i];
			$_FILES['jawaban']['error']    = $file['error'][$i];
			$_FILES['jawaban']['size']     = $file['size'][$i];

			$name[] = $id . '_' . time() . '_' . $file['name'][$i];
			$this->load->library('upload', $config);
			$this->upload->do_multi_upload('jawaban');
			$skor = 0;
			if ($this->input->post('jawaban_5') == $i)
				$skor = 1;
			$data['jawaban'][] = array(
				'soal_id' => $id,
				'jawaban' => $name[$i],
				'skor' => $skor,
				'type' => 'gambar',
			);
		}

		return $data['jawaban'];
	}

	public function get_option_gambar_edit($id)
	{
		$file = $_FILES['jawaban'];
		$name = [];

		$config['upload_path']          = './uploads/soal/';
		$config['allowed_types']        = 'gif|jpg|png|jpeg';
		$config['overwrite']            = true;
		$config['max_size']             = 1024;

		for ($i = 0; $i < 5; $i++) {

			$_FILES['jawaban']['name']     = $id . '_' . time() . '_' . $file['name'][$i];
			$_FILES['jawaban']['type']     = $file['type'][$i];
			$_FILES['jawaban']['tmp_name'] = $file['tmp_name'][$i];
			$_FILES['jawaban']['error']    = $file['error'][$i];
			$_FILES['jawaban']['size']     = $file['size'][$i];

			$name[] = $id . '_' . time() . '_' . $file['name'][$i];
			$this->load->library('upload', $config);
			$this->upload->do_multi_upload('jawaban');
			$skor = 0;
			if ($this->input->post('jawaban_5') == $i)
				$skor = 1;
			$data['jawaban'][$i] = array(
				'id' => $this->input->post('data_' . $i),
				'soal_id' => $id,
				'skor' => $skor,
			);
			if ($file['name'][$i] != '') {
				$data['jawaban'][$i]['jawaban'] = $name[$i];

				//remove foto
				$data_param['id'] = $this->input->post('data_' . $i);
				$old_file = $this->m_soal->get_option_by_id($data_param)->row();
				$this->remove_photo($old_file->jawaban);
			}
		}

		return $data['jawaban'];
	}

	public function get_option_isian($soal_id)
	{
		$id = null;
		if (null !== $this->input->post('data_4'))
			$id = $this->input->post('data_4');
		$data[] = [
			'id' => $id,
			'soal_id' => $soal_id,
			'type' => 'isian',
			'jawaban' => $this->input->post('jawaban_4'),
			'skor' => $this->input->post('skor'),
		];
		return $data;
	}

	public function get_option_esai($soal_id)
	{
		$id = null;
		if (null !== $this->input->post('data_4'))
			$id = $this->input->post('data_4');
		$data[] = [
			'id' => $id,
			'soal_id' => $soal_id,
			'type' => 'esai',
			'jawaban' => $this->input->post('jawaban_4'),
			'skor' => $this->input->post('skor'),
		];

		return $data;
	}


	public function generate_id()
	{
		$data = $this->m_soal->get_number($this->input->get('b'))->row();
		if (!$data->kode)
			$kode = 'S-1';
		else {
			$id = substr($data->kode, 2) + 1;
			$kode = 'S-' . $id;
		}
		return $kode;
	}
}
