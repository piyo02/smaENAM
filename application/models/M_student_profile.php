<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_student_profile extends MY_Model
{
  protected $table = "student_profile";

  function __construct()
  {
    parent::__construct($this->table);
    // parent::set_join_key('class_id');
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

  public function get_student_class($param)
  {
    $this->db->select('id');
    $this->db->select('school_id');
    $this->db->select('school.name AS school_name');
    $this->db->select('class_id');
    $this->db->select('classes.name AS class_name');
    $this->db->join(
      'classes',
      'classes.id = student_profile.class_id',
      'join'
    );
    $this->db->join(
      'school',
      'school.id = student_profile.school_id',
      'join'
    );
    $this->db->where($param);
    return $this->db->get($this->table);
  }

  public function get_student_by_class($data_param)
  {
    $this->db->select('table_users.id');
    $this->db->select('CONCAT(table_users.first_name, " ", table_users.last_name) AS username');
    $this->db->select('student_profile.school_id');
    $this->db->select('schools.name AS school_name');
    $this->db->select('student_profile.class_id');
    $this->db->select('classes.name AS class_name');
    $this->db->join(
      'classes',
      'classes.id = student_profile.class_id',
      'join'
    );
    $this->db->join(
      'schools',
      'schools.id = student_profile.school_id',
      'join'
    );
    $this->db->join(
      'table_users',
      'table_users.id = student_profile.user_id',
      'join'
    );
    $this->db->where($data_param);
    return $this->db->get($this->table);
  }
}
