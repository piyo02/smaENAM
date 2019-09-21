<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_kelas extends MY_Model
{
  protected $table = "tabel_kelas";

  function __construct()
  {
    parent::__construct($this->table);
    parent::set_join_key('group_id');
  }

  /**
   * create
   *
   * @param array  $data
   * @return static
   * @author madukubah
   */
  public function create($data)
  {
    $this->db->insert($this->table, $data);
  }
  /**
   * update
   *
   * @param array  $data
   * @param array  $data_param
   * @return bool
   * @author madukubah
   */
  public function update($data, $option, $id)
  {
    $param['id'] = $id;
    $this->db->update($this->table, $data, $param);


    if ($option[0])
      $this->db->update_batch('tabel_jawaban', $option, 'id');
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();

      $this->set_error("gagal");
      return FALSE;
    }

    $this->db->trans_commit();

    $this->set_message("berhasil");
    return TRUE;
  }
  /**
   * delete
   *
   * @param array  $data_param
   * @return bool
   * @author madukubah
   */
  public function delete($data_param)
  {

    $this->db->where('soal_id', $data_param);
    if (!$this->db->delete('tabel_jawaban'))
      return false;

    $this->db->where('id', $data_param);
    if (!$this->db->delete($this->table))
      return false;

    $this->set_message("berhasil"); //('group_delete_successful');
    return TRUE;
  }

  public function get_class()
  {
    $this->db->select('id');
    $this->db->select('nama');
    $this->db->where('id !=', 1);
    return $this->db->get('tabel_kelas');
  }

  public function list_class()
  {
    $classes = $this->get_class()->result();
    $list[] = '-- Pilih Kelas --';
    foreach ($classes as $key => $class) {
      if ($class->id != 1)
        $list[$class->id] = $class->nama;
    }
    return $list;
  }
}
