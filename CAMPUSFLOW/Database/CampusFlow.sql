SET NOCOUNT ON;
GO

IF DB_ID('CampusFlow_DB') IS NULL
BEGIN
    PRINT 'Membuat database CampusFlow_DB...';
    CREATE DATABASE CampusFlow_DB;
END
GO

USE CampusFlow_DB;
GO

/* =================================================================
   1. TABEL
   ================================================================= */

/* 1.1 MataKuliah */
IF OBJECT_ID('dbo.MataKuliah', 'U') IS NULL
BEGIN
    CREATE TABLE dbo.MataKuliah (
        MataKuliahId INT IDENTITY(1,1) NOT NULL CONSTRAINT PK_MataKuliah PRIMARY KEY,
        Kode         NVARCHAR(20)  NOT NULL,
        Nama         NVARCHAR(200) NOT NULL,
        SKS          INT           NOT NULL
    );
END
GO

/* 1.2 Tugas */
IF OBJECT_ID('dbo.Tugas', 'U') IS NULL
BEGIN
    CREATE TABLE dbo.Tugas (
        TugasId      INT IDENTITY(1,1) NOT NULL CONSTRAINT PK_Tugas PRIMARY KEY,
        MataKuliahId INT            NULL,
        Judul        NVARCHAR(300)  NOT NULL,
        Deskripsi    NVARCHAR(1000) NULL,
        Deadline     DATETIME2(0)   NOT NULL,
        Prioritas    NVARCHAR(20)   NOT NULL,
        Status       NVARCHAR(40)   NOT NULL,
        CONSTRAINT FK_Tugas_MataKuliah
            FOREIGN KEY (MataKuliahId) REFERENCES dbo.MataKuliah(MataKuliahId),
        CONSTRAINT CK_Tugas_Prioritas
            CHECK (Prioritas IN ('Rendah', 'Sedang', 'Tinggi')),
        CONSTRAINT CK_Tugas_Status
            CHECK (Status IN ('Belum Dikerjakan', 'Dalam Proses', 'Selesai'))
    );
END
GO

/* 1.3 JadwalKuliah */
IF OBJECT_ID('dbo.JadwalKuliah', 'U') IS NULL
BEGIN
    CREATE TABLE dbo.JadwalKuliah (
        JadwalId     INT IDENTITY(1,1) NOT NULL CONSTRAINT PK_JadwalKuliah PRIMARY KEY,
        MataKuliahId INT          NOT NULL,
        Hari         NVARCHAR(10) NOT NULL,
        WaktuMulai   TIME(0)      NOT NULL,
        WaktuSelesai TIME(0)      NOT NULL,
        Ruang        NVARCHAR(50) NOT NULL,
        CONSTRAINT FK_JadwalKuliah_MataKuliah
            FOREIGN KEY (MataKuliahId) REFERENCES dbo.MataKuliah(MataKuliahId),
        CONSTRAINT CK_JadwalKuliah_Waktu
            CHECK (WaktuSelesai > WaktuMulai)
    );
END
GO

/* 1.4 Kegiatan */
IF OBJECT_ID('dbo.Kegiatan', 'U') IS NULL
BEGIN
    CREATE TABLE dbo.Kegiatan (
        KegiatanId   INT IDENTITY(1,1) NOT NULL CONSTRAINT PK_Kegiatan PRIMARY KEY,
        Judul        NVARCHAR(200) NOT NULL,
        Kategori     NVARCHAR(50)  NOT NULL,
        Tanggal      DATE          NOT NULL,
        WaktuMulai   TIME(0)       NOT NULL,
        WaktuSelesai TIME(0)       NOT NULL,
        Lokasi       NVARCHAR(100) NULL,
        Catatan      NVARCHAR(500) NULL,
        CONSTRAINT CK_Kegiatan_Waktu
            CHECK (WaktuSelesai > WaktuMulai)
    );
END
GO

/* =================================================================
   2. BERSIHKAN DATA LAMA
   ================================================================= */
IF EXISTS (SELECT 1 FROM dbo.MataKuliah WHERE Kode IN ('IF101', 'IF102', 'IF103'))
BEGIN
    PRINT 'Membersihkan data lama (IF101/IF102/IF103)...';

    DELETE FROM dbo.Tugas
    WHERE MataKuliahId IN (SELECT MataKuliahId FROM dbo.MataKuliah WHERE Kode IN ('IF101', 'IF102', 'IF103'));

    DELETE FROM dbo.JadwalKuliah
    WHERE MataKuliahId IN (SELECT MataKuliahId FROM dbo.MataKuliah WHERE Kode IN ('IF101', 'IF102', 'IF103'));

    DELETE FROM dbo.MataKuliah WHERE Kode IN ('IF101', 'IF102', 'IF103');
END
GO

/* =================================================================
   3. SEED MATA KULIAH
   ================================================================= */
INSERT INTO dbo.MataKuliah (Kode, Nama, SKS)
SELECT v.Kode, v.Nama, v.SKS
FROM (VALUES
    ('IF401', N'Rekayasa Sistem Berbasis Pengetahuan', 3),
    ('IF402', N'Grafika Komputer',                     3),
    ('IF403', N'Pemodelan dan Simulasi',               3),
    ('IF404', N'Pemrograman Berbasis Kerangka Kerja',  3),
    ('IF405', N'Keamanan Informasi',                   3)
) AS v(Kode, Nama, SKS)
WHERE NOT EXISTS (SELECT 1 FROM dbo.MataKuliah m WHERE m.Kode = v.Kode);
GO

/* =================================================================
   4. SEED JADWAL KULIAH
   ================================================================= */
IF NOT EXISTS (SELECT 1 FROM dbo.JadwalKuliah)
BEGIN
    INSERT INTO dbo.JadwalKuliah (MataKuliahId, Hari, WaktuMulai, WaktuSelesai, Ruang)
    SELECT m.MataKuliahId, v.Hari, v.Mulai, v.Selesai, v.Ruang
    FROM (VALUES
        ('IF401', 'Senin',  '08:00', '10:30', 'IF101'),
        ('IF402', 'Senin',  '13:00', '15:30', 'LP1'),
        ('IF403', 'Selasa', '08:00', '10:30', 'IF102'),
        ('IF404', 'Selasa', '13:00', '15:30', 'LP2'),
        ('IF405', 'Rabu',   '08:00', '10:30', 'IF103'),
        ('IF401', 'Rabu',   '13:00', '15:30', 'IF104'),
        ('IF402', 'Kamis',  '08:00', '10:30', 'IF105'),
        ('IF403', 'Kamis',  '13:00', '15:30', 'IF107'),
        ('IF404', 'Jumat',  '08:00', '10:30', 'IF108')
    ) AS v(Kode, Hari, Mulai, Selesai, Ruang)
    JOIN dbo.MataKuliah m ON m.Kode = v.Kode;
END
GO

/* =================================================================
   5. SEED TUGAS
   ================================================================= */
IF NOT EXISTS (SELECT 1 FROM dbo.Tugas)
BEGIN
    INSERT INTO dbo.Tugas (MataKuliahId, Judul, Deskripsi, Deadline, Prioritas, Status)
    SELECT m.MataKuliahId, v.Judul, v.Deskripsi,
           DATEADD(DAY, v.OffsetHari, CAST(GETDATE() AS DATE)), v.Prioritas, v.Status
    FROM (VALUES
        ('IF401', N'Makalah Sistem Pakar',   N'Kajian penerapan sistem pakar', 3,  N'Tinggi', N'Belum Dikerjakan'),
        ('IF402', N'Tugas Render 3D',        N'Latihan transformasi 3D',       7,  N'Sedang', N'Dalam Proses'),
        ('IF403', N'Simulasi Antrian',       N'Model simulasi sistem antrian', 10, N'Rendah', N'Belum Dikerjakan'),
        ('IF404', N'Proyek Framework',       N'CRUD dengan framework pilihan', 5,  N'Tinggi', N'Belum Dikerjakan'),
        ('IF405', N'Laporan Audit Keamanan', N'Analisis celah keamanan',       14, N'Sedang', N'Belum Dikerjakan')
    ) AS v(Kode, Judul, Deskripsi, OffsetHari, Prioritas, Status)
    JOIN dbo.MataKuliah m ON m.Kode = v.Kode;
END
GO

/* =================================================================
   6. SEED KEGIATAN
   ================================================================= */
IF NOT EXISTS (SELECT 1 FROM dbo.Kegiatan)
BEGIN
    INSERT INTO dbo.Kegiatan (Judul, Kategori, Tanggal, WaktuMulai, WaktuSelesai, Lokasi, Catatan)
    VALUES
        (N'Rapat Divisi Acara',      N'Organisasi',      CAST(GETDATE() AS DATE),
         '16:00', '18:00', N'Sekretariat BEM', N'Persiapan seminar'),
        (N'Belajar Mandiri Grafika', N'Belajar Mandiri', DATEADD(DAY, 2, CAST(GETDATE() AS DATE)),
         '19:00', '21:00', N'Perpustakaan', N'Latihan rendering'),
        (N'Futsal Rutin',            N'Pribadi',         DATEADD(DAY, 3, CAST(GETDATE() AS DATE)),
         '17:00', '19:00', N'GOR Kampus', N'Bawa sepatu');
END
GO

/* =================================================================
   7. VERIFIKASI
   ================================================================= */
PRINT '==============================================';
PRINT 'CampusFlow database siap.';
PRINT '==============================================';

SELECT 'MataKuliah'   AS Tabel, COUNT(*) AS JumlahBaris FROM dbo.MataKuliah
UNION ALL
SELECT 'JadwalKuliah', COUNT(*) FROM dbo.JadwalKuliah
UNION ALL
SELECT 'Tugas',        COUNT(*) FROM dbo.Tugas
UNION ALL
SELECT 'Kegiatan',     COUNT(*) FROM dbo.Kegiatan;
GO
