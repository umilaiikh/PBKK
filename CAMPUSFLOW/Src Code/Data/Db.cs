using System.IO;
using System.Text.Json;
using Microsoft.Data.SqlClient;

namespace CampusFlow.Data;

public static class Db
{
    private static readonly Lazy<string> _connectionString = new(Load);

    public static string ConnectionString => _connectionString.Value;

    public static SqlConnection CreateConnection() => new(ConnectionString);

    public static async Task<SqlConnection> OpenAsync()
    {
        var con = new SqlConnection(ConnectionString);
        await con.OpenAsync().ConfigureAwait(false);
        return con;
    }

    private static string Load()
    {
        var path = Path.Combine(AppContext.BaseDirectory, "appsettings.json");
        if (!File.Exists(path))
            throw new FileNotFoundException(
                "appsettings.json tidak ditemukan di folder aplikasi.", path);

        using var doc = JsonDocument.Parse(File.ReadAllText(path));
        if (!doc.RootElement.TryGetProperty("ConnectionString", out var prop))
            throw new InvalidOperationException("Kunci 'ConnectionString' tidak ada pada appsettings.json.");

        var value = prop.GetString();
        if (string.IsNullOrWhiteSpace(value))
            throw new InvalidOperationException("'ConnectionString' pada appsettings.json kosong.");

        return value;
    }
}
