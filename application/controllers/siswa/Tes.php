<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tes extends Siswa_Controller
{
	public $list = [];
	public $current_page = 'siswa/tes/';
	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_soal');
		$this->load->model('m_ulangan');
		$this->load->model('m_hasil_ulangan');
		$this->load->model('m_referensi');
		$this->load->model('m_jawaban');
		$this->load->model('m_jawaban_siswa');
		$this->load->model('m_tes');
	}

	public function index()
	{
		$data_param['ulangan_id'] = $this->input->post('id');

		//session ulangan id
		$this->session->set_userdata($data_param);

		//cek apakah siswa sedang atau sudah mengerjakan soal
		$param = [
			'ulangan_id' => $this->input->post('id'),
			'user_id' => $this->session->userdata('user_id'),
		];
		if ($this->m_jawaban_siswa->get_soal_id($param)->result())
			redirect('siswa/tes/ulangan');

		//insert data ke tabel kerja
		$param['waktu_mulai'] = time();
		$this->m_tes->create($param);

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
		//nama ulangan
		$data_param['tabel_ulangan.id'] = $this->session->userdata('ulangan_id');
		$ulangan = $this->m_ulangan->get_ulangan($data_param)->row();

		//ambil soal dari tabel jawaban siswa
		$data_param = [
			'ulangan_id' => $this->session->userdata('ulangan_id'),
			'user_id' => $this->session->userdata('user_id'),
		];
		$list_soal = $this->m_jawaban_siswa->get_soal_id($data_param)->result();

		//ambil data user di tabel kerja
		$siswa = $this->m_tes->get_data_tes($data_param)->row();

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

		//button konfirmasi
		$button_confirm = array(
			"name" => "Selesai",
			"modal_id" => "confirm",
			"button_color" => "success",
			"url" => site_url($this->current_page . "examination"),
		);

		$button_confirm = $this->load->view('templates/actions/modal_confirm_tes', $button_confirm, true);

		//render
		$this->data["siswa"] = $siswa;
		$this->data["ulangan"] = $ulangan;
		$this->data["confirm"] =  $button_confirm;
		$this->data["number"] = $number;
		$this->data["contents"] = $list_soal;
		$this->data["soal"] = $soal;
		$this->data["options"] = $options;
		$this->render("siswa/tes/content");
	}

	public function answer()
	{
		$data_param = [
			'ulangan_id' => $this->session->userdata('ulangan_id'),
			'user_id' => $this->session->userdata('user_id'),
			'soal_id' => $this->input->post('soal_id'),
		];

		$type = $this->input->post('type');
		if ($type == 'gambar' || $type == 'teks') {
			//jawaban
			$jawaban = $this->input->post('jawaban');
			//operasi string
			$pisah = strpos($jawaban, '-');
			$option = substr($jawaban, ($pisah + 1));
			$jawaban = substr($jawaban, 0, $pisah);
		} else {
			$jawaban = $this->input->post('jawaban');
			$option = '';
		}

		$data = [
			'jawaban' => $jawaban,
			'option' => $option,
			'uncertain' => 0
		];
		$this->m_jawaban_siswa->update($data, $data_param);
		echo json_encode($data);
	}

	public function uncertain()
	{
		$data_param = [
			'ulangan_id' => $this->session->userdata('ulangan_id'),
			'user_id' => $this->session->userdata('user_id'),
			'soal_id' => $this->input->post('soal_id'),
		];

		$type = $this->input->post('type');
		if ($type == 'gambar' || $type == 'teks') {
			//jawaban
			$jawaban = $this->input->post('jawaban');
			//operasi string
			$pisah = strpos($jawaban, '-');
			$option = substr($jawaban, ($pisah + 1));
			$jawaban = substr($jawaban, 0, $pisah);
		} else {
			$jawaban = $this->input->post('jawaban');
			$option = '';
		}

		$data = [
			'jawaban' => $jawaban,
			'option' => $option,
			'uncertain' => 1
		];
		$this->m_jawaban_siswa->update($data, $data_param);
		echo json_encode($data);
	}

	public function examination()
	{

		$data_param['tabel_ulangan.id'] = $this->session->userdata('ulangan_id');
		$ulangan = $this->m_ulangan->get_ulangan($data_param)->row();

		$nilai = $this->penilaian($ulangan->nilai_maks);
		$data = [
			'user_id' 	 => $this->session->userdata('user_id'),
			'ulangan_id' => $this->session->userdata('ulangan_id'),
		];
		//hapus data kerja siswa
		if ($this->m_tes->get_data_tes($data)->row()) {
			$this->m_tes->delete($data);
		}

		$data['nilai'] = $nilai['nilai'];
		$this->m_hasil_ulangan->create($data);
		$this->data["nilai"] = $nilai;
		$this->data["ulangan"] = $ulangan;

		//hapus session
		$this->session->unset_userdata('ulangan_id');
		$this->render("siswa/tes/confirm");
	}

	public function penilaian($skor_max = 100)
	{
		$data_param = [
			'ulangan_id' => $this->session->userdata('ulangan_id'),
			'user_id' => $this->session->userdata('user_id'),
		];
		$answers = $this->m_jawaban_siswa->get_soal_id($data_param)->result();
		$nilai = $benar = 0;
		foreach ($answers as $key => $answer) {
			if ($answer->option) {
				$param['id'] = $answer->jawaban;
				$soal = $this->m_jawaban->get_jawaban($param)->row();
				$skor = $soal->skor;
				if ($skor == 1)
					$benar++;
				$nilai += 1;
			} else {
				$soal_id = ['soal_id'  => $answer->soal_id];
				$soal = $this->m_jawaban->get_jawaban($soal_id)->row();
				if ($answer->jawaban == $soal->jawaban) {
					$skor = $soal->skor;
					$benar++;
				} else {
					$skor = 0;
				}
				$nilai += $soal->skor;
			}
			$data = ['skor' => $skor];
			$param = ['id' => $answer->id];
			$this->m_jawaban_siswa->update($data, $param);
		}
		$skor = $this->m_jawaban_siswa->get_skor($data_param)->row();
		$data = [
			'nilai' => $skor->skor / $nilai * $skor_max,
			'benar' => $benar,
			'jumlah' => count($answers),
		];
		return $data;
	}
}
