using System.Collections.ObjectModel;
using System.Windows;
using CampusFlow.Data;
using CampusFlow.Models;
using CommunityToolkit.Mvvm.ComponentModel;
using CommunityToolkit.Mvvm.Input;

namespace CampusFlow.ViewModels;

public partial class KegiatanViewModel : ObservableObject, IRefreshable
{
    private readonly KegiatanRepository _repo = new();
    private List<Kegiatan> _all = new();

    public ObservableCollection<Kegiatan> Items { get; } = new();

    public string[] KategoriOptions { get; } = { "Organisasi", "Belajar Mandiri", "Pribadi" };
    public string[] FilterKategoriOptions { get; }
    public List<string> WaktuOptions { get; } = BuildWaktu();

    [ObservableProperty] private Kegiatan? _selected;
    [ObservableProperty] private string _judul = "";
    [ObservableProperty] private string _kategori = "Pribadi";
    [ObservableProperty] private DateTime? _tanggal = DateTime.Today;
    [ObservableProperty] private string _waktuMulai = "09:00";
    [ObservableProperty] private string _waktuSelesai = "11:00";
    [ObservableProperty] private string? _lokasi;
    [ObservableProperty] private string? _catatan;
    [ObservableProperty] private string _search = "";
    [ObservableProperty] private string _filterKategori = "Semua";

    public KegiatanViewModel()
    {
        var f = new List<string> { "Semua" };
        f.AddRange(KategoriOptions);
        FilterKategoriOptions = f.ToArray();
        _ = RefreshAsync();
    }

    private static List<string> BuildWaktu()
    {
        var list = new List<string>();
        for (var t = new TimeSpan(7, 0, 0); t <= new TimeSpan(22, 0, 0); t = t.Add(TimeSpan.FromMinutes(30)))
            list.Add(t.ToString(@"hh\:mm"));
        return list;
    }

    public async Task RefreshAsync()
    {
        try
        {
            _all = await _repo.GetAllAsync();
            ApplyFilter();
        }
        catch (Exception ex)
        {
            MessageBox.Show("Gagal memuat kegiatan: " + ex.Message, "Error",
                MessageBoxButton.OK, MessageBoxImage.Error);
        }
    }

    partial void OnSearchChanged(string value) => ApplyFilter();
    partial void OnFilterKategoriChanged(string value) => ApplyFilter();

    private void ApplyFilter()
    {
        var q = _all.AsEnumerable();

        if (!string.IsNullOrWhiteSpace(Search))
        {
            var s = Search.Trim();
            q = q.Where(k =>
                k.Judul.Contains(s, StringComparison.OrdinalIgnoreCase) ||
                (k.Lokasi ?? "").Contains(s, StringComparison.OrdinalIgnoreCase));
        }

        if (FilterKategori != "Semua") q = q.Where(k => k.Kategori == FilterKategori);

        Items.Clear();
        foreach (var k in q) Items.Add(k);
    }

    partial void OnSelectedChanged(Kegiatan? value)
    {
        if (value == null) return;
        Judul = value.Judul;
        Kategori = value.Kategori;
        Tanggal = value.Tanggal;
        WaktuMulai = value.WaktuMulai.ToString(@"hh\:mm");
        WaktuSelesai = value.WaktuSelesai.ToString(@"hh\:mm");
        Lokasi = value.Lokasi;
        Catatan = value.Catatan;
    }

    [RelayCommand]
    private void Baru()
    {
        Selected = null;
        Judul = "";
        Kategori = "Pribadi";
        Tanggal = DateTime.Today;
        WaktuMulai = "09:00";
        WaktuSelesai = "11:00";
        Lokasi = null;
        Catatan = null;
    }

    [RelayCommand]
    private async Task Simpan()
    {
        if (string.IsNullOrWhiteSpace(Judul))
        {
            MessageBox.Show("Judul kegiatan wajib diisi.", "Validasi", MessageBoxButton.OK, MessageBoxImage.Warning);
            return;
        }
        if (Tanggal == null)
        {
            MessageBox.Show("Tanggal wajib diisi.", "Validasi", MessageBoxButton.OK, MessageBoxImage.Warning);
            return;
        }
        if (!TimeSpan.TryParse(WaktuMulai, out var mulai) || !TimeSpan.TryParse(WaktuSelesai, out var selesai))
        {
            MessageBox.Show("Format waktu tidak valid.", "Validasi", MessageBoxButton.OK, MessageBoxImage.Warning);
            return;
        }
        if (selesai <= mulai)
        {
            MessageBox.Show("Waktu selesai harus setelah waktu mulai.", "Validasi", MessageBoxButton.OK, MessageBoxImage.Warning);
            return;
        }

        try
        {
            await _repo.SaveAsync(new Kegiatan
            {
                Id = Selected?.Id ?? 0,
                Judul = Judul.Trim(),
                Kategori = Kategori,
                Tanggal = Tanggal.Value.Date,
                WaktuMulai = mulai,
                WaktuSelesai = selesai,
                Lokasi = string.IsNullOrWhiteSpace(Lokasi) ? null : Lokasi.Trim(),
                Catatan = string.IsNullOrWhiteSpace(Catatan) ? null : Catatan.Trim()
            });
            await RefreshAsync();
            Baru();
            MessageBox.Show("Kegiatan berhasil disimpan.", "Sukses", MessageBoxButton.OK, MessageBoxImage.Information);
        }
        catch (Exception ex)
        {
            MessageBox.Show("Gagal menyimpan kegiatan: " + ex.Message, "Error", MessageBoxButton.OK, MessageBoxImage.Error);
        }
    }

    [RelayCommand]
    private async Task Hapus()
    {
        if (Selected == null)
        {
            MessageBox.Show("Pilih baris kegiatan yang ingin dihapus.", "Info", MessageBoxButton.OK, MessageBoxImage.Information);
            return;
        }
        if (MessageBox.Show($"Hapus \"{Selected.Judul}\"?", "Konfirmasi Hapus",
                MessageBoxButton.YesNo, MessageBoxImage.Question) != MessageBoxResult.Yes) return;

        try
        {
            await _repo.DeleteAsync(Selected.Id);
            await RefreshAsync();
            Baru();
            MessageBox.Show("Kegiatan berhasil dihapus.", "Sukses", MessageBoxButton.OK, MessageBoxImage.Information);
        }
        catch (Exception ex)
        {
            MessageBox.Show("Gagal menghapus kegiatan: " + ex.Message, "Error", MessageBoxButton.OK, MessageBoxImage.Error);
        }
    }
}
