using CampusFlow.Models;
using Microsoft.Data.SqlClient;

namespace CampusFlow.Data;

public class JadwalRepository
{
    private const string OrderHari =
        "CASE j.Hari WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 WHEN 'Sabtu' THEN 6 WHEN 'Minggu' THEN 7 ELSE 8 END";

    public async Task<List<JadwalKuliah>> GetAllAsync()
    {
        var list = new List<JadwalKuliah>();
        using var con = Db.CreateConnection();
        await con.OpenAsync();
        using var cmd = new SqlCommand($@"SELECT j.JadwalId, j.MataKuliahId, m.Nama, j.Hari, j.WaktuMulai, j.WaktuSelesai, j.Ruang
            FROM JadwalKuliah j JOIN MataKuliah m ON m.MataKuliahId = j.MataKuliahId
            ORDER BY {OrderHari}, j.WaktuMulai", con);
        using var r = await cmd.ExecuteReaderAsync();
        while (await r.ReadAsync()) list.Add(Map(r));
        return list;
    }

    public async Task<List<JadwalKuliah>> GetByHariAsync(string hari)
    {
        var list = new List<JadwalKuliah>();
        using var con = Db.CreateConnection();
        await con.OpenAsync();
        using var cmd = new SqlCommand($@"SELECT j.JadwalId, j.MataKuliahId, m.Nama, j.Hari, j.WaktuMulai, j.WaktuSelesai, j.Ruang
            FROM JadwalKuliah j JOIN MataKuliah m ON m.MataKuliahId = j.MataKuliahId
            WHERE j.Hari = @hari ORDER BY j.WaktuMulai", con);
        cmd.Parameters.AddWithValue("@hari", hari);
        using var r = await cmd.ExecuteReaderAsync();
        while (await r.ReadAsync()) list.Add(Map(r));
        return list;
    }

    public async Task<List<MataKuliah>> GetMataKuliahAsync()
    {
        var list = new List<MataKuliah>();
        using var con = Db.CreateConnection();
        await con.OpenAsync();
        using var cmd = new SqlCommand("SELECT MataKuliahId, Kode, Nama, SKS FROM MataKuliah ORDER BY Nama", con);
        using var r = await cmd.ExecuteReaderAsync();
        while (await r.ReadAsync())
            list.Add(new MataKuliah { Id = r.GetInt32(0), Kode = r.GetString(1), Nama = r.GetString(2), SKS = r.GetInt32(3) });
        return list;
    }

    public async Task SaveAsync(JadwalKuliah j)
    {
        using var con = Db.CreateConnection();
        await con.OpenAsync();
        var sql = j.Id == 0
            ? "INSERT INTO JadwalKuliah (MataKuliahId,Hari,WaktuMulai,WaktuSelesai,Ruang) VALUES (@mk,@hari,@mulai,@selesai,@ruang)"
            : "UPDATE JadwalKuliah SET MataKuliahId=@mk, Hari=@hari, WaktuMulai=@mulai, WaktuSelesai=@selesai, Ruang=@ruang WHERE JadwalId=@id";
        using var cmd = new SqlCommand(sql, con);
        cmd.Parameters.AddWithValue("@id", j.Id);
        cmd.Parameters.AddWithValue("@mk", j.MataKuliahId);
        cmd.Parameters.AddWithValue("@hari", j.Hari);
        cmd.Parameters.AddWithValue("@mulai", j.WaktuMulai);
        cmd.Parameters.AddWithValue("@selesai", j.WaktuSelesai);
        cmd.Parameters.AddWithValue("@ruang", j.Ruang);
        await cmd.ExecuteNonQueryAsync();
    }

    public async Task DeleteAsync(int id)
    {
        using var con = Db.CreateConnection();
        await con.OpenAsync();
        using var cmd = new SqlCommand("DELETE FROM JadwalKuliah WHERE JadwalId=@id", con);
        cmd.Parameters.AddWithValue("@id", id);
        await cmd.ExecuteNonQueryAsync();
    }

    internal static JadwalKuliah Map(SqlDataReader r) => new()
    {
        Id = r.GetInt32(0),
        MataKuliahId = r.GetInt32(1),
        MataKuliahNama = r.GetString(2),
        Hari = r.GetString(3),
        WaktuMulai = r.GetTimeSpan(4),
        WaktuSelesai = r.GetTimeSpan(5),
        Ruang = r.GetString(6)
    };
}
