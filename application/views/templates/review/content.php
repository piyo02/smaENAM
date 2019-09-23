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
                                <div class="col-10">
                                    <div class="row">
                                        <?php $i = 1;
                                        foreach ($quests as $key => $quest) : ?>
                                            <form action="" method="get">
                                                <input type="hidden" name="id" value="<?= $quest->soal_id ?>">
                                                <input type="hidden" name="nomor" value="<?= $i ?>">
                                                <button class="mr-2 mb-2 btn btn-sm btn-primary"><?= $i++; ?></button>
                                            </form>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="float-right">
                                        <?php echo (isset($header_button)) ? $header_button : '';  ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body table-responsive">
                            <!--  -->
                            <?php echo (isset($contents)) ? $contents : '';  ?>
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