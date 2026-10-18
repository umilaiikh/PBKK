using CampusFlow.Models;
using Microsoft.Data.SqlClient;

namespace CampusFlow.Data;

public class KegiatanRepository
{
    public async Task<List<Kegiatan>> GetAllAsync()
    {
        var list = new List<Kegiatan>();
        using var con = Db.CreateConnection();
        await con.OpenAsync();
        using var cmd = new SqlCommand(@"SELECT KegiatanId, Judul, Kategori, Tanggal, WaktuMulai, WaktuSelesai, Lokasi, Catatan
            FROM Kegiatan ORDER BY Tanggal, WaktuMulai", con);
        using var r = await cmd.ExecuteReaderAsync();
        while (await r.ReadAsync()) list.Add(Map(r));
        return list;
    }

    public async Task SaveAsync(Kegiatan k)
    {
        using var con = Db.CreateConnection();
        await con.OpenAsync();
        var sql = k.Id == 0
            ? "INSERT INTO Kegiatan (Judul,Kategori,Tanggal,WaktuMulai,WaktuSelesai,Lokasi,Catatan) VALUES (@judul,@kat,@tgl,@mulai,@selesai,@lok,@cat)"
            : "UPDATE Kegiatan SET Judul=@judul, Kategori=@kat, Tanggal=@tgl, WaktuMulai=@mulai, WaktuSelesai=@selesai, Lokasi=@lok, Catatan=@cat WHERE KegiatanId=@id";
        using var cmd = new SqlCommand(sql, con);
        cmd.Parameters.AddWithValue("@id", k.Id);
        cmd.Parameters.AddWithValue("@judul", k.Judul);
        cmd.Parameters.AddWithValue("@kat", k.Kategori);
        cmd.Parameters.AddWithValue("@tgl", k.Tanggal);
        cmd.Parameters.AddWithValue("@mulai", k.WaktuMulai);
        cmd.Parameters.AddWithValue("@selesai", k.WaktuSelesai);
        cmd.Parameters.AddWithValue("@lok", (object?)k.Lokasi ?? DBNull.Value);
        cmd.Parameters.AddWithValue("@cat", (object?)k.Catatan ?? DBNull.Value);
        await cmd.ExecuteNonQueryAsync();
    }

    public async Task DeleteAsync(int id)
    {
        using var con = Db.CreateConnection();
        await con.OpenAsync();
        using var cmd = new SqlCommand("DELETE FROM Kegiatan WHERE KegiatanId=@id", con);
        cmd.Parameters.AddWithValue("@id", id);
        await cmd.ExecuteNonQueryAsync();
    }

    internal static Kegiatan Map(SqlDataReader r) => new()
    {
        Id = r.GetInt32(0),
        Judul = r.GetString(1),
        Kategori = r.GetString(2),
        Tanggal = r.GetDateTime(3),
        WaktuMulai = r.GetTimeSpan(4),
        WaktuSelesai = r.GetTimeSpan(5),
        Lokasi = r.IsDBNull(6) ? null : r.GetString(6),
        Catatan = r.IsDBNull(7) ? null : r.GetString(7)
    };
}
