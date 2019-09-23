        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
            </li>
            <?php if ($this->session->identity == null) : ?>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="<?= base_url('auth/') ?>register" class="nav-link">Register</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="<?= base_url('auth/') ?>login" class="btn btn-outline-primary nav-link">Login</a>
                </li>
            <?php else : ?>
                <li class="nav-item d-none d-sm-inline-block">
                    <?php
                        switch ($this->session->userdata('group_id')):
                            case 1:
                                $url =  site_url('admin');
                                break;
                            case 2:
                                $url =  site_url('user');
                                break;
                            case 3:
                                $url =  site_url('siswa/home');
                                break;
                        endswitch;
                        ?>
                        <a href="<?= $url ?>" class="btn btn-default nav-link">Dashboard</a>
                </li>
            <?php endif; ?>
        </ul>
        </div>
        </nav>