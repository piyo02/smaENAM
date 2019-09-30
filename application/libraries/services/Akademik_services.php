<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Akademik_services
{


  function __construct()
  { }

  public function __get($var)
  {
    return get_instance()->$var;
  }

  public function groups_table_config($_page, $start_number = 1)
  {
    $table["header"] = array(
      'name' => 'Nama Group',
      'description' => 'Deskripsi',
    );
    $table["number"] = $start_number;
    $table["action"] = array(
      array(
        "name" => "Detail",
        "type" => "link",
        "url" => site_url($_page . "group/"),
        "button_color" => "primary",
        "param" => "id",
      ),
    );
    return $table;
  }

  public function course_table_config($_page, $start_number = 1)
  {
    $table["header"] = array(
      'code' => 'Kode',
      'name' => 'Nama',
      'course_name' => 'Materi',
      'description' => 'Deskripsi',
    );
    $table["number"] = $start_number;
    $table["action"] = array(
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
          "code" => array(
            'type' => 'text',
            'label' => "Kode Kelas",
            'readonly' => "readonly",
          ),
          "name" => array(
            'type' => 'text',
            'label' => "Nama Kelas",
          ),
          "description" => array(
            'type' => 'textarea',
            'label' => "Deskripsi Kelas",
          ),
        ),
        "title" => "Jurnal",
        "data_name" => "name",
      ),
      array(
        "name" => 'X',
        "type" => "modal_delete",
        "modal_id" => "delete_category_",
        "url" => site_url($_page . "delete/"),
        "button_color" => "danger",
        "param" => "id",
        "form_data" => array(
          "id" => array(
            'type' => 'hidden',
            'label' => "id",
          ),
        ),
        "title" => "User",
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
        'label' => 'nama',
        'rules' =>  'trim|required',
      ),
      array(
        'field' => 'description',
        'label' => 'deskripsi',
        'rules' =>  'trim|required',
      ),
    );

    return $config;
  }
}
