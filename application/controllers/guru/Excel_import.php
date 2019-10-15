<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Excel_import extends Users_Controller
{
    private $services = null;
    private $name = null;
    private $parent_page = 'guru';
    private $current_page = 'guru/excel_import/';

    public function __construct()
    {
        parent::__construct();
        $this->load->library('services/Akademik_services');
        $this->services = new Akademik_services;
        $this->load->model(array(
            'm_bank_soal',
            'm_mapel',
            'm_soal',
            'm_jawaban',
        ));
        $this->load->library('excel');
    }

    function index()
    {
        $this->load->view('excel_import');
    }

    function import()
    {

        if (isset($_FILES["file"]["name"])) {
            $path = $_FILES["file"]["tmp_name"];
            $object = PHPExcel_IOFactory::load($path);
            foreach ($object->getWorksheetIterator() as $worksheet) {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                $row = 11;
                while ($row <= $highestRow) {
                    $teks = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    if ($teks == null) {
                        $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, 'Tidak ada data soal'));
                        redirect(site_url('guru/soal/daftar_soal/' . $this->input->post('b')));
                    }
                    $data = array(
                        'kode'          => $this->generate_id(),
                        'bank_soal_id'  => $this->input->post('b'),
                        'type'          => 'teks',
                        'text'          => $teks,
                    );
                    $id = $this->m_soal->insert_soal($data);
                    $tipe = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    if ($tipe != 'teks' && $tipe != 'isian' && $tipe != 'esai') {
                        $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, 'Silahkan cek kembali tipe soal'));
                        redirect(site_url('guru/soal/daftar_soal/' . $this->input->post('b')));
                    }
                    $method = 'get_option_' . $tipe;

                    $data_option = $this->$method($worksheet, $id, $row);
                    $this->m_soal->insert_option($data_option['option']);
                    $row = $data_option['row'];
                }
            }
        }
        $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->m_soal->messages()));
        redirect(site_url('guru/soal/daftar_soal/' . $this->input->post('b')));
    }


    public function generate_id()
    {
        $data = $this->m_soal->get_number($this->input->post('b'))->row();
        if (!$data->kode)
            $kode = 'S-1';
        else {
            $id = substr($data->kode, 2) + 1;
            $kode = 'S-' . $id;
        }
        return $kode;
    }

    public function get_option_teks($worksheet, $id, $row)
    {
        for ($i = 0; $i < 5; $i++) {
            $_option['soal_id'] = $id;
            $_option['type']    = 'teks';
            $_option['jawaban'] = $worksheet->getCellByColumnAndRow(3, ($row + $i))->getValue();

            $_option['skor'] = $worksheet->getCellByColumnAndRow(4, ($row + $i))->getValue();
            $_data_option[] = $_option;
        }
        $return = [
            'option' => $_data_option,
            'row'    => $row + 5
        ];
        return $return;
    }

    public function get_option_isian($worksheet, $soal_id, $row)
    {
        $data[] = [
            'soal_id' => $soal_id,
            'type' => 'isian',
            'jawaban' => $worksheet->getCellByColumnAndRow(3, $row)->getValue(),
            'skor' => $worksheet->getCellByColumnAndRow(4, $row)->getValue(),
        ];
        $return = [
            'option' => $data,
            'row'    => $row + 1
        ];
        return $return;
    }

    public function get_option_esai($worksheet, $soal_id, $row)
    {
        $data[] = [
            'soal_id' => $soal_id,
            'type' => 'esai',
            'jawaban' => $worksheet->getCellByColumnAndRow(3, $row)->getValue(),
            'skor' => $worksheet->getCellByColumnAndRow(4, $row)->getValue(),
        ];

        $return = [
            'option' => $data,
            'row'    => $row + 1
        ];
        return $return;
    }
}
