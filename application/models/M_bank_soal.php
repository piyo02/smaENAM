<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_bank_soal extends MY_Model
{
  protected $table = "tabel_bank_soal";

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
      $this->set_message("Bank soal baru berhasil dibuat");
      return $id;
    }
    $this->set_error("Bank soal baru gagal dibuat");
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
  public function update($data, $data_param)
  {
    $this->db->trans_begin();
    $data = $this->_filter_data($this->table, $data);

    $this->db->update($this->table, $data, $data_param);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();

      $this->set_error("Bank soal gagal di update gagal");
      return FALSE;
    }

    $this->db->trans_commit();

    $this->set_message("Bank soal berhasil di update");
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
      $this->set_error("Bank soal gagal di hapus"); //('group_delete_unsuccessful');
      return FALSE;
    }
    //foreign
    $this->db->trans_begin();

    $this->db->delete($this->table, $data_param);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();

      $this->set_error("Bank soal gagal dihapus"); //('group_delete_unsuccessful');
      return FALSE;
    }

    $this->db->trans_commit();

    $this->set_message("Bank soal berhasil dihapus"); //('group_delete_successful');
    return TRUE;
  }

  /**
   * group
   *
   * @param int|array|null $id = id_groups
   * @return static
   * @author madukubah
   */
  public function group($id = NULL)
  {
    if (isset($id)) {
      $this->where($this->table . '.id', $id);
    }

    $this->limit(1);
    $this->order_by($this->table . '.id', 'desc');

    $this->groups();

    return $this;
  }
  /**
   * groups
   *
   *
   * @return static
   * @author madukubah
   */
  public function bank_soal($user_id)
  {
    $this->db->select($this->table . '.id');
    $this->db->select($this->table . '.nama');
    $this->db->select('tabel_mapel.id AS mapel_id');
    $this->db->select('tabel_mapel.nama AS mapel_name');
    $this->db->select('tabel_subbab.id AS subbab_id');
    $this->db->select('tabel_subbab.nama AS subbab_name');
    $this->db->select('
                      CASE
                        WHEN tabel_bank_soal.status > 0 THEN "Berbagi"
                        ELSE "Tidak Berbagi"
                      END AS status', FALSE);
    $this->db->join(
      'tabel_mapel',
      'tabel_mapel.id = ' . $this->table . '.mapel_id',
      'join'
    );
    $this->db->join(
      'tabel_subbab',
      'tabel_subbab.id = ' . $this->table . '.subbab_id',
      'join'
    );
    $this->db->where($this->table . '.user_id', $user_id);
    return $this->db->get($this->table);
  }

  public function get_mapel_subbab($id)
  {
    $this->db->select('tabel_mapel.nama AS mapel_name');
    $this->db->select('tabel_subbab.nama AS subbab_name');
    $this->db->join(
      'tabel_mapel',
      'tabel_mapel.id = ' . $this->table . '.mapel_id',
      'join'
    );
    $this->db->join(
      'tabel_subbab',
      'tabel_subbab.id = ' . $this->table . '.subbab_id',
      'join'
    );
    $this->db->where($this->table . '.id', $id);
    return $this->db->get($this->table);
  }

  public function get_bank_soal($id)
  {
    $this->db->select('bank_soal_id as id');
    $this->db->where('ulangan_id', $id);
    return $this->db->get('tabel_referensi_soal');
  }

  public function get_list_mapel_subbab($id)
  {
    $bank_soal = $this->get_bank_soal($id)->result();
    foreach ($bank_soal as $key => $value) {
      $list[] = $this->get_mapel_subbab($value->id)->row();
    }
    return $list;
  }
}
