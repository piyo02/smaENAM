<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Akademik extends Users_Controller
{
    private $services = null;
    private $name = null;
    private $parent_page = 'guru';
    private $current_page = 'guru/akademik/';

    public function __construct()
    {
        parent::__construct();
        $this->load->library('services/Akademik_services');
        $this->services = new Akademik_services;
        $this->load->model(array(
            'm_mapel',
            'm_group',
        ));
    }

    public function courses($group_id = null)
    {
        $group = $this->m_group->group($group_id)->row();
        $this->data["mapel_tree"] = $this->m_mapel->tree_mapel();;
        $this->data["mapel_list"] = $this->m_mapel->get_mapel_list();
        $this->data["group"] = $group;
        $this->data["contents"] = '';
        ##################################################################################################################################
        $add_menu = array(
            "name" => "Tambah Mapel",
            "modal_id" => "add_mapel_",
            "button_color" => "primary",
            "url" => site_url($this->current_page . "add/"),
            "form_data" => array(
                "nama" => array(
                    'type' => 'text',
                    'label' => "Mata Pelajaran",
                ),
                "deskripsi" => array(
                    'type' => 'textarea',
                    'label' => "Deskripsi",
                    'value' => "-",
                ),
            ),
            'data' => NULL
        );

        $add_menu = $this->load->view('templates/actions/modal_form', $add_menu, true);

        $this->data["header_button"] =  $add_menu;
        // return;
        ##################################################################################################################################
        $alert = $this->session->flashdata('alert');
        $this->data["key"] = $this->input->get('key', FALSE);
        $this->data["alert"] = (isset($alert)) ? $alert : NULL;
        $this->data["current_page"] = $this->current_page;
        $this->data["block_header"] = "Mata Pelajaran";
        $this->data["header"] = "Daftar Mata Pelajaran";
        $this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';

        $this->render("guru/akademik/content_courses");
    }

    public function add()
    {
        if (!($_POST)) redirect(site_url($this->current_page));

        // echo var_dump( $data );return;
        $this->form_validation->set_rules($this->services->validation_config());
        if ($this->form_validation->run() === TRUE) {
            $data['nama'] = $this->input->post('nama');
            $data['deskripsi'] = $this->input->post('deskripsi');

            if ($this->m_mapel->create($data)) {
                $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->m_mapel->messages()));
            } else {
                $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->m_mapel->errors()));
            }
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_account->errors() ? $this->m_mapel->errors() : $this->session->flashdata('message')));
            if (validation_errors() || $this->m_mapel->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));
        }

        redirect(site_url($this->current_page . 'courses'));
    }

    public function add_subbab()
    {
        if (!($_POST)) redirect(site_url($this->current_page));
        $this->form_validation->set_rules($this->services->validation_config());
        if ($this->form_validation->run() === TRUE) {
            $data['nama'] = $this->input->post('nama');
            $data['deskripsi'] = $this->input->post('deskripsi');
            $data['mapel_id'] = $this->input->post('mapel_id');

            if ($this->m_mapel->create_subbab($data)) {
                $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->m_mapel->messages()));
            } else {
                $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->m_mapel->errors()));
            }
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_account->errors() ? $this->m_mapel->errors() : $this->session->flashdata('message')));
            if (validation_errors() || $this->m_mapel->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));
        }

        redirect(site_url($this->current_page . 'courses'));
    }

    public function edit()
    {
        if (!($_POST)) redirect(site_url($this->current_page));

        $mapel_id = $this->input->post('mapel_id');
        // echo var_dump( $data );return;
        $this->form_validation->set_rules($this->services->validation_config());
        if ($this->form_validation->run() === TRUE) {
            $tabel = '';
            if ($mapel_id)
                $tabel = 'tabel_subbab';
            $data['nama'] = $this->input->post('nama');
            $data['deskripsi'] = $this->input->post('deskripsi');
            $data_param['id'] = $this->input->post('id');

            if ($this->m_mapel->update($tabel, $data, $data_param)) {
                $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->m_mapel->messages()));
            } else {
                $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->m_mapel->errors()));
            }
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_account->errors() ? $this->m_mapel->errors() : $this->session->flashdata('message')));
            if (validation_errors() || $this->m_mapel->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));
        }

        redirect(site_url($this->current_page . 'courses/'));
    }

    public function delete()
    {
        if (!($_POST)) redirect(site_url($this->current_page));

        $data_param['id']     = $this->input->post('id');
        $mapel_id = $this->input->post('mapel_id');
        $table = '';
        if ($mapel_id)
            $table = 'tabel_subbab';
        if ($this->m_mapel->delete($data_param, $table)) {
            $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->m_mapel->messages()));
        } else {
            $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->m_mapel->errors()));
        }
        redirect(site_url($this->current_page . 'courses/'));
    }

    public function get_subbab()
    {
        $mapel_id = $this->input->post('map_id');
        $dataD = '';
        if ($mapel_id)
            $dataD = $this->m_mapel->get_subbab($mapel_id)->result();
        echo json_encode($dataD);
    }
}
