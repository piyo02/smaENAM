<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_bank_soal extends MY_Model
{
  protected $table = "bank_soal";

  function __construct()
  {
    parent::__construct($this->table);
    parent::set_join_key('bank_soal_id');
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
    $this->db->select($this->table . '.materi');
    $this->db->select('classes.id AS class_id');
    $this->db->select('classes.name AS class');
    $this->db->select('courses.id AS course_id');
    $this->db->select('courses.name AS course');
    $this->db->select('
                      CASE
                        WHEN bank_soal.status > 0 THEN "Berbagi"
                        ELSE "Tidak Berbagi"
                      END AS status', FALSE);
    $this->db->join(
      'classes',
      'classes.id = ' . $this->table . '.class_id',
      'join'
    );
    $this->db->join(
      'courses',
      'courses.id = ' . $this->table . '.course_id',
      'join'
    );
    $this->db->where($this->table . '.user_id', $user_id);
    return $this->db->get($this->table);
  }

  public function get_materi($id)
  {
    $this->db->select('materi');
    $this->db->where('id', $id);
    return $this->db->get($this->table);
  }

  public function get_bank_soal($id)
  {
    $this->db->select('bank_soal_id as id');
    $this->db->where('ulangan_id', $id);
    return $this->db->get('tabel_referensi_soal');
  }

  public function list_materi($id)
  {
    $materi = null;
    $bank_soal = $this->get_bank_soal($id)->row();
    $topics = $this->get_materi($bank_soal->id)->result();
    foreach ($topics as $key => $topic) {
      $materi = $materi . ' ' . $topic->materi;
    }
    return $materi;
  }
}
