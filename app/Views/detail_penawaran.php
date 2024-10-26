<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="container mt-5">
    <h1>Detail Penawaran</h1>

    <table class="table table-bordered">
        <tr>
            <th>Nomor</th>
            <td><?php echo esc($penawaran['nomor']); ?></td>
        </tr>
        <tr>
            <th>Tanggal</th>
            <td><?php echo esc($penawaran['tanggal']); ?></td>
        </tr>
        <tr>
            <th>Status</th>
            <td><?php echo esc($penawaran['status']); ?></td>
        </tr>
        <tr>
            <th>Kantor Cabang</th>
            <td><?php echo esc($penawaran['kantor_cabang']); ?></td>
        </tr>
        <tr>
            <th>Jenis Penawaran</th>
            <td><?php echo esc($penawaran['jenis_penawaran']); ?></td>
        </tr>
        <tr>
            <th>Limit Penawaran</th>
            <td><?php echo esc($penawaran['limit_penawaran']); ?></td>
        </tr>
        <tr>
            <th>Judul</th>
            <td><?php echo esc($penawaran['judul']); ?></td>
        </tr>
        <tr>
            <th>Keterangan</th>
            <td><?php echo esc($penawaran['keterangan']); ?></td>
        </tr>
        <tr>
            <th>Keterangan Urgency Penawaran</th>
            <td><?php echo esc($penawaran['keterangan_urgency']); ?></td>
        </tr>
        <tr>
            <th>Lampiran</th>
            <td>
                <?php if (!empty($penawaran['lampiran'])): ?>
                    <?php $lampiranArray = explode(',', $penawaran['lampiran']); ?>
                    <?php foreach ($lampiranArray as $lampiran): ?>
                        <a href="<?php echo base_url('uploads/' . trim($lampiran)); ?>" target="_blank"><?php echo esc(trim($lampiran)); ?></a><br>
                    <?php endforeach; ?>
                <?php else: ?>
                    Tidak ada lampiran.
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <th>Komentar Underwriting</th>
            <td>
                <?php echo !empty($penawaran['komentar']) ? esc($penawaran['komentar']) : 'Belum ada komentar.'; ?>
            </td>
        </tr>
    </table>

    <!-- Tombol untuk underwriting -->
    <?php if (session()->get('role') === 'underwriting' && $penawaran['status'] === 'PENDING'): ?>
        <div class="mt-3">
            <form action="<?php echo site_url('penawaran/decision/' . $penawaran['id']); ?>" method="post">
                <div class="form-group">
                    <label for="komentar">Tambahkan Komentar:</label>
                    <textarea class="form-control" id="komentar" name="komentar" rows="3" required></textarea>
                </div>
                <button type="submit" name="decision" value="approve" class="btn btn-success">Approve</button>
                <button type="submit" name="decision" value="reject" class="btn btn-danger">Reject</button>
            </form>
        </div>
    <?php endif; ?>

    <a href="<?php echo site_url('dashboard'); ?>" class="btn btn-secondary mt-3">Kembali ke Dashboard</a>
</div>

<?= $this->endSection() ?>