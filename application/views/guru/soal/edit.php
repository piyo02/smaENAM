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
                                    <h6>
                                        <?php echo strtoupper($header) ?>
                                        <p class="text-secondary"><small><?php echo $sub_header ?></small></p>
                                    </h6>
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
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-8">
                                        <?php echo (isset($contents)) ? $contents : '';  ?>
                                    </div>
                                    <div class="col-3 mt-4">
                                        <?php
                                        if (isset($data->gambar) && $data->gambar != null)
                                            echo '<div>
                                                    <label>Tampilan Gambar</label>
                                                    <img src="' . base_url("uploads/soal/") . $data->gambar . '" width="150px" height="150px">
                                                </div>';
                                        if (isset($data->audio) && $data->audio != null)
                                            echo '<div class="mt-4">
                                                <label>Soal Audio</label>
                                                <audio controls>
                                                    <source src="' . base_url('uploads/soal/soal') . $data->audio . '" type="audio/mp3">
                                                </audio>
                                            </div>';
                                        if (isset($option))
                                            foreach ($option as $key => $value) {
                                                echo '<div class="col-12">
                                                        <label>Tampilan Pilihan</label>
                                                        <img src="' . base_url("uploads/soal/") . $value->jawaban . '" width="150px" height="150px">
                                                    </div>';
                                            }
                                        ?>
                                    </div>
                                </div>

                                <button class="btn btn-bold btn-success btn-sm " style="margin-left: 5px; margin-top: 30px" type="submit">
                                    Simpan
                                </button>

                                <?php echo form_close()  ?>
                                <!--  -->
                                <!--  -->
                                <?php echo (isset($pagination_links)) ? $pagination_links : '';  ?>
                                <!--  -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>