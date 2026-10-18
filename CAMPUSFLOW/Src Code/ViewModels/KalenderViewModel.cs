using System.Collections.ObjectModel;
using System.Windows;
using System.Windows.Media;
using CampusFlow.Data;
using CampusFlow.Models;
using CommunityToolkit.Mvvm.ComponentModel;
using CommunityToolkit.Mvvm.Input;

namespace CampusFlow.ViewModels;

public class KalenderItem
{
    public string Waktu { get; set; } = "";
    public string Judul { get; set; } = "";
    public string Sub { get; set; } = "";
    public string Kategori { get; set; } = "";
    public Brush Warna { get; set; } = Brushes.Gray;
    internal int Urutan { get; set; }
}

public class KalenderHari
{
    public string NamaHari { get; set; } = "";
    public string TanggalLabel { get; set; } = "";
    public bool IsToday { get; set; }
    public ObservableCollection<KalenderItem> Items { get; } = new();
}

public partial class KalenderViewModel : ObservableObject, IRefreshable
{
    private readonly JadwalRepository _jadwalRepo = new();
    private readonly TugasRepository _tugasRepo = new();
    private readonly KegiatanRepository _kegiatanRepo = new();

    private List<JadwalKuliah> _jadwal = new();
    private List<Tugas> _tugas = new();
    private List<Kegiatan> _kegiatan = new();

    [ObservableProperty] private DateTime _selectedDate = DateTime.Today;
    [ObservableProperty] private string _viewMode = "Mingguan";
    [ObservableProperty] private string _rentangLabel = "";
    [ObservableProperty] private bool _isMingguan = true;
    [ObservableProperty] private bool _isHarian;

    public string[] ViewModeOptions { get; } = { "Mingguan", "Harian" };
    public ObservableCollection<KalenderHari> Minggu { get; } = new();
    public ObservableCollection<KalenderItem> HarianItems { get; } = new();

    public KalenderViewModel() { _ = RefreshAsync(); }

    public async Task RefreshAsync()
    {
        try
        {
            _jadwal = await _jadwalRepo.GetAllAsync();
            _tugas = await _tugasRepo.GetAllAsync();
            _kegiatan = await _kegiatanRepo.GetAllAsync();
            Rebuild();
        }
        catch (Exception ex)
        {
            MessageBox.Show("Gagal memuat kalender: " + ex.Message, "Error",
                MessageBoxButton.OK, MessageBoxImage.Error);
        }
    }

    partial void OnSelectedDateChanged(DateTime value) => Rebuild();

    partial void OnViewModeChanged(string value)
    {
        IsMingguan = value == "Mingguan";
        IsHarian = !IsMingguan;
        Rebuild();
    }

    [RelayCommand]
    private void MingguSebelumnya()
    {
        SelectedDate = SelectedDate.AddDays(-7);
    }

    [RelayCommand]
    private void MingguBerikutnya()
    {
        SelectedDate = SelectedDate.AddDays(7);
    }

    [RelayCommand]
    private void HariIni()
    {
        SelectedDate = DateTime.Today;
    }

    private void Rebuild()
    {
        var diff = ((int)SelectedDate.DayOfWeek + 6) % 7;
        var monday = SelectedDate.Date.AddDays(-diff);

        RentangLabel = IsMingguan
            ? $"{monday:dd MMM} - {monday.AddDays(6):dd MMM yyyy}"
            : $"{SelectedDate:dddd, dd MMMM yyyy}";

        Minggu.Clear();
        for (var i = 0; i < 7; i++)
        {
            var date = monday.AddDays(i);
            var hari = new KalenderHari
            {
                NamaHari = Hari.Urutan[i],
                TanggalLabel = date.ToString("dd MMM"),
                IsToday = date.Date == DateTime.Today
            };

            foreach (var item in ItemsFor(date, hari.NamaHari).OrderBy(x => x.Urutan).ThenBy(x => x.Waktu))
                hari.Items.Add(item);

            Minggu.Add(hari);
        }

        HarianItems.Clear();
        var namaHariIni = Hari.DariDayOfWeek(SelectedDate.DayOfWeek);
        foreach (var item in ItemsFor(SelectedDate, namaHariIni).OrderBy(x => x.Urutan).ThenBy(x => x.Waktu))
            HarianItems.Add(item);
    }

    private IEnumerable<KalenderItem> ItemsFor(DateTime date, string namaHari)
    {
        foreach (var j in _jadwal.Where(j => j.Hari == namaHari))
            yield return new KalenderItem
            {
                Waktu = j.Waktu,
                Judul = j.MataKuliahNama,
                Sub = j.Ruang,
                Kategori = "Jadwal",
                Warna = Res("KatJadwalBrush"),
                Urutan = 0
            };

        foreach (var k in _kegiatan.Where(k => k.Tanggal.Date == date.Date))
            yield return new KalenderItem
            {
                Waktu = k.Waktu,
                Judul = k.Judul,
                Sub = string.IsNullOrWhiteSpace(k.Lokasi) ? k.Kategori : k.Lokasi!,
                Kategori = "Kegiatan",
                Warna = Res(KategoriWarnaKey(k.Kategori)),
                Urutan = 1
            };

        foreach (var t in _tugas.Where(t => t.Deadline.Date == date.Date))
            yield return new KalenderItem
            {
                Waktu = "Deadline",
                Judul = t.Judul,
                Sub = t.MataKuliahNama,
                Kategori = "Tugas",
                Warna = Res("KatTugasBrush"),
                Urutan = 2
            };
    }

    private static string KategoriWarnaKey(string kategori) => kategori switch
    {
        "Organisasi" => "KatOrganisasiBrush",
        "Belajar Mandiri" => "KatBelajarBrush",
        _ => "KatPribadiBrush"
    };

    private static Brush Res(string key) =>
        Application.Current?.TryFindResource(key) as Brush ?? Brushes.Gray;
}
