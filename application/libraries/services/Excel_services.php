<?php

class Excel_services
{
    function __construct()
    {
        //
    }

    public function excel_config($_data)
    {
        $detail = $_data['detail'];
        $headers = $_data['headers'];
        $datas = $_data['rows'];
        $title = $_data['title'];
        // $name = $_data['name']->name;
        require(APPPATH . 'PHPExcel-1.8/Classes/PHPExcel.php');
        require(APPPATH . 'PHPExcel-1.8/Classes/PHPExcel/Writer/Excel2007.php');

        $PHPExcel = new PHPExcel();

        ########################  DATA UMUM  ########################
        $PHPExcel->getProperties()->setCreator('Creator');
        $PHPExcel->getProperties()->setLastModifiedBy();
        $PHPExcel->getProperties()->setTitle('Creator');
        $PHPExcel->getProperties()->setSubject('Creator');
        $PHPExcel->getProperties()->setDescription('Creator');

        $PHPExcel->getActiveSheetIndex(0);
        $PHPExcel->getActiveSheet()->setTitle($title);

        ########################  ARRAY STYLE  ########################

        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => '0000'))));
        $outsideborder = array('borders' => array('outsideborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => '0000'))));

        //Header
        $PHPExcel->getActiveSheet()->setCellValue('B2', 'HASIL NILAI TES ');

        ##############################################################################
        //keterangan ulangan
        $column = 5;
        foreach ($headers['detail'] as $key => $header) {
            $PHPExcel->getActiveSheet()->setCellValue('C' . $column, $header);
            $PHPExcel->getActiveSheet()->setCellValue('D' . $column, ':');
            if ($key == 'waktu_mulai') {
                $detail->$key = date('d-m-Y', $detail->$key);
            }
            $PHPExcel->getActiveSheet()->setCellValue('E' . $column, $detail->$key);

            //style keterangan ulangan        
            $PHPExcel->getActiveSheet()->mergeCells('E' . $column . ':F' . $column);
            $column++;
        }

        //header tabel
        $cell = 66;
        $cell_kop_hasil = 66; //string B
        $PHPExcel->getActiveSheet()->setCellValue(chr($cell_kop_hasil++) . 14, 'No');

        foreach ($headers['hasil'] as $key => $header) {
            $PHPExcel->getActiveSheet()->setCellValue(chr($cell_kop_hasil) . '14', $header);
            //style
            $cell_kop_hasil++;
            if ($header == 'Name Student' || $header == 'Class')
                $PHPExcel->getActiveSheet()->getColumnDimension(chr($cell))->setWidth(25);
        }

        //isi tabel B => nomor, C => Nama, E => Nilai, F => keterangan
        $row_data  = 15;
        $nomor        = 1;
        $endcell      = 73;
        foreach ($datas as $key => $data) {
            $PHPExcel->getActiveSheet()->setCellValue('B' . $row_data, $nomor++); //C
            $PHPExcel->getActiveSheet()->setCellValue('C' . $row_data, $data->nama); //D
            $PHPExcel->getActiveSheet()->setCellValue('D' . $row_data, $data->nilai); //D
            $PHPExcel->getActiveSheet()->setCellValue('E' . $row_data, $data->ket); //D

            $PHPExcel->getActiveSheet()->getStyle('D' . $row_data . ':E' . $row_data)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $PHPExcel->getActiveSheet()->mergeCells('E' . $row_data . ':F' . $row_data++);
        }

        // //rekap C => D, E => F 
        $row_rekap = $row_data + 1; //18
        $PHPExcel->getActiveSheet()->mergeCells('B' . ($row_rekap) . ':B' . ($row_rekap + 4));
        $cell_header = 'C';
        $cell_value  = 'D';
        foreach ($headers['rekap'] as $key => $header) {
            $PHPExcel->getActiveSheet()->setCellValue($cell_header . $row_rekap, $header);
            $PHPExcel->getActiveSheet()->setCellValue($cell_value . $row_rekap++, $headers['rumus'][$key]);
            if ($header == 'Simpangan Baku') {
                $cell_header = 'E';
                $cell_value  = 'F';
                $row_rekap     -= 5;
            }
        }


        ############    style aligment   ####################
        $PHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $PHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        // $PHPExcel->getActiveSheet()->getStyle('B5:F12')->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle('E11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $PHPExcel->getActiveSheet()->getStyle('B14:E14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $PHPExcel->getActiveSheet()->getStyle('D5:D12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        #####################   style global width colum  #########################
        $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(3.8);
        $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30.3);
        $PHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(5.6);
        $PHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(33.9);
        $PHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(3);

        #########################     style font  ############################
        $PHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);
        $PHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setSize(14);
        $PHPExcel->getActiveSheet()->getStyle('B2:F' . --$row_rekap)->getFont()->setName('Times New Roman');
        $PHPExcel->getActiveSheet()->getStyle('B15:F' . $row_rekap)->getFont()->setSize(9);
        $PHPExcel->getActiveSheet()->getStyle('B5:F12')->getFont()->setBold(true);
        $PHPExcel->getActiveSheet()->getStyle('B14:F14')->getFont()->setBold(true);

        #################  style merge column   ##########################
        $PHPExcel->getActiveSheet()->mergeCells('E14:F14');
        $PHPExcel->getActiveSheet()->mergeCells('B2:F3');
        $PHPExcel->getActiveSheet()->mergeCells('B5:B12');


        #################  style border column   ##########################
        $PHPExcel->getActiveSheet()->getStyle('B2:F3')->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle('B5:B12')->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle('B14:F' . --$row_data)->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle('B' . ($row_rekap - 4) . ':F' . $row_rekap)->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle('C5:F5')->applyFromArray($styleArray);

        ###################################################################################
        $filename = 'Hasil Test' . date('d-m-Y') . '.xlsx';

        header('Content-Type: appliaction/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Chace-Control: max-age=0 ');

        $writer = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');

        $writer->save('php://output');
        exit;
    }
}
