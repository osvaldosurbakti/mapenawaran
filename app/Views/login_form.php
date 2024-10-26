<?= $this->extend('layout') ?>

<?php $this->setVar('isLoginPage', true); ?> <!-- Set isLoginPage ke true -->

<?= $this->section('content') ?>
<div class="container mt-5">
    <h2 class="text-center">Login</h2>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo session()->getFlashdata('error'); ?>
        </div>
    <?php endif; ?>

    <div class="card mx-auto" style="max-width: 400px;">
        <div class="card-header text-center">
            <strong>Underwriting/Marketing</strong>
        </div>
        <div class="card-body">
            <form action="<?php echo site_url('login/authenticate'); ?>" method="post">
                <?= csrf_field(); ?>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" name="email" id="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" name="password" id="password" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
            <p class="text-center mt-3">Don't have an account? <a href="<?php echo site_url('register'); ?>">Register here</a>.</p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>