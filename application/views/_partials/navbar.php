<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="<?= base_url() ?>">CI</a>
    <button
        class="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation"
    >
        <span class="navbar-toggler-icon"></span>
    </button>

    <div
        class="collapse navbar-collapse"
        id="navbarSupportedContent"
    >
        <ul class="navbar-nav mr-auto">
            <?php if(!is_logged_in()) : ?>
            <li class="nav-item <?php
                if (isset($title)) {
                    if ($title === 'home') {
                        echo 'active';
                    }
                }
            ?>">
                <a class="nav-link" href="<?= base_url() ?>">
                    Home <span class="sr-only">(current)</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if(is_logged_in()) : ?>
                <li class="nav-item <?php
                    if (isset($title)) {
                        if ($title === 'dashboard') {
                            echo 'active';
                        }
                    }
                ?>">
                    <a class="nav-link" href="<?= base_url() ?>dash/home">
                        Dashboard <span class="sr-only"></span>
                    </a>
                </li>

                <li class="nav-item dropdown <?php
                    if (isset($title)) {
                        if ($title === 'access') {
                            echo 'active';
                        }
                    }
                ?>">
                    <a
                        class="nav-link dropdown-toggle"
                        href="#"
                        id="navbarDropdown"
                        role="button"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        Access
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <?php if(has_roles('root')) : ?>
                    <a class="dropdown-item" href="<?= base_url() ?>dash/access/root">Root</a>
                    <?php endif; ?>
                    <?php if(has_roles('vendor')) : ?>
                    <a class="dropdown-item" href="<?= base_url() ?>dash/access/vendor">Vendor</a>
                    <?php endif; ?>
                    <?php if(has_roles('admin')) : ?>
                    <a class="dropdown-item" href="<?= base_url() ?>dash/access/admin">Admin</a>
                    <?php endif; ?>
                    <?php if(has_roles('staff')) : ?>
                    <a class="dropdown-item" href="<?= base_url() ?>dash/access/staff">Staff</a>
                    <?php endif; ?>
                    <?php if(has_roles('member')) : ?>
                    <a class="dropdown-item" href="<?= base_url() ?>dash/access/member">Member</a>
                    <?php endif; ?>

                </li>
                <?php if(has_roles(['root', 'vendor', 'admin'])) : ?>
                    <li class="nav-item dropdown <?php
                        if (isset($title)) {
                            if ($title === 'user') {
                                echo 'active';
                            }
                        }
                    ?>">
                        <a
                            class="nav-link dropdown-toggle"
                            href="#"
                            id="navbarDropdown"
                            role="button"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                        >
                            User
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?= base_url() ?>dash/users">List</a>
                        <a class="dropdown-item" href="<?= base_url() ?>dash/users/create">Create</a>
                    </li>
                <?php endif; ?>
                <?php if(has_roles(['root', 'vendor'])) : ?>
                    <li class="nav-item dropdown <?php
                        if (isset($title)) {
                            if ($title === 'company') {
                                echo 'active';
                            }
                        }
                    ?>">
                        <a
                            class="nav-link dropdown-toggle"
                            href="#"
                            id="navbarDropdown"
                            role="button"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                        >
                            Company
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?= base_url() ?>dash/companies">List</a>
                        <a class="dropdown-item" href="<?= base_url() ?>dash/companies/create">Create</a>
                    </li>
                <?php endif; ?>
                <?php if(has_roles('root')) : ?>
                    <li class="nav-item dropdown <?php
                        if (isset($title)) {
                            if ($title === 'role') {
                                echo 'active';
                            }
                        }
                    ?>">
                        <a
                            class="nav-link dropdown-toggle"
                            href="#"
                            id="navbarDropdown"
                            role="button"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                        >
                            Role
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?= base_url() ?>dash/roles">List</a>
                        <a class="dropdown-item" href="<?= base_url() ?>dash/roles/create">Create</a>
                    </li>
                <?php endif; ?>
                <?php if(has_roles('root')) : ?>
                    <li class="nav-item dropdown <?php
                        if (isset($title)) {
                            if ($title === 'permission') {
                                echo 'active';
                            }
                        }
                    ?>">
                        <a
                            class="nav-link dropdown-toggle"
                            href="#"
                            id="navbarDropdown"
                            role="button"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                        >
                            Permission
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?= base_url() ?>dash/permissions">List</a>
                        <a class="dropdown-item" href="<?= base_url() ?>dash/permissions/create">Create</a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
        </ul>
        <div class="my-2 my-lg-0">
            <!-- kalau login status === false se tunjung login dng register button -->
            <?php if(!is_logged_in()) : ?>
                <a class="btn btn-outline-success my-2 my-sm-0" href="<?= base_url() ?>auth/login">Login</a>
                <a class="btn btn-outline-success my-2 my-sm-0" href="<?= base_url() ?>auth/register">Register</a>
            <?php else : ?>
                <a class="btn btn-outline-success my-2 my-sm-0" href="<?= base_url() ?>auth/logout">Logout</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
