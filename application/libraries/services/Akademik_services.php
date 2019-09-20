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

  public function bidi_table_config($_page, $start_number = 1)
  {
    $table["header"] = array(
      '1' => 'IPA',
      '2' => 'IPS',
      '3' => 'BAHASA',
    );
    $table["number"] = $start_number;
    return $table;
  }


  public function validation_config()
  {
    $config = array(
      array(
        'field' => 'nama',
        'label' => 'nama',
        'rules' =>  'trim|required',
      ),
      array(
        'field' => 'deskripsi',
        'label' => 'deskripsi',
        'rules' =>  'trim|required',
      ),
    );

    return $config;
  }
}
