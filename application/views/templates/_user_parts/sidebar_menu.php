<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="row" style="width: 100%">
        <div class="col-8">
            <p href="" class="brand-link">
                <?php if ($this->session->userdata('user_image')) : ?>
                    <img src="<?php echo base_url('uploads/users_photo/') . $this->session->userdata('user_image') ?>" class="brand-image img-circle elevation-2" style="opacity: .8" alt="User Image">
                <?php else : ?>
                    <img src="<?php echo base_url('assets/') ?>img/user.png" class="brand-image img-circle elevation-2" style="opacity: .8" alt="User Image">
                <?php endif; ?>
                <span class="brand-text font-weight-light">
                    <?php echo ucwords($this->session->userdata('user_profile_name')) ?>
        </div>
        <div class="col-3">
            <ul class="navbar-nav ml-auto text-right mr-3 mt-2">
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fas fa-angle-down right"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="<?= base_url('user/profile') ?>" class="dropdown-item dropdown-footer">Edit Profil</a>
                    </div>
                </li>
            </ul>
        </div>
        </span>
        </p>
    </div>


    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="" id="menu">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- <li class="nav-item has-treeview menu-open"> -->
                <li class="nav-header">DAFTAR MENU</li>
                <?php
                function print_menus($datas)
                {
                    foreach ($datas as $data) {
                        if ((!$data->status)) continue;

                        if (!empty($data->branch)) {
                            ?>
                            <li class="nav-item has-treeview">
                                <a id="<?php echo $data->list_id ?>" href="#" class="nav-link">
                                    <i class="fas fa-<?php echo $data->icon ?> nav-icon"></i>
                                    <p>
                                        <?php echo $data->name ?>
                                        <i class="fas fa-angle-left right"></i>
                                        <!-- <span></span> -->
                                    </p>
                                </a>
                                <ul class="nav nav-treeview ml-4">
                                    <?php
                                                print_menus($data->branch);
                                                ?>
                                </ul>
                            </li>
                        <?php
                                } else {
                                    ?>
                            <li class="nav-item">
                                <a id="<?php echo $data->list_id ?>" href="<?php echo site_url($data->link) ?>" class="nav-link">
                                    <i class="fas fa-<?php echo $data->icon ?> nav-icon"></i>
                                    <p>
                                        <?php echo $data->name ?>
                                        <span id="<?php echo 'notif_' . $data->list_id ?>" class="right badge badge-danger"></span>
                                    </p>
                                </a>
                            </li>
                <?php
                        }
                    }
                }

                print_menus($_menus);
                ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
        <!-- <div class="">
            <div class="copyright">
                &copy; 2019 <a href="javascript:void(0);">Coreigniter</a>.
            </div>
            <div class="version">
                <b>Version: </b> 0.1
            </div>
        </div> -->
    </div>
    <!-- /.sidebar -->
</aside>


<script type="text/javascript">
    function menuActive(id) {
        id = id.trim();
        // console.log(id);
        // console.log(a = document.getElementById(id.trim()));
        a = document.getElementById(id.trim())
        // // var a =document.getElementById("menu").children[num-1].className="active";
        // var a = document.getElementById(id.trim());
        // console.log(a.parentNode.parentNode);
        a.classList.add("active");
        b = a.parentNode.parentNode.parentNode;
        b.classList.add("menu-open");
        b.children[0].classList.add("active");
        // console.log( b.children[0] );
        // document.getElementById(id).classList.add("active");

    }
</script>