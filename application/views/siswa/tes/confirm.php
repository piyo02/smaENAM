<div class="content-wrapper">
    <div class="row justify-content-center" style="width: 100%">
        <div class="mt-5 col-6">
            <div class="card card-widget widget-user">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-info">
                    <h3 class="widget-user-username"><?= $ulangan->nama; ?></h3>
                    <h5 class="widget-user-desc">
                        <?php echo ucwords($this->session->userdata('user_profile_name')) ?>
                    </h5>
                </div>
                <div class="widget-user-image">
                    <?php if ($this->session->userdata('user_image')) : ?>
                        <img src="<?php echo base_url('uploads/users_photo/') . $this->session->userdata('user_image') ?>" class="brand-image img-circle elevation-2" style="opacity: .8" alt="User Image">
                    <?php else : ?>
                        <img src="<?php echo base_url('assets/') ?>img/user.png" class="brand-image elevation-2" style="opacity: .8" alt="User Image">
                    <?php endif; ?>
                    <!-- <img class="img-circle elevation-2" src="../dist/img/user1-128x128.jpg" alt="User Avatar"> -->
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header"><?= $nilai['jumlah']; ?></h5>
                                <span class="description-text">Jumlah Soal</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header"><?= $nilai['benar']; ?></h5>
                                <span class="description-text">Benar</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4">
                            <div class="description-block">
                                <h5 class="description-header"><?= $nilai['nilai']; ?></h5>
                                <span class="description-text">Nilai Sementara</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center" style="width: 100%">
        <a href="<?= base_url('siswa/home') ?>" class="btn btn-success">Kembali</a>
    </div>
</div>