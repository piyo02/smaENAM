<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Soal_services
{
	// user var
	protected $id;
	protected $identity;
	protected $first_name;
	protected $last_name;
	protected $phone;
	protected $address;
	protected $email;
	protected $group_id;

	function __construct()
	{
		$this->id		      = '';
		$this->identity		= '';
		$this->first_name	= "";
		$this->last_name	= "";
		$this->phone		  = "";
		$this->address		= "";
		$this->email		  = "";
		$this->group_id		= '';
	}

	public function __get($var)
	{
		return get_instance()->$var;
	}

	public function groups_table_config($_page, $start_number = 1)
	{
		$table["header"] = array(
			'kode' => 'Kode',
			'text' => 'Soal',
			'type' => 'Tipe Soal',
			'jawaban' => 'Jawaban',
		);
		$table["number"] = $start_number;
		$table["action"] = array(
			array(
				"name" => "Detail",
				"type" => "link",
				"url" => site_url($_page . "detail/"),
				"button_color" => "success",
				"param" => "id",
			),
			array(
				"name" => "Edit",
				"type" => "link",
				"url" => site_url($_page . "edit/"),
				"button_color" => "primary",
				"param" => "id",
			),
			array(
				"name" => 'X',
				"type" => "modal_delete",
				"modal_id" => "delete_category_",
				"url" => site_url($_page . "delete/"),
				"button_color" => "danger",
				"param" => "id",
				"form_data" => array(
					"id" => array(
						'type' => 'hidden',
						'label' => "id",
					),
					"bank_soal_id" => array(
						'type' => 'hidden',
						'label' => "id",
					),
				),
				"title" => "User",
				"data_name" => "kode",
			),
		);
		return $table;
	}

	public function validation_teks()
	{
		$config = array(
			array(
				'field' => 'text',
				'label' => 'Soal Teks',
				'rules' =>  'trim|required',
			),
			array(
				'field' => 'jawaban_0',
				'label' => 'Pilihan A',
				'rules' =>  'trim|required',
			),
			array(
				'field' => 'jawaban_1',
				'label' => 'Pilihan B',
				'rules' =>  'trim|required',
			),
			array(
				'field' => 'jawaban_2',
				'label' => 'Pilihan C',
				'rules' =>  'trim|required',
			),
			array(
				'field' => 'jawaban_3',
				'label' => 'Pilihan D',
				'rules' =>  'trim|required',
			),
			array(
				'field' => 'jawaban_4',
				'label' => 'Pilihan E',
				'rules' =>  'trim|required',
			),
		);

		return $config;
	}

	public function validation_gambar()
	{
		$config = array(
			array(
				'field' => 'text',
				'label' => 'Soal Teks',
				'rules' =>  'trim|required',
			),
		);

		return $config;
	}

	public function validation_isian()
	{
		$config = array(
			array(
				'field' => 'text',
				'label' => 'Soal Teks',
				'rules' =>  'trim|required',
			),
			array(
				'field' => 'jawaban_4',
				'label' => 'Jawaban',
				'rules' =>  'trim|required',
			),
			array(
				'field' => 'skor',
				'label' => 'Nilai Jawaban',
				'rules' =>  'trim|required',
			),
		);

		return $config;
	}

	public function validation_esai()
	{
		$config = array(
			array(
				'field' => 'text',
				'label' => 'Soal Teks',
				'rules' =>  'trim|required',
			),
		);

		return $config;
	}

	public function get_form_soal_teks($data = null)
	{
		if ($data)
			$_data['data'] = $data;
		$_data["form_data"] = array(
			"text" => array(
				'type' => 'ckeditor',
				'label' => "Soal teks",
			),
		);
		if ($data)
			$_data["form_data"]['text']['value'] = $data->text;
		return $_data;
	}

	public function get_form_soal_gambar()
	{
		$_data["form_data"] = array(
			"gambar" => array(
				'type' => 'file',
				'label' => "Soal gambar",
			),
			"text" => array(
				'type' => 'ckeditor',
				'label' => "Soal teks",
			),
		);
		return $_data;
	}

	public function get_form_soal_audio()
	{
		$_data["form_data"] = array(
			"audio" => array(
				'type' => 'file',
				'label' => "Soal audio (*.mp3)",
			),
			"text" => array(
				'type' => 'ckeditor',
				'label' => "Soal teks",
			),
		);
		return $_data;
	}

	public function get_form_option_teks($data = null)
	{

		$value = '';
		$opsi = ['Pilihan A', 'Pilihan B', 'Pilihan C', 'Pilihan D', 'Pilihan E'];
		$_data["form_data"] = array();
		for ($i = 0; $i < 5; $i++) {
			$_data["form_data"]['jawaban_' . $i] = array(
				'type' => 'text',
				'label' => $opsi[$i],
			);
			if ($data) {
				$_data["form_data"]['jawaban_' . $i]['value'] = $data[$i]->jawaban;
				if ($data[$i]->skor == 1)
					$value = $i;
				$_data["form_data"]['data_' . $i] = array(
					'type' => 'hidden',
					'label' => 'id',
					'value' => $data[$i]->id,
				);
				$_data["form_data"]['type'] = array(
					'type' => 'hidden',
					'label' => 'type option',
					'value' => 'teks'
				);
			}
		}
		$_data["form_data"]['jawaban_5'] = array(
			'type' => 'select',
			'label' => 'Jawaban',
			'options' => array(
				0 => 'Pilihan A',
				1 => 'Pilihan B',
				2 => 'Pilihan C',
				3 => 'Pilihan D',
				4 => 'Pilihan E'
			),
			'selected' => $value
		);
		return $_data;
	}

	public function get_form_option_gambar($data = null)
	{
		if ($data)
			$_data['data'] = $data;
		$opsi = ['Pilihan A', 'Pilihan B', 'Pilihan C', 'Pilihan D', 'Pilihan E'];
		$_data["form_data"] = array();
		for ($i = 0; $i < 5; $i++) {
			$_data["form_data"]['jawaban[' . $i . ']'] = array(
				'type' => 'file',
				'label' => $opsi[$i],
			);
			if ($data) {
				$_data["form_data"]['data_' . $i] = array(
					'type' => 'hidden',
					'label' => 'id',
					'value' => $data[$i]->id,
				);
				if ($data[$i]->skor == 1)
					$value = $i;
			}
		}
		$_data["form_data"]['jawaban_5'] = array(
			'type' => 'select',
			'label' => 'Jawaban',
			'options' => array(
				0 => 'Pilihan A',
				1 => 'Pilihan B',
				2 => 'Pilihan C',
				3 => 'Pilihan D',
				4 => 'Pilihan E'
			)
		);
		if ($data)
			$_data["form_data"]['jawaban_5']['selected'] = $value;
		return $_data;
	}

	public function get_form_option_isian($data = null)
	{
		$_data["form_data"]["jawaban_4"] = array(
			'type' => 'text',
			'label' => "Jawaban *panjang karakter hanya 255",
		);
		$_data["form_data"]["skor"] = array(
			'type' => 'number',
			'label' => "Skor",
		);
		if ($data) {
			$_data["form_data"]['jawaban_4']['value'] = $data[0]->jawaban;
			$_data["form_data"]['skor']['value'] = $data[0]->skor;
			$_data["form_data"]['data_4'] = array(
				'type' => 'hidden',
				'label' => "id",
				'value' => $data[0]->id,
			);
			$_data["form_data"]['type'] = array(
				'type' => 'hidden',
				'label' => 'type option',
				'value' => 'isian'
			);
		}
		return $_data;
	}

	public function get_form_option_esai($data = null)
	{
		$_data["form_data"]['jawaban_4'] = array(
			'type' => 'text',
			'label' => "Silahkan tekan tombol simpan",
			'readonly' => 'readonly',
			'value' => 'Soal esai'
		);
		$_data["form_data"]['skor'] = array(
			'type' => 'number',
			'label' => "Skor",
		);
		if ($data) {
			$_data["form_data"]['jawaban_4']['type'] = 'hidden';
			$_data["form_data"]['skor']['value'] = $data[0]->skor;
			$_data["form_data"]['data_4'] = array(
				'type' => 'hidden',
				'label' => "id",
				'value' => $data[0]->id,
			);
			$_data["form_data"]['type'] = array(
				'type' => 'hidden',
				'label' => 'type option',
				'value' => 'esai'
			);
		}
		return $_data;
	}

	public function get_form_data_teks($data)
	{
		$_data['data'] = $data;
		$_data["form_data"] = array(
			"id" => array(
				'type' => 'hidden',
				'label' => "Soal Id",
			),
			"bank_soal_id" => array(
				'type' => 'hidden',
				'label' => "Bank Soal Id",
			),
			"text" => array(
				'type' => 'ckeditor',
				'label' => "Soal teks",
				'value' => $data->text
			),
		);
		return $_data;
	}

	public function get_form_data_gambar($data)
	{
		$_data['data'] = $data;
		$_data["form_data"] = array(
			"id" => array(
				'type' => 'hidden',
				'label' => "Soal Id",
			),
			"bank_soal_id" => array(
				'type' => 'hidden',
				'label' => "Bank Soal Id",
			),
			"gambar" => array(
				'type' => 'file',
				'label' => "Soal gambar",
			),
			"text" => array(
				'type' => 'ckeditor',
				'label' => "Soal teks",
				'value' => $data->text
			),
		);
		return $_data;
	}

	public function get_form_data_audio($data)
	{
		$_data['data'] = $data;
		$_data["form_data"] = array(
			"id" => array(
				'type' => 'hidden',
				'label' => "Soal Id",
			),
			"bank_soal_id" => array(
				'type' => 'hidden',
				'label' => "Bank Soal Id",
			),
			"text" => array(
				'type' => 'ckeditor',
				'label' => "Soal teks",
				'value' => $data->text
			),
			"audio" => array(
				'type' => 'file',
				'label' => "Soal audio",
			),
		);
		return $_data;
	}

	public function get_form_data_edit($data)
	{
		$_data['data'] = $data;
		$_data["form_data"] = array(
			"id" => array(
				'type' => 'hidden',
				'label' => "Soal Id",
			),
			"bank_soal_id" => array(
				'type' => 'hidden',
				'label' => "Bank Soal Id",
			),
			"jawaban[5]" => array(
				'type' => 'file',
				'label' => "Soal Gambar",
				'value' => $data->text
			),
			"text" => array(
				'type' => 'ckeditor',
				'label' => "Soal teks",
				'value' => $data->text
			),
			"audio" => array(
				'type' => 'file',
				'label' => "Soal audio",
			),
		);
		return $_data;
	}


	/**
	 * get_form_data
	 *
	 * @return array
	 * @author madukubah
	 **/
	public function get_form_data_readonly($user_id = -1)
	{
		$_data["form_data"] = array(
			"id" => array(
				'type' => 'hidden',
				'label' => "ID",
				'value' => $this->form_validation->set_value('id', $this->id),
			),
			"first_name" => array(
				'type' => 'text',
				'label' => "Nama Depan",
				'value' => $this->form_validation->set_value('first_name', $this->first_name),
			),
			"last_name" => array(
				'type' => 'text',
				'label' => "Nama Belakang",
				'value' => $this->form_validation->set_value('last_name', $this->last_name),

			),
			"email" => array(
				'type' => 'text',
				'label' => "Email",
				'value' => $this->form_validation->set_value('email', $this->email),
			),
			"phone" => array(
				'type' => 'number',
				'label' => "Nomor Telepon",
				'value' => $this->form_validation->set_value('phone', $this->phone),
			),
			"group_id" => array(
				'type' => 'text',
				'label' => "User Group",
			),
		);
		return $_data;
	}
}
