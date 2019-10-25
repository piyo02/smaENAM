<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_courses extends MY_Model
{
  protected $table = "courses";
  protected $mapel_list = array();

  function __construct()
  {
    parent::__construct($this->table);
    parent::set_join_key('course_id');
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
      $this->set_message("Mata Pelajaran berhasil di tambahkan");
      return $id;
    }
    $this->set_error("Mata Pelajaran gagal di tambahkan");
    return FALSE;
  }

  public function insert_teacher_course($data)
  {
    // Filter the data passed
    $data = $this->_filter_data('teacher_course', $data);
    $this->db->insert('teacher_course', $data);
    $id = $this->db->insert_id('teacher_course' . '_id_seq');

    if (isset($id)) {
      $this->set_message("Mata Pelajaran berhasil di tambahkan");
      return $id;
    }
    $this->set_error("Mata Pelajaran gagal di tambahkan");
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
    if (!$this->delete_foreign($data_param, ['m_ulangan', 'm_teacher', 'm_bank_soal'])) {
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

  public function delete_teacher_course($data_param)
  {
    $this->db->trans_begin();
    $this->db->delete('teacher_course', $data_param);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();

      $this->set_error("gagal"); //('menu_delete_unsuccessful');
      return FALSE;
    }

    $this->db->trans_commit();

    $this->set_message("berhasil"); //('menu_delete_successful');
    return TRUE;
  }

  public function get_courses($data_param = null)
  {
    $this->db->select($this->table . '.id');
    $this->db->select($this->table . '.name');
    $this->db->select('description');
    $this->db->select('edu_ladder.name AS edu_ladder_name');
    $this->db->join(
      'edu_ladder',
      'edu_ladder.id =' . $this->table . '.edu_ladder_id',
      'join'
    );
    if ($data_param)
      $this->db->where($data_param);
    $this->db->order_by('edu_ladder.id');
    return $this->db->get($this->table);
  }

  public function list_courses($param = null)
  {
    $courses = $this->get_courses($param)->result();
    $list[''] = '-- Pilih Mata Pelajaran --';
    foreach ($courses as $key => $course) {
      $list[$course->id] = $course->name;
    }
    return $list;
  }

  public function list_teacher_course($param = null)
  {
    $courses = $this->get_teacher_course($param)->result();
    $list[''] = '-- Pilih Mata Pelajaran --';
    foreach ($courses as $key => $course) {
      $list[$course->id] = $course->name;
    }
    return $list;
  }

  public function get_teacher_course($param = null)
  {
    $this->db->select('teacher_course.id AS teacher_course_id');
    $this->db->select($this->table . '.id');
    $this->db->select('name');
    $this->db->select('description');
    $this->db->join(
      'teacher_course',
      'teacher_course.course_id = courses.id',
      'join'
    );
    if ($param)
      $this->db->where($param);
    return $this->db->get($this->table);
  }
}
