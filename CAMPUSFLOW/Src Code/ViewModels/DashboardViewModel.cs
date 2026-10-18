using System.Collections.ObjectModel;
using System.Windows;
using CampusFlow.Data;
using CampusFlow.Models;
using CommunityToolkit.Mvvm.ComponentModel;

namespace CampusFlow.ViewModels;

public partial class DashboardViewModel : ObservableObject, IRefreshable
{
    private readonly DashboardRepository _repo = new();

    [ObservableProperty] private int _jumlahMataKuliah;
    [ObservableProperty] private int _jadwalHariIni;
    [ObservableProperty] private int _tugasMendatang;
    [ObservableProperty] private int _kegiatanHariIni;
    [ObservableProperty] private int _totalTugas;
    [ObservableProperty] private int _tugasSelesai;
    [ObservableProperty] private int _progres;

    public ObservableCollection<JadwalKuliah> JadwalHariIniList { get; } = new();
    public ObservableCollection<Tugas> TugasTerdekat { get; } = new();
    public ObservableCollection<Kegiatan> KegiatanMendatang { get; } = new();

    public string HariIni => Models.Hari.HariIni;
    public string TanggalIni => DateTime.Today.ToString("dd MMMM yyyy");

    public DashboardViewModel() { }

    public async Task RefreshAsync()
    {
        try
        {
            var d = await _repo.GetAsync();

            JumlahMataKuliah = d.JumlahMataKuliah;
            JadwalHariIni = d.JadwalHariIni;
            TugasMendatang = d.TugasMendatang;
            KegiatanHariIni = d.KegiatanHariIni;
            TotalTugas = d.TotalTugas;
            TugasSelesai = d.TugasSelesai;
            Progres = d.Progres;

            JadwalHariIniList.Clear();
            foreach (var j in d.JadwalHariIniList) JadwalHariIniList.Add(j);

            TugasTerdekat.Clear();
            foreach (var t in d.TugasTerdekat) TugasTerdekat.Add(t);

            KegiatanMendatang.Clear();
            foreach (var k in d.KegiatanMendatang) KegiatanMendatang.Add(k);
        }
        catch (Exception ex)
        {
            MessageBox.Show("Gagal memuat dashboard: " + ex.Message, "Error",
                MessageBoxButton.OK, MessageBoxImage.Error);
        }
    }
}
