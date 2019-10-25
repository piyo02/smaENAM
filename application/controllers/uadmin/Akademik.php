<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Akademik extends Uadmin_Controller
{
    private $services = null;
    private $name = null;
    private $parent_page = 'uadmin';
    private $current_page = 'uadmin/akademik/';

    public function __construct()
    {
        parent::__construct();
        $this->load->library('services/Akademik_services');
        $this->services = new Akademik_services;
        $this->load->model(array(
            'm_mapel',
            'm_class',
            'm_group',
            'm_courses',
        ));
    }

    public function class()
    {
        $table = $this->services->course_table_config($this->current_page);
        $table['rows'] = $this->m_class->get_classes()->result();
        $this->data["contents"] = $this->load->view('templates/tables/plain_table_12', $table, true);
        ##################################################################################################################################
        $add_menu = array(
            "name" => "Tambah Kelas",
            "modal_id" => "add_mapel_",
            "button_color" => "primary",
            "url" => site_url($this->current_page . "add/"),
            "form_data" => array(
                "name" => array(
                    'type' => 'text',
                    'label' => "Nama Kelas",
                ),
                "description" => array(
                    'type' => 'textarea',
                    'label' => "Deskripsi Kelas",
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
        $this->data["block_header"] = "Kelas";
        $this->data["header"] = "Daftar Kelas";
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

            if ($this->m_class->create($data)) {
                $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->m_class->messages()));
            } else {
                $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->m_class->errors()));
            }
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_account->errors() ? $this->m_class->errors() : $this->session->flashdata('message')));
            if (validation_errors() || $this->m_class->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));
        }

        redirect(site_url($this->current_page . 'class'));
    }

    public function edit()
    {
        if (!($_POST)) redirect(site_url($this->current_page));

        $this->form_validation->set_rules($this->services->validation_config());
        if ($this->form_validation->run() === TRUE) {
            $data_param['id'] = $this->input->post('id');

            $data['name'] = $this->input->post('name');
            $data['description'] = $this->input->post('description');

            if ($this->m_class->update($data, $data_param)) {
                $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->m_class->messages()));
            } else {
                $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->m_class->errors()));
            }
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_account->errors() ? $this->m_class->errors() : $this->session->flashdata('message')));
            if (validation_errors() || $this->m_class->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));
        }

        redirect(site_url($this->current_page . 'class/'));
    }

    public function delete()
    {
        if (!($_POST)) redirect(site_url($this->current_page));

        $data_param['id']     = $this->input->post('id');
        if ($this->m_class->delete($data_param)) {
            $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->m_class->messages()));
        } else {
            $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->m_class->errors()));
        }
        redirect(site_url($this->current_page . 'class/'));
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
