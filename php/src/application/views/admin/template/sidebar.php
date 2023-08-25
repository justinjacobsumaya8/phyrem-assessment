<div class="d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 280px;">
    <a href="<?php echo base_url('admin/home') ?>" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
        <svg class="bi me-2" width="40" height="32">
            <use xlink:href="#toggles2" />
        </svg>
        <span class="fs-4">Timely</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="<?php echo base_url('admin/home') ?>" class="nav-link <?php echo uri_string() == "admin/home" ? 'active' : 'link-dark' ?>">
                <svg class="bi me-2" width="16" height="16">
                    <use xlink:href="#home" />
                </svg>
                Home
            </a>
        </li>
        <li>
            <a href="<?php echo base_url('admin/time-records') ?>" class="nav-link <?php echo strpos(uri_string(), "admin/time-records") !== false ? 'active' : 'link-dark' ?>">
                <svg class="bi me-2" width="16" height="16">
                    <use xlink:href="#calendar3" />
                </svg>
                Time Records
            </a>
        </li>
        <?php if ($this->session->userdata("auth_user")->user_type == "1") { ?>
            <li>
                <a href="<?php echo base_url('admin/employees') ?>" class="nav-link <?php echo uri_string() == "admin/employees" ? 'active' : 'link-dark' ?>">
                    <svg class="bi me-2" width="16" height="16">
                        <use xlink:href="#grid" />
                    </svg>
                    Employees
                </a>
            </li>
            <li>
                <a href="<?php echo base_url('admin/users') ?>" class="nav-link <?php echo strpos(uri_string(), "admin/users") !== false ? 'active' : 'link-dark' ?>">
                    <svg class="bi me-2" width="16" height="16">
                        <use xlink:href="#people-circle" />
                    </svg>
                    Users
                </a>
            </li>
        <?php } ?>
    </ul>
    <hr>
    <div class="dropdown">
        <a href="javascript:void(0)" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
            <strong><?= $this->session->userdata("auth_user")->user_name ?></strong>
        </a>
        <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2">
            <!-- <li><a class="dropdown-item" href="javascript:void(0)">Profile</a></li>
            <li>
                <hr class="dropdown-divider">
            </li> -->
            <li>
                <?php echo form_open('logout'); ?>
                <button type="submit" class="dropdown-item">Sign out</button>
                <?php echo form_close(); ?>
            </li>
        </ul>
    </div>
</div>