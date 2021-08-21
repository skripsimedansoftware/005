<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mahasiswa extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('template', ['module' => 'mahasiswa']);
		$this->load->model(['mahasiswa', 'email_confirm', 'dosen', 'dokumen_persyaratan', 'dosen_pembimbing', 'judul_mahasiswa', 'konsultasi', 'jadwal', 'lokasi_jadwal']);
		if (empty($this->session->userdata('mahasiswa')))
		{
			if (!in_array($this->router->fetch_method(), ['login', 'register', 'forgot_password', 'email_confirm', 'reset_password']))
			{
				redirect(base_url($this->router->fetch_class().'/login'), 'refresh');
			}
		}
	}

	public function index()
	{
		$data['session'] = $this->mahasiswa->detail(array('id' => $this->session->userdata(strtolower($this->router->fetch_class()))))->row();
		$this->template->load('home', $data);
	}

	public function login()
	{
		if ($this->input->method() == 'post')
		{
			$this->form_validation->set_rules('identity', 'Email/Username', 'trim|required');
			$this->form_validation->set_rules('password', 'Kata Sandi', 'trim|required');
			if ($this->form_validation->run() == TRUE)
			{
				$mahasiswa = $this->mahasiswa->masuk($this->input->post('identity'), $this->input->post('password'));
				if ($mahasiswa->num_rows() >= 1)
				{
					$this->session->set_userdata(strtolower($this->router->fetch_class()), $mahasiswa->row()->id);
					redirect(base_url($this->router->fetch_class()), 'refresh');
				}
				else
				{
					redirect(base_url($this->router->fetch_class().'/'.$this->router->fetch_method()), 'refresh');
				}
			}
			else
			{
				$this->load->view('mahasiswa/login');
			}
		}
		else
		{
			$this->load->view('mahasiswa/login');
		}
	}

	public function profile($id = NULL, $option = NULL)
	{
		$data['session'] = $this->mahasiswa->detail(array('id' => $this->session->userdata(strtolower($this->router->fetch_class()))))->row();
		$data['profile'] = $this->mahasiswa->detail(array('id' => (!empty($id))?$id:$this->session->userdata(strtolower($this->router->fetch_class()))))->row();

		switch ($option)
		{
			case 'create':
				$this->template->load('profile/create', $data);
			break;

			case 'edit':
				if ($this->input->method() == 'post')
				{
					$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

					if ($this->form_validation->run() == TRUE)
					{
						$update_data = array(
							'nama_lengkap' => $this->input->post('nama_lengkap'),
							'email' => $this->input->post('email'),
							'nomor_hp' => $this->input->post('nomor_hp'),
							'jenis_kelamin' => $this->input->post('jenis_kelamin'),
							'alamat' => $this->input->post('alamat')
						);

						if (!empty($this->input->post('password')))
						{
							$update_data['password'] = sha1($this->input->post('password'));
						}

						if (!empty($_FILES['photo']))
						{
							$config['upload_path'] = './uploads/';
							$config['allowed_types'] = 'png|jpg|jpeg';
							$config['file_name'] = url_title('user-profile-'.$this->uri->segment(3));
							$this->load->library('upload', $config);

							if (!$this->upload->do_upload('photo'))
							{
								$this->session->set_flashdata('upload_photo_error', $this->upload->display_errors());
							}
							else
							{
								// resize
								$config['image_library']	= 'gd2';
								$config['source_image']		= $this->upload->data()['full_path'];
								$config['maintain_ratio']	= TRUE;
								$config['width']			= 160;
								$config['height']			= 160;
								// watermark
								$config['wm_text'] 			= ($this->input->post('jenis_kelamin') == 'perempuan')?'mahasiswi':'mahasiswa';
								$config['wm_type'] 			= 'text';
								$config['wm_font_color'] 	= 'ffffff';
								$config['wm_font_size'] 	= 12;
								$config['wm_vrt_alignment'] = 'middle';
								$config['wm_hor_alignment'] = 'center';
								$this->load->library('image_lib', $config);

								if ($this->image_lib->resize())
								{
									$this->image_lib->watermark();
								}

								$update_data['foto'] = $this->upload->data()['file_name'];
							}
						}

						$this->mahasiswa->sunting(array('id' => $id), $update_data);
						redirect(base_url($this->router->fetch_class().'/profile/'.$id) ,'refresh');
					}
					else
					{
						$this->template->load('profile/edit', $data);
					}
				}
				else
				{
					$this->template->load('profile/edit', $data);
				}
			break;

			default:
				$this->template->load('profile/view', $data);
			break;
		}
	}

	public function judul_kerja_praktek()
	{
		$data['session'] = $this->mahasiswa->detail(array('id' => $this->session->userdata(strtolower($this->router->fetch_class()))))->row();
		$data['data'] = $this->judul_mahasiswa->detail(array('mahasiswa' => $this->session->userdata(strtolower($this->router->fetch_class())), 'jenis' => 'kerja-praktek'));
		$data['dokumen_persyaratan'] = $this->dokumen_persyaratan->detail(array('mahasiswa' => $this->session->userdata(strtolower($this->router->fetch_class())), 'tujuan' => 'kerja-praktek'));
		$this->template->load('judul_kerja_praktek', $data);
	}

	public function judul_skripsi()
	{
		$data['session'] = $this->mahasiswa->detail(array('id' => $this->session->userdata(strtolower($this->router->fetch_class()))))->row();
		$data['data'] = $this->judul_mahasiswa->detail(array('mahasiswa' => $this->session->userdata(strtolower($this->router->fetch_class())), 'jenis' => 'tugas-akhir'));
		$data['dokumen_persyaratan'] = $this->dokumen_persyaratan->detail(array('mahasiswa' => $this->session->userdata(strtolower($this->router->fetch_class())), 'tujuan' => 'tugas-akhir'));
		$this->template->load('judul_skripsi', $data);
	}

	public function tambah_judul($jenis)
	{
		$this->load->helper('inflector');
		if ($this->input->method() == 'post')
		{
			$this->form_validation->set_rules('judul', 'Judul', 'trim|required|min_length[10]|max_length[100]');
			if ($this->form_validation->run() == TRUE)
			{
				$config['upload_path'] = './uploads/';
				$config['allowed_types'] = '*';
				$config['file_name'] = url_title(str_replace(' ', '-', $this->input->post('judul')).'--'.$this->session->userdata('pengguna')['nama']);
				$this->load->library('upload', $config);
				if (!$this->upload->do_upload('dokumen'))
				{
					$this->session->set_flashdata('form_error', $this->upload->display_errors());
					redirect(base_url($this->router->fetch_class().'/tambah_judul/'.$jenis), 'refresh');
				}
				else
				{
					$jenis = ($jenis == 'kerja_praktek')?'kerja-praktek':'tugas-akhir';
					$this->judul_mahasiswa->tambah(array(
						'mahasiswa' => $this->session->userdata(strtolower($this->router->fetch_class())),
						'jenis' => $jenis,
						'judul' => $this->input->post('judul'),
						'dokumen' => $this->upload->data()['file_name'],
						'keterangan' => $this->input->post('keterangan'),
						'tanggal_permintaan' => nice_date(unix_to_human(now('Asia/Jakarta')), 'Y-m-d H:i:s'),
						'tanggapan_doping_1' => 'proses',
						'tanggapan_doping_2' => 'proses',
						'status' => 'proses'
					));

					$jenis = ($jenis == 'kerja-praktek')?'judul_kerja_praktek':'judul_skripsi';
					redirect(base_url($this->router->fetch_class().'/'.$jenis), 'refresh');
				}
			}
			else
			{
				$data['jenis'] = $jenis;
				$data['doping_mahasiswa'] = $this->dosen_pembimbing->dosen_mahasiswa($this->session->userdata(strtolower($this->router->fetch_class())));
				$this->template->load('tambah_judul', $data);
			}
		}
		else
		{
			$data['jenis'] = $jenis;
			$data['doping_mahasiswa'] = $this->dosen_pembimbing->dosen_mahasiswa($this->session->userdata(strtolower($this->router->fetch_class())));
			$this->template->load('tambah_judul', $data);
		}
	}

	public function konsultasi_judul($id_judul)
	{
		if ($this->input->method() == 'post')
		{
			$config['upload_path'] = './uploads/';
			$config['allowed_types'] = '*';
			$this->load->library('upload', $config);
			$dokumen = NULL;
			if ($this->upload->do_upload('attachment'))
			{
				$dokumen = $this->upload->data()['file_name'];
			}

			$this->konsultasi->tambah(array('judul_id' => $id_judul, 'pengirim' => 'mahasiswa', 'mahasiswa' => $this->session->userdata('pengguna')['id'], 'text' => $this->input->post('message'), 'dokumen' => $dokumen, 'time' => nice_date(unix_to_human(now('Asia/Jakarta')), 'Y-m-d H:i:s')));

			redirect(base_url($this->router->fetch_class().'/konsultasi_judul/'.$id_judul), 'refresh');
		}
		else
		{
			$data['judul_mahasiswa'] = $this->judul_mahasiswa->detail(array('id' => $id_judul))->row();
			$data['konsultasi'] = $this->konsultasi->ambil_data(1000, $id_judul)->result_array();
			$this->template->load('konsultasi_judul', $data);
		}
	}

	public function logout()
	{
		session_destroy();
		redirect(base_url($this->router->fetch_class().'/login'), 'refresh');
	}

	public function register()
	{
		$this->load->view('mahasiswa/register');
	}

	public function forgot_password()
	{
		if ($this->input->method() == 'post')
		{
			$find_account = $this->mahasiswa->where($this->input->post('identity'));
			if ($find_account->num_rows() >= 1)
			{
				$confirm_code = random_string('numeric', 6);
				$this->load->library('email');
				$this->email->to($find_account->row()->email);
				$this->email->from('no-reply@medansoftware.my.id', 'Medan Software');
				$this->email->subject('Ganti Kata Sandi');
				$data['code'] = $confirm_code;
				$data['link'] = base_url($this->router->fetch_class().'/email_confirm/'.$confirm_code);
				$data['full_name'] = $find_account->row()->full_name;
				$this->email->message($this->load->view('email/reset_password', $data, TRUE));
				if (!$this->email->send())
				{
					$this->session->set_flashdata('email_confirm', FALSE);
					redirect(base_url($this->router->fetch_class().'/forgot_password'), 'refresh');
				}
				else
				{
					$this->email_confirm->new('mahasiswa-'.$find_account->row()->id, $confirm_code);
					$this->session->set_flashdata('email_confirm', TRUE);
					redirect(base_url($this->router->fetch_class().'/forgot_password'), 'refresh');
				}
			}
		}
		else
		{
			$this->load->view('mahasiswa/forgot_password');
		}
	}

	public function email_confirm($code = NULL)
	{
		$data = array();

		if ($this->input->method() == 'post')
		{
			$this->form_validation->set_rules('confirm_code', 'Kode Konfirmasi', 'trim|required|integer|max_length[6]');
			if ($this->form_validation->run() == TRUE)
			{
				$data['email_confirm_detail'] = $this->email_confirm->detail(array('confirm_code' => $this->input->post('confirm_code')));
			}
		}
		else
		{
			if (!empty($code))
			{
				$data['email_confirm_detail'] = (!empty($code))?$this->email_confirm->detail(array('confirm_code' => $code)):NULL;
			}
		}

		$this->load->view('mahasiswa/email_confirm', $data);
	}

	public function reset_password()
	{
		$data = array();
		if ($this->session->has_userdata('reset_password'))
		{
			$this->form_validation->set_rules('new_password', 'Kata Sandi Baru', 'trim|required');
			$this->form_validation->set_rules('repeat_new_password', 'Ulangi Kata Sandi Baru', 'trim|required|matches[new_password]');

			if ($this->form_validation->run() == TRUE)
			{
				$model_data = explode('-', $this->session->userdata('reset_password')['user_id']);
				$this->{$model_data[0]}->reset_password($model_data[1], $this->input->post('new_password'));
				$this->email_confirm->confirm($this->session->userdata('reset_password')['confirm_code']);
				session_destroy();
				redirect($this->router->fetch_class().'/login', 'refresh');
			}
			else
			{
				$this->load->view('mahasiswa/email_confirm', $data);
			}
		}
		else
		{
			show_error('Akses halaman dibatasi!!', 401, 'Tidak memiliki akses!!');
		}
	}
}

/* End of file Mahasiswa.php */
/* Location: ./application/controllers/Mahasiswa.php */