<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Compro extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library(['session', 'email', 'form_validation']);
        $this->load->helper(['url', 'form']);
    }

    public function index()
    {
        $this->load->view('index');
    }

    public function send()
    {
        // VALIDASI (sesuai name di form)
        $this->form_validation->set_rules('nama', 'Nama', 'required|min_length[3]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('telepon', 'No Telepon', 'required|min_length[5]');
        $this->form_validation->set_rules('pesan', 'Pesan', 'required|min_length[5]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('index');
            return;
        }

        $nama  = $this->input->post('nama', TRUE);
        $email = $this->input->post('email', TRUE);
        $telepon = $this->input->post('telepon', TRUE);
        $pesan = $this->input->post('pesan', TRUE);

        // Email ke admin
  $this->email->from('maliki.edulogi@gmail.com', 'Website Maliki');
  $this->email->reply_to($email, $nama);
  $this->email->to('maliki.edulogi@gmail.com');
   $this->email->subject('Pesan dari Website');
        $this->email->message("
        Nama: $nama
        Telepon: $telepon
        Email: $email

        Pesan:
        $pesan
        ");

        if ($this->email->send()) {

            // Auto reply
            $this->email->clear();
            $this->email->from('maliki.edulogi@gmail.com', 'Maliki.ID');
            $this->email->to($email);
            $this->email->subject('Pesan Anda Sudah Kami Terima');
            $this->email->message(
                'Terima kasih sudah menghubungi kami. Tim kami akan segera merespon.'
            );
            $this->email->send();

            $this->session->set_flashdata('success', 'Pesan berhasil dikirim!');
        } else {
            $this->session->set_flashdata('error', 'Pesan gagal dikirim!');
        }

        redirect('compro');
    }
}

