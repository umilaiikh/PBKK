namespace CampusFlow.Models;

public class DashboardData
{
    public int JumlahMataKuliah { get; set; }
    public int JadwalHariIni { get; set; }
    public int TugasMendatang { get; set; }
    public int KegiatanHariIni { get; set; }
    public int TotalTugas { get; set; }
    public int TugasSelesai { get; set; }

    public int Progres => TotalTugas == 0 ? 0 : (int)Math.Round(TugasSelesai * 100.0 / TotalTugas);

    public List<JadwalKuliah> JadwalHariIniList { get; set; } = new();
    public List<Tugas> TugasTerdekat { get; set; } = new();
    public List<Kegiatan> KegiatanMendatang { get; set; } = new();
}
