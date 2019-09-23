<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_tes extends MY_Model
{
  protected $table = "tabel_kerja";

  function __construct()
  {
    parent::__construct($this->table);
    parent::set_join_key('group_id');
  }

  public function create($data)
  {
    // Filter the data passed
    $data = $this->_filter_data($this->table, $data);

    $this->db->insert($this->table, $data);
    $id = $this->db->insert_id($this->table . '_id_seq');

    if (isset($id)) {
      $this->set_message("berhasil");
      return $id;
    }
    $this->set_error("gagal");
    return FALSE;
  }


  public function delete($data_param)
  {

    $this->db->where($data_param);
    if (!$this->db->delete($this->table)) {
      $this->set_error("Hasil ulangan gagal dihapus "); //('group_delete_successful');
      return FALSE;
    }

    $this->set_message("Hasil ulangan berhasil dihapus"); //('group_delete_successful');
    return TRUE;
  }

  public function get_data_tes($data_param)
  {
    $this->db->select('waktu_mulai');
    $this->db->where($data_param);
    return $this->db->get($this->table);
  }
}
