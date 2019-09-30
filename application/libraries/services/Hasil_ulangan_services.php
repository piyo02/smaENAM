<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Hasil_ulangan_services
{


  function __construct()
  {
    //
  }

  public function __get($var)
  {
    return get_instance()->$var;
  }

  public function groups_table_config($_page, $data, $start_number = 1)
  {
    if ($data) {
      $this->mapel = '';
      $this->mapel = '';
    }
    $table["header"] = array(
      'nama' => 'Nama Ulangan',
      'class' => 'Kelas',
    );
    $table["number"] = $start_number;
    $table["action"] = array(
      array(
        "name" => 'Detail',
        "type" => "link",
        "url" => site_url($_page . "detail/"),
        "button_color" => "primary",
        "param" => "id",
      ),
      array(
        "name" => 'Kosongkan',
        "type" => "modal_delete",
        "modal_id" => "delete_",
        "url" => site_url($_page . "delete/"),
        "button_color" => "danger",
        "param" => "id",
        "form_data" => array(
          "id" => array(
            'type' => 'hidden',
            'label' => "id",
          ),
        ),
        "title" => "Jurnal",
        "data_name" => "nama",
      ),
    );
    return $table;
  }

  public function tabel_hasil_ulangan($_page, $start_number = 1)
  {
    $table["header"] = array(
      'nama' => 'Nama Siswa',
      'nilai' => 'Nilai',
      'ket' => 'Keterangan',
    );
    $table["number"] = $start_number;
    $table["action"] = array(
      array(
        "name" => 'Detail',
        "type" => "link",
        "url" => site_url($_page . "review/"),
        "button_color" => "primary",
        "param" => "id",
      ),
      array(
        "name" => 'Periksa Ulang',
        "type" => "link",
        "url" => site_url($_page . "recheck/"),
        "button_color" => "success",
        "param" => "id",
      ),
      array(
        "name" => 'X',
        "type" => "modal_delete",
        "modal_id" => "delete_",
        "url" => site_url($_page . "delete_hasil/"),
        "button_color" => "danger",
        "param" => "id",
        "form_data" => array(
          "id" => array(
            'type' => 'hidden',
            'label' => "id",
          ),
          "ulangan_id" => array(
            'type' => 'hidden',
            'label' => "id",
          ),
        ),
        "title" => "Jurnal",
        "data_name" => "nama",
      ),
    );
    return $table;
  }


  public function header_excel($qty)
  {
    $colum = 'D15:D' . (15 + $qty - 1);
    $colum_avg = 'D' . (15 + $qty + 2);
    $table['detail'] = array(
      'nama_sekolah'  => 'NAMA SEKOLAH',
      'course_name' => 'MATA PELAJARAN',
      'class'   => 'KELAS',
      'nama'   => 'NAMA ULANGAN',
      'materi'   => 'MATERI',
      'waktu_mulai'   => 'TANGGAL ULANGAN',
      'kkm'   => 'NILAI KETUNTASAN',
      'teacher_name'   => 'GURU',
    );
    $table['hasil'] = array(
      'nama'  => 'Nama Siswa',
      'nilai' => 'Nilai',
      'ket'   => 'Keterangan Ketuntasan'
    );
    $table['rekap'] = array(
      'qty'     => 'Jumlah',
      'mean'    => 'Rata-rata',
      'max'     => 'Nilai Tertinggi',
      'min'     => 'Nilai Terendah',
      'dev'     => 'Simpangan Baku',
      'qty_s'   => 'Jumlah Peserta Ujian (Siswa)',
      'qty_c'   => 'Jumlah Yang Tuntas (Siswa)',
      'qty_nc'  => 'Jumlah Yang Belum Tuntas (Siswa)',
      'qty_tme' => 'Di Atas Rata-rata (Siswa)',
      'qty_ume' => 'Di Bawah Rata-rata (Siswa)',
    );
    $table['rumus'] = array(
      'qty'     => '=SUM(' . $colum . ')',
      'mean'    => '=AVERAGE(' . $colum . ')',
      'max'     => '=MAX(' . $colum . ')',
      'min'     => '=MIN(' . $colum . ')',
      'dev'     => '=STDEV(' . $colum . ')',
      'qty_s'   => '=COUNT(' . $colum . ')',
      'qty_c'   => 'COUNTIF(' . $colum . ';CONCATENATE(">=";E11))',
      'qty_nc'  => 'COUNTIF(' . $colum . ';CONCATENATE("<";E11))',
      'qty_tme' => 'COUNTIF(' . $colum . ';CONCATENATE(">=";' . $colum_avg . '))',
      'qty_ume' => 'COUNTIF(' . $colum . ';CONCATENATE("<";' . $colum_avg . '))',
    );
    return $table;
  }
}
