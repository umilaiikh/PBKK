<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 text-gray-800">Item Reports</h1>
        <p class="mb-0 text-muted">Manage lost and found reports.</p>
    </div>

    <a href="<?= site_url('/items/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Report
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">All Reports</h6>
    </div>

    <div class="card-body">

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

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Image</th>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Location</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th width="150">Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($items)): ?>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($item['image'])): ?>
                                        <img
                                            src="<?= base_url('uploads/items/' . rawurlencode($item['image'])) ?>"
                                            alt="Item image"
                                            style="width:65px;height:55px;object-fit:cover;"
                                        >
                                    <?php else: ?>
                                        <span class="text-muted">No image</span>
                                    <?php endif; ?>
                                </td>

                                <td><?= esc($item['item_name']) ?></td>
                                <td><?= esc($item['category']) ?></td>
                                <td><?= esc($item['location']) ?></td>
                                <td><?= esc($item['date']) ?></td>

                                <td>
                                    <?php if ($item['status'] === 'Lost'): ?>
                                        <span class="badge badge-danger">Lost</span>
                                    <?php else: ?>
                                        <span class="badge badge-success">Found</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <a href="<?= site_url('items/edit/' . $item['id']) ?>"
                                       class="btn btn-warning btn-sm">
                                        Edit
                                    </a>

                                    <?php if (session()->get('role') === 'Admin'): ?>
                                        <form
                                            action="<?= site_url('items/delete/' . $item['id']) ?>"
                                            method="post"
                                            class="d-inline"
                                            onsubmit="return confirm('Delete this report?')"
                                        >
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                Delete
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                No reports available.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?= $this->endSection() ?>