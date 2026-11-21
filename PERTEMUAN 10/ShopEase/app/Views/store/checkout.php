<?php $total=0; foreach($items as $i) $total += $i['price']*$i['quantity']; ?>
<div class="row g-4"><div class="col-md-7"><div class="card border-0 shadow-sm rounded-4"><div class="card-body p-4"><h4>Alamat Pengiriman</h4>
<form method="post" action="/checkout"><?= csrf_field() ?>
<div class="mb-3"><label class="form-label">Nama penerima</label><input class="form-control" name="recipient_name" required value="<?= old('recipient_name',session('name')) ?>"></div>
<div class="mb-3"><label class="form-label">Nomor telepon</label><input class="form-control" name="phone" required></div>
<div class="mb-3"><label class="form-label">Alamat lengkap</label><textarea class="form-control" name="address" required></textarea></div>
<div class="row"><div class="col-md-6 mb-3"><label class="form-label">Kota</label><input class="form-control" name="city" required></div><div class="col-md-6 mb-3"><label class="form-label">Provinsi</label><input class="form-control" name="province" required></div></div>
<div class="mb-3"><label class="form-label">Kode pos</label><input class="form-control" name="postal_code" required></div>
<button class="btn btn-shop w-100">Buat Pesanan</button></form></div></div></div>
<div class="col-md-5"><div class="card border-0 shadow-sm rounded-4"><div class="card-body p-4"><h5>Ringkasan Pesanan</h5><?php foreach($items as $i): ?><div class="d-flex justify-content-between py-2 border-bottom"><span><?= esc($i['name']) ?> × <?= $i['quantity'] ?></span><b>Rp <?= number_format($i['price']*$i['quantity'],0,',','.') ?></b></div><?php endforeach ?><div class="d-flex justify-content-between mt-3"><b>Total</b><b class="text-success">Rp <?= number_format($total,0,',','.') ?></b></div><small class="text-muted">Ongkos kirim versi demo: Rp 0</small></div></div></div></div>
