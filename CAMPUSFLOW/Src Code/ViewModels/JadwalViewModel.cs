using System.Collections.ObjectModel;
using System.Windows;
using CampusFlow.Data;
using CampusFlow.Models;
using CommunityToolkit.Mvvm.ComponentModel;
using CommunityToolkit.Mvvm.Input;

namespace CampusFlow.ViewModels;

public partial class JadwalViewModel : ObservableObject, IRefreshable
{
    private readonly JadwalRepository _repo = new();
    private List<JadwalKuliah> _all = new();

    public ObservableCollection<JadwalKuliah> Items { get; } = new();
    public ObservableCollection<MataKuliah> MataKuliahList { get; } = new();

    public string[] HariOptions => Models.Hari.Urutan;
    public string[] FilterHariOptions { get; }
    public string[] RuangOptions { get; } = { "IF101", "IF102", "IF103", "IF104", "IF105", "IF107", "IF108", "LP1", "LP2" };
    public List<string> WaktuOptions { get; } = BuildWaktu();

    [ObservableProperty] private JadwalKuliah? _selected;
    [ObservableProperty] private int _mataKuliahId;
    [ObservableProperty] private string _hari = "Senin";
    [ObservableProperty] private string _waktuMulai = "08:00";
    [ObservableProperty] private string _waktuSelesai = "10:30";
    [ObservableProperty] private string _ruang = "IF101";
    [ObservableProperty] private string _search = "";
    [ObservableProperty] private string _filterHari = "Semua";

    public JadwalViewModel()
    {
        var f = new List<string> { "Semua" };
        f.AddRange(Models.Hari.Urutan);
        FilterHariOptions = f.ToArray();
        _ = RefreshAsync();
    }

    private static List<string> BuildWaktu()
    {
        var list = new List<string>();
        for (var t = new TimeSpan(7, 0, 0); t <= new TimeSpan(21, 0, 0); t = t.Add(TimeSpan.FromMinutes(30)))
            list.Add(t.ToString(@"hh\:mm"));
        return list;
    }

    public async Task RefreshAsync()
    {
        try
        {
            _all = await _repo.GetAllAsync();

            MataKuliahList.Clear();
            foreach (var m in await _repo.GetMataKuliahAsync()) MataKuliahList.Add(m);

            ApplyFilter();
        }
        catch (Exception ex)
        {
            MessageBox.Show("Gagal memuat jadwal: " + ex.Message, "Error",
                MessageBoxButton.OK, MessageBoxImage.Error);
        }
    }

    partial void OnSearchChanged(string value) => ApplyFilter();
    partial void OnFilterHariChanged(string value) => ApplyFilter();

    private void ApplyFilter()
    {
        var q = _all.AsEnumerable();

        if (!string.IsNullOrWhiteSpace(Search))
        {
            var s = Search.Trim();
            q = q.Where(j =>
                j.MataKuliahNama.Contains(s, StringComparison.OrdinalIgnoreCase) ||
                j.Ruang.Contains(s, StringComparison.OrdinalIgnoreCase));
        }

        if (FilterHari != "Semua") q = q.Where(j => j.Hari == FilterHari);

        Items.Clear();
        foreach (var j in q) Items.Add(j);
    }

    partial void OnSelectedChanged(JadwalKuliah? value)
    {
        if (value == null) return;
        MataKuliahId = value.MataKuliahId;
        Hari = value.Hari;
        WaktuMulai = value.WaktuMulai.ToString(@"hh\:mm");
        WaktuSelesai = value.WaktuSelesai.ToString(@"hh\:mm");
        Ruang = value.Ruang;
    }

    [RelayCommand]
    private void Baru()
    {
        Selected = null;
        MataKuliahId = 0;
        Hari = "Senin";
        WaktuMulai = "08:00";
        WaktuSelesai = "10:30";
        Ruang = "IF101";
    }

    [RelayCommand]
    private async Task Simpan()
    {
        if (MataKuliahId <= 0)
        {
            MessageBox.Show("Mata kuliah wajib dipilih.", "Validasi", MessageBoxButton.OK, MessageBoxImage.Warning);
            return;
        }
        if (string.IsNullOrWhiteSpace(Ruang))
        {
            MessageBox.Show("Ruang wajib diisi.", "Validasi", MessageBoxButton.OK, MessageBoxImage.Warning);
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
            await _repo.SaveAsync(new JadwalKuliah
            {
                Id = Selected?.Id ?? 0,
                MataKuliahId = MataKuliahId,
                Hari = Hari,
                WaktuMulai = mulai,
                WaktuSelesai = selesai,
                Ruang = Ruang.Trim()
            });
            await RefreshAsync();
            Baru();
            MessageBox.Show("Jadwal berhasil disimpan.", "Sukses", MessageBoxButton.OK, MessageBoxImage.Information);
        }
        catch (Exception ex)
        {
            MessageBox.Show("Gagal menyimpan jadwal: " + ex.Message, "Error", MessageBoxButton.OK, MessageBoxImage.Error);
        }
    }

    [RelayCommand]
    private async Task Hapus()
    {
        if (Selected == null)
        {
            MessageBox.Show("Pilih baris jadwal yang ingin dihapus.", "Info", MessageBoxButton.OK, MessageBoxImage.Information);
            return;
        }
        if (MessageBox.Show($"Hapus jadwal {Selected.MataKuliahNama} hari {Selected.Hari}?", "Konfirmasi Hapus",
                MessageBoxButton.YesNo, MessageBoxImage.Question) != MessageBoxResult.Yes) return;

        try
        {
            await _repo.DeleteAsync(Selected.Id);
            await RefreshAsync();
            Baru();
            MessageBox.Show("Jadwal berhasil dihapus.", "Sukses", MessageBoxButton.OK, MessageBoxImage.Information);
        }
        catch (Exception ex)
        {
            MessageBox.Show("Gagal menghapus jadwal: " + ex.Message, "Error", MessageBoxButton.OK, MessageBoxImage.Error);
        }
    }
}
