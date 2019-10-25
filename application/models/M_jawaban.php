<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_jawaban extends MY_Model
{
  protected $table = "tabel_jawaban";

  function __construct()
  {
    parent::__construct($this->table);
    // parent::set_join_key('group_id');
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
    $this->db->insert_batch($this->table, $data);
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
  public function get_soal($bank_soal_id, $limit, $start)
  {
    $this->db->select($this->table . '.id');
    $this->db->select($this->table . '.nomor');
    $this->db->select($this->table . '.type');
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
    $this->db->where($this->table . '.bank_soal_id', $bank_soal_id, $limit, $start);
    $this->db->where('tabel_jawaban.skor !=', 0);
    $this->db->order_by($this->table . '.nomor', 'asc');
    return $this->db->get($this->table);
  }

  public function get_soal_by_id($data_param)
  {
    $this->db->select($this->table . '.id');
    $this->db->select($this->table . '.type');
    $this->db->select($this->table . '.text');
    $this->db->select($this->table . '.gambar');
    $this->db->select($this->table . '.audio');
    $this->db->where($data_param);
    return $this->db->get($this->table);
  }

  public function get_jawaban($data_param)
  {
    $this->db->select('skor');
    $this->db->select('jawaban');
    $this->db->where($data_param);
    return $this->db->get($this->table);
  }

  public function get_skor_by_id($param)
  {
    $this->db->select('skor');
    $this->db->where($param);
    $this->db->where('skor !=', 0);
    return $this->db->get($this->table);
  }

  public function get_option_by_id($data_param)
  {
    $this->db->select('id');
    $this->db->select('type');
    $this->db->select('jawaban');
    $this->db->select('skor');
    $this->db->where($data_param);
    return $this->db->get($this->table);
  }

  public function get_soal_id_pg($data_param, $limit)
  {
    $this->db->select('DISTINCT(tabel_soal.id)');
    $this->db->join(
      'tabel_soal',
      'tabel_soal.id = tabel_jawaban.soal_id',
      'join'
    );
    $this->db->where($data_param);
    $this->db->where('(`tabel_jawaban`.`type` = "teks" OR `tabel_jawaban`.`type` = "gambar")');
    $this->db->order_by('id', 'RANDOM');
    return $this->db->get($this->table, $limit);
  }

  public function get_soal_id_isian($data_param, $limit)
  {
    $this->db->select('DISTINCT(tabel_soal.id)');
    $this->db->join(
      'tabel_soal',
      'tabel_soal.id = tabel_jawaban.soal_id',
      'join'
    );
    $this->db->where($data_param);
    $this->db->where('tabel_jawaban.type', 'isian');
    $this->db->order_by('id', 'RANDOM');
    return $this->db->get($this->table, $limit);
  }


  public function get_soal_id_esai($data_param, $limit)
  {
    $this->db->select('DISTINCT(tabel_soal.id)');
    $this->db->join(
      'tabel_soal',
      'tabel_soal.id = tabel_jawaban.soal_id',
      'join'
    );
    $this->db->where($data_param);
    $this->db->where('tabel_jawaban.type', 'esai');
    $this->db->order_by('id', 'RANDOM');
    return $this->db->get($this->table, $limit);
  }
}
