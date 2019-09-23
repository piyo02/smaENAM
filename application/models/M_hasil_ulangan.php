<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_hasil_ulangan extends MY_Model
{
  protected $table = "tabel_hasil_ulangan";

  function __construct()
  {
    parent::__construct($this->table);
    parent::set_join_key('group_id');
  }

  public function create($data)
  {
    $this->db->insert($this->table, $data);
  }

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

  public function get_kkm($id)
  {
    $this->db->select('kkm');
    $this->db->where('id', $id);
    return $this->db->get('tabel_ulangan');
  }

  public function get_hasil_ulangan($ulangan_id)
  {
    $kkm = $this->get_kkm($ulangan_id)->row()->kkm;

    $this->db->select($this->table . '.id');
    $this->db->select($this->table . '.user_id');
    $this->db->select($this->table . '.ulangan_id');
    $this->db->select($this->table . '.nilai');
    $this->db->select("CONCAT((table_users.first_name),(' '),(table_users.last_name)) as nama");
    $this->db->select('
                      CASE
                        WHEN tabel_hasil_ulangan.nilai >' . $kkm . ' THEN "Tuntas"
                        ELSE "Belum Tuntas"
                      END AS ket', FALSE);
    $this->db->join(
      'table_users',
      'table_users.id = tabel_hasil_ulangan.user_id',
      'join'
    );
    $this->db->where($this->table . '.ulangan_id', $ulangan_id);
    return $this->db->get($this->table);
  }

  public function get_detail_ulangan($data_param)
  {
    $this->db->select('user_id');
    $this->db->select('ulangan_id');
    $this->db->select('nilai');
    $this->db->where($data_param);
    return $this->db->get($this->table);
  }
}
