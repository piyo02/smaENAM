<div class="content-wrapper">
    <div class="row justify-content-center" style="width: 100%">
        <h3 class="mt-2">Ulangan Akhir Semester Matematika</h3>
    </div>
    <div class="row" style="width: 100%">
        <div class="col-3 ml-5 mt-5">
            <div class="card">
                <div class="card-header">
                    Daftar Soal
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php $nomor = 1;
                        foreach ($contents as $key => $content) : ?>
                            <div class="col-md-3 mb-2">
                                <form action="<?= base_url('siswa/tes/ulangan') ?>" method="get">
                                    <input type="hidden" name="id" value="<?= $content->soal_id ?>">
                                    <input type="hidden" name="nomor" value="<?= $nomor ?>">
                                    <button type='submit' class="btn btn-default w-100"><?= $nomor++; ?></button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <button class="btn btn-success">Selesai</button>
            </div>
        </div>
        <div class="col-8 mt-5">
            <div class="card">
                <div class="card-header">
                    <div id="no">
                        <!-- <input type="hidden" name="id" value="<= #$soal->id ?>"> -->
                        <h4>Soal nomor <?= $number; ?></h4>
                    </div>
                    <div class="row">
                        <div class="col-12" id="audio">
                            <audio src=""></audio>
                        </div>
                        <div class="row col-12">
                            <?php if ($soal->gambar != '') : ?>
                                <div class="col-3">
                                    <img src="<?= base_url('uploads/soal/') . $soal->gambar ?>" width="200px" height="200px">
                                </div>
                            <?php endif; ?>
                            <div class="col-8">
                                <?= $soal->text; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="option">
                    <?php $opt = ['A', 'B', 'C', 'D', 'E'];
                    $i = 0;
                    ?>
                    <div class="row">
                        <?php foreach ($options as $key => $option) : ?>
                            <?php switch ($option->type) {
                                    case 'gambar': ?>
                                    <div class="input-group mt-3 col-md-6">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <input type="radio" name="jawaban" id=""><?= $opt[$i++]; ?>
                                            </span>
                                        </div>
                                        <img src="<?= base_url("uploads/soal/") . $option->jawaban ?>" width="200px" height="200px">
                                    <?php break;
                                        case 'teks': ?>
                                        <div class="input-group mt-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <input type="radio" name="jawaban" value=""><?= $opt[$i++]; ?>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" value="<?= $option->jawaban; ?>">
                                        <?php break;
                                            case 'isian': ?>
                                            <div class="input-group mt-3">
                                                <div class="form-group col-12">
                                                    <label for="">Jawaban Anda</label>
                                                    <input type="text" class="form-control">
                                                </div>
                                            <?php break;
                                                case 'esai': ?>
                                                <div class="input-group mt-3">
                                                    <div class="form-group col-12">
                                                        <label for="">Jawaban Anda</label>
                                                        <textarea class="form-control" name="" id="" cols="100" rows="10"></textarea>
                                                    </div>
                                                <?php break;
                                                } ?>
                                                </div>
                                            <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <div class="card-footer row justify-content-center">
                                            <button class="btn btn-secondary mr-2">Kembali</button>
                                            <button class="btn btn-warning">Ragu-ragu</button>
                                            <button class="btn btn-primary ml-2">Simpan</button>
                                        </div>
                                    </div>
                    </div>
                </div>
            </div>