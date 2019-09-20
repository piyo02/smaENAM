<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Bank_soal extends Users_Controller
{
    private $services = null;
    private $name = null;
    private $parent_page = 'guru';
    private $current_page = 'guru/bank_soal/';

    public function __construct()
    {
        parent::__construct();
        $this->load->library('services/Bank_soal_services');
        $this->services = new Bank_soal_services;
        $this->load->model(array(
            'm_bank_soal',
            'm_mapel',
            'm_ulangan',
        ));
    }
    public function index()
    {
        #################################################################3
        $mapel = $this->m_mapel->get_main_mapel();
        $data = '';
        $table = $this->services->groups_table_config($this->current_page, $data);
        $table["rows"] = $this->m_bank_soal->bank_soal($this->session->userdata('user_id'))->result();
        $table = $this->load->view('templates/tables/plain_table_12', $table, true);
        $this->data["contents"] = $table;
        $add_menu = array(
            "name" => "Tambah Bank Soal",
            "modal_id" => "add_bank_soal",
            "button_color" => "primary",
            "url" => site_url($this->current_page . "add/"),
            "form_data" => array(
                "nama" => array(
                    'type' => 'text',
                    'label' => "Nama Bank Soal",
                    'value' => "",
                ),
                "mapel_id" => array(
                    'type' => 'select',
                    'label' => "Mata Pelajaran",
                    'options' => $mapel,
                ),
                "subbab_id" => array(
                    'type' => 'select',
                    'label' => "Materi Bab",
                    'options' => array(),
                ),
                "status" => array(
                    'type' => 'select',
                    'label' => "Deskripsi",
                    'options' => array(
                        0 => 'Tidak berbagi soal',
                        1 => 'Berbagi soal'
                    ),
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
        $this->data["block_header"] = "Bank Soal";
        $this->data["header"] = "Daftar Bank Soal";
        $this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
        $this->render("templates/contents/plain_content");
    }


    public function add()
    {
        if (!($_POST)) redirect(site_url($this->current_page));

        // echo var_dump( $data );return;
        $this->form_validation->set_rules($this->services->validation_config());
        if ($this->form_validation->run() === TRUE) {
            $data['user_id'] = $this->session->userdata('user_id');
            $data['mapel_id'] = $this->input->post('mapel_id');
            $data['subbab_id'] = $this->input->post('subbab_id');
            $data['nama'] = $this->input->post('nama');
            $data['status'] = $this->input->post('status');
            // $data['status'] = $this->input->post('description');

            if ($this->m_bank_soal->create($data)) {
                $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->m_bank_soal->messages()));
            } else {
                $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->m_bank_soal->errors()));
            }
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_account->errors() ? $this->m_bank_soal->errors() : $this->session->flashdata('message')));
            if (validation_errors() || $this->m_bank_soal->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));
        }

        redirect(site_url($this->current_page));
    }

    public function edit()
    {
        if (!($_POST)) redirect(site_url($this->current_page));

        // echo var_dump( $data );return;
        $this->form_validation->set_rules($this->services->validation_config());
        if ($this->form_validation->run() === TRUE) {
            $data['mapel_id'] = $this->input->post('mapel_id');
            $data['subbab_id'] = $this->input->post('subbab_id');
            $data['nama'] = $this->input->post('nama');
            $data['status'] = $this->input->post('status');

            $data_param['id'] = $this->input->post('id');

            if ($this->m_bank_soal->update($data, $data_param)) {
                $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->m_bank_soal->messages()));
            } else {
                $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->m_bank_soal->errors()));
            }
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_account->errors() ? $this->m_bank_soal->errors() : $this->session->flashdata('message')));
            if (validation_errors() || $this->m_bank_soal->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));
        }

        redirect(site_url($this->current_page));
    }

    public function delete()
    {
        if (!($_POST)) redirect(site_url($this->current_page));

        $data_param['id']     = $this->input->post('id');
        if ($this->m_bank_soal->delete($data_param)) {
            $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->m_bank_soal->messages()));
        } else {
            $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->m_bank_soal->errors()));
        }
        redirect(site_url($this->current_page));
    }

    public function count_type()
    {
        $id = $this->input->post('id');
        $data['pg'] = $this->m_ulangan->get_num_type($id, 'teks', 'gambar')->num_rows();
        $data['isian'] = $this->m_ulangan->get_num_type($id, 'isian')->num_rows();
        $data['esai'] = $this->m_ulangan->get_num_type($id, 'esai')->num_rows();
        echo json_encode($data);
    }
}
