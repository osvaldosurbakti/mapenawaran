<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<?php
$title = 'Dashboard ' . ucfirst(session()->get('role'));
$header = 'Dashboard ' . ucfirst(session()->get('role'));
$showDashboardLink = false; // Menyembunyikan tautan kembali di dashboard
?>

<h1><?= $header ?></h1>

<?php if (session()->get('isLoggedIn')): ?>
    <p><b>User : <?= esc(session()->get('email')) ?></b></p>

    <?php if (session()->get('role') === 'marketing'): ?>
        <a href="<?= site_url('penawaran/create'); ?>" class="btn btn-primary mb-3">Tambah Penawaran</a>
    <?php endif; ?>

    <!-- Menampilkan pesan sukses dan error -->
    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success">
            <?= esc(session()->getFlashdata('message')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <!-- Form Pencarian -->
    <div class="mb-3">
        <input type="text" id="filterInput" class="form-control" placeholder="Cari Penawaran..." onkeyup="filterTable()">
    </div>

    <!-- Tabel Penawaran -->
    <div class="table-responsive"> <!-- Added for responsiveness -->
        <table class="table table-bordered" id="penawaranTable">
            <thead>
                <tr>
                    <th>Nomor</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Kantor Cabang</th>
                    <th>Jenis Penawaran</th>
                    <th>Limit Penawaran</th>
                    <th>Judul</th>
                    <th>Keterangan</th>
                    <th>Keterangan Urgency Penawaran</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($penawaran)): ?>
                    <?php foreach ($penawaran as $item): ?>
                        <?php
                        // Logika untuk menambahkan kelas CSS berdasarkan keterangan urgency
                        $rowClass = '';
                        if ($item['keterangan_urgency'] === 'TOP URGENT') {
                            $rowClass = 'top-urgent';
                        } elseif ($item['keterangan_urgency'] === 'URGENT') {
                            $rowClass = 'urgent';
                        }
                        ?>
                        <tr class="<?= $rowClass; ?>">
                            <td><?= esc($item['nomor']); ?></td>
                            <td><?= esc($item['tanggal']); ?></td>
                            <td><?= esc($item['status']); ?></td>
                            <td><?= esc($item['kantor_cabang']); ?></td>
                            <td><?= esc($item['jenis_penawaran']); ?></td>
                            <td><?= esc($item['limit_penawaran']); ?></td>
                            <td><?= esc($item['judul']); ?></td>
                            <td><?= esc($item['keterangan']); ?></td>
                            <td><?= esc($item['keterangan_urgency']); ?></td>
                            <td>
                                <a href="<?= site_url('penawaran/detail/' . $item['id']); ?>" class="btn btn-info btn-sm">Detail</a>
                                <?php if (session()->get('role') === 'marketing' && $item['status'] !== 'CANCEL'): ?>
                                    <button class="btn btn-danger btn-sm" onclick="confirmCancel('<?= $item['id']; ?>')">Batal</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="text-center">Tidak ada penawaran ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

<?php else: ?>
    <p>Anda belum login. Silakan <a href="<?= site_url('login'); ?>">login</a>.</p>
<?php endif; ?>

<?= $this->endSection() ?>