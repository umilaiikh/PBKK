namespace CampusFlow.Models;

public static class Hari
{
    public static readonly string[] Urutan =
        { "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu" };

    public static string DariDayOfWeek(DayOfWeek d) => d switch
    {
        DayOfWeek.Monday => "Senin",
        DayOfWeek.Tuesday => "Selasa",
        DayOfWeek.Wednesday => "Rabu",
        DayOfWeek.Thursday => "Kamis",
        DayOfWeek.Friday => "Jumat",
        DayOfWeek.Saturday => "Sabtu",
        _ => "Minggu"
    };

    public static string HariIni => DariDayOfWeek(DateTime.Today.DayOfWeek);

    public static int Indeks(string hari) => Array.IndexOf(Urutan, hari);
}
