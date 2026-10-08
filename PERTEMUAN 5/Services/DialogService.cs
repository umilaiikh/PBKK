using System.Windows;

namespace StudentRegistrationApp.Services
{
    public class DialogService : IDialogService
    {
        public void ShowMessage(string message)
            => MessageBox.Show(message);

        public void ShowInfo(string message, string title)
            => MessageBox.Show(message, title, MessageBoxButton.OK, MessageBoxImage.Information);
    }
}
