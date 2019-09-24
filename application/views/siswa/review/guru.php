<div class="row">
    <div class="row col-12">
        <div class="col-6">
            Soal Nomor <?= $nomor; ?>
        </div>
        <div class="row col-6 justify-content-end">
            <b>Kode Soal : <?= $soal->kode; ?></b>
        </div>
    </div>
    <?php if ($soal->audio != '') : ?>
        <div class="col-12">
            <p><?= $soal->audio; ?></p>
        </div>
    <?php endif; ?>
    <?php if ($soal->gambar != '') : ?>
        <div class="col-12">
            <img src="<?= base_url('uploads/soal/') . $soal->gambar; ?>" alt="" width="200px" height="200px">
        </div>
    <?php endif; ?>
    <div class="col-12">
        <p><?= $soal->text; ?></p>
    </div>
    <div class="col-12">
        <?php
        $opt = ['A', 'B', 'C', 'D', 'E'];
        $i = 0;
        switch ($options[0]->type) {
            case 'teks': ?>
                <?php foreach ($options as $key => $option) : ?>
                    <div class="col-12">
                        <?php if ($option->skor == 1) : ?>
                            <P class="text-success"><?= $opt[$i] . '. ' . $option->jawaban ?></P>
                        <?php elseif ($option->id == $jawaban->jawaban) : ?>
                            <?php if ($option->skor != 1) : ?>
                                <P class="text-danger"><?= $opt[$i] . '. ' . $option->jawaban ?></P>
                            <?php endif; ?>
                        <?php else : ?>
                            <P><?= $opt[$i] . '. ' . $option->jawaban ?></P>
                        <?php endif; ?>
                    </div>
                <?php
                        $i++;
                    endforeach; ?>
            <?php break;
            case 'gambar': ?>
                <div class="row">
                    <?php foreach ($options as $key => $option) : ?>
                        <div class="col-6 mb-2">
                            <?php if ($option->skor == 1) : ?>
                                <P class="text-success"><?= $opt[$i] . '. ' ?><img src="<?= base_url('uploads/soal/') . $option->jawaban ?>" alt="" width="200px" height="200px"></P>
                            <?php elseif ($option->id == $jawaban->jawaban) : ?>
                                <?php if ($option->skor != 1) : ?>
                                    <P class="text-danger"><?= $opt[$i] . '. ' ?><img src="<?= base_url('uploads/soal/') . $option->jawaban ?>" alt="" width="200px" height="200px"></P>
                                <?php endif; ?>
                            <?php else : ?>
                                <P><?= $opt[$i] . '. ' ?><img src="<?= base_url('uploads/soal/') . $option->jawaban ?>" alt="" width="200px" height="200px"></P>
                            <?php endif; ?>
                        </div>
                    <?php
                            $i++;
                        endforeach; ?>
                </div>
            <?php break;
            case 'isian': ?>
                <?php foreach ($options as $key => $option) : ?>
                    <label for="">Jawaban</label>
                    <div class="col-12 mb-4">
                        <input type="text" class="form-control" name="" id="" value="<?= $option->jawaban ?>" readonly>
                    </div>
                <?php endforeach; ?>
                <label for="">Jawaban Siswa</label>
                <div class="col-12">
                    <input type="text" class="form-control" name="" id="" value="<?= $jawaban->jawaban ?>" readonly>
                </div>
            <?php break;
            case 'esai': ?>
                <?php foreach ($options as $key => $option) : ?>
                    <label for="">Jawaban</label>
                    <div class="col-12 mb-4">
                        <textarea class="form-control" name="" id="" cols="30" rows="4"><?= $option->jawaban ?></textarea>
                    </div>
                <?php endforeach; ?>
                <label for="">Jawaban Siswa</label>
                <div class="col-12">
                    <textarea class="form-control" name="" id="" cols="30" rows="4"><?= $jawaban->jawaban ?></textarea>
                </div>
            <?php break;
        }
        ?>
    </div>
    <div class="col-12">
        <div class="mr-4 row justify-content-end">
            <b>Skor : <?= $jawaban->skor; ?></b>
        </div>
        <?php if ($options[0]->type == 'esai') : ?>
            <div class="row justify-content-end">
                <form action="<?= base_url('guru/hasil_ulangan/update/') ?>" method="post">
                    <input type="hidden" name="user_id" value="<?= $id; ?>">
                    <input type="hidden" name="id" value="<?= $this->input->get('id'); ?>">
                    <input type="hidden" name="nomor" value="<?= $this->input->get('nomor'); ?>">
                    <input type="hidden" name="jawaban_id" value="<?= $jawaban->id ?>">
                    <input type="text" name="skor" id="skor" class="form-control">
                    <button type="submit" class="btn btn-success mt-2">Ubah Skor</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>