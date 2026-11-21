<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3"><h2>Data Pesanan</h2><a href="/admin" class="btn btn-outline-secondary">Dashboard</a></div>
<div class="card p-3 table-responsive"><table class="table align-middle"><thead><tr><th>Kode</th><th>Pembeli</th><th>Total</th><th>Status</th><th>Ubah Status</th></tr></thead><tbody>
<?php foreach($pesanan as $p): ?><tr><td><?= esc($p['kode_pesanan']) ?><br><small><?= esc($p['created_at']) ?></small></td><td><?= esc($p['nama_pembeli']) ?><br><small><?= esc($p['telepon']) ?></small></td><td>Rp <?= number_format($p['total'],0,',','.') ?></td><td><?= esc($p['status']) ?></td><td>
<form method="post" action="/admin/pesanan/status/<?= $p['id'] ?>" class="d-flex gap-2"><?= csrf_field() ?><select name="status" class="form-select form-select-sm">
<?php foreach(['Menunggu','Diproses','Dikirim','Selesai','Dibatalkan'] as $s): ?><option value="<?= $s ?>" <?= $p['status']===$s?'selected':'' ?>><?= $s ?></option><?php endforeach ?>
</select><button class="btn btn-sm btn-main">Simpan</button></form></td></tr><?php endforeach ?>
<?php if(!$pesanan): ?><tr><td colspan="5">Belum ada pesanan.</td></tr><?php endif ?>
</tbody></table></div>
<?= $this->endSection() ?>
