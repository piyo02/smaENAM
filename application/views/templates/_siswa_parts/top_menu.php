<?php if ($this->uri->segment(2) != 'tes') : ?>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a href="<?= base_url('siswa/') ?>home" class="nav-link btn btn-outline-info ml-2 mr-2">Dashboard</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?= base_url('siswa/') ?>grafik" class="nav-link btn btn-outline-success">Grafik</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('auth/logout') ?>">
                <i class="fas fa-sign-in-alt"></i>
            </a>
        </li>
    </ul>
<?php else : ?>
    <div class="row justify-content-center">
        <div id="timer"></div>
    </div>
<?php endif; ?>
</div>
</nav>