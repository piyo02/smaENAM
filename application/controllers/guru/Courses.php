<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Courses extends Users_Controller
{
    private $services = null;
    private $user_id = '';
    private $name = null;
    private $parent_page = 'guru';
    private $current_page = 'guru/courses/';

    public function __construct()
    {
        parent::__construct();
        $this->load->library('services/Courses_services');
        $this->services = new Courses_services;
        $this->load->model(array(
            'm_mapel',
            'm_courses',
            'm_group',
            'm_teacher',
            'm_edu_ladder',
            'm_teacher_profile',
        ));
        $this->user_id = $this->session->userdata('user_id');
    }

    public function index()
    {
        $param['user_id'] = $this->user_id;
        $teacher = $this->m_teacher_profile->get_profile($param)->row();
        $table = $this->services->course_table_config($this->current_page);
        $table['rows'] = $this->m_courses->get_teacher_course(['user_id' => $this->user_id])->result();
        $this->data["contents"] = $this->load->view('templates/tables/plain_table_12', $table, true);
        ##################################################################################################################################
        $data_param['edu_ladder_id'] = $teacher->edu_ladder_id;
        $add_menu = array(
            "name" => "Pilih Mata Pelajaran",
            "modal_id" => "add_mapel_",
            "button_color" => "primary",
            "url" => site_url($this->current_page . "add/"),
            "form_data" => array(
                "course_id" => array(
                    'type' => 'select',
                    'label' => "Mata Pelajaran",
                    'options' => $this->m_courses->list_courses($data_param),
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
            $data['user_id'] = $this->session->userdata('user_id');
            $data['course_id'] = $this->input->post('course_id');

            if ($this->m_courses->insert_teacher_course($data)) {
                $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->m_courses->messages()));
            } else {
                $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->m_courses->errors()));
            }
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_account->errors() ? $this->m_courses->errors() : $this->session->flashdata('message')));
            if (validation_errors() || $this->m_courses->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));
        }

        redirect(site_url($this->current_page));
    }

    public function delete()
    {
        if (!($_POST)) redirect(site_url($this->current_page));

        $data_param['id']     = $this->input->post('teacher_course_id');
        if ($this->m_courses->delete_teacher_course($data_param)) {
            $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->m_courses->messages()));
        } else {
            $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->m_courses->errors()));
        }
        redirect(site_url($this->current_page));
    }
}
