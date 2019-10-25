<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_referensi extends MY_Model
{
  protected $table = "tabel_referensi_soal";

  function __construct()
  {
    parent::__construct($this->table);
    parent::set_join_key('group_id');
  }

  public function create($data)
  {
    $this->db->insert_batch($this->table, $data);
  }

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


  public function get_referensi_bank_soal($data_param)
  {
    $this->db->select('bank_soal_id');
    $this->db->where($data_param);
    return $this->db->get($this->table);
  }

  public function get_sum_soal($data_param, $bank_soal_id)
  {
    $this->db->select_sum('pg');
    $this->db->select_sum('isian');
    $this->db->select_sum('esai');
    $this->db->where($data_param);
    $this->db->where($bank_soal_id);
    return $this->db->get($this->table);
  }

  public function get_bank_soal($id)
  {
    $this->db->select('bank_soal_id as id');
    $this->db->where('ulangan_id', $id);
    return $this->db->get($this->table);
  }

  public function get_referensi_soal_by_id($id)
  {
    $this->db->select('bank_soal.id');
    $this->db->select('bank_soal.nama');
    $this->db->select('tabel_referensi_soal.id AS ref_id');
    $this->db->select('tabel_referensi_soal.pg');
    $this->db->select('tabel_referensi_soal.isian');
    $this->db->select('tabel_referensi_soal.esai');
    $this->db->join(
      'bank_soal',
      'bank_soal.id = tabel_referensi_soal.bank_soal_id',
      'join'
    );
    $this->db->where('tabel_referensi_soal.ulangan_id', $id);
    return $this->db->get($this->table);
  }
}
