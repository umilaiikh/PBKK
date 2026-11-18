<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost & Found - Find It</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="logo">Find<span>It.</span></div>
    <div class="nav-links">
        <a href="/">Home</a>
        <a href="/items">Lost & Found</a>
    </div>
    <a href="/items/create" class="login-btn">+ Add Item</a>
</nav>

<section class="items-section">

    <h1>Lost & Found Items</h1>
    <p>Find what you've lost or help return something to its owner.</p>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert-success">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <div class="item-container">

        <?php if (empty($items)): ?>

            <p class="empty-message">
                No items have been reported yet.
            </p>

        <?php else: ?>

            <?php foreach ($items as $item): ?>

                <div class="item-card">

                    <span class="status <?= strtolower(esc($item['status'])) ?>">
                        <?= esc($item['status']) ?>
                    </span>

                    <h3><?= esc($item['item_name']) ?></h3>

                    <p><strong>Category:</strong>
                        <?= esc($item['category']) ?>
                    </p>

                    <p><strong>Location:</strong>
                        <?= esc($item['location']) ?>
                    </p>

                    <p><strong>Date:</strong>
                        <?= esc($item['date']) ?>
                    </p>

                    <p><?= esc($item['description']) ?></p>

                    <div class="card-actions">
                        <a href="/items/edit/<?= $item['id'] ?>"
                           class="edit-btn">Edit</a>

                        <form action="/items/delete/<?= $item['id'] ?>"
                              method="post"
                              onsubmit="return confirm('Delete this item?')">

                            <?= csrf_field() ?>

                            <button type="submit" class="delete-btn">
                                Delete
                            </button>
                        </form>
                    </div>

                </div>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>
</section>

<footer>
    <p>© 2026 Find It. Lost and Found Platform.</p>
</footer>

</body>
</html>