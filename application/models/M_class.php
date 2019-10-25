<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_class extends MY_Model
{
  protected $table = "classes";
  protected $mapel_list = array();

  function __construct()
  {
    parent::__construct($this->table);
    parent::set_join_key('class_id');
  }

  /**
   * create
   *
   * @param array  $data
   * @return static
   * @author kz
   */
  public function create($data)
  {
    // Filter the data passed
    $data = $this->_filter_data($this->table, $data);

    $this->db->insert($this->table, $data);
    $id = $this->db->insert_id($this->table . '_id_seq');

    if (isset($id)) {
      $this->set_message("Kelas berhasil dibuat");
      return $id;
    }
    $this->set_error("Kelas gagal dibuat");
    return FALSE;
  }


  /**
   * update
   *
   * @param array  $data
   * @param array  $data_param
   * @return bool
   * @author kz
   */
  public function update($data, $data_param)
  {
    $this->db->trans_begin();
    $data = $this->_filter_data($this->table, $data);
    $this->db->update($this->table, $data, $data_param);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();

      $this->set_error("Kelas gagal di Edit");
      return FALSE;
    }

    $this->db->trans_commit();

    $this->set_message("Kelas berhasil di edit");
    return TRUE;
  }
  /**
   * delete
   *
   * @param array  $data_param
   * @return bool
   * @author kz
   */
  public function delete($data_param)
  {
    //foreign
    //delete_foreign( $data_param. $models[]  )
    if (!$this->delete_foreign($data_param, ['m_ulangan', 'm_student_profile', 'm_bank_soal'])) {
      $this->set_error("gagal"); //('menu_delete_unsuccessful');
      return FALSE;
    }
    //foreign
    $this->db->trans_begin();
    $this->db->delete($this->table, $data_param);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();

      $this->set_error("gagal"); //('menu_delete_unsuccessful');
      return FALSE;
    }

    $this->db->trans_commit();

    $this->set_message("berhasil"); //('menu_delete_successful');
    return TRUE;
  }

  public function get_classes($data_param = null)
  {
    $this->db->select('classes.id');
    $this->db->select('classes.name');
    $this->db->select('classes.description');
    if ($data_param)
      $this->db->where($data_param);
    $this->db->order_by('name');
    return $this->db->get($this->table);
  }

  public function list_classes($data_param = null)
  {
    $param['user_id'] = $this->session->userdata('user_id');
    $classes = $this->get_classes()->result();
    $list[''] = '-- Pilih Kelas --';
    foreach ($classes as $key => $course) {
      $list[$course->id] = $course->name;
    }
    return $list;
  }

  public function get_course($id)
  {
    $this->db->select('classes.name AS class_name');
    $this->db->select('courses.name AS name');
    $this->db->join(
      'tabel_ulangan',
      'tabel_ulangan.class_id = classes.id',
      'left'
    );
    $this->db->join(
      'courses',
      'courses.id = classes.course_id',
      'left'
    );
    $this->db->where('tabel_ulangan.id', $id);
    return $this->db->get($this->table);
  }
}
