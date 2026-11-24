<section class="hero mb-4"><div class="row align-items-center"><div class="col-md-8"><span class="badge text-bg-light mb-2">WELCOME TO SHOPEASE</span><h1 class="display-5 fw-bold">Temukan barang favoritmu.</h1><p class="lead mb-0">Pilihan sederhana untuk menemani aktivitas sehari-hari.</p></div><div class="col-md-4 text-md-end mt-3 mt-md-0"><a class="btn btn-shop btn-lg" href="#catalog">Mulai belanja</a></div></div></section>
<div id="catalog" class="d-flex justify-content-between align-items-center mb-3"><div><h3 class="mb-0">Katalog Produk</h3><small class="text-muted">Pilih produk yang kamu suka</small></div></div>
<form class="row g-2 mb-4" method="get" action="/products">
<div class="col-md-6"><input class="form-control" name="q" placeholder="Cari nama produk..." value="<?= esc($keyword) ?>"></div>
<div class="col-md-4"><select class="form-select" name="category"><option value="">Semua kategori</option><?php foreach($categories as $c): ?><option value="<?= $c['id'] ?>" <?= (string)$selectedCategory===(string)$c['id']?'selected':'' ?>><?= esc($c['name']) ?></option><?php endforeach ?></select></div>
<div class="col-md-2 d-grid"><button class="btn btn-shop">Cari</button></div></form>
<div class="row g-4">
<?php foreach($products as $p): ?><div class="col-6 col-md-4 col-lg-3"><div class="card product-card">
<?php if($p['image'] && is_file(FCPATH.'uploads/products/'.$p['image'])): ?><img class="product-img" src="/uploads/products/<?= esc($p['image']) ?>" alt="<?= esc($p['name']) ?>"><?php else: ?><div class="product-img d-flex align-items-center justify-content-center text-muted">ShopEase</div><?php endif ?>
<div class="card-body"><small class="text-muted"><?= esc($p['category_name']) ?></small><h6 class="mt-1"><?= esc($p['name']) ?></h6><div class="fw-bold text-success mb-2">Rp <?= number_format($p['price'],0,',','.') ?></div><small class="text-muted">Stok: <?= (int)$p['stock'] ?></small><a class="btn btn-shop w-100 mt-3" href="/products/<?= $p['id'] ?>">Lihat detail</a></div></div></div><?php endforeach ?>
<?php if(!$products): ?><div class="col-12"><div class="alert alert-light">Produk tidak ditemukan.</div></div><?php endif ?>
</div>
