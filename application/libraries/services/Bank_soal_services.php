<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Bank_soal_services
{
  public $mapel = '';
  public $subbab = '';


  function __construct()
  {
    $this->load->model('m_mapel');
  }

  public function __get($var)
  {
    return get_instance()->$var;
  }

  public function get_subbab()
  {
    return  $this->m_mapel->subbab();
  }

  public function get_mapel()
  {
    return $this->m_mapel->get_main_mapel();
  }

  public function groups_table_config($_page, $data, $start_number = 1)
  {
    if ($data) {
      $this->mapel = '';
      $this->mapel = '';
    }
    $mapel = $this->get_mapel();
    $subbab = $this->get_subbab();
    $table["header"] = array(
      'nama' => 'Nama Bank Soal',
      'mapel_name' => 'Mapel',
      'subbab_name' => 'Materi',
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
          "mapel_id" => array(
            'type' => 'select',
            'label' => "Mata Pelajaran",
            'options' => $mapel,
          ),
          "subbab_id" => array(
            'type' => 'select',
            'label' => "Materi Bab",
            'options' => $subbab,
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
        'field' => 'subbab_id',
        'label' => 'Materi',
        'rules' =>  'trim|required',
      ),
      array(
        'field' => 'mapel_id',
        'label' => 'Mata Pelajaran',
        'rules' =>  'trim|required',
      )
    );

    return $config;
  }
}
