<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Siswa_services
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
      'name' => 'Kelas',
      'qty' => 'Jumlah Murid',
    );
    $table["number"] = $start_number;
    $table["action"] = array(
      array(
        "name" => 'Detail',
        "type" => "link",
        "url" => site_url($_page . "detail/"),
        "button_color" => "primary",
        "param" => "id",
      )
    );
    return $table;
  }

  public function course_table_config($_page, $start_number = 1, $class_id)
  {
    $table["header"] = array(
      'course' => 'Mata Pelajaran',
    );
    $table["number"] = $start_number;
    $table["action"] = array(
      array(
        "name" => 'Detail',
        "type" => "link",
        "url" => site_url($_page . "list/"),
        "button_color" => "primary",
        "param" => "course_id",
        "get" => "?class_id=" . $class_id,
      )
    );
    return $table;
  }

  public function lists_table_config($_page, $start_number = 1, $class_id, $course_id)
  {
    $table["header"] = array(
      'username' => 'Nama Siswa',
    );
    $table["number"] = $start_number;
    $table["action"] = array(
      array(
        "name" => 'Laporan',
        "type" => "link",
        "url" => site_url($_page . "report/"),
        "button_color" => "primary",
        "param" => "id",
        "get" => "?course_id=$course_id",
      )
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
}
