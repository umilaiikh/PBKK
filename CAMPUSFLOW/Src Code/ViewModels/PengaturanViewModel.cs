using System.Reflection;
using System.Runtime.InteropServices;
using System.Windows;
using CampusFlow.Data;
using CommunityToolkit.Mvvm.ComponentModel;
using CommunityToolkit.Mvvm.Input;
using Microsoft.Data.SqlClient;

namespace CampusFlow.ViewModels;

public partial class PengaturanViewModel : ObservableObject, IRefreshable
{
    [ObservableProperty] private string _server = "-";
    [ObservableProperty] private string _database = "-";
    [ObservableProperty] private string _appVersion = "1.0.0";
    [ObservableProperty] private string _dotNetVersion = "-";
    [ObservableProperty] private string _statusMessage = "Klik \"Uji Koneksi\" untuk memeriksa koneksi database.";

    public string ConnectionString => Db.ConnectionString;

    public PengaturanViewModel()
    {
        AppVersion = Assembly.GetExecutingAssembly().GetName().Version?.ToString() ?? "1.0.0";
        DotNetVersion = RuntimeInformation.FrameworkDescription;
        try
        {
            var b = new SqlConnectionStringBuilder(Db.ConnectionString);
            Server = b.DataSource;
            Database = b.InitialCatalog;
        }
        catch
        {
            Server = "-";
            Database = "-";
        }
    }

    public Task RefreshAsync()
    {
        OnPropertyChanged(nameof(ConnectionString));
        return Task.CompletedTask;
    }

    [RelayCommand]
    private async Task UjiKoneksi()
    {
        try
        {
            using var con = Db.CreateConnection();
            await con.OpenAsync();
            StatusMessage = "Koneksi berhasil ke database.";
            MessageBox.Show("Koneksi ke database berhasil.", "Uji Koneksi",
                MessageBoxButton.OK, MessageBoxImage.Information);
        }
        catch (Exception ex)
        {
            StatusMessage = "Koneksi gagal: " + ex.Message;
            MessageBox.Show("Koneksi gagal: " + ex.Message, "Uji Koneksi",
                MessageBoxButton.OK, MessageBoxImage.Error);
        }
    }
}
