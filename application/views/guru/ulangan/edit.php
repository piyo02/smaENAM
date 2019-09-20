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
                            <?php echo form_open();  ?>
                            <div class="row">
                                <div class="col-8">
                                    <?php echo (isset($contents)) ? $contents : '';  ?>
                                </div>
                                <div class="col-1"></div>
                                <div class="col-3 mt-4">
                                    <?php
                                    if (isset($data->gambar) && $data->gambar != null)
                                        echo '<div>
                                                <label>Tampilan Gambar</label>
                                                <img src="' . base_url('uploads/soal/soal/') . $data->gambar . '" width="200px" style="border-radius:5px;">
                                                </div>';
                                    ?>
                                    <?php
                                    if (isset($data->audio) && $data->audio != null)
                                        echo '<div class="mt-4">
                                                <label>Soal Audio</label>
                                                <audio controls>
                                                    <source src="' . base_url('uploads/soal/soal') . $data->audio . '" type="audio/mp3">
                                                </audio>
                                            </div>';
                                    ?>
                                </div>
                            </div>
                            <div class="mt-4">
                                <h4>Jawaban</h4>
                                <?php if ($data->type == 'text') : ?>
                                    <div class="form-group row">
                                        <div class="col-7">
                                            <div class="row">
                                                <div class="col-10">
                                                    <label for="">Pilihan A</label>
                                                    <input type="text" name="jawaban" class="form-control">
                                                </div>
                                                <div class="col-1">
                                                    <label for="">Skor</label>
                                                    <input type="text" name="skor" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <label for="">Pilihan A</label>
                                            <p><?= 1; ?></p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-7">
                                            <div class="row">
                                                <div class="col-10">
                                                    <label for="">Pilihan B</label>
                                                    <input type="text" name="jawaban" class="form-control">
                                                </div>
                                                <div class="col-1">
                                                    <label for="">Skor</label>
                                                    <input type="text" name="skor" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <label for="">Pilihan B</label>
                                            <p><?= 1; ?></p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-7">
                                            <div class="row">
                                                <div class="col-10">
                                                    <label for="">Pilihan C</label>
                                                    <input type="text" name="jawaban" class="form-control">
                                                </div>
                                                <div class="col-1">
                                                    <label for="">Skor</label>
                                                    <input type="text" name="skor" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <label for="">Pilihan C</label>
                                            <p><?= 1; ?></p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-7">
                                            <div class="row">
                                                <div class="col-10">
                                                    <label for="">Pilihan D</label>
                                                    <input type="text" name="jawaban" class="form-control">
                                                </div>
                                                <div class="col-1">
                                                    <label for="">Skor</label>
                                                    <input type="text" name="skor" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <label for="">Pilihan D</label>
                                            <p><?= 1; ?></p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-7">
                                            <div class="row">
                                                <div class="col-10">
                                                    <label for="">Pilihan E</label>
                                                    <input type="text" name="jawaban" class="form-control">
                                                </div>
                                                <div class="col-1">
                                                    <label for="">Skor</label>
                                                    <input type="text" name="skor" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <label for="">Pilihan E</label>
                                            <p><?= 1; ?></p>
                                        </div>
                                    </div>
                                <?php elseif ($data->type == 'image') : ?>
                                    <div class="form-group row">
                                        <div class="col-7 row">
                                            <div class="col-10">
                                                <label for="">Pilihan A</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="jawaban" id="jawaban">
                                                        <label class=" custom-file-label" for="">Choose file</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <label for="">Skor</label>
                                                <input type="text" name="skor" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <label for="">Pilihan A</label><br>
                                            <img src="<?= base_url('uploads/soal/jawaban') ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-7 row">
                                            <div class="col-10">
                                                <label for="">Pilihan B</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="jawaban" id="jawaban">
                                                        <label class=" custom-file-label" for="">Choose file</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <label for="">Skor</label>
                                                <input type="text" name="skor" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <label for="">Pilihan B</label><br>
                                            <img src="<?= base_url('uploads/soal/jawaban') ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-7 row">
                                            <div class="col-10">
                                                <label for="">Pilihan C</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="jawaban" id="jawaban">
                                                        <label class=" custom-file-label" for="">Choose file</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <label for="">Skor</label>
                                                <input type="text" name="skor" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <label for="">Pilihan C</label><br>
                                            <img src="<?= base_url('uploads/soal/jawaban') ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-7 row">
                                            <div class="col-10">
                                                <label for="">Pilihan D</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="jawaban" id="jawaban">
                                                        <label class=" custom-file-label" for="">Choose file</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <label for="">Skor</label>
                                                <input type="text" name="skor" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <label for="">Pilihan D</label><br>
                                            <img src="<?= base_url('uploads/soal/jawaban') ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-7 row">
                                            <div class="col-10">
                                                <label for="">Pilihan E</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="jawaban" id="jawaban">
                                                        <label class=" custom-file-label" for="">Choose file</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <label for="">Skor</label>
                                                <input type="text" name="skor" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <label for="">Pilihan E</label><br>
                                            <img src="<?= base_url('uploads/soal/jawaban') ?>">
                                        </div>
                                    </div>
                                <?php elseif ($data->type == 'short-answer') : ?>
                                    <div class="form-group row">
                                        <div class="col-7 row">
                                            <div class="col-10">
                                                <label for="">Masukan jawaban</label>
                                                <input type="text" name="jawaban" class="form-control">
                                            </div>
                                            <div class="col-1">
                                                <label for="">Skor</label>
                                                <input type="text" name="skor" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <label for="">Jawaban</label><br>
                                            <?= $data->type; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
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