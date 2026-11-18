<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<h1 class="h3 text-gray-800 mb-4">Add New Report</h1>

<div class="card shadow mb-4">
    <div class="card-body">

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <div><?= esc($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('/items/store') ?>"
              method="post"
              enctype="multipart/form-data">

            <?= csrf_field() ?>

            <div class="form-group">
                <label>Item Name</label>
                <input type="text" name="item_name" class="form-control"
                       value="<?= old('item_name') ?>" required>
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category" class="form-control" required>
                    <option value="">Select Category</option>
                    <?php foreach (['Electronics', 'Accessories', 'Documents', 'Bags', 'Clothing', 'Others'] as $category): ?>
                        <option value="<?= esc($category) ?>"
                            <?= old('category') === $category ? 'selected' : '' ?>>
                            <?= esc($category) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3"><?= old('description') ?></textarea>
            </div>

            <div class="form-group">
                <label>Item Image (Optional, max 2 MB)</label>
                <input type="file" name="image" class="form-control-file"
                       accept=".jpg,.jpeg,.png,.webp">
            </div>

            <div class="form-group">
                <label>Location</label>
                <input type="text" name="location" class="form-control"
                       value="<?= old('location') ?>" required>
            </div>

            <div class="form-group">
                <label>Date</label>
                <input type="date" name="date" class="form-control"
                       value="<?= old('date') ?>" required>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control" required>
                    <option value="">Select Status</option>
                    <option value="Lost" <?= old('status') === 'Lost' ? 'selected' : '' ?>>Lost</option>
                    <option value="Found" <?= old('status') === 'Found' ? 'selected' : '' ?>>Found</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Save Report</button>
            <a href="<?= site_url('/items') ?>" class="btn btn-secondary">Cancel</a>

        </form>
    </div>
</div>

<?= $this->endSection() ?>