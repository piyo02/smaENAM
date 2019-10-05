<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_teacher extends MY_Model
{
  protected $table = "edu_ladder";

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

  public function update_teacher($data, $data_param)
  {
    $this->db->trans_begin();
    $data = $this->_filter_data('table_users', $data);

    $this->db->update('table_users', $data, $data_param);
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

  public function delete_teacher($data_param)
  {
    //foreign
    //delete_foreign( $data_param. $models[]  )
    if (!$this->delete_foreign($data_param)) {
      $this->set_error("gagal"); //('group_delete_unsuccessful');
      return FALSE;
    }
    //foreign
    $this->db->trans_begin();

    $param['user_id'] = $data_param['id'];
    $this->db->delete('teacher_profile', $param);
    $this->db->delete('table_users', $data_param);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();

      $this->set_error("gagal"); //('group_delete_unsuccessful');
      return FALSE;
    }

    $this->db->trans_commit();

    $this->set_message("berhasil"); //('group_delete_successful');
    return TRUE;
  }

  public function get_edu_ladder()
  {
    $this->db->select('id');
    $this->db->select('name');
    return $this->db->get($this->table);
  }

  public function list_edu_ladder()
  {
    $lists = $this->get_edu_ladder()->result();
    $select[] = '-- Pilih Jenjang Pendidikan --';
    foreach ($lists as $key => $list) {
      $select[$list->id] = $list->name;
    }
    return $select;
  }

  public function get_edu_ladder_teacher($id)
  {
    $this->db->select('teacher_profile.id');
    $this->db->select('teacher_profile.nip');
    $this->db->select('edu_ladder.id AS edu_ladder_id');
    $this->db->select('edu_ladder.name');
    $this->db->join(
      'edu_ladder',
      'edu_ladder.id = teacher_profile.edu_ladder_id',
      'join'
    );
    $this->db->where('user_id', $id);
    return $this->db->get('teacher_profile');
  }

  public function update_profile($data)
  {
    $this->db->trans_begin();
    $data = $this->_filter_data('teacher_profile', $data);
    if (!$this->get_edu_ladder_teacher($this->session->userdata('user_id'))->row()) {
      $data['user_id'] = $this->session->userdata('user_id');
      $this->db->trans_commit();

      $this->create_profile($data);
      return TRUE;
    }


    $data_param['user_id'] = $this->session->userdata('user_id');
    $this->db->update('teacher_profile', $data, $data_param);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();

      $this->set_error("gagal");
      return FALSE;
    }

    $this->db->trans_commit();

    $this->set_message("berhasil");
    return TRUE;
  }

  public function create_profile($data)
  {
    $data = $this->_filter_data('teacher_profile', $data);

    $this->db->insert('teacher_profile', $data);
    $id = $this->db->insert_id('teacher_profile' . '_id_seq');

    if (isset($id)) {
      $this->set_message("berhasil");
      return $id;
    }
    $this->set_error("gagal");
    return FALSE;
  }

  public function insert_teacher_profile($data)
  {
    // Filter the data passed
    $data = $this->_filter_data('teacher_profile', $data);

    $this->db->insert('teacher_profile', $data);
    $id = $this->db->insert_id('teacher_profile' . '_id_seq');

    if (isset($id)) {
      $this->set_message("berhasil");
      return $id;
    }
    $this->set_error("gagal");
    return FALSE;
  }
}
