using System.Collections.ObjectModel;
using System.Windows;
using CampusFlow.Data;
using CampusFlow.Models;
using CommunityToolkit.Mvvm.ComponentModel;
using CommunityToolkit.Mvvm.Input;

namespace CampusFlow.ViewModels;

public partial class TugasViewModel : ObservableObject, IRefreshable
{
    private readonly TugasRepository _repo = new();
    private List<Tugas> _all = new();

    public ObservableCollection<Tugas> Items { get; } = new();
    public ObservableCollection<MataKuliah> MataKuliahList { get; } = new();

    public string[] PrioritasOptions { get; } = { "Rendah", "Sedang", "Tinggi" };
    public string[] StatusOptions { get; } = { "Belum Dikerjakan", "Dalam Proses", "Selesai" };
    public string[] FilterPrioritasOptions { get; } = { "Semua", "Rendah", "Sedang", "Tinggi" };
    public string[] FilterStatusOptions { get; } = { "Semua", "Belum Dikerjakan", "Dalam Proses", "Selesai" };

    [ObservableProperty] private Tugas? _selected;
    [ObservableProperty] private string _judul = "";
    [ObservableProperty] private string? _deskripsi;
    [ObservableProperty] private int? _mataKuliahId;
    [ObservableProperty] private DateTime? _deadline = DateTime.Today;
    [ObservableProperty] private string _prioritas = "Sedang";
    [ObservableProperty] private string _status = "Belum Dikerjakan";
    [ObservableProperty] private string _search = "";
    [ObservableProperty] private string _filterPrioritas = "Semua";
    [ObservableProperty] private string _filterStatus = "Semua";

    public TugasViewModel() { _ = RefreshAsync(); }

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
            MessageBox.Show("Gagal memuat data tugas: " + ex.Message, "Error",
                MessageBoxButton.OK, MessageBoxImage.Error);
        }
    }

    partial void OnSearchChanged(string value) => ApplyFilter();
    partial void OnFilterPrioritasChanged(string value) => ApplyFilter();
    partial void OnFilterStatusChanged(string value) => ApplyFilter();

    private void ApplyFilter()
    {
        var q = _all.AsEnumerable();

        if (!string.IsNullOrWhiteSpace(Search))
        {
            var s = Search.Trim();
            q = q.Where(t =>
                t.Judul.Contains(s, StringComparison.OrdinalIgnoreCase) ||
                t.MataKuliahNama.Contains(s, StringComparison.OrdinalIgnoreCase));
        }

        if (FilterPrioritas != "Semua") q = q.Where(t => t.Prioritas == FilterPrioritas);
        if (FilterStatus != "Semua") q = q.Where(t => t.Status == FilterStatus);

        Items.Clear();
        foreach (var t in q) Items.Add(t);
    }

    partial void OnSelectedChanged(Tugas? value)
    {
        if (value == null) return;
        Judul = value.Judul;
        Deskripsi = value.Deskripsi;
        MataKuliahId = value.MataKuliahId;
        Deadline = value.Deadline;
        Prioritas = value.Prioritas;
        Status = value.Status;
    }

    [RelayCommand]
    private void Baru()
    {
        Selected = null;
        Judul = ""; Deskripsi = null; MataKuliahId = null;
        Deadline = DateTime.Today; Prioritas = "Sedang"; Status = "Belum Dikerjakan";
    }

    [RelayCommand]
    private async Task Simpan()
    {
        if (string.IsNullOrWhiteSpace(Judul))
        {
            MessageBox.Show("Judul tugas wajib diisi.", "Validasi", MessageBoxButton.OK, MessageBoxImage.Warning);
            return;
        }
        if (Deadline == null)
        {
            MessageBox.Show("Deadline wajib diisi.", "Validasi", MessageBoxButton.OK, MessageBoxImage.Warning);
            return;
        }

        try
        {
            await _repo.SaveAsync(new Tugas
            {
                Id = Selected?.Id ?? 0,
                MataKuliahId = MataKuliahId,
                Judul = Judul.Trim(),
                Deskripsi = string.IsNullOrWhiteSpace(Deskripsi) ? null : Deskripsi.Trim(),
                Deadline = Deadline.Value.Date,
                Prioritas = Prioritas,
                Status = Status
            });
            await RefreshAsync();
            Baru();
            MessageBox.Show("Tugas berhasil disimpan.", "Sukses", MessageBoxButton.OK, MessageBoxImage.Information);
        }
        catch (Exception ex)
        {
            MessageBox.Show("Gagal menyimpan tugas: " + ex.Message, "Error", MessageBoxButton.OK, MessageBoxImage.Error);
        }
    }

    [RelayCommand]
    private async Task Hapus()
    {
        if (Selected == null)
        {
            MessageBox.Show("Pilih baris tugas yang ingin dihapus.", "Info", MessageBoxButton.OK, MessageBoxImage.Information);
            return;
        }
        if (MessageBox.Show($"Hapus \"{Selected.Judul}\"?", "Konfirmasi Hapus",
                MessageBoxButton.YesNo, MessageBoxImage.Question) != MessageBoxResult.Yes) return;

        try
        {
            await _repo.DeleteAsync(Selected.Id);
            await RefreshAsync();
            Baru();
            MessageBox.Show("Tugas berhasil dihapus.", "Sukses", MessageBoxButton.OK, MessageBoxImage.Information);
        }
        catch (Exception ex)
        {
            MessageBox.Show("Gagal menghapus tugas: " + ex.Message, "Error", MessageBoxButton.OK, MessageBoxImage.Error);
        }
    }
}
