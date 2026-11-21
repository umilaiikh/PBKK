<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<h2 class="fw-bold mb-3">Keranjang Belanja</h2>
<?php if (!$items): ?><div class="card p-4"><p>Keranjang masih kosong.</p><a href="/" class="btn btn-main align-self-start">Mulai Belanja</a></div>
<?php else: ?>
<form method="post" action="/keranjang/update"><?= csrf_field() ?>
<div class="card p-3"><div class="table-responsive"><table class="table align-middle">
<thead><tr><th>Barang</th><th>Harga</th><th style="width:130px">Jumlah</th><th>Subtotal</th><th></th></tr></thead><tbody>
<?php foreach($items as $i): ?><tr><td><?= esc($i['nama']) ?></td><td>Rp <?= number_format($i['harga'],0,',','.') ?></td>
<td><input class="form-control" type="number" min="0" max="<?= $i['stok'] ?>" name="jumlah[<?= $i['id'] ?>]" value="<?= $i['jumlah'] ?>"></td>
<td>Rp <?= number_format($i['subtotal'],0,',','.') ?></td><td><button formaction="/keranjang/hapus/<?= $i['id'] ?>" formmethod="post" class="btn btn-sm btn-outline-danger">Hapus</button></td></tr><?php endforeach ?>
</tbody></table></div>
<div class="d-flex justify-content-between align-items-center"><button class="btn btn-outline-secondary">Perbarui Keranjang</button><h5>Total: <span class="price">Rp <?= number_format($total,0,',','.') ?></span></h5></div>
</div></form>
<div class="text-end mt-3"><a href="/" class="btn btn-outline-secondary">Lanjut Belanja</a> <a href="/checkout" class="btn btn-main">Checkout</a></div>
<?php endif ?>
<?= $this->endSection() ?>
