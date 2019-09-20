<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_mapel extends MY_Model
{
  protected $table = "tabel_mapel";
  protected $mapel_list = array();

  function __construct()
  {
    parent::__construct($this->table);
    parent::set_join_key('mapel_id');
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
      $this->set_message("berhasil");
      return $id;
    }
    $this->set_error("gagal");
    return FALSE;
  }

  public function create_pelajaran($data)
  {
    // Filter the data passed
    $data = $this->_filter_data('tabel_pelajaran', $data);

    $this->db->insert('tabel_pelajaran', $data);
    $id = $this->db->insert_id('tabel_pelajaran' . '_id_seq');

    if (isset($id)) {
      $this->set_message("berhasil");
      return $id;
    }
    $this->set_error("gagal");
    return FALSE;
  }

  public function create_subbab($data)
  {
    return $this->db->insert('tabel_subbab', $data);
  }



  /**
   * update
   *
   * @param array  $data
   * @param array  $data_param
   * @return bool
   * @author kz
   */
  public function update($tabel, $data, $data_param)
  {
    if ($tabel)
      $this->table = $tabel;
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
   * @author kz
   */
  public function delete($data_param, $table = null)
  {
    //foreign
    //delete_foreign( $data_param. $models[]  )
    if (!$this->delete_foreign($data_param, ['m_mapel'])) {
      $this->set_error("gagal"); //('menu_delete_unsuccessful');
      return FALSE;
    }
    //foreign
    $this->db->trans_begin();
    if ($table)
      $this->table = $table;
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

  public function get_pelajaran()
  {
    $this->db->select('id');
    $this->db->select('bidang_studi_id');
    $this->db->select('nama');
    return $this->db->get('tabel_pelajaran');
  }

  public function get_main_mapel()
  {
    $tree = $this->get_mapel()->result();
    $mapel[] = '-- Pilih Mata Pelajaran --';
    if (empty($tree)) {
      return array();
    }
    foreach ($tree as $branch) {
      $mapel[$branch->id] = $branch->nama;
    }

    return $mapel;
  }
  ################################################################################
  public function get_mapel()
  {
    $this->db->select('id');
    $this->db->select('nama');
    $this->db->select('deskripsi');
    return $this->db->get('tabel_mapel');
  }

  public function get_subbab($mapel_id)
  {
    $this->db->select('id');
    $this->db->select('mapel_id');
    $this->db->select('nama');
    $this->db->select('deskripsi');
    if ($mapel_id)
      $this->db->where('mapel_id', $mapel_id);
    return $this->db->get('tabel_subbab');
  }

  public function get_list_subbab($mapel_id)
  {
    $subbabs = $this->get_subbab($mapel_id)->result();
    if ($subbabs == '')
      return array();
    foreach ($subbabs as $key => $subbab) {
      $this->mapel_list[] = $subbab;
      $subbab->branch = array('die');
    }
    return $subbabs;
  }

  public function tree_mapel()
  {
    $mapel = $this->get_mapel()->result();
    foreach ($mapel as $key => $value) {
      $this->mapel_list[] = $value;
      $subbabs = $this->get_list_subbab($value->id);
      $value->branch = $subbabs;
    }
    return $mapel;
  }

  public function subbab()
  {
    $subbabs = $this->get_subbab(null)->result();
    if ($subbabs == '')
      return array();
    $list_subbab[] = '-- Pilih Materi --';
    foreach ($subbabs as $key => $subbab) {
      $list_subbab[$subbab->id] = $subbab->nama;
    }
    return $list_subbab;
  }

  public function get_mapel_list()
  {
    return $this->mapel_list;
  }
}
