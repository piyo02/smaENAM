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
    </div>
    <div id="carouselExampleIndicators" class="carousel slide m-5" data-ride="carousel">
        <div class="carousel-inner">
            <?php
            $k = 0;
            for ($i = 0; $i < ceil(count($rows) / 2); $i++) : ?>
                <?php if ($i == 0) : ?>
                    <div class="carousel-item active">
                    <?php else : ?>
                        <div class="carousel-item">
                        <?php endif; ?>
                        <?php
                            echo '<div class="row">';
                            echo '<div class="col-2"></div>';
                            for ($j = 0; $j < 2; $j++) { ?>
                            <div class="col-4">
                                <form action="<?= base_url('siswa/tes') ?>">
                                    <input type="hidden" name="id" value="<?= $rows[$k]->id ?>">
                                    <div class="card card-outline card-success">
                                        <div class="card-header row justify-content-center">
                                            <h3 class="card-title"><?= $rows[$k]->nama; ?></h3>
                                        </div>
                                        <div class="card-body bg-gray-light">
                                            <div class="row">
                                                <div class="col-9">
                                                    <?= $rows[$k]->class; ?>
                                                </div>
                                                <div class="col-3 row justify-content-end">
                                                    <p><?= $rows[$k]->durasi; ?> menit</p>
                                                </div>
                                            </div>
                                            <div class="row justify-content-between">
                                                <button class="btn btn-default">80</button>
                                                <button type="submit" class="btn btn-success">Review</button>
                                            </div>
                                        </div>
                                </form>
                            </div>
                        <?php
                                echo '</div>';
                                $k++;
                                if (!isset($rows[$k]))
                                    break;
                            }
                            ?>
                        </div>
                    </div>
                <?php endfor; ?>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
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