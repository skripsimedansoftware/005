<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dosen extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('template', ['module' => 'dosen']);
		$this->load->model(['dosen', 'email_confirm', 'mahasiswa', 'dokumen_persyaratan', 'dosen_pembimbing', 'judul_mahasiswa', 'konsultasi', 'jadwal', 'lokasi_jadwal']);
		if (empty($this->session->userdata('dosen')))
		{
			if (!in_array($this->router->fetch_method(), ['login', 'register', 'forgot_password', 'email_confirm', 'reset_password']))
			{
				redirect(base_url($this->router->fetch_class().'/login'), 'refresh');
			}
		}
	}

	public function index()
	{
		$data['session'] = $this->dosen->detail(array('id' => $this->session->userdata(strtolower($this->router->fetch_class()))))->row();
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
				$dosen = $this->dosen->masuk($this->input->post('identity'), $this->input->post('password'));
				if ($dosen->num_rows() >= 1)
				{
					$this->session->set_userdata(strtolower($this->router->fetch_class()), $dosen->row()->id);
					redirect(base_url($this->router->fetch_class()), 'refresh');
				}
				else
				{
					redirect(base_url($this->router->fetch_class().'/'.$this->router->fetch_method()), 'refresh');
				}
			}
			else
			{
				$this->load->view('dosen/login');
			}
		}
		else
		{
			$this->load->view('dosen/login');
		}
	}

	public function konsultasi($id_judul = NULL)
	{
		$data['session'] = $this->dosen->detail(array('id' => $this->session->userdata(strtolower($this->router->fetch_class()))))->row();
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

			$this->konsultasi->tambah(array('judul_id' => $id_judul, 'pengirim' => 'dosen', 'dosen' => $this->session->userdata(strtolower($this->router->fetch_class())), 'text' => $this->input->post('message'), 'dokumen' => $dokumen, 'time' => nice_date(unix_to_human(now('Asia/Jakarta')), 'Y-m-d H:i:s')));

			redirect(base_url($this->router->fetch_class().'/konsultasi/'.$id_judul), 'refresh');
		}
		else
		{
			if (!empty($id_judul))
			{
				$data['judul_mahasiswa'] = $this->judul_mahasiswa->detail(array('id' => $id_judul))->row();
				$data['konsultasi'] = $this->konsultasi->ambil_data(1000, $id_judul)->result_array();
				$this->template->load('konsultasi_detail', $data);
			}
			else
			{
				$data['konsultasi'] = array_merge($this->dosen->konsultasi_mahasiswa($this->session->userdata(strtolower($this->router->fetch_class())))['dosen_kerja_praktek'], $this->dosen->konsultasi_mahasiswa($this->session->userdata(strtolower($this->router->fetch_class())))['dosen_tugas_akhir']);
				$this->template->load('konsultasi', $data);
			}
		}
	}

	public function tanggapi_judul($id, $status = NULL)
	{
		$data['session'] = $this->dosen->detail(array('id' => $this->session->userdata(strtolower($this->router->fetch_class()))))->row();
		$judul = $this->judul_mahasiswa->detail(array('id' => $id))->row();
		if (!empty($status))
		{
			if (!empty($judul))
			{
				if ($judul->jenis == 'kerja-praktek')
				{
					$this->judul_mahasiswa->sunting(array('id' => $id), array('status' => $status));
				}
				else
				{
					$dosen = $this->dosen_pembimbing->dosen_mahasiswa($judul->mahasiswa)->row();
					if (!empty($dosen))
					{
						if ($dosen->dosen_ta1 == $this->session->userdata(strtolower($this->router->fetch_class())))
						{
							$this->judul_mahasiswa->sunting(array('id' => $id), array('tanggapan_doping_1' => $status));
						}
						else
						{
							$this->judul_mahasiswa->sunting(array('id' => $id), array('tanggapan_doping_2' => $status));
						}
					}

					$judul = $this->judul_mahasiswa->detail(array('id' => $id))->row();
					if ($judul->tanggapan_doping_1 == $judul->tanggapan_doping_2)
					{
						$this->judul_mahasiswa->sunting(array('id' => $id), array('status' => $status));
					}
					else
					{
						if ($judul->status == 'diterima')
						{
							if ($judul->tanggapan_doping_1 !== 'diterima')
							{
								$this->judul_mahasiswa->sunting(array('id' => $id), array('status' => $judul->tanggapan_doping_1));
							}
							elseif ($judul->tanggapan_doping_2 !== 'diterima')
							{
								$this->judul_mahasiswa->sunting(array('id' => $id), array('status' => $judul->tanggapan_doping_2));
							}
						}
						else
						{
							$this->judul_mahasiswa->sunting(array('id' => $id), array('status' => $status));
						}
					}
				}
			}

			redirect(base_url($this->router->fetch_class().'/konsultasi'), 'refresh');
		}
		else
		{
			$data['data'] = $this->judul_mahasiswa->detail(array('id' => $id))->row();
			$this->template->load('tanggapi_judul', $data);
		}
	}

	public function jadwal()
	{
		$data['session'] = $this->dosen->detail(array('id' => $this->session->userdata(strtolower($this->router->fetch_class()))))->row();
		$data['data'] = $this->jadwal->dosen($this->session->userdata(strtolower($this->router->fetch_class())))->result_array();
		$this->template->load('jadwal', $data);
	}

	public function cetak_sk()
	{
		$data['session'] = $this->dosen->detail(array('id' => $this->session->userdata(strtolower($this->router->fetch_class()))))->row();
		$data['data'] = $this->dosen_pembimbing->ambil_data(100);
		$this->template->load('cetak_sk', $data);
	}

	public function profil_dosen($id = NULL)
	{
		$data['session'] = $this->dosen->detail(array('id' => $this->session->userdata(strtolower($this->router->fetch_class()))))->row();
		$data['data'] = $this->dosen->detail(array('id' => $id))->row();
		$this->template->load('profil_dosen', $data);
	}

	public function profil_mahasiswa($id = NULL)
	{
		$data['session'] = $this->dosen->detail(array('id' => $this->session->userdata(strtolower($this->router->fetch_class()))))->row();
		$data['data'] = $this->mahasiswa->detail(array('id' => $id))->row();
		$this->template->load('profil_mahasiswa', $data);
	}

	public function profile($id = NULL, $option = NULL)
	{
		$data['session'] = $this->dosen->detail(array('id' => $this->session->userdata(strtolower($this->router->fetch_class()))))->row();
		$data['profile'] = $this->dosen->detail(array('id' => (!empty($id))?$id:$this->session->userdata(strtolower($this->router->fetch_class()))))->row();

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
								$config['wm_text'] 			= strtolower($this->router->fetch_class());
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

						$this->dosen->sunting(array('id' => $id), $update_data);
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

	public function logout()
	{
		session_destroy();
		redirect(base_url($this->router->fetch_class().'/login'), 'refresh');
	}

	public function register()
	{
		$this->load->view('dosen/register');
	}

	public function forgot_password()
	{
		if ($this->input->method() == 'post')
		{
			$find_account = $this->dosen->where($this->input->post('identity'));
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
					$this->email_confirm->new('dosen-'.$find_account->row()->id, $confirm_code);
					$this->session->set_flashdata('email_confirm', TRUE);
					redirect(base_url($this->router->fetch_class().'/forgot_password'), 'refresh');
				}
			}
		}
		else
		{
			$this->load->view('dosen/forgot_password');
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

		$this->load->view('dosen/email_confirm', $data);
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
				$this->load->view('dosen/email_confirm', $data);
			}
		}
		else
		{
			show_error('Akses halaman dibatasi!!', 401, 'Tidak memiliki akses!!');
		}
	}
}

/* End of file Dosen.php */
/* Location: ./application/controllers/Dosen.php */