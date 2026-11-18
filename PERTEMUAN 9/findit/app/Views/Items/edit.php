<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item - Find It</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="logo">Find<span>It.</span></div>
    <div class="nav-links">
        <a href="/">Home</a>
        <a href="/items">Lost & Found</a>
    </div>
</nav>

<section class="form-section">

    <h1>Edit Item</h1>
    <p>Update the item information below.</p>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert-error">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <p><?= esc($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="/items/update/<?= $item['id'] ?>" method="post">

        <?= csrf_field() ?>

        <label>Item Name</label>
        <input type="text" name="item_name"
               value="<?= esc(old('item_name', $item['item_name'])) ?>"
               required>

        <label>Category</label>
        <select name="category" required>
            <?php foreach (['Electronics', 'Accessories', 'Documents', 'Bags', 'Clothing', 'Others'] as $category): ?>
                <option value="<?= $category ?>"
                    <?= old('category', $item['category']) == $category ? 'selected' : '' ?>>
                    <?= $category ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Description</label>
        <textarea name="description" rows="4"><?= esc(old('description', $item['description'])) ?></textarea>

        <label>Location</label>
        <input type="text" name="location"
               value="<?= esc(old('location', $item['location'])) ?>"
               required>

        <label>Date</label>
        <input type="date" name="date"
               value="<?= esc(old('date', $item['date'])) ?>"
               required>

        <label>Status</label>
        <select name="status" required>
            <option value="Lost" <?= old('status', $item['status']) == 'Lost' ? 'selected' : '' ?>>Lost</option>
            <option value="Found" <?= old('status', $item['status']) == 'Found' ? 'selected' : '' ?>>Found</option>
        </select>

        <button type="submit" class="submit-btn">
            Update Item
        </button>

        <a href="/items" class="back-link">Cancel</a>

    </form>

</section>

</body>
</html>