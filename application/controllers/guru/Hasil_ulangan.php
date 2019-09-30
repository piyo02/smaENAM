<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Hasil_ulangan extends Users_Controller
{
    private $services = null;
    private $name = null;
    private $parent_page = 'guru';
    private $current_page = 'guru/hasil_ulangan/';

    public function __construct()
    {
        parent::__construct();
        $this->load->library('services/Hasil_ulangan_services');
        $this->load->library('services/Excel_services');
        $this->services = new Hasil_ulangan_services;
        $this->excel = new Excel_services;
        $this->load->model(array(
            'm_bank_soal',
            'm_soal',
            'm_mapel',
            'm_ulangan',
            'm_jawaban',
            'm_jawaban_siswa',
            'm_hasil_ulangan',
        ));
    }

    public function index()
    {
        #################################################################3
        $data = '';
        $data_param['creator_id'] = $this->session->userdata('user_id');
        $table = $this->services->groups_table_config($this->current_page, $data);
        $table["rows"] = $this->m_ulangan->get_ulangan($data_param)->result();
        $table = $this->load->view('templates/tables/plain_table_12', $table, true);
        $this->data["contents"] = $table;

        #################################################################3
        $alert = $this->session->flashdata('alert');
        $this->data["key"] = $this->input->get('key', FALSE);
        $this->data["alert"] = (isset($alert)) ? $alert : NULL;
        $this->data["current_page"] = $this->current_page;
        $this->data["block_header"] = "Hasil Ulangan";
        $this->data["header"] = "Daftar Hasil Ulangan";
        $this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
        $this->render("templates/contents/plain_content");
    }

    public function detail($id)
    {
        #################################################################3
        $table = $this->services->tabel_hasil_ulangan($this->current_page);
        $table["rows"] = $this->m_hasil_ulangan->get_hasil_ulangan($id)->result();
        $table = $this->load->view('templates/tables/plain_table_12', $table, true);
        $this->data["contents"] = $table;

        $add_menu = array(
            "name" => "Export <i class='fas fa-file-excel'></i>",
            "button_color" => "primary",
            "url" => site_url($this->current_page . "export_excel/" . $id),
            'param' => null
        );

        $add_menu = $this->load->view('templates/actions/link', $add_menu, true);
        $this->data["header_button"] =  $add_menu;
        #################################################################3
        $alert = $this->session->flashdata('alert');
        $this->data["key"] = $this->input->get('key', FALSE);
        $this->data["alert"] = (isset($alert)) ? $alert : NULL;
        $this->data["current_page"] = $this->current_page;
        $this->data["block_header"] = "Hasil Ulangan";
        $this->data["header"] = "Daftar Hasil Ulangan";
        $this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
        $this->render("templates/contents/plain_content");
    }

    public function delete()
    {
        if (!($_POST)) redirect(site_url($this->current_page));

        $data_param['ulangan_id']     = $this->input->post('id');
        if ($this->m_hasil_ulangan->delete($data_param)) {
            $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->m_hasil_ulangan->messages()));
        } else {
            $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->m_hasil_ulangan->errors()));
        }
        redirect(site_url($this->current_page));
    }

    public function delete_hasil()
    {
        if (!($_POST)) redirect(site_url($this->current_page));

        $data_param['id'] = $this->input->post('id');
        $ulangan_id       = $this->input->post('ulangan_id');
        if ($this->m_hasil_ulangan->delete($data_param)) {
            $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->m_hasil_ulangan->messages()));
        } else {
            $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->m_hasil_ulangan->errors()));
        }
        redirect(site_url($this->current_page . 'detail/' . $ulangan_id));
    }

    public function export_excel($id)
    {
        $course = $this->m_ulangan->get_course($id)->row();
        $detail = $this->m_ulangan->get_task_by_id($id)->row();
        $materi = $this->m_bank_soal->list_materi($id);

        $detail->materi = $materi;
        $detail->nama_sekolah = 'SMA NEGERI 6 KENDARI';
        $detail->course_name = $course->name;
        $_data = [
            'rows' => $this->m_hasil_ulangan->get_hasil_ulangan($id)->result(),
            'headers' => $this->services->header_excel($this->m_hasil_ulangan->get_hasil_ulangan($id)->num_rows()),
            'title' => 'Hasil Ulangan',
            'detail' => $detail,
        ];
        #################################################################
        $this->excel->excel_config($_data);
        redirect('teacher/my_class');
    }


    public function review($id)
    {
        $data_param['id'] = $id;
        $detail = $this->m_hasil_ulangan->get_detail_ulangan($data_param)->row();
        $data_param = [
            'ulangan_id' => $detail->ulangan_id,
            'user_id' => $detail->user_id,
        ];
        $quests = $this->m_jawaban_siswa->get_soal_id($data_param)->result();
        #################################################################3
        $review = $this->services->tabel_hasil_ulangan($this->current_page);
        if (null === $this->input->get('id')) {
            foreach ($quests as $key => $soal) {
                $soal_id = $soal->soal_id;
                break;
            }
        } else {
            $soal_id = $this->input->get('id');
        }
        //soal
        $param['id'] = $soal_id;
        $review['soal'] = $this->m_soal->get_soal_by_id($param)->row();

        //option
        $param = ['soal_id' => $soal_id];
        $review['options'] = $this->m_soal->get_option_by_id($param)->result();

        //jawaban siswa
        $param['ulangan_id'] = $detail->ulangan_id;
        $param['user_id'] = $detail->user_id;
        $review['jawaban'] = $this->m_jawaban_siswa->get_jawaban_siswa_by_id($param)->row();
        // var_dump($review['jawaban']);
        // die;
        #################################################################3
        //nomor
        $nomor = 1;
        if (null !== $this->input->get('nomor'))
            $nomor = $this->input->get('nomor');
        $review["nomor"] = $nomor;
        $review['id'] = $id;

        //////////////////////////////////////////////////////////////
        $review = $this->load->view('siswa/review/guru', $review, true);
        $this->data["contents"] = $review;

        $add_menu = array(
            "name" => "Kembali",
            "button_color" => "success",
            "url" => site_url($this->current_page . "detail/" . $detail->ulangan_id),
            'param' => null
        );

        $add_menu = $this->load->view('templates/actions/link', $add_menu, true);
        $this->data["header_button"] =  $add_menu;
        #################################################################3
        $alert = $this->session->flashdata('alert');
        $this->data["quests"] = $quests;
        $this->data["key"] = $this->input->get('key', FALSE);
        $this->data["alert"] = (isset($alert)) ? $alert : NULL;
        $this->data["current_page"] = $this->current_page;
        $this->data["block_header"] = "Review Ulangan";
        $this->data["header"] = "Siswa ";
        $this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
        $this->render("templates/review/content");
    }

    public function update()
    {
        $user_id = $this->input->post('user_id');
        $id = $this->input->post('id');
        $nomor = $this->input->post('nomor');
        $jawaban_id = $this->input->post('jawaban_id');
        $skor = $this->input->post('skor');
        $param['id'] = $jawaban_id;
        $data = [
            'skor' => $skor
        ];
        if ($this->m_jawaban_siswa->update($data, $param)) {
            $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->m_jawaban_siswa->messages()));
            redirect(site_url($this->current_page)  . 'review/' . $user_id . '?id=' . $id . '&nomor=' . $nomor);
        } else {
            $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->m_jawaban_siswa->errors()));
            redirect(site_url($this->current_page)  . 'review/' . $user_id . '?id=' . $id . '&nomor=' . $nomor);
        }
    }

    public function recheck($id)
    {
        $param['id'] = $id;
        $data = $this->m_hasil_ulangan->get_detail_ulangan($param)->row();
        $skor_max = 100;
        $ulangan_id = $data->ulangan_id;

        $data_param = [
            'user_id' => $data->user_id,
            'ulangan_id' => $ulangan_id,
        ];

        $answers = $this->m_jawaban_siswa->get_soal_id($data_param)->result();
        $nilai = 0;
        foreach ($answers as $key => $answer) {
            $param = [
                'soal_id' => $answer->soal_id,
            ];
            $nilai_soal = $this->m_soal->get_skor_by_id($param)->row();
            $nilai += $nilai_soal->skor;
        }
        $skor = $this->m_jawaban_siswa->get_skor($data_param)->row();

        $data = [
            'nilai' => $skor->skor / $nilai * $skor_max
        ];
        $this->m_hasil_ulangan->update($data, $data_param);
        redirect($this->current_page . 'detail/' . $ulangan_id);
    }
}
