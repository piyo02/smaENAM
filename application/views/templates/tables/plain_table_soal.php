<div class="card-body table-responsive">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th style="width:50px">No</th>
                <?php foreach ($header as $key => $value) : ?>
                    <th><?php echo $value ?></th>
                <?php endforeach; ?>
                <?php if (isset($action)) : ?>
                    <th><?php echo "Aksi" ?></th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $audio = $gambar = '';
            $no = 1;
            foreach ($rows_soal as $ind => $row) :
                ?>
                <tr>
                    <td> <?php echo $no++; ?> </td>
                    <?php foreach ($header as $key => $value) : ?>
                        <td>
                            <?php
                                    $attr = "";
                                    $gambar = '';
                                    if ($key == 'text') {
                                        if ($row->audio == '')
                                            $row->audio = '';
                                        if ($row->gambar != '')
                                            $gambar = '<img src="' . base_url("uploads/soal/") . $row->gambar . '" width="150px" height="150px">';
                                        $attr =  '<div>
                                                    ' . $row->audio . '
                                                    <div class="row">
                                                        ' . $gambar . '
                                                        <div class="col-9">
                                                            <p>' . $row->$key . '</p>
                                                        </div>
                                                    </div>
                                                </div>';
                                    } elseif ($key == 'jawaban') {
                                        $gambar = $row->$key;
                                        if ($row->skor == -1)
                                            $row->skor = '-';
                                        if ($row->skor == -2)
                                            $row->skor = 'manual';
                                        if ($row->type == 'gambar')
                                            $gambar = '<img src="' . base_url("uploads/soal/") . $row->$key . '" width="150px" height="150px">';
                                        $attr =  '<div>
                                                    <p>' . $gambar . '</p>
                                                    <div class="float-right">
                                                        <p><b>Skor: ' . $row->skor . '</b></p>
                                                    </div>
                                                  </div>';
                                    } else {
                                        $attr = $row->$key;
                                    }
                                    echo $attr;
                                    ?>
                        </td>
                    <?php endforeach; ?>
                    <?php if (isset($action)) : ?>
                        <td>
                            <!--  -->
                            <?php
                                    foreach ($action as $ind => $value) :
                                        ?>
                                <?php
                                            switch ($value['type']) {
                                                case "link":
                                                    $value["data"] = $row;
                                                    $this->load->view('templates/actions/link', $value);
                                                    break;
                                                case "modal_delete":
                                                    $value["data"] = $row;
                                                    $this->load->view('templates/actions/modal_delete', $value);
                                                    break;
                                                case "modal_form":
                                                    $value["data"] = $row;
                                                    $this->load->view('templates/actions/modal_form', $value);
                                                    break;
                                                case "button_dropdowns":
                                                    $value["data"] = $row;
                                                    $this->load->view('templates/actions/button_dropdown', $value);
                                                    break;
                                            }
                                            ?>
                                <?php
                                        endforeach;
                                        ?>
                                <!--  -->
                        </td>
                    <?php endif; ?>
                </tr>
            <?php
            endforeach;
            ?>
        </tbody>
    </table>
</div>