namespace StudentRegistrationApp.Models
{
    public class Student
    {
        public string Nim { get; set; } = string.Empty;
        public string Nama { get; set; } = string.Empty;
        public string Prodi { get; set; } = string.Empty;
        public string JenisKelamin { get; set; } = string.Empty;

        // Dipakai ListBox untuk menampilkan item (format sama seperti sebelumnya)
        public override string ToString()
            => $"{Nim} | {Nama} | {Prodi} | {JenisKelamin}";
    }
}
