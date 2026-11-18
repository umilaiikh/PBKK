<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item - Find It</title>
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

    <h1>Report an Item</h1>
    <p>Submit information about a lost or found item.</p>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert-error">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <p><?= esc($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="/items/store" method="post">

        <?= csrf_field() ?>

        <label>Item Name</label>
        <input type="text" name="item_name"
               value="<?= old('item_name') ?>" required>

        <label>Category</label>
        <select name="category" required>
            <option value="">Select Category</option>
            <?php foreach (['Electronics', 'Accessories', 'Documents', 'Bags', 'Clothing', 'Others'] as $category): ?>
                <option value="<?= $category ?>"
                    <?= old('category') == $category ? 'selected' : '' ?>>
                    <?= $category ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Description</label>
        <textarea name="description" rows="4"><?= old('description') ?></textarea>

        <label>Location</label>
        <input type="text" name="location"
               value="<?= old('location') ?>"
               placeholder="Example: Informatics Building" required>

        <label>Date</label>
        <input type="date" name="date"
               value="<?= old('date') ?>" required>

        <label>Status</label>
        <select name="status" required>
            <option value="">Select Status</option>
            <option value="Lost" <?= old('status') == 'Lost' ? 'selected' : '' ?>>Lost</option>
            <option value="Found" <?= old('status') == 'Found' ? 'selected' : '' ?>>Found</option>
        </select>

        <button type="submit" class="submit-btn">
            Submit Report
        </button>

        <a href="/items" class="back-link">Back to Items</a>

    </form>

</section>

</body>
</html>