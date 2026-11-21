<?= $this->extend('layouts/dosen') ?>
<?= $this->section('content') ?>

<h3>Input Nilai Mahasiswa</h3>

<div class="card">
    <div class="card-body">
        <p><strong>Mahasiswa:</strong> <?= esc($krs['nama']) ?></p>
        <p><strong>NIM:</strong> <?= esc($krs['nim']) ?></p>
        <p><strong>Mata Kuliah:</strong> <?= esc($krs['nama_mk']) ?></p>

        <form action="/dosen/kelas/nilai/simpan/<?= $krs['id'] ?>"
              method="post">
            <?= csrf_field() ?>

            <input type="hidden" name="matakuliah_id"
                   value="<?= esc($krs['matakuliah_id']) ?>">

            <div class="mb-3">
                <label class="form-label">Nilai Angka (0–100)</label>
                <input type="number"
                       name="nilai_angka"
                       class="form-control"
                       min="0"
                       max="100"
                       step="0.01"
                       value="<?= esc($krs['nilai_angka'] ?? '') ?>"
                       required>
            </div>

            <button type="submit" class="btn btn-primary">
                Simpan Nilai
            </button>

            <a href="/dosen/kelas/peserta/<?= $krs['matakuliah_id'] ?>"
               class="btn btn-secondary">
                Kembali
            </a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>