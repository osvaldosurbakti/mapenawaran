<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="container">
    <h1>Tambah Penawaran</h1>

    <form action="<?php echo site_url('penawaran/store'); ?>" method="POST" enctype="multipart/form-data">

        <div class="form-group">
            <label for="nomor">Nomor</label>
            <input type="text" id="nomor" name="nomor" class="form-control" value="<?php echo $nomor; ?>" readonly>
        </div>

        <div class="form-group">
            <label for="tanggal">Tanggal</label>
            <input type="date" id="tanggal" name="tanggal" class="form-control" value="<?php echo date('Y-m-d'); ?>" readonly>
        </div>

        <div class="form-group">
            <label for="kantor_cabang">Kantor Cabang</label>
            <select id="kantor_cabang" name="kantor_cabang" class="form-control">
                <option value="SBY1">SBY1</option>
                <option value="SBY2">SBY2</option>
            </select>
        </div>

        <div class="form-group">
            <label for="jenis_penawaran">Jenis Penawaran</label>
            <select id="jenis_penawaran" name="jenis_penawaran" class="form-control">
                <option value="AUTO">AUTO</option>
                <option value="CARGO">CARGO</option>
                <option value="HVC">HVC</option>
                <option value="FIRE">FIRE</option>
                <option value="PAR">PAR</option>
                <option value="IAR">IAR</option>
                <option value="MI">MI</option>
            </select>
        </div>

        <div class="form-group">
            <label for="limit_penawaran">Limit Penawaran</label>
            <select id="limit_penawaran" name="limit_penawaran" class="form-control">
                <option value="Rp &lt;1M">Rp &lt;1M</option>
                <option value="Rp >1M">Rp >1M</option>
                <option value="Rp >10M">Rp >10M</option>
                <option value="Rp >100M">Rp >100M</option>
                <option value="Rp >250M">Rp >250M</option>
            </select>
        </div>

        <div class="form-group">
            <label for="keterangan_urgency">Keterangan Urgency Penawaran</label>
            <select id="keterangan_urgency" name="keterangan_urgency" class="form-control">
                <option value="NORMAL">NORMAL</option>
                <option value="URGENT">URGENT</option>
                <option value="TOP URGENT">TOP URGENT</option>
            </select>
        </div>

        <!-- Kolom untuk Judul -->
        <div class="form-group">
            <label for="judul">Judul Penawaran</label>
            <input type="text" id="judul" name="judul" class="form-control" required>
        </div>

        <!-- Kolom untuk Keterangan -->
        <div class="form-group">
            <label for="keterangan">Keterangan Penawaran</label>
            <textarea id="keterangan" name="keterangan" class="form-control" rows="3" required></textarea>
        </div>

        <div class="form-group">
            <label for="lampiran">Lampiran File Penawaran</label>
            <input type="file" id="lampiran" name="lampiran[]" class="form-control" multiple>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Penawaran</button>
    </form>

    <p><a href="<?php echo site_url('dashboard'); ?>" class="btn btn-secondary mt-3">Kembali ke Dashboard</a></p>
</div>

<?= $this->endSection() ?>