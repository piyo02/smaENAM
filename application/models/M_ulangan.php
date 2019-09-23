<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_ulangan extends MY_Model
{
  protected $table = "tabel_ulangan";

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

  public function create_ref($data)
  {
    return $this->db->insert_batch('tabel_referensi_soal', $data);
  }
  /**
   * update
   *
   * @param array  $data
   * @param array  $data_param
   * @return bool
   * @author madukubah
   */
  public function update($data, $data_param, $ref)
  {
    $this->db->trans_begin();
    $data = $this->_filter_data($this->table, $data);
    $this->db->update($this->table, $data, $data_param);

    if ($ref)
      $this->db->update_batch('tabel_referensi_soal', $ref, 'id');

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $this->set_error("Ulangan gagal diedit");
      return FALSE;
    }

    $this->db->trans_commit();

    $this->set_message("Ulangan berhasil diedit");
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

    $this->db->where('ulangan_id', $data_param['id']);
    if ($this->db->get('tabel_hasil_ulangan')->result()) {
      $this->set_error("Ulangan Gagal Dihapus untuk menjaga data hasil ulangan"); //('group_delete_successful');
      return FALSE;
    }

    $this->db->where('ulangan_id', $data_param['id']);
    if (!$this->db->delete('tabel_referensi_soal'))
      return FALSE;

    $this->db->where($data_param);
    if (!$this->db->delete('tabel_ulangan'))
      return FALSE;

    $this->set_message("Ulangan Berhasil"); //('group_delete_successful');
    return TRUE;
  }

  public function get_task_by_id($ulangan_id)
  {
    $this->db->select('tabel_ulangan.id');
    $this->db->select('tabel_ulangan.nama');
    $this->db->select('tabel_ulangan.waktu_mulai');
    $this->db->select('tabel_ulangan.durasi');
    $this->db->select('tabel_ulangan.kkm');
    $this->db->select('tabel_ulangan.nilai_maks');
    $this->db->select('tabel_kelas.id as kelas_id');
    $this->db->select('tabel_kelas.nama as class');
    $this->db->select("CONCAT((table_users.first_name),(' '),(table_users.last_name)) as teacher_name");
    $this->db->join(
      'tabel_kelas',
      'tabel_kelas.id = tabel_ulangan.kelas_id',
      'join'
    );
    $this->db->join(
      'table_users',
      'table_users.id = tabel_ulangan.creator_id',
      'join'
    );
    $this->db->where('tabel_ulangan.id', $ulangan_id);
    return $this->db->get('tabel_ulangan');
  }

  public function get_referensi_soal_by_id($id)
  {
    $this->db->select('tabel_bank_soal.id');
    $this->db->select('tabel_bank_soal.nama');
    $this->db->select('tabel_referensi_soal.id AS ref_id');
    $this->db->select('tabel_referensi_soal.pg');
    $this->db->select('tabel_referensi_soal.isian');
    $this->db->select('tabel_referensi_soal.esai');
    $this->db->join(
      'tabel_bank_soal',
      'tabel_bank_soal.id = tabel_referensi_soal.bank_soal_id',
      'join'
    );
    $this->db->where('tabel_referensi_soal.ulangan_id', $id);
    return $this->db->get('tabel_referensi_soal');
  }

  public function get_ulangan($data_param, $user_id = null)
  {
    $this->db->select('tabel_ulangan.id');
    $this->db->select('tabel_ulangan.nama');
    $this->db->select('tabel_ulangan.durasi');
    $this->db->select('tabel_ulangan.kkm');
    $this->db->select('tabel_ulangan.nilai_maks');
    $this->db->select('tabel_kelas.nama AS class');
    if ($user_id) {
      $this->db->select('tabel_hasil_ulangan.nilai');
      $this->db->join(
        'tabel_hasil_ulangan',
        'tabel_hasil_ulangan.ulangan_id = tabel_ulangan.id',
        'left'
      );
      $this->db->where('user_id', $user_id);
      $this->db->or_where('user_id is null');
    }
    $this->db->join(
      'tabel_kelas',
      'tabel_kelas.id = tabel_ulangan.kelas_id',
      'join'
    );
    $this->db->where($data_param);
    return $this->db->get('tabel_ulangan');
  }

  public function get_num_type($id, $type1, $type2 = null)
  {
    $this->db->select('DISTINCT(tabel_soal.id)');
    $this->db->select('tabel_jawaban.type');
    $this->db->join(
      'tabel_jawaban',
      'tabel_jawaban.soal_id = tabel_soal.id',
      'join'
    );
    $this->db->where('tabel_soal.bank_soal_id', $id);
    if ($type2 == null)
      $this->db->where('tabel_jawaban.type', $type1);
    else
      $this->db->where('(tabel_jawaban.type = "' . $type1 . '" OR tabel_jawaban.type = "' . $type2 . '")');
    return $this->db->get('tabel_soal');
  }

  public function get_max_id()
  {
    $this->db->select_max('id');
    return $this->db->get('tabel_ulangan');
  }
}
