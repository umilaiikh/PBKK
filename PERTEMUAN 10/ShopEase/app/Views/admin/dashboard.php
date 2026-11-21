
<h3 class="mb-4">Dashboard Administrator</h3>

<div class="row g-3">
    <?php foreach ([
        ['Total Produk', $productCount],
        ['Total Pesanan', $orderCount],
        ['Customer', $customerCount],
        ['Penjualan', 'Rp ' . number_format((float) $sales, 0, ',', '.')]
    ] as $stat): ?>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <small class="text-muted"><?= esc($stat[0]) ?></small>
                    <h4 class="mt-2 mb-0"><?= esc($stat[1]) ?></h4>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="mt-4 d-flex flex-wrap gap-2">
    <a class="btn btn-shop" href="/admin/products">Kelola Produk</a>
    <a class="btn btn-outline-success" href="/admin/categories">Kelola Kategori</a>
    <a class="btn btn-outline-success" href="/admin/orders">Kelola Pesanan</a>
</div>