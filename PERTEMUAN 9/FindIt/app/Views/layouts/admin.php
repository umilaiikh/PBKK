<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Find It Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/startbootstrap-sb-admin-2@4.1.4/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<body id="page-top">

<div id="wrapper">

    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion">

        <a class="sidebar-brand d-flex align-items-center justify-content-center"
           href="<?= site_url('/items') ?>">
            <div class="sidebar-brand-text mx-3">Find It</div>
        </a>

        <hr class="sidebar-divider my-0">

        <li class="nav-item active">
            <a class="nav-link" href="<?= site_url('/items') ?>">
                <i class="fas fa-fw fa-list"></i>
                <span>Item Reports</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="<?= site_url('/items/create') ?>">
                <i class="fas fa-fw fa-plus"></i>
                <span>Add Report</span>
            </a>
        </li>

        <hr class="sidebar-divider">

        <li class="nav-item">
            <a class="nav-link" href="<?= site_url('/') ?>">
                <i class="fas fa-fw fa-home"></i>
                <span>Public Home</span>
            </a>
        </li>

    </ul>

    <div id="content-wrapper" class="d-flex flex-column">

        <div id="content">

            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                <span class="font-weight-bold text-primary">
                    Campus Lost & Found Management
                </span>

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <span class="nav-link">
                            <?= esc(session()->get('username')) ?>
                            (<?= esc(session()->get('role')) ?>)
                        </span>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-danger" href="<?= site_url('/logout') ?>">
                            Logout
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="container-fluid">

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <?= esc(session()->getFlashdata('success')) ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= esc(session()->getFlashdata('error')) ?>
                    </div>
                <?php endif; ?>

                <?= $this->renderSection('content') ?>

            </div>
        </div>

        <footer class="sticky-footer bg-white">
            <div class="container my-auto text-center">
                <span>© <?= date('Y') ?> Find It</span>
            </div>
        </footer>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/startbootstrap-sb-admin-2@4.1.4/js/sb-admin-2.min.js"></script>

</body>
</html>