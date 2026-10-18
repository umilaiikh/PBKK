using CampusFlow.Models;
using Microsoft.Data.SqlClient;

namespace CampusFlow.Data;

public class DashboardRepository
{
    public async Task<DashboardData> GetAsync()
    {
        var data = new DashboardData();
        var hariIni = Hari.HariIni;
        var today = DateTime.Today;

        using var con = Db.CreateConnection();
        await con.OpenAsync();

        data.JumlahMataKuliah = await ScalarAsync(con, "SELECT COUNT(*) FROM MataKuliah");

        using (var cmd = new SqlCommand("SELECT COUNT(*) FROM JadwalKuliah WHERE Hari=@h", con))
        {
            cmd.Parameters.AddWithValue("@h", hariIni);
            data.JadwalHariIni = Convert.ToInt32(await cmd.ExecuteScalarAsync());
        }

        using (var cmd = new SqlCommand("SELECT COUNT(*) FROM Tugas WHERE Status<>'Selesai' AND Deadline>=@t", con))
        {
            cmd.Parameters.AddWithValue("@t", today);
            data.TugasMendatang = Convert.ToInt32(await cmd.ExecuteScalarAsync());
        }

        using (var cmd = new SqlCommand("SELECT COUNT(*) FROM Kegiatan WHERE Tanggal>=@t", con))
        {
            cmd.Parameters.AddWithValue("@t", today);
            data.KegiatanHariIni = Convert.ToInt32(await cmd.ExecuteScalarAsync());
        }

        data.TotalTugas = await ScalarAsync(con, "SELECT COUNT(*) FROM Tugas");
        data.TugasSelesai = await ScalarAsync(con, "SELECT COUNT(*) FROM Tugas WHERE Status='Selesai'");

        using (var cmd = new SqlCommand(@"SELECT j.JadwalId, j.MataKuliahId, m.Nama, j.Hari, j.WaktuMulai, j.WaktuSelesai, j.Ruang
            FROM JadwalKuliah j JOIN MataKuliah m ON m.MataKuliahId=j.MataKuliahId
            WHERE j.Hari=@h ORDER BY j.WaktuMulai", con))
        {
            cmd.Parameters.AddWithValue("@h", hariIni);
            using var r = await cmd.ExecuteReaderAsync();
            while (await r.ReadAsync()) data.JadwalHariIniList.Add(JadwalRepository.Map(r));
        }

        using (var cmd = new SqlCommand(@"SELECT TOP 5 t.TugasId, t.MataKuliahId, ISNULL(m.Nama,'-'), t.Judul, t.Deskripsi, t.Deadline, t.Prioritas, t.Status
            FROM Tugas t LEFT JOIN MataKuliah m ON m.MataKuliahId=t.MataKuliahId
            WHERE t.Status<>'Selesai' ORDER BY t.Deadline", con))
        {
            using var r = await cmd.ExecuteReaderAsync();
            while (await r.ReadAsync()) data.TugasTerdekat.Add(TugasRepository.Map(r));
        }

        using (var cmd = new SqlCommand(@"SELECT TOP 5 KegiatanId, Judul, Kategori, Tanggal, WaktuMulai, WaktuSelesai, Lokasi, Catatan
            FROM Kegiatan WHERE Tanggal>=@t ORDER BY Tanggal, WaktuMulai", con))
        {
            cmd.Parameters.AddWithValue("@t", today);
            using var r = await cmd.ExecuteReaderAsync();
            while (await r.ReadAsync()) data.KegiatanMendatang.Add(KegiatanRepository.Map(r));
        }

        return data;
    }

    private static async Task<int> ScalarAsync(SqlConnection con, string sql)
    {
        using var cmd = new SqlCommand(sql, con);
        var result = await cmd.ExecuteScalarAsync();
        return result is null ? 0 : Convert.ToInt32(result);
    }
}
