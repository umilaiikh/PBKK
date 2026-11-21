<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4"><div><h2>Dashboard Admin</h2><p class="text-secondary mb-0">Halo, <?= esc(session()->get('admin_nama')) ?></p></div><a href="/admin/logout" class="btn btn-outline-danger">Logout</a></div>
<div class="row g-3 mb-4">
<?php foreach ([['Total Barang',$jumlahBarang],['Total Pesanan',$jumlahPesanan],['Pesanan Menunggu',$pesananBaru],['Total Penjualan','Rp '.number_format($totalPenjualan,0,',','.')] ] as $s): ?>
<div class="col-md-3"><div class="card p-4"><div class="text-secondary"><?= $s[0] ?></div><h3 class="fw-bold"><?= $s[1] ?></h3></div></div>
<?php endforeach ?>
</div>
<div class="d-flex gap-2"><a href="/admin/barang" class="btn btn-main">Kelola Barang</a><a href="/admin/pesanan" class="btn btn-outline-secondary">Kelola Pesanan</a></div>
<?= $this->endSection() ?>
