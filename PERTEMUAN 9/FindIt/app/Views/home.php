<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<section class="bg-light py-5">
    <div class="container py-5 text-center">

        <h1 class="display-4 fw-bold text-primary">
            Lost it? Find it.
        </h1>

        <p class="lead text-muted mt-3">
            A campus Lost & Found platform to report lost items
            and help reunite belongings with their owners.
        </p>

        <div class="mt-4">
            <a href="<?= site_url('/items') ?>"
               class="btn btn-primary btn-lg me-2">
                Explore Items
            </a>

            <?php if (session()->get('logged_in')): ?>
                <a href="<?= site_url('/items/create') ?>"
                   class="btn btn-outline-primary btn-lg">
                    Report an Item
                </a>
            <?php else: ?>
                <a href="<?= site_url('/login') ?>"
                   class="btn btn-outline-primary btn-lg">
                    Login to Report
                </a>
            <?php endif; ?>
        </div>

    </div>
</section>

<section class="container py-5">
    <div class="row text-center g-4">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 p-4">
                <h4 class="text-primary">Report</h4>
                <p class="text-muted">Report lost or found belongings on campus.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 p-4">
                <h4 class="text-primary">Discover</h4>
                <p class="text-muted">Browse item reports to find missing belongings.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 p-4">
                <h4 class="text-primary">Reconnect</h4>
                <p class="text-muted">Help belongings return to their owners.</p>
            </div>
        </div>

    </div>
</section>

<?= $this->endSection() ?>