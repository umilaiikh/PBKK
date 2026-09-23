using System;
using System.Windows.Forms;

namespace kalkulator
{
    public partial class Form1 : Form
    {
        public Form1()
        {
            InitializeComponent();
        }

        private void textBox1_TextChanged(object sender, EventArgs e)
        {

        }

        private void textBox2_TextChanged(object sender, EventArgs e)
        {

        }

        private void label1_Click(object sender, EventArgs e)
        {

        }

        private void label2_Click(object sender, EventArgs e)
        {

        }

        private void label3_Click(object sender, EventArgs e)
        {

        }

        private void button3_Click(object sender, EventArgs e)
        {
            int nilai1 = int.Parse(textBox1.Text);
            int nilai2 = int.Parse(textBox2.Text);
            int hasil;
            hasil = nilai1 * nilai2;
            textBox3.Text = hasil.ToString();
        }

        private void button2_Click(object sender, EventArgs e)
        {
            int nilai1 = int.Parse(textBox1.Text);
            int nilai2 = int.Parse(textBox2.Text);
            int hasil;
            hasil = nilai1 - nilai2;
            textBox3.Text = hasil.ToString();
        }

        private void button5_Click(object sender, EventArgs e)
        {
            textBox1.Text = "";
            textBox2.Text = "";
            textBox3.Text = "";
        }

        private void button1_Click(object sender, EventArgs e)
        {
            int nilai1 = int.Parse(textBox1.Text);
            int nilai2 = int.Parse(textBox2.Text);
            int hasil;
            hasil = nilai1 + nilai2;
            textBox3.Text = hasil.ToString();
        }

        private void button4_Click(object sender, EventArgs e)
        {
            int nilai1 = int.Parse(textBox1.Text);
            int nilai2 = int.Parse(textBox2.Text);
            int hasil;
            if (nilai2 != 0)
            {
                hasil = nilai1 / nilai2;
                textBox3.Text = hasil.ToString();
            }
            else
            {
                textBox3.Text = "Tidak bisa dibagi 0";
            }

        }

        private void textBox3_TextChanged(object sender, EventArgs e)
        {

        }

        private void Form1_Load(object sender, EventArgs e)
        {

        }
    }
}
