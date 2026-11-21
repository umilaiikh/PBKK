<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3"><h2>Data Barang</h2><a href="/admin" class="btn btn-outline-secondary">Dashboard</a></div>
<a href="/admin/barang/tambah" class="btn btn-main mb-3">+ Tambah Barang</a>
<div class="card p-3 table-responsive"><table class="table align-middle"><thead><tr><th>Nama</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr></thead><tbody>
<?php foreach($barang as $b): ?><tr><td><?= esc($b['nama']) ?></td><td>Rp <?= number_format($b['harga'],0,',','.') ?></td><td><?= $b['stok'] ?></td><td><a class="btn btn-sm btn-outline-primary" href="/admin/barang/edit/<?= $b['id'] ?>">Edit</a>
<form class="d-inline" method="post" action="/admin/barang/hapus/<?= $b['id'] ?>" onsubmit="return confirm('Hapus barang ini?')"><?= csrf_field() ?><button class="btn btn-sm btn-outline-danger">Hapus</button></form></td></tr><?php endforeach ?>
<?php if(!$barang): ?><tr><td colspan="4">Belum ada data barang.</td></tr><?php endif ?>
</tbody></table></div>
<?= $this->endSection() ?>
