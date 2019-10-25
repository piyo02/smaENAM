<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Ulangan_services
{
  public $r          = '';
  public $nama       = '';
  public $class_id   = '';
  public $course_id   = '';
  public $waktu_mulai = '';
  public $durasi     = '';
  public $kkm        = '';
  public $nilai_maks = '';


  function __construct()
  {
    $this->load->model(array(
      'm_class',
      'm_courses',
    ));
  }

  public function __get($var)
  {
    return get_instance()->$var;
  }


  public function groups_table_config($_page, $start_number = 1)
  {
    $table["header"] = array(
      'id' => 'Kode',
      'nama' => 'Nama Ulangan',
      'class' => 'Kelas',
      'durasi' => 'Durasi',
      'kkm' => 'Nilai kkm',
    );
    $table["number"] = $start_number;
    $table["action"] = array(
      array(
        "name" => 'Detail',
        "type" => "link",
        "url" => site_url($_page . "detail/"),
        "button_color" => "success",
        "param" => 'id',
      ),
      array(
        "name" => 'Edit',
        "type" => "link",
        "url" => site_url($_page . "edit/"),
        "button_color" => "primary",
        "param" => 'id',
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

  public function table_bank()
  {
    $table["header"] = array(
      'nama' => 'Bank Soal',
      'pg' => 'PG',
      'isian' => 'Isian',
      'esai' => 'Esai',
    );
    return $table;
  }

  public function validation_config()
  {
    $config = array(
      array(
        'field' => 'nama',
        'label' => 'Nama Soal',
        'rules' =>  'trim|required',
      ),
      array(
        'field' => 'class_id',
        'label' => 'Pilih Kelas',
        'rules' =>  'trim|required',
      ),
      array(
        'field' => 'waktu_mulai',
        'label' => 'Tanggal Ulangan',
        'rules' =>  'trim|required',
      ),
      array(
        'field' => 'durasi',
        'label' => 'Durasi Ulangan',
        'rules' =>  'trim|required',
      ),
      array(
        'field' => 'nilai_maks',
        'label' => 'Nilai Maksimal',
        'rules' =>  'trim|required',
      ),
    );

    return $config;
  }

  public function get_form_data($data = null)
  {
    if ($data) {
      $this->r          = '';
      $this->nama       = $data->nama;
      $this->class_id   = $data->class_id;
      $this->course_id   = $data->course_id;
      $this->waktu_mulai = $data->waktu_mulai;
      $this->durasi     = $data->durasi;
      $this->kkm        = $data->kkm;
      $this->nilai_maks = $data->nilai_maks;
    }
    $param['edu_ladder_id'] = $this->session->userdata('edu_ladder_id');
    $list_classes = $this->m_class->list_classes($param);

    $data_param['teacher_course.user_id'] = $this->session->userdata('user_id');
    $list_courses = $this->m_courses->list_teacher_course($data_param);
    $_data["form_data"] = array(
      "r" => array(
        'type' => 'hidden',
        'label' => "banyak referensi",
        'value' => $this->input->get('r'),
      ),
      "nama" => array(
        'type' => 'text',
        'label' => "Nama Ulangan",
        'value' => $this->nama,
      ),
      "class_id" => array(
        'type' => 'select',
        'label' => "Pilih Kelas",
        'options' => $list_classes,
        'selected' => $this->class_id,
      ),
      "course_id" => array(
        'type' => 'select',
        'label' => "Pilih Mata Pelajaran",
        'options' => $list_courses,
        'selected' => $this->course_id,
      ),
      "waktu_mulai" => array(
        'type' => 'date_range',
        'label' => "Tanggal Ulangan",
        'value' => $this->waktu_mulai,
      ),
      "durasi" => array(
        'type' => 'number',
        'label' => "Durasi (dalam menit)",
        'value' => $this->durasi,
      ),
      "kkm" => array(
        'type' => 'number',
        'label' => "Nilai Kelulusan",
        'value' => $this->kkm,
      ),
      "nilai_maks" => array(
        'type' => 'number',
        'label' => "Nilai Maksimal",
        'value' => $this->nilai_maks,
      ),
    );
    return $_data;
  }

  public function get_form_data_readonly($data)
  {
    $_data["form_data"] = array(
      "nama" => array(
        'type' => 'text',
        'label' => "Nama Ulangan",
        'readonly' => 'readonly',
        'value' => $data->nama
      ),
      "class_id" => array(
        'type' => 'text',
        'label' => "Kelas",
        'readonly' => 'readonly',
        'value' => $data->class,
      ),
      "waktu_mulai" => array(
        'type' => 'text',
        'label' => "Tanggal Ulangan",
        'readonly' => 'readonly',
        'value' => date('d-m-Y', $data->waktu_mulai),
      ),
      "durasi" => array(
        'type' => 'number',
        'label' => "Durasi (dalam menit)",
        'readonly' => 'readonly',
        'value' => $data->durasi,
      ),
      "kkm" => array(
        'type' => 'date',
        'label' => "Nilai kkm",
        'readonly' => 'readonly',
        'value' => $data->kkm,
      ),
      "nilai_maks" => array(
        'type' => 'date',
        'label' => "Nilai Maksimal",
        'readonly' => 'readonly',
        'value' => $data->nilai_maks,
      ),
    );
    return $_data;
  }
}
