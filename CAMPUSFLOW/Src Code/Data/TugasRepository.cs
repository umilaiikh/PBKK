using CampusFlow.Models;
using Microsoft.Data.SqlClient;

namespace CampusFlow.Data;

public class TugasRepository
{
    public async Task<List<Tugas>> GetAllAsync()
    {
        var list = new List<Tugas>();
        using var con = Db.CreateConnection();
        await con.OpenAsync();
        using var cmd = new SqlCommand(@"SELECT t.TugasId, t.MataKuliahId, ISNULL(m.Nama,'-'), t.Judul, t.Deskripsi, t.Deadline, t.Prioritas, t.Status
            FROM Tugas t LEFT JOIN MataKuliah m ON m.MataKuliahId = t.MataKuliahId ORDER BY t.Deadline", con);
        using var r = await cmd.ExecuteReaderAsync();
        while (await r.ReadAsync())
            list.Add(Map(r));
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
            list.Add(new MataKuliah
            {
                Id = r.GetInt32(0),
                Kode = r.GetString(1),
                Nama = r.GetString(2),
                SKS = r.GetInt32(3)
            });
        return list;
    }

    public async Task SaveAsync(Tugas t)
    {
        using var con = Db.CreateConnection();
        await con.OpenAsync();
        var sql = t.Id == 0
            ? "INSERT INTO Tugas (MataKuliahId,Judul,Deskripsi,Deadline,Prioritas,Status) VALUES (@mk,@judul,@desk,@dl,@pr,@st)"
            : "UPDATE Tugas SET MataKuliahId=@mk, Judul=@judul, Deskripsi=@desk, Deadline=@dl, Prioritas=@pr, Status=@st WHERE TugasId=@id";
        using var cmd = new SqlCommand(sql, con);
        cmd.Parameters.AddWithValue("@id", t.Id);
        cmd.Parameters.AddWithValue("@mk", (object?)t.MataKuliahId ?? DBNull.Value);
        cmd.Parameters.AddWithValue("@judul", t.Judul);
        cmd.Parameters.AddWithValue("@desk", (object?)t.Deskripsi ?? DBNull.Value);
        cmd.Parameters.AddWithValue("@dl", t.Deadline);
        cmd.Parameters.AddWithValue("@pr", t.Prioritas);
        cmd.Parameters.AddWithValue("@st", t.Status);
        await cmd.ExecuteNonQueryAsync();
    }

    public async Task DeleteAsync(int id)
    {
        using var con = Db.CreateConnection();
        await con.OpenAsync();
        using var cmd = new SqlCommand("DELETE FROM Tugas WHERE TugasId=@id", con);
        cmd.Parameters.AddWithValue("@id", id);
        await cmd.ExecuteNonQueryAsync();
    }

    internal static Tugas Map(SqlDataReader r) => new()
    {
        Id = r.GetInt32(0),
        MataKuliahId = r.IsDBNull(1) ? null : r.GetInt32(1),
        MataKuliahNama = r.GetString(2),
        Judul = r.GetString(3),
        Deskripsi = r.IsDBNull(4) ? null : r.GetString(4),
        Deadline = r.GetDateTime(5),
        Prioritas = r.GetString(6),
        Status = r.GetString(7)
    };
}
