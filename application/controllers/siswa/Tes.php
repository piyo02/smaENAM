<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tes extends Siswa_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_ulangan');
		$this->load->model('m_referensi');
	}
	public function index()
	{
		$data_param['ulangan_id'] = $this->input->post('id');
		$refs = $this->m_referensi->get_referensi_bank_soal($data_param)->result();
		$qty = $this->m_referensi->get_sum_soal($data_param)->row();
		var_dump($qty->isian);
		die;
		$lists_pg = [];
		$lists_isian = [];
		$lists_esai = [];
		foreach ($refs as $key => $ref) {
			$_data_param['tabel_soal.bank_soal_id'] = $ref->bank_soal_id;
			$lists_pg += $this->m_referensi->get_soal_id_pg($_data_param, $qty->pg)->result();
			$lists_isian += $this->m_referensi->get_soal_id_isian($_data_param, $qty->isian)->result();
			$lists_esai += $this->m_referensi->get_soal_id_esai($_data_param, $qty->esai)->result();
		}
		var_dump($lists_pg);
		var_dump($lists_isian);
		var_dump($lists_esai);
		die;
		$this->data["page_title"] = "Beranda";
		$this->render("siswa/tes/content");
	}
}
