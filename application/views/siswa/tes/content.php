<?php
// timer
if (isset($siswa) && isset($ulangan->durasi)) {
    $mulai = $siswa->waktu_mulai;
    $waktu_berlalu = time() - $mulai;
    $waktu = (int) $ulangan->durasi;

    $temp_waktu = $waktu * 60 - $waktu_berlalu;
    $temp_menit = (int) ($temp_waktu / 60);
    $temp_detik = $temp_waktu % 60;

    if ($temp_menit < 60) {
        $jam   = 0;
        $menit = $temp_menit;
        $detik = $temp_detik;
    } else {
        $jam   = (int) ($temp_menit / 60);
        $menit = $temp_menit % 60;
        $detik = $temp_detik;
    }
}
?>
<div class="content-wrapper">
    <div class="row justify-content-center" style="width: 100%">
        <h3 class="mt-2"><?= $ulangan->nama; ?></h3>
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
                                <form id="soal_<?= $nomor ?>" action="<?= base_url('siswa/tes/ulangan') ?>" method="get">
                                    <input type="hidden" name="id" value="<?= $content->soal_id ?>">
                                    <input type="hidden" name="nomor" value="<?= $nomor ?>">
                                    <?php if ($content->uncertain) : ?>
                                        <button type='submit' id="tombol_soal_<?= $nomor ?>" class="btn btn-warning w-100"><?= $nomor++ . ' ' . $content->option; ?></button>
                                    <?php else : ?>
                                        <?php if ($content->option || $content->jawaban) : ?>
                                            <button type='submit' id="tombol_soal_<?= $nomor ?>" class="btn btn-primary w-100"><?= $nomor++ . ' ' . $content->option; ?></button>
                                        <?php else : ?>
                                            <button type='submit' id="tombol_soal_<?= $nomor ?>" class="btn btn-default w-100"><?= $nomor++ ?></button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <?= $confirm; ?>
            </div>
        </div>
        <div class="col-8 mt-5">
            <div class="card">
                <div class="card-header">
                    <div id="no">
                        <input type="hidden" name="soal_id" id="soal_id" value="<?= $soal->id ?>">
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
                            <input type="hidden" name="uncertain" id="uncertain" value="<?= $contents[$number - 1]->uncertain ?>">
                            <input type="hidden" name="type" id="type" value="<?= $option->type ?>">
                            <?php switch ($option->type) {
                                    case 'gambar': ?>
                                    <div class="input-group mt-3 col-md-6">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <?php if ($contents[$number - 1]->jawaban == $option->id) : ?>
                                                    <input type="radio" name="jawaban" value="<?= $option->id . '-' . $opt[$i]; ?>" checked><?= $opt[$i++]; ?>
                                                <?php else : ?>
                                                    <input type="radio" name="jawaban" value="<?= $option->id . '-' . $opt[$i]; ?>"><?= $opt[$i++]; ?>
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                        <img src="<?= base_url("uploads/soal/") . $option->jawaban ?>" width="200px" height="200px">
                                    <?php break;
                                        case 'teks': ?>
                                        <div class="input-group mt-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <?php if ($contents[$number - 1]->jawaban == $option->id) : ?>
                                                        <input type="radio" name="jawaban" value="<?= $option->id . '-' . $opt[$i]; ?>" checked><?= $opt[$i++]; ?>
                                                    <?php else : ?>
                                                        <input type="radio" name="jawaban" value="<?= $option->id . '-' . $opt[$i]; ?>"><?= $opt[$i++]; ?>
                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" value="<?= $option->jawaban; ?>">
                                        <?php break;
                                            case 'isian': ?>
                                            <div class="input-group mt-3">
                                                <div class="form-group col-12">
                                                    <label for="">Jawaban Anda</label>
                                                    <input type="text" class="form-control" name="jawaban" value="<?= $contents[$number - 1]->jawaban ?>">
                                                </div>
                                            <?php break;
                                                case 'esai': ?>
                                                <div class="input-group mt-3">
                                                    <div class="form-group col-12">
                                                        <label for="">Jawaban Anda</label>
                                                        <textarea class="form-control" name="jawaban" id="" cols="100" rows="10"><?= $contents[$number - 1]->jawaban ?></textarea>
                                                    </div>
                                                <?php break;
                                                } ?>
                                                </div>
                                            <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <div class="card-footer row justify-content-center">
                                            <button onclick="back(<?= $number ?>)" id="back" class="btn btn-secondary mr-2">Kembali</button>
                                            <button onclick="uncertain(<?= $number ?>)" id="uncertain" class="btn btn-warning mr-2">Ragu-ragu</button>
                                            <button onclick="answer(<?= $number ?>)" class="btn btn-primary ">Simpan</button>
                                            <button onclick="next(<?= $number ?>)" class="btn btn-secondary ml-2">Lewati</button>
                                        </div>
                                    </div>
                    </div>
                </div>
            </div>
            <script>
                $(document).ready(function() {
                    var detik = <?= $detik; ?>;
                    var menit = <?= $menit; ?>;
                    var jam = <?= $jam; ?>;

                    function hitung() {
                        setTimeout(hitung, 1000);

                        $('#timer').html(
                            '<h4 class="text-danger" align="center">' + jam + ' jam : ' + menit + ' menit : ' + detik + ' detik</h4>'
                        );

                        detik--;

                        if (detik < 0) {
                            detik = 59;
                            menit--;

                            if (menit < 0) {
                                menit = 59;
                                jam--;

                                if (jam < 0) {
                                    clearInterval();
                                    var formSoal = document.getElementById('formSoal');
                                    formSoal.submit();
                                }
                            }
                        }
                    }

                    function work() {
                        setTimeout(work, 1000);

                        $.ajax({
                            type: 'GET',
                            url: '<?= base_url('siswa/tes/working') ?>',
                            success: function(data) {
                                if (data == 0) {
                                    var formSoal = document.getElementById('formSoal');
                                    formSoal.submit();
                                }
                            }
                        })
                    }
                    work();
                    hitung();
                });

                function uncertain(nomor) {
                    var jawaban_siswa = $('input:radio[name=jawaban]:checked').val();
                    if (jawaban_siswa == undefined)
                        var jawaban_siswa = $('input:text[name=jawaban]').val();
                    if (jawaban_siswa == undefined)
                        var jawaban_siswa = $('textarea[name=jawaban]').val();
                    if (jawaban_siswa != undefined && jawaban_siswa != '') {
                        var id = $('#soal_id').val();
                        var type_soal = $('#type').val();
                        $.ajax({
                            type: 'POST', //method
                            url: '<?= base_url('siswa/tes/uncertain') ?>', //action
                            data: {
                                soal_id: id,
                                jawaban: jawaban_siswa,
                                type: type_soal
                            }, //data yang dikrim ke action $_POST['id']
                            dataType: 'json',
                            async: false,
                            success: function(data) {
                                console.log(data);
                                if (data) {
                                    setTimeout((funcntion) => {
                                        location.reload();
                                    }, 0001);
                                }
                            }
                        });
                    }
                }

                function answer(nomor) {
                    var jawaban_siswa = $('input:radio[name=jawaban]:checked').val();
                    if (jawaban_siswa == undefined)
                        var jawaban_siswa = $('input:text[name=jawaban]').val();
                    if (jawaban_siswa == undefined)
                        var jawaban_siswa = $('textarea[name=jawaban]').val();
                    if (jawaban_siswa != undefined && jawaban_siswa != '') {
                        var id = $('#soal_id').val();
                        var type_soal = $('#type').val();
                        $.ajax({
                            type: 'POST', //method
                            url: '<?= base_url('siswa/tes/answer') ?>', //action
                            data: {
                                soal_id: id,
                                jawaban: jawaban_siswa,
                                type: type_soal
                            }, //data yang dikrim ke action $_POST['id']
                            dataType: 'json',
                            async: false,
                            success: function(data) {
                                console.log(data);
                                if (data) {
                                    setTimeout((funcntion) => {
                                        location.reload();
                                    }, 0001);
                                }
                            }
                        });
                    }
                }

                function next(nomor) {
                    var uncertain = $('#uncertain').val();
                    var jawaban_siswa = $('input:radio[name=jawaban]:checked').val();
                    if (jawaban_siswa == undefined)
                        var jawaban_siswa = $('input:text[name=jawaban]').val();
                    if (jawaban_siswa == undefined)
                        var jawaban_siswa = $('textarea[name=jawaban]').val();
                    if (jawaban_siswa != undefined && jawaban_siswa != '' && uncertain != '1') {
                        var id = $('#soal_id').val();
                        var type_soal = $('#type').val();
                        $.ajax({
                            type: 'POST', //method
                            url: '<?= base_url('siswa/tes/answer') ?>', //action
                            data: {
                                soal_id: id,
                                jawaban: jawaban_siswa,
                                type: type_soal
                            }, //data yang dikrim ke action $_POST['id']
                            dataType: 'json',
                            async: false,
                            success: function(data) {
                                console.log(data);
                                if (data) {
                                    var btn_number = document.getElementById('soal_' + (nomor + 1));
                                    btn_number.submit();
                                }
                            }
                        });
                    } else {
                        var btn_number = document.getElementById('soal_' + (nomor + 1));
                        btn_number.submit();
                    }
                }

                function back(nomor) {
                    var btn_number = document.getElementById('soal_' + (nomor - 1));
                    btn_number.submit();
                }
            </script>