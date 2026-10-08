namespace StudentRegistrationApp.Services
{
    public interface IDialogService
    {
        void ShowMessage(string message);
        void ShowInfo(string message, string title);
    }
}
