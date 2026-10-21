using Microsoft.Data.SqlClient;
using System.Data;
using System.Windows;
using System.Windows.Controls;

namespace StudentRegistrationApp
{
    public partial class MainWindow : Window
    {
        private readonly string connectionString =
            @"Server=.\SQLEXPRESS;Database=StudentRegistrationDB;Trusted_Connection=True;TrustServerCertificate=True;";

        private int? selectedStudentId;

        public MainWindow()
        {
            InitializeComponent();
            Loaded += MainWindow_Loaded;
        }

        private void MainWindow_Loaded(object sender, RoutedEventArgs e)
        {
            LoadPrograms();
            LoadStudents();
        }

        private SqlConnection GetConnection() => new SqlConnection(connectionString);

        private void LoadPrograms()
        {
            try
            {
                using var conn = GetConnection();
                conn.Open();
                using var cmd = new SqlCommand(
                    "SELECT ProgramId, ProgramName FROM dbo.Programs WHERE IsActive = 1 ORDER BY ProgramName", conn);
                using var reader = cmd.ExecuteReader();

                var programs = new List<ProgramItem>();
                while (reader.Read())
                {
                    programs.Add(new ProgramItem
                    {
                        ProgramId = reader.GetInt32(0),
                        ProgramName = reader.GetString(1)
                    });
                }

                cmbProdi.ItemsSource = programs;
                if (programs.Count > 0) cmbProdi.SelectedIndex = 0;
            }
            catch (Exception ex)
            {
                MessageBox.Show(
                    "Program studi gagal dimuat. Periksa nama server dan database.\n\nDetail: " + ex.Message,
                    "Koneksi Database", MessageBoxButton.OK, MessageBoxImage.Error);
            }
        }

        private void LoadStudents()
        {
            try
            {
                using var conn = GetConnection();
                conn.Open();

                string sql = @"
                    SELECT s.StudentId, s.NIM, s.Name, s.ProgramId, p.ProgramName,
                           s.Gender, s.BirthDate, s.Address, s.PhoneNumber
                    FROM dbo.Students s
                    INNER JOIN dbo.Programs p ON p.ProgramId = s.ProgramId
                    ORDER BY s.StudentId DESC;";

                using var adapter = new SqlDataAdapter(sql, conn);
                var table = new DataTable();
                adapter.Fill(table);
                gridMahasiswa.ItemsSource = table.DefaultView;
                txtStatus.Text = $"Berhasil memuat {table.Rows.Count} data mahasiswa dari database.";
            }
            catch (Exception ex)
            {
                txtStatus.Text = "Data belum dapat dimuat dari database.";
                MessageBox.Show(
                    "Gagal mengambil data mahasiswa.\nPastikan SQL script DatabaseSetup.sql sudah dijalankan dan connection string benar.\n\nDetail: " + ex.Message,
                    "Database", MessageBoxButton.OK, MessageBoxImage.Error);
            }
        }

        private bool ValidateInput()
        {
            if (string.IsNullOrWhiteSpace(txtNim.Text))
            {
                MessageBox.Show("NIM harus diisi!");
                txtNim.Focus();
                return false;
            }
            if (string.IsNullOrWhiteSpace(txtNama.Text))
            {
                MessageBox.Show("Nama mahasiswa harus diisi!");
                txtNama.Focus();
                return false;
            }
            if (cmbProdi.SelectedValue == null)
            {
                MessageBox.Show("Pilih program studi!");
                cmbProdi.Focus();
                return false;
            }
            if (cmbGender.SelectedItem is not ComboBoxItem)
            {
                MessageBox.Show("Pilih jenis kelamin!");
                cmbGender.Focus();
                return false;
            }
            return true;
        }

        private string SelectedGender =>
            (cmbGender.SelectedItem as ComboBoxItem)?.Content?.ToString() ?? "";

        private object BirthDateValue =>
            dpBirthDate.SelectedDate.HasValue ? dpBirthDate.SelectedDate.Value.Date : DBNull.Value;

        private object AddressValue =>
            string.IsNullOrWhiteSpace(txtAddress.Text) ? DBNull.Value : txtAddress.Text.Trim();

        private object PhoneValue =>
            string.IsNullOrWhiteSpace(txtPhone.Text) ? DBNull.Value : txtPhone.Text.Trim();

        private void AddParameters(SqlCommand cmd)
        {
            cmd.Parameters.Add("@NIM", SqlDbType.NVarChar, 30).Value = txtNim.Text.Trim();
            cmd.Parameters.Add("@Name", SqlDbType.NVarChar, 150).Value = txtNama.Text.Trim();
            cmd.Parameters.Add("@ProgramId", SqlDbType.Int).Value = Convert.ToInt32(cmbProdi.SelectedValue);
            cmd.Parameters.Add("@Gender", SqlDbType.NVarChar, 20).Value = SelectedGender;
            cmd.Parameters.Add("@BirthDate", SqlDbType.Date).Value = BirthDateValue;
            cmd.Parameters.Add("@Address", SqlDbType.NVarChar, 500).Value = AddressValue;
            cmd.Parameters.Add("@PhoneNumber", SqlDbType.NVarChar, 30).Value = PhoneValue;
        }

        private void BtnSimpan_Click(object sender, RoutedEventArgs e)
        {
            if (!ValidateInput()) return;

            try
            {
                using var conn = GetConnection();
                conn.Open();
                string sql = @"
                    INSERT INTO dbo.Students (NIM, Name, ProgramId, Gender, BirthDate, Address, PhoneNumber)
                    VALUES (@NIM, @Name, @ProgramId, @Gender, @BirthDate, @Address, @PhoneNumber);";

                using var cmd = new SqlCommand(sql, conn);
                AddParameters(cmd);
                cmd.ExecuteNonQuery();

                MessageBox.Show("Data mahasiswa berhasil disimpan ke database.");
                ResetForm();
                LoadStudents();
            }
            catch (SqlException ex) when (ex.Number == 2627 || ex.Number == 2601)
            {
                MessageBox.Show("NIM tersebut sudah terdaftar. Gunakan NIM yang berbeda.");
            }
            catch (Exception ex)
            {
                MessageBox.Show("Data gagal disimpan.\n\nDetail: " + ex.Message, "Database");
            }
        }

        private void BtnUbah_Click(object sender, RoutedEventArgs e)
        {
            if (selectedStudentId == null)
            {
                MessageBox.Show("Pilih data mahasiswa pada tabel yang ingin diubah.");
                return;
            }
            if (!ValidateInput()) return;

            try
            {
                using var conn = GetConnection();
                conn.Open();
                string sql = @"
                    UPDATE dbo.Students
                    SET NIM=@NIM, Name=@Name, ProgramId=@ProgramId, Gender=@Gender,
                        BirthDate=@BirthDate, Address=@Address, PhoneNumber=@PhoneNumber,
                        UpdatedAt=GETDATE()
                    WHERE StudentId=@StudentId;";

                using var cmd = new SqlCommand(sql, conn);
                AddParameters(cmd);
                cmd.Parameters.Add("@StudentId", SqlDbType.Int).Value = selectedStudentId.Value;
                cmd.ExecuteNonQuery();

                MessageBox.Show("Data mahasiswa berhasil diubah.");
                ResetForm();
                LoadStudents();
            }
            catch (Exception ex)
            {
                MessageBox.Show("Data gagal diubah.\n\nDetail: " + ex.Message, "Database");
            }
        }

        private void BtnHapus_Click(object sender, RoutedEventArgs e)
        {
            if (selectedStudentId == null)
            {
                MessageBox.Show("Pilih data mahasiswa pada tabel yang ingin dihapus.");
                return;
            }

            var result = MessageBox.Show("Yakin ingin menghapus data mahasiswa yang dipilih?",
                "Konfirmasi Hapus", MessageBoxButton.YesNo, MessageBoxImage.Warning);

            if (result != MessageBoxResult.Yes) return;

            try
            {
                using var conn = GetConnection();
                conn.Open();
                using var cmd = new SqlCommand("DELETE FROM dbo.Students WHERE StudentId=@StudentId", conn);
                cmd.Parameters.Add("@StudentId", SqlDbType.Int).Value = selectedStudentId.Value;
                cmd.ExecuteNonQuery();

                MessageBox.Show("Data mahasiswa berhasil dihapus.");
                ResetForm();
                LoadStudents();
            }
            catch (Exception ex)
            {
                MessageBox.Show("Data gagal dihapus.\n\nDetail: " + ex.Message, "Database");
            }
        }

        private void BtnReset_Click(object sender, RoutedEventArgs e) => ResetForm();

        private void BtnRefresh_Click(object sender, RoutedEventArgs e)
        {
            LoadPrograms();
            LoadStudents();
        }

        private void GridMahasiswa_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            if (gridMahasiswa.SelectedItem is not DataRowView row) return;

            selectedStudentId = Convert.ToInt32(row["StudentId"]);
            txtNim.Text = row["NIM"]?.ToString() ?? "";
            txtNama.Text = row["Name"]?.ToString() ?? "";
            cmbProdi.SelectedValue = Convert.ToInt32(row["ProgramId"]);

            string gender = row["Gender"]?.ToString() ?? "";
            cmbGender.SelectedIndex = gender == "Perempuan" ? 1 : gender == "Laki-laki" ? 0 : -1;

            dpBirthDate.SelectedDate = row["BirthDate"] == DBNull.Value ? null : Convert.ToDateTime(row["BirthDate"]);
            txtAddress.Text = row["Address"] == DBNull.Value ? "" : row["Address"].ToString();
            txtPhone.Text = row["PhoneNumber"] == DBNull.Value ? "" : row["PhoneNumber"].ToString();
            txtStatus.Text = $"Data StudentId {selectedStudentId} dipilih. Kamu bisa klik Ubah Data atau Hapus Data.";
        }

        private void ResetForm()
        {
            selectedStudentId = null;
            txtNim.Clear();
            txtNama.Clear();
            if (cmbProdi.Items.Count > 0) cmbProdi.SelectedIndex = 0;
            cmbGender.SelectedIndex = -1;
            dpBirthDate.SelectedDate = null;
            txtAddress.Clear();
            txtPhone.Clear();
            gridMahasiswa.SelectedItem = null;
            txtStatus.Text = "Form siap digunakan.";
            txtNim.Focus();
        }

        private class ProgramItem
        {
            public int ProgramId { get; set; }
            public string ProgramName { get; set; } = "";
        }
    }
}
