<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tes extends Siswa_Controller
{
	public $list = [];
	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_soal');
		$this->load->model('m_ulangan');
		$this->load->model('m_referensi');
		$this->load->model('m_jawaban_siswa');
	}
	public function index()
	{
		$data_param['ulangan_id'] = $this->input->post('id');

		//session ulangan id
		$this->session->set_userdata($data_param);

		//get bank soal id
		$refs = $this->m_referensi->get_referensi_bank_soal($data_param)->result();

		//get id soal
		$lists_pg = [];
		$lists_isian = [];
		$lists_esai = [];
		foreach ($refs as $key => $ref) {
			$_data_param['bank_soal_id'] = $ref->bank_soal_id;
			$qty = $this->m_referensi->get_sum_soal($data_param, $_data_param)->row();
			$lists_pg = array_merge($lists_pg, $this->m_referensi->get_soal_id_pg($_data_param, $qty->pg)->result());
			$lists_isian =  array_merge($lists_isian, $this->m_referensi->get_soal_id_isian($_data_param, $qty->isian)->result());
			$lists_esai = array_merge($lists_esai, $this->m_referensi->get_soal_id_esai($_data_param, $qty->esai)->result());
		}

		//merge list
		$lists_soal = array_merge($lists_pg, $lists_isian);
		$lists_soal = array_merge($lists_soal, $lists_esai);

		//input list soal ke tabel jawaban siswa
		foreach ($lists_soal as $key => $list) {
			$data['ulangan_id'] = $data_param['ulangan_id'];
			$data['soal_id'] = $list->id;
			$data['user_id'] = $this->session->userdata('user_id');
			$insert_data[] = $data;
		}
		$this->m_jawaban_siswa->insert_batch_soal($insert_data);

		redirect('siswa/tes/ulangan');
	}

	public function ulangan()
	{
		//ambil soal dari tabel jawaban siswa
		$data_param = [
			'ulangan_id' => $this->session->userdata('ulangan_id'),
			'user_id' => $this->session->userdata('user_id'),
		];
		$list_soal = $this->m_jawaban_siswa->get_soal_id($data_param)->result();

		if (null !== $this->input->get('id')) {
			//get soal nomor
			$data_param = ['id' => $this->input->get('id')];
			//get soal
			$soal = $this->m_soal->get_soal_by_id($data_param)->row();

			//get option
			$data_param = ['soal_id' => $this->input->get('id')];
			$options = $this->m_soal->get_option_by_id($data_param)->result();

			$number = $this->input->get('nomor');
		} else {

			//get soal nomor 1
			$data_param = ['id' => $list_soal[0]->soal_id];
			//get soal
			$soal = $this->m_soal->get_soal_by_id($data_param)->row();

			//get option
			$data_param = ['soal_id' => $list_soal[0]->soal_id];
			$options = $this->m_soal->get_option_by_id($data_param)->result();

			$number = 1;
		}

		//render
		$this->data["page_title"] = "Beranda";
		$this->data["number"] = $number;
		$this->data["contents"] = $list_soal;
		$this->data["soal"] = $soal;
		$this->data["options"] = $options;
		$this->render("siswa/tes/content");
	}
}
