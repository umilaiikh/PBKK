using CampusFlow.Models;
using CommunityToolkit.Mvvm.ComponentModel;
using CommunityToolkit.Mvvm.Input;

namespace CampusFlow.ViewModels;

public partial class MainViewModel : ObservableObject
{
    private readonly DashboardViewModel _dashboard = new();
    private readonly JadwalViewModel _jadwal = new();
    private readonly TugasViewModel _tugas = new();
    private readonly KalenderViewModel _kalender = new();
    private readonly KegiatanViewModel _kegiatan = new();
    private readonly PengaturanViewModel _pengaturan = new();

    [ObservableProperty] private object _currentView;
    [ObservableProperty] private string _currentPage = "Dashboard";

    public string Greeting =>
        $"Selamat datang kembali. Hari ini {Hari.HariIni}, {DateTime.Today:dd MMMM yyyy}.";

    public MainViewModel()
    {
        _currentView = _dashboard;
        _ = _dashboard.RefreshAsync();
    }

    [RelayCommand]
    private async Task Navigate(string page)
    {
        CurrentPage = page;
        CurrentView = page switch
        {
            "Jadwal" => _jadwal,
            "Tugas" => _tugas,
            "Kalender" => _kalender,
            "Kegiatan" => _kegiatan,
            "Pengaturan" => _pengaturan,
            _ => _dashboard
        };

        if (CurrentView is IRefreshable refreshable)
        {
            try { await refreshable.RefreshAsync(); }
            catch (Exception ex)
            {
                System.Windows.MessageBox.Show(
                    "Gagal memuat data: " + ex.Message, "Error",
                    System.Windows.MessageBoxButton.OK, System.Windows.MessageBoxImage.Error);
            }
        }
    }
}
