namespace CampusFlow.Models;

public class MataKuliah
{
    public int Id { get; set; }
    public string Kode { get; set; } = "";
    public string Nama { get; set; } = "";
    public int SKS { get; set; }

    public string Display => string.IsNullOrWhiteSpace(Kode) ? Nama : $"{Kode} - {Nama}";
}

public class Tugas
{
    public int Id { get; set; }
    public int? MataKuliahId { get; set; }
    public string MataKuliahNama { get; set; } = "";
    public string Judul { get; set; } = "";
    public string? Deskripsi { get; set; }
    public DateTime Deadline { get; set; }
    public string Prioritas { get; set; } = "Sedang";
    public string Status { get; set; } = "Belum Dikerjakan";
}

public class JadwalKuliah
{
    public int Id { get; set; }
    public int MataKuliahId { get; set; }
    public string MataKuliahNama { get; set; } = "";
    public string Hari { get; set; } = "";
    public TimeSpan WaktuMulai { get; set; }
    public TimeSpan WaktuSelesai { get; set; }
    public string Ruang { get; set; } = "";

    public string Waktu => $"{WaktuMulai:hh\\:mm} - {WaktuSelesai:hh\\:mm}";
}

public class Kegiatan
{
    public int Id { get; set; }
    public string Judul { get; set; } = "";
    public string Kategori { get; set; } = "Pribadi";
    public DateTime Tanggal { get; set; }
    public TimeSpan WaktuMulai { get; set; }
    public TimeSpan WaktuSelesai { get; set; }
    public string? Lokasi { get; set; }
    public string? Catatan { get; set; }

    public string Waktu => $"{WaktuMulai:hh\\:mm} - {WaktuSelesai:hh\\:mm}";
}
