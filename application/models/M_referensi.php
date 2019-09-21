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
    return $this->db->get('tabel_jawaban', $limit);
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
    return $this->db->get('tabel_jawaban', $limit);
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
    return $this->db->get('tabel_jawaban', $limit);
  }
}
