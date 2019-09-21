<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends Public_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library(array(
            'form_validation'
        ));
        $this->load->helper('form');
        $this->config->load('ion_auth', TRUE);
        $this->load->helper(array(
            'url',
            'language'
        ));
        $this->lang->load('auth');
        $this->load->model('m_kelas');
    }

    public function login()
    {
        $this->form_validation->set_rules('identity', 'identity', 'required');
        $this->form_validation->set_rules('user_password', 'user_password', 'trim|required');
        if ($this->form_validation->run() == true) {
            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('user_password'))) {
                //if the login is successful
                //redirect them back to the home page
                $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->ion_auth->messages()));

                if ($this->ion_auth->is_admin())
                    redirect(site_url('/admin'));
                if ($this->ion_auth->is_student())
                    redirect(site_url('/siswa/home'));
                redirect(site_url('/user'), 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
            } else {
                // if the login was un-successful
                // redirect them back to the login page
                $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->ion_auth->errors()));
                redirect('auth/login', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
            }
        } else {
            $this->render("V_login_page");
        }
    }

    public function register()
    {
        $tables                        = $this->config->item('tables', 'ion_auth');
        $identity_column               = $this->config->item('identity', 'ion_auth');
        $this->data['identity_column'] = $identity_column;
        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'trim|required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'trim|required');
        if ($identity_column !== 'email') {
            // $this->form_validation->set_rules('identity', $this->lang->line('create_user_validation_identity_label'), 'trim|required|is_unique[' . $tables['users'] . '.' . $identity_column . ']');
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email');
        } else {
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
        }
        $this->form_validation->set_rules('class_id', $this->lang->line('create_user_validation_class_label'), 'required');
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

        if ($this->form_validation->run() === TRUE) {
            $email    = strtolower($this->input->post('email'));
            $identity = ($identity_column === 'email') ? $email : $this->input->post('identity');
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'phone' => $this->input->post('phone'),
                'class_id' => $this->input->post('class_id')
            );
        }
        if ($this->form_validation->run() === TRUE && $this->ion_auth->register($identity, $password, $email, $additional_data, 3)) {

            // check to see if we are creating the user
            // redirect them back to the admin page
            $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->ion_auth->messages()));
            redirect("auth/login", 'refresh');
        } else {
            $select = $this->m_kelas->list_class();
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            if (!empty(validation_errors()) || $this->ion_auth->errors())
                $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));

            $this->data['first_name']       = array(
                'name' => 'first_name',
                'placeholder' => 'Nama Depan',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('first_name')
            );
            $this->data['last_name']        = array(
                'name' => 'last_name',
                'id' => 'last_name',
                'type' => 'text',
                'placeholder' => 'Nama Belakang',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('last_name')
            );
            $this->data['identity']         = array(
                'name' => 'identity',
                'id' => 'identity',
                'type' => 'text',
                'value' => $this->form_validation->set_value('identity')
            );
            $this->data['email']            = array(
                'name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'placeholder' => 'Email',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('email')
            );
            $this->data['class_id']            = array(
                'name' => 'class_id',
                'id' => 'class_id',
                'type' => 'select',
                'placeholder' => 'Pilih Kelas',
                'class' => 'form-control',
                'options' => $select,
                'selected' => $this->form_validation->set_value('class_id')
            );
            $this->data['phone']            = array(
                'name' => 'phone',
                'id' => 'phone',
                'type' => 'text',
                'placeholder' => 'Nomor HP',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('phone')
            );
            $this->data['password']         = array(
                'name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'placeholder' => 'Password',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('password')
            );
            $this->data['password_confirm'] = array(
                'name' => 'password_confirm',
                'id' => 'password_confirm',
                'type' => 'password',
                'placeholder' => 'Konfirmasi Password',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('password_confirm')
            );

            $this->render("V_register_page");
        }
    }

    public function logout()
    {
        $this->data['title'] = "Logout";

        // log the user out
        $logout = $this->ion_auth->logout();

        // redirect them to the login page
        $this->session->set_flashdata('message', $this->ion_auth->messages());
        redirect(site_url(), 'refresh');
    }
}
