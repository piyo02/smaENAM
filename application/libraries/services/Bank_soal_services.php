<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Bank_soal_services
{
  public $mapel = '';


  function __construct()
  {
    $this->load->model('m_class');
    $this->load->model('m_courses');
  }

  public function __get($var)
  {
    return get_instance()->$var;
  }


  public function get_mapel()
  {
    $param['user_id'] = $this->session->userdata('user_id');
    return $this->m_class->list_classes();
  }

  public function get_courses($param)
  {
    return $this->m_courses->list_courses($param);
  }

  public function groups_table_config($_page, $data, $param, $start_number = 1)
  {
    if ($data) {
      $this->mapel = '';
    }

    $mapel = $this->get_mapel();
    $courses = $this->get_courses($param);
    $table["header"] = array(
      'nama' => 'Nama Bank Soal',
      'class' => 'Kelas',
      'course' => 'Mapel',
      'materi' => 'Materi',
      'status' => 'Deskripsi',
    );
    $table["number"] = $start_number;
    $table["action"] = array(
      array(
        "name" => "Buat Soal",
        "type" => "link",
        "url" => site_url('guru/soal/daftar_soal/'),
        "button_color" => "primary",
        "param" => "id",
      ),
      array(
        "name" => 'Edit',
        "type" => "modal_form",
        "modal_id" => "edit_",
        "url" => site_url($_page . "edit/"),
        "button_color" => "primary",
        "param" => "id",
        "form_data" => array(
          "id" => array(
            'type' => 'hidden',
            'label' => "id",
          ),
          "nama" => array(
            'type' => 'text',
            'label' => "Nama Group",
          ),
          "class_id" => array(
            'type' => 'select',
            'label' => "Kelas",
            'options' => $courses,
          ),
          "course_id" => array(
            'type' => 'select',
            'label' => "Mata Pelajaran",
            'options' => $mapel,
          ),
          "materi" => array(
            'type' => 'text',
            'label' => "Materi Bab",
          ),
          "status" => array(
            'type' => 'select',
            'label' => "Deskripsi",
            'options' => array(
              0 => 'Tidak berbagi soal',
              1 => 'Berbagi soal'
            ),
          ),
        ),
        "title" => "Jurnal",
        "data_name" => "name",
      ),
      array(
        "name" => 'X',
        "type" => "modal_delete",
        "modal_id" => "delete_",
        "url" => site_url($_page . "delete/"),
        "button_color" => "danger",
        "param" => "id",
        "form_data" => array(
          "id" => array(
            'type' => 'hidden',
            'label' => "id",
          ),
        ),
        "title" => "Jurnal",
        "data_name" => "nama",
      ),
    );
    return $table;
  }
  public function validation_config()
  {
    $config = array(
      array(
        'field' => 'nama',
        'label' => 'Nama bank soal',
        'rules' =>  'trim|required',
      ),
      array(
        'field' => 'materi',
        'label' => 'Materi',
        'rules' =>  'trim|required',
      ),
      array(
        'field' => 'course_id',
        'label' => 'Kelas',
        'rules' =>  'trim|required',
      )
    );

    return $config;
  }
}
