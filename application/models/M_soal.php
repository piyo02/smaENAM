<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_soal extends MY_Model
{
  protected $table = "tabel_soal";

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

      $this->set_error("Soal gagal di edit");
      return FALSE;
    }

    $this->db->trans_commit();

    $this->set_message("Soal berhasil diedit");
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

    $this->set_message("Soal berhasil di hapus"); //('group_delete_successful');
    return TRUE;
  }

  /**
   * groups
   *
   *
   * @return static
   * @author madukubah
   */
  public function get_soal($bank_soal_id, $limit, $start)
  {
    $this->db->select($this->table . '.id');
    $this->db->select($this->table . '.kode');
    $this->db->select($this->table . '.bank_soal_id');
    $this->db->select($this->table . '.type AS type_soal');
    $this->db->select('tabel_jawaban.type AS type');
    $this->db->select($this->table . '.text');
    $this->db->select($this->table . '.gambar');
    $this->db->select($this->table . '.audio');
    $this->db->select('tabel_jawaban.id AS jawaban_id');
    $this->db->select('tabel_jawaban.jawaban');
    $this->db->select('tabel_jawaban.skor');
    $this->db->join(
      'tabel_jawaban',
      'tabel_jawaban.soal_id = ' . $this->table . '.id',
      'left'
    );
    $this->db->where($this->table . '.bank_soal_id', $bank_soal_id);
    $this->db->where('tabel_jawaban.skor !=', 0);
    return $this->db->get($this->table);
  }

  public function get_soal_by_id($data_param)
  {
    $this->db->select($this->table . '.id');
    $this->db->select($this->table . '.kode');
    $this->db->select($this->table . '.bank_soal_id');
    $this->db->select($this->table . '.type');
    $this->db->select($this->table . '.text');
    $this->db->select($this->table . '.gambar');
    $this->db->select($this->table . '.audio');
    $this->db->where($data_param);
    return $this->db->get($this->table);
  }

  public function get_option_by_id($data_param)
  {
    $this->db->select('id');
    $this->db->select('type');
    $this->db->select('jawaban');
    $this->db->select('skor');
    $this->db->where($data_param);
    return $this->db->get('tabel_jawaban');
  }

  public function get_number($data_param)
  {
    $this->db->select('kode');
    $this->db->where('id = (SELECT MAX(id) FROM tabel_soal WHERE bank_soal_id = ' . $data_param . ')');
    return $this->db->get('tabel_soal');
  }


  public function insert_soal($data)
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

  public function insert_option($data)
  {
    return $this->db->insert_batch('tabel_jawaban', $data);
  }

  public function get_skor_by_id($param)
  {
    $this->db->select('skor');
    $this->db->where($param);
    $this->db->where('skor !=', 0);
    return $this->db->get('tabel_jawaban');
  }
}
