<?php
defined('BASEPATH') or exit('No direct script access allowed');
class School_services
{


  function __construct()
  { }

  public function __get($var)
  {
    return get_instance()->$var;
  }

  public function school_table_config($_page, $start_number = 1)
  {
    $table["header"] = array(
      'name' => 'Nama Sekolah',
      'description' => 'Deskripsi',
    );
    $table["number"] = $start_number;
    $table["action"] = array(
      array(
        "name" => "Daftar Guru",
        "type" => "link",
        "url" => site_url($_page . "detail/"),
        "button_color" => "success",
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
          "name" => array(
            'type' => 'text',
            'label' => "Nama Sekolah",
          ),
          "description" => array(
            'type' => 'textarea',
            'label' => "Deskripsi",
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
        "data_name" => "name",
      ),
    );
    return $table;
  }

  public function validation_config()
  {
    $config = array(
      array(
        'field' => 'name',
        'label' => 'name',
        'rules' =>  'trim|required',
      ),
      array(
        'field' => 'description',
        'label' => 'description',
        'rules' =>  'trim|required',
      ),
    );

    return $config;
  }

  public function teacher_table_config($_page, $start_number = 1)
  {
    $table["header"] = array(
      'full_name' => 'Nama Guru',
      'email' => 'Email Guru',
      'nip' => 'NIP Guru',
      'phone' => 'No HP Guru',
      'status' => 'Status',
    );
    $table["number"] = $start_number;
    $table["action"] = array(
      array(
        "name" => 'Edit',
        "type" => "modal_form",
        "modal_id" => "edit_",
        "url" => site_url($_page . "edit_teacher/"),
        "button_color" => "primary",
        "param" => "id",
        "form_data" => array(
          "id" => array(
            'type' => 'hidden',
            'label' => "id",
          ),
          "active" => array(
            'type' => 'select',
            'label' => "Status",
            'options' => array(
              '0' => 'Tidak Aktif',
              '1' => 'Aktif'
            )
          ),
        ),
        "title" => "Jurnal",
        "data_name" => "name",
      ),
      array(
        "name" => 'X',
        "type" => "modal_delete",
        "modal_id" => "delete_",
        "url" => site_url($_page . "delete_teacher/"),
        "button_color" => "danger",
        "param" => "id",
        "form_data" => array(
          "id" => array(
            'type' => 'hidden',
            'label' => "id",
          ),
        ),
        "title" => "Jurnal",
        "data_name" => "full_name",
      ),
    );
    return $table;
  }
}
