<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Ulangan extends Users_Controller
{
    private $services = null;
    private $name = null;
    private $parent_page = 'guru';
    private $current_page = 'guru/ulangan/';
    public $user_id = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->library('services/Ulangan_services');
        $this->services = new Ulangan_services;
        $this->load->model(array(
            'm_ulangan',
            'm_bank_soal',
            'm_jawaban',
            'm_referensi',
        ));
        $this->user_id = $this->session->userdata('user_id');
    }
    public function index()
    {
        #################################################################3
        $table = $this->services->groups_table_config($this->current_page);
        $data_param['user_id'] = $this->user_id;
        $table["rows"] = $this->m_ulangan->get_ulangan($data_param)->result();
        $table = $this->load->view('templates/tables/plain_table_12', $table, true);
        $this->data["contents"] = $table;
        $add_menu = array(
            "name" => "Tambah Ulangan",
            "modal_id" => "add_referensi",
            "button_color" => "primary",
            "url" => site_url($this->current_page . "create/"),
            "form_data" => array(
                "r" => array(
                    'type' => 'text',
                    'label' => "Banyak Referensi",
                ),
                'data' => NULL
            ),
        );

        $add_menu = $this->load->view('templates/actions/modal_form_get', $add_menu, true);

        $this->data["header_button"] =  $add_menu;

        #################################################################3
        $alert = $this->session->flashdata('alert');
        $this->data["key"] = $this->input->get('key', FALSE);
        $this->data["alert"] = (isset($alert)) ? $alert : NULL;
        $this->data["current_page"] = $this->current_page;
        $this->data["block_header"] = "Ulangan";
        $this->data["header"] = "Daftar Ulangan";
        $this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
        $this->render("templates/contents/plain_content");
    }


    public function create()
    {
        // echo var_dump( $data );return;
        $this->form_validation->set_rules($this->services->validation_config());
        if ($this->form_validation->run() === TRUE) {

            $data['class_id']   = $this->input->post('class_id');
            $data['user_id'] = $this->session->userdata('user_id');
            $data['course_id'] = $this->input->post('course_id');
            $data['nama']       = $this->input->post('nama');
            $data['waktu_mulai'] = strtotime($this->input->post('waktu_mulai'));
            $data['durasi']     = $this->input->post('durasi');
            $data['kkm']        = $this->input->post('kkm');
            $data['nilai_maks'] = $this->input->post('nilai_maks');
            //insert soal
            $id = $this->m_ulangan->create($data);

            $r = $this->input->post('r');
            for ($i = 0; $i < $r; $i++) {
                $data_ref[] = [
                    'ulangan_id'    => $id,
                    'bank_soal_id'  => $this->input->post('bank_soal_id_' . $i),
                    'pg'            => $this->input->post('pg_' . $i),
                    'isian'         => $this->input->post('isian_' . $i),
                    'esai'          => $this->input->post('esai_' . $i),
                ];
            }

            if ($this->m_referensi->create($data_ref)) {
                $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->m_ulangan->messages()));
            } else {
                $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->m_ulangan->errors()));
            }
            $this->session->unset_userdata('r');
            redirect(site_url($this->current_page));
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            if (!empty(validation_errors()) || $this->ion_auth->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));


            $bank_soal['bank_soal'] = $this->m_bank_soal->bank_soal($this->user_id)->result();
            if ($this->input->get('r')) {
                $bank_soal['referensi'] = $this->input->get('r');
                $this->session->set_userdata(['r' => $this->input->get('r')]);
            } else {
                $bank_soal['referensi'] = $this->session->userdata('r');
            }

            $table_referensi = $this->load->view('templates/tables/plain_table_referensi', $bank_soal, TRUE);


            $alert = $this->session->flashdata('alert');
            $this->data['bank_soal'] = $table_referensi;
            $this->data["key"] = $this->input->get('key', FALSE);
            $this->data["alert"] = (isset($alert)) ? $alert : NULL;
            $this->data["current_page"] = $this->current_page;
            $this->data["block_header"] = "Tambah Ulangan ";
            $this->data["header"] = "Tambah Ulangan ";
            $this->data["sub_header"] = '';

            $form_data = $this->services->get_form_data();
            $form_data = $this->load->view('templates/form/bsb_form', $form_data, TRUE);

            $this->data["contents"] =  $form_data;

            $this->render("guru/ulangan/create");
        }
    }

    public function detail($id)
    {
        // echo var_dump( $data );return;
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        if (!empty(validation_errors()) || $this->ion_auth->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));

        $add_menu = array(
            "name" => "Kembali",
            "button_color" => "primary",
            "url" => site_url($this->current_page),
        );

        $add_menu = $this->load->view('templates/actions/link', $add_menu, true);

        $this->data["header_button"] =  $add_menu;

        $table = $this->services->table_bank();
        $table["rows"] = $this->m_ulangan->get_referensi_soal_by_id($id)->result();
        $table_referensi = $this->load->view('templates/tables/plain_table_12', $table, TRUE);

        $alert = $this->session->flashdata('alert');
        $this->data["key"] = $this->input->get('key', FALSE);
        $this->data["alert"] = (isset($alert)) ? $alert : NULL;
        $this->data["current_page"] = $this->current_page;
        $this->data["block_header"] = "Ulangan ";
        $this->data["header"] = "Detail Ulangan ";
        $this->data["sub_header"] = '';

        $data = $this->m_ulangan->get_task_by_id($id)->row();
        $form_data = $this->services->get_form_data_readonly($data);
        $form_data = $this->load->view('templates/form/bsb_form', $form_data, TRUE);

        $this->data["bank_soal"] =  $table_referensi;
        $this->data["contents"] =  $form_data;

        $this->render("guru/ulangan/content");
    }

    public function edit($id)
    {
        $this->form_validation->set_rules($this->services->validation_config());
        if ($this->form_validation->run() === TRUE) {
            $data['nama']       = $this->input->post('nama');
            $data['class_id']   = $this->input->post('class_id');
            $data['course_id'] = $this->session->userdata('course_id');
            $data['waktu_mulai'] = strtotime($this->input->post('waktu_mulai'));
            $data['durasi']     = $this->input->post('durasi');
            $data['kkm']        = $this->input->post('kkm');
            $data['nilai_maks'] = $this->input->post('nilai_maks');

            $data_param['id'] = $id;
            $r = $this->input->post('r');
            for ($i = 0; $i < $r; $i++) {
                $data_ref[] = [
                    'id' => $this->input->post('ref_id_' . $i),
                    'bank_soal_id' => $this->input->post('bank_soal_id_' . $i),
                    'pg' => $this->input->post('pg_' . $i),
                    'isian' => $this->input->post('isian_' . $i),
                    'esai' => $this->input->post('esai_' . $i),
                ];
                if ($this->input->post('pg_' . $i) === null && $this->input->post('isian_' . $i) === null && $this->input->post('esai_' . $i) === null) {
                    $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, 'Isi Referensi Soal'));
                    redirect(site_url($this->current_page . 'edit/' . $id));
                }
            }

            if ($this->m_ulangan->update($data, $data_param, $data_ref)) {
                $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->m_ulangan->messages()));
            } else {
                $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->m_ulangan->errors()));
            }
            redirect(site_url($this->current_page));
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_ulangan->errors() ? $this->m_ulangan->errors() : $this->session->flashdata('message')));
            if (validation_errors() || $this->m_ulangan->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));

            $bank_soal['bank_soal'] = $this->m_bank_soal->bank_soal($this->user_id)->result();
            $bank_soal['data'] = $this->m_ulangan->get_referensi_soal_by_id($id)->result();
            $bank_soal['referensi'] = $this->m_ulangan->get_referensi_soal_by_id($id)->num_rows();
            $table_referensi = $this->load->view('templates/tables/plain_table_referensi', $bank_soal, TRUE);


            $alert = $this->session->flashdata('alert');
            $this->data['bank_soal'] = $table_referensi;
            $this->data["key"] = $this->input->get('key', FALSE);
            $this->data["alert"] = (isset($alert)) ? $alert : NULL;
            $this->data["current_page"] = $this->current_page;
            $this->data["block_header"] = "Ulangan ";
            $this->data["header"] = "Edit Ulangan ";
            $this->data["sub_header"] = '';

            $_data = $this->m_ulangan->get_task_by_id($id)->row();
            $form_data = $this->services->get_form_data($_data);
            $form_data = $this->load->view('templates/form/bsb_form', $form_data, TRUE);

            $this->data["contents"] =  $form_data;

            $this->render("guru/ulangan/edit_ulangan");
        }
    }

    public function delete()
    {
        if (!($_POST)) redirect(site_url($this->current_page));

        $data_param['id']     = $this->input->post('id');
        if ($this->m_ulangan->delete($data_param)) {
            $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->m_ulangan->messages()));
        } else {
            $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->m_ulangan->errors()));
        }
        redirect(site_url($this->current_page));
    }
}
