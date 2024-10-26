<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<?php
$title = 'History ' . ucfirst(session()->get('role'));
$header = 'History ' . ucfirst(session()->get('role'));
$showDashboardLink = true; // Menampilkan tautan kembali di history
?>

<h1><?= esc($header) ?></h1>

<?php if ($showDashboardLink): ?>
    <a href="<?= site_url('dashboard'); ?>" class="btn btn-primary mb-3">Kembali ke Dashboard</a>
<?php endif; ?>

<!-- Input filter -->
<div class="mb-3">
    <input type="text" id="filterInput" class="form-control" placeholder="Filter Penawaran..." onkeyup="filterTable()">
</div>
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
                <th>Komentar</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($history)): ?> <!-- Pastikan memeriksa 'history' -->
                <?php foreach ($history as $item): ?>
                    <tr>
                        <td><?= esc($item['nomor']); ?></td>
                        <td><?= esc($item['tanggal']); ?></td>
                        <td><?= esc($item['status']); ?></td>
                        <td><?= esc($item['kantor_cabang']); ?></td>
                        <td><?= esc($item['jenis_penawaran']); ?></td>
                        <td><?= esc($item['limit_penawaran']); ?></td>
                        <td><?= esc($item['judul']); ?></td>
                        <td><?= esc($item['keterangan']); ?></td>
                        <td><?= esc($item['keterangan_urgency']); ?></td>
                        <td><?= esc($item['komentar']); ?></td>
                        <td>
                            <a href="<?= site_url('penawaran/detail/' . $item['id']); ?>" class="btn btn-info btn-sm">Detail</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="11" class="text-center">Tidak ada penawaran dalam riwayat.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


<?= $this->endSection() ?>