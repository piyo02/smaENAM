<div class="content-wrapper bg-dark" style="margin-bottom: 0 !important; min-height: 0 !important">
    <div class="bd-example p-2" style="width:900px; margin: auto;">
        <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleCaptions" data-slide-to="1"></li>
                <li data-target="#carouselExampleCaptions" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="<?= site_url('assets/img/') ?>bg-masthead.jpg" class="d-block w-100 rounded" alt="<?= site_url('assets/img/') ?>bg-masthead.jpg">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>First slide label</h5>
                        <p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="<?= site_url('assets/img/') ?>bg-masthead.jpg" class="d-block w-100 rounded" alt="<?= site_url('assets/img/') ?>bg-masthead.jpg">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Second slide label</h5>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="<?= site_url('assets/img/') ?>bg-masthead.jpg" class="d-block w-100 rounded" alt="<?= site_url('assets/img/') ?>bg-masthead.jpg">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Third slide label</h5>
                        <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur.</p>
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
</div>
</div>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2 mt-4 justify-content-center">
            <h4 class="m-0 text-dark">Daftar Ulangan</h4>
        </div>
        <div class="row  justify-content-center">
            <div class="col-12 row  justify-content-center">
                <div class="col-4">
                    <?php
                    echo $alert;
                    ?>
                </div>
            </div>
            <?php
            for ($i = 0; $i < count($rows); $i++) : ?>
                <div class="col-3 ml-2">
                    <form action="<?= base_url('siswa/tes') ?>" method="post">
                        <input type="hidden" name="id" value="<?= $rows[$i]->id ?>">
                        <div class="card card-outline card-success">
                            <div class="card-header row justify-content-center">
                                <h3 class="card-title"><?= $rows[$i]->nama; ?></h3>
                            </div>
                            <div class="card-body bg-gray-light">
                                <div class="row">
                                    <div class="col-9">
                                        <?= $rows[$i]->class; ?>
                                    </div>
                                    <div class="col-3 row justify-content-end">
                                        <p><?= $rows[$i]->durasi; ?> menit</p>
                                    </div>
                                </div>
                                <div class="row">
                                    Jumlah Soal : <?= $rows[$i]->qty; ?> nomor
                                </div>
                                <div class="row justify-content-between">
                                    <?php if ($rows[$i]->nilai) : ?>
                                        <button class="btn btn-default"><?= $rows[$i]->nilai; ?></button>
                                        <button type='reset' class="btn btn-success">Review</button>
                                    <?php else : ?>
                                        <button type="submit" class="btn btn-primary">Kerjakan</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</div>
</div>
</div>
<div class="wrapper bg-white">
    <div class="content-wrapper" style="margin-bottom:0 !important; min-height:300px !important">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2 justify-content-center">
                    <h4 class="m-0 text-dark text-center">Nilai Rata-rata<br>Ulangan</h4>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row ml-5 mr-5">
                    <div class="col-2"></div>
                    <div class="col-3">
                        <div class="callout callout-warning">
                            <h3 class="text-red">Terendah</h3>
                            <h4 class="row justify-content-end">Matematika</h4>
                            <div class="row justify-content-center">
                                <h1 class="text-red">56</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-2"></div>
                    <div class="col-3">
                        <div class="callout callout-success">
                            <h3 class="text-blue">Tertinggi</h3>
                            <h4 class="row justify-content-end">Bahasa Inggris</h4>
                            <div class="row justify-content-center">
                                <h1 class="text-blue">95</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
</div>