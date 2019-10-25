<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_school extends MY_Model
{
  protected $table = "schools";

  function __construct()
  {
    parent::__construct($this->table);
    parent::set_join_key('school_id');
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
      $this->set_message("Sekolah berhasil dibuat");
      return $id;
    }
    $this->set_error("Sekolah gagal dibuat");
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

      $this->set_error("Sekolah gagal diedit");
      return FALSE;
    }

    $this->db->trans_commit();

    $this->set_message("Sekolah berhasil diedit");
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
      $this->set_error("Sekolah gagal dihapus"); //('group_delete_unsuccessful');
      return FALSE;
    }
    //foreign
    $this->db->trans_begin();

    $this->db->delete($this->table, $data_param);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();

      $this->set_error("Sekolah gagal dihapus"); //('group_delete_unsuccessful');
      return FALSE;
    }

    $this->db->trans_commit();

    $this->set_message("Sekolah berhasil dihapus"); //('group_delete_successful');
    return TRUE;
  }

  public function school($param = null)
  {
    $this->db->select('id');
    $this->db->select('name');
    $this->db->select('description');
    if ($param)
      $this->db->where($param);
    return $this->db->get($this->table);
  }

  public function schools()
  {
    $schools = $this->school()->result();
    foreach ($schools as $key => $school) {
      $list[$school->id] = $school->name;
    }
    return $list;
  }


  //pindah?
  public function teacher_by_school_id($data_param)
  {
    $this->db->select('table_users.id');
    $this->db->select('teacher_profile.nip');
    $this->db->select('table_users.username');
    $this->db->select('table_users.email');
    $this->db->select('CONCAT(table_users.first_name, " ", table_users.last_name) AS full_name');
    $this->db->select('table_users.phone');
    $this->db->select('table_users.active');
    $this->db->select('
                      CASE
                        WHEN table_users.active = 1 THEN "Aktif"
                        ELSE "Tidak Aktif"
                      END AS status', FALSE);
    $this->db->join(
      'teacher_profile',
      'teacher_profile.user_id = table_users.id',
      'inner'
    );
    $this->db->where($data_param);
    return $this->db->get('table_users');
  }
}
