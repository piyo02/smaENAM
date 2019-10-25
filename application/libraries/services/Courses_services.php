<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Courses_services
{


  function __construct()
  { }

  public function __get($var)
  {
    return get_instance()->$var;
  }

  public function course_admin_table_config($_page, $start_number = 1)
  {
    $table["header"] = array(
      'name' => 'Mata Pelajaran',
      'description' => 'Deskripsi',
      'edu_ladder_name' => 'Jenjang',
    );
    $table["number"] = $start_number;
    $table["action"] = array(
      // array(
      //   "name" => "Detail",
      //   "type" => "link",
      //   "url" => site_url($_page . "group/"),
      //   "button_color" => "primary",
      //   "param" => "id",
      // ),
      array(
        "name" => 'X',
        "type" => "modal_delete",
        "modal_id" => "delete_course_",
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

  public function course_table_config($_page, $start_number = 1)
  {
    $table["header"] = array(
      'name' => 'Mata Pelajaran',
      'description' => 'Deskripsi',
    );
    $table["number"] = $start_number;
    $table["action"] = array(
      array(
        "name" => 'X',
        "type" => "modal_delete",
        "modal_id" => "delete_category_",
        "url" => site_url($_page . "delete/"),
        "button_color" => "danger",
        "param" => "teacher_course_id",
        "form_data" => array(
          "teacher_course_id" => array(
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
        'field' => 'course_id',
        'label' => 'Mata Pelajaran',
        'rules' =>  'trim|required',
      ),
    );

    return $config;
  }

  public function validation_admin_config()
  {
    $config = array(
      array(
        'field' => 'edu_ladder_id',
        'label' => 'Jenjang',
        'rules' =>  'trim|required',
      ),
      array(
        'field' => 'name',
        'label' => 'Mata Pelajaran',
        'rules' =>  'trim|required',
      ),
      array(
        'field' => 'description',
        'label' => 'Deskripsi',
        'rules' =>  'trim|required',
      ),
    );

    return $config;
  }
}
