using System.Collections.ObjectModel;
using System.Windows.Input;
using StudentRegistrationApp.Models;
using StudentRegistrationApp.Services;

namespace StudentRegistrationApp.ViewModels
{
    public enum FocusTarget
    {
        Nim,
        Nama
    }

    public class MainViewModel : ViewModelBase
    {
        private readonly IDialogService _dialogService;

        private string _nim = string.Empty;
        private string _nama = string.Empty;
        private string? _selectedProdi;
        private bool _isLaki;
        private bool _isPerempuan;
        private Student? _selectedStudent;

        public MainViewModel(IDialogService dialogService)
        {
            _dialogService = dialogService;

            SimpanCommand = new RelayCommand(_ => Simpan());
            ResetCommand = new RelayCommand(_ => Reset());
            HapusCommand = new RelayCommand(_ => Hapus());
        }

        // View subscribe ke event ini untuk memindahkan fokus (urusan UI, bukan logic)
        public event Action<FocusTarget>? FocusRequested;

        // ===== Data untuk Binding =====

        public string Nim
        {
            get => _nim;
            set => SetProperty(ref _nim, value);
        }

        public string Nama
        {
            get => _nama;
            set => SetProperty(ref _nama, value);
        }

        public string? SelectedProdi
        {
            get => _selectedProdi;
            set => SetProperty(ref _selectedProdi, value);
        }

        public bool IsLaki
        {
            get => _isLaki;
            set => SetProperty(ref _isLaki, value);
        }

        public bool IsPerempuan
        {
            get => _isPerempuan;
            set => SetProperty(ref _isPerempuan, value);
        }

        public Student? SelectedStudent
        {
            get => _selectedStudent;
            set => SetProperty(ref _selectedStudent, value);
        }

        public IReadOnlyList<string> ProdiList { get; } = new List<string>
        {
            "Teknik Informatika",
            "Sistem Informasi",
            "Manajemen",
            "Akuntansi"
        };

        public ObservableCollection<Student> Students { get; } = new();

        // ===== Commands =====

        public ICommand SimpanCommand { get; }
        public ICommand ResetCommand { get; }
        public ICommand HapusCommand { get; }

        // ===== Logic =====

        private void Simpan()
        {
            if (string.IsNullOrWhiteSpace(Nim))
            {
                _dialogService.ShowMessage("NIM harus diisi!");
                FocusRequested?.Invoke(FocusTarget.Nim);
                return;
            }

            if (string.IsNullOrWhiteSpace(Nama))
            {
                _dialogService.ShowMessage("Nama harus diisi!");
                FocusRequested?.Invoke(FocusTarget.Nama);
                return;
            }

            if (SelectedProdi == null)
            {
                _dialogService.ShowMessage("Pilih program studi!");
                return;
            }

            if (!IsLaki && !IsPerempuan)
            {
                _dialogService.ShowMessage("Pilih jenis kelamin!");
                return;
            }

            Students.Add(new Student
            {
                Nim = Nim,
                Nama = Nama,
                Prodi = SelectedProdi,
                JenisKelamin = IsLaki ? "Laki-laki" : "Perempuan"
            });

            _dialogService.ShowInfo("Data mahasiswa berhasil disimpan!", "Informasi");
        }

        private void Reset()
        {
            Nim = string.Empty;
            Nama = string.Empty;
            SelectedProdi = null;
            IsLaki = false;
            IsPerempuan = false;

            FocusRequested?.Invoke(FocusTarget.Nim);
        }

        private void Hapus()
        {
            if (SelectedStudent != null)
            {
                Students.Remove(SelectedStudent);
            }
            else
            {
                _dialogService.ShowMessage("Pilih data yang ingin dihapus!");
            }
        }
    }
}
