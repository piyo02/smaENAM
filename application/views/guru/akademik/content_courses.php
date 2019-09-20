<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5 class="m-0 text-dark"><?php echo $block_header ?></h5>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="col-12">
                                <?php
                                echo $alert;
                                ?>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <h5>
                                        <?php echo strtoupper($header) ?>
                                        <p class="text-secondary"><small><?php echo $sub_header ?></small></p>
                                    </h5>
                                </div>
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-2"></div>
                                        <div class="col-10">
                                            <div class="float-right">
                                                <?php echo (isset($header_button)) ? $header_button : '';  ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- HIRARKI -->
                            <div style="  ">
                                <div class="tree">
                                    <ol>
                                        <?php
                                        function print_tree($datas, $class, $branch = null)
                                        {
                                            foreach ($datas as $data) {
                                                echo  '<li class="' . $class . '">';
                                                echo '<a href="#">' . $data->nama . '</a>';
                                                ?>
                                                <?php if (isset($data->branch[0]) && $data->branch[0] == 'die') :
                                                            $modal_edit_name = 'edit_subbab_';
                                                            $modal_delete_name = 'delete_subbab_';
                                                        else :
                                                            $modal_edit_name = 'edit_mapel_';
                                                            $modal_delete_name = 'delete_mapel_';
                                                            ?>
                                                    <button class="btn btn-white btn-info btn-bold btn-xs" data-toggle="modal" data-target="#add_mapel_<?php echo $data->id ?>">
                                                        +
                                                    </button>
                                                <?php endif; ?>
                                                <button class="btn btn-white btn-info btn-bold btn-xs" data-toggle="modal" data-target="#<?php echo $modal_edit_name . $data->id ?>">
                                                    Edit
                                                </button>
                                                <button class="btn btn-white btn-danger btn-bold btn-xs" data-toggle="modal" data-target="#<?php echo $modal_delete_name . $data->id ?>">
                                                    X
                                                </button>
                                                <?php echo $data->deskripsi ?>
                                        <?php
                                                if (isset($data->branch[0]) && $data->branch[0] != 'die') {
                                                    echo "<ol>";
                                                    print_tree($data->branch, 'mt-0');
                                                    echo "</ol>";
                                                    echo  '</li>';
                                                }
                                            }
                                        };
                                        print_tree($mapel_tree, 'mt-3');
                                        ?>
                                    </ol>
                                </div>
                            </div>
                            <!-- HIRARKI -->
                        </div>
                    </div>
                </div>
            </div>
            <?php
            foreach ($mapel_list as $mapel) {
                $model_form_add = array(
                    "name" => "Tambah Child Mapel",
                    "modal_id" => "add_mapel_",
                    "button_color" => "primary",
                    "url" => site_url($current_page . "add_subbab/"),
                    "form_data" => array(
                        "nama" => array(
                            'type' => 'text',
                            'label' => "Materi",
                            'value' => '',
                        ),
                        "deskripsi" => array(
                            'type' => 'textarea',
                            'label' => "Deskripsi",
                            'value' => "-",
                        ),
                        "mapel_id" => array(
                            'type' => 'hidden',
                            'label' => "menu_id",
                            'value' => $mapel->id,
                        ),
                    ),
                    'data' => $mapel,
                    'param' => "id",
                );
                $this->load->view('templates/actions/modal_form_no_button', $model_form_add);
                $model_form_edit = array(
                    "name" => "Edit Mapel",
                    "modal_id" => "edit_mapel_",
                    "button_color" => "primary",
                    "url" => site_url($current_page . "edit/"),
                    "form_data" => array(
                        "nama" => array(
                            'type' => 'text',
                            'label' => "Nama Mapel",
                        ),
                        "deskripsi" => array(
                            'type' => 'textarea',
                            'label' => "Deskripsi",
                        ),
                        "mapel_id" => array(
                            'type' => 'hidden',
                            'label' => "mapel_id",
                        ),
                        "id" => array(
                            'type' => 'hidden',
                            'label' => "mapel_id",
                        ),
                    ),
                    'data' => $mapel,
                    'param' => "id",
                );
                if (isset($mapel->branch[0]) && $mapel->branch[0] == 'die')
                    $model_form_edit['modal_id'] = 'edit_subbab_';
                $this->load->view('templates/actions/modal_form_no_button', $model_form_edit);

                // $mapel->mapel_id = $group->id;
                $model_form_delete = array(
                    "type" => "modal_delete",
                    "modal_id" => "delete_mapel_",
                    "url" => site_url($current_page . "delete/"),
                    "button_color" => "danger",
                    "param" => "id",
                    "form_data" => array(
                        "id" => array(
                            'type' => 'hidden',
                            'label' => "id",
                        ),
                        "mapel_id" => array(
                            'type' => 'hidden',
                            'label' => "id",
                        ),
                    ),
                    'data' => $mapel,
                    "title" => "Mapel",
                    "data_name" => "nama",
                );
                if (isset($mapel->branch[0]) && $mapel->branch[0] == 'die')
                    $model_form_delete['modal_id'] = 'delete_subbab_';
                $this->load->view('templates/actions/modal_delete_no_button', $model_form_delete);
            }
            ?>
        </div>
    </section>
</div>