<?= $this->extend('layout') ?>

<?php $this->setVar('isLoginPage', true); ?> <!-- Set isLoginPage ke true untuk layout minimalis -->

<?= $this->section('content') ?>
<div class="container">
    <h2>Registrasi Pengguna</h2>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('register/create') ?>" method="POST">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="role">Role:</label>
            <select name="role" id="role" class="form-control" required>
                <option value="marketing">Marketing</option>
                <option value="underwriting">Underwriting</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Daftar</button>
    </form>

    <p>Sudah punya akun? <a href="<?= base_url('login') ?>">Login di sini</a></p>
</div>
<?= $this->endSection() ?>