<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= esc($title ?? 'ShopEase') ?> - ShopEase</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
:root {
    --green: #58745f;
    --cream: #f7f6ef;
    --ink: #26352b;
}

body {
    background: var(--cream);
    color: var(--ink);
    font-family: Arial, sans-serif;
}

.navbar {
    background: #fff;
}

.brand {
    font-weight: 800;
    color: var(--green) !important;
    letter-spacing: .3px;
}

.btn-shop {
    background: var(--green);
    color: white;
    border: 0;
}

.btn-shop:hover {
    background: #405a47;
    color: white;
}

.hero {
    background: #e5ede4;
    border-radius: 22px;
    padding: 48px 34px;
}

.product-card {
    border: 0;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 18px #26352b0c;
    height: 100%;
}

.product-img {
    height: 190px;
    object-fit: cover;
    background: #edf0e9;
}

footer {
    color: #778078;
}
</style>
</head>

<body>

<nav class="navbar navbar-expand-lg shadow-sm">
    <div class="container">

        <a class="navbar-brand brand" href="/">ShopEase</a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="nav">

            <ul class="navbar-nav me-auto">

                <li class="nav-item">
                    <a class="nav-link" href="/products">Produk</a>
                </li>

                <?php if (session('logged_in') && session('role') === 'customer'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/cart">Keranjang</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/orders">Pesanan Saya</a>
                    </li>
                <?php endif ?>

                <?php if (session('role') === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin">Dashboard</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/admin/products">Produk</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/admin/categories">Kategori</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/admin/orders">Pesanan</a>
                    </li>
                <?php endif ?>

                <?php if (session('role') === 'operator'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/operator/orders">Pesanan</a>
                    </li>
                <?php endif ?>

            </ul>

            <div class="d-flex align-items-center gap-2">

                <?php if (session('logged_in')): ?>

                    <span class="small">
                        Hai, <?= esc(session('name')) ?>
                    </span>

                    <form action="<?= base_url('logout') ?>" method="post" class="m-0">
                        <?= csrf_field() ?>

                        <button type="submit" class="btn btn-outline-secondary btn-sm">
                            Logout
                        </button>
                    </form>

                <?php else: ?>

                    <a class="btn btn-outline-success btn-sm" href="/login">
                        Login
                    </a>

                    <a class="btn btn-shop btn-sm" href="/register">
                        Daftar
                    </a>

                <?php endif ?>

            </div>

        </div>
    </div>
</nav>

<main class="container py-4">

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif ?>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <?php foreach (session()->getFlashdata('errors') as $e): ?>
                <div><?= esc($e) ?></div>
            <?php endforeach ?>
        </div>
    <?php endif ?>