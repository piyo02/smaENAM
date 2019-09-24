<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_jawaban_siswa extends MY_Model
{
  protected $table = "tabel_jawaban_siswa";

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
  public function insert_batch_soal($data)
  {
    return $this->db->insert_batch($this->table, $data);
  }
  /**
   * update
   *
   * @param array  $data
   * @param array  $data_param
   * @return bool
   * @author madukubah
   */
  public function update($data, $data_param)
  {
    $this->db->trans_begin();
    $data = $this->_filter_data($this->table, $data);

    $this->db->update($this->table, $data, $data_param);
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
    //foreign
    //delete_foreign( $data_param. $models[]  )
    if (!$this->delete_foreign($data_param)) {
      $this->set_error("gagal"); //('group_delete_unsuccessful');
      return FALSE;
    }
    //foreign
    $this->db->trans_begin();

    $this->db->delete($this->table, $data_param);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();

      $this->set_error("gagal"); //('group_delete_unsuccessful');
      return FALSE;
    }

    $this->db->trans_commit();

    $this->set_message("berhasil"); //('group_delete_successful');
    return TRUE;
  }

  /**
   * groups
   *
   *
   * @return static
   * @author madukubah
   */
  public function get_soal_id($data_param)
  {
    $this->db->select('id');
    $this->db->select('soal_id');
    $this->db->select('option');
    $this->db->select('jawaban');
    $this->db->select('uncertain');
    $this->db->select('skor');
    $this->db->where($data_param);
    return $this->db->get($this->table);
  }

  public function get_skor($data_param)
  {
    $this->db->select_sum('skor');
    $this->db->where($data_param);
    return $this->db->get($this->table);
  }

  public function get_jawaban_siswa_by_id($data_param)
  {
    $this->db->select('id');
    $this->db->select('jawaban');
    $this->db->select('skor');
    $this->db->where($data_param);
    return $this->db->get($this->table);
  }
}
