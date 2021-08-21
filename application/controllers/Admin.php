<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('template', ['module' => 'admin']);
		$this->load->model(['admin', 'email_confirm', 'dosen', 'mahasiswa', 'dokumen_persyaratan', 'dosen_pembimbing', 'judul_mahasiswa', 'konsultasi', 'jadwal', 'lokasi_jadwal']);
		if (empty($this->session->userdata('admin')))
		{
			if (!in_array($this->router->fetch_method(), ['login', 'register', 'forgot_password', 'email_confirm', 'reset_password']))
			{
				redirect(base_url($this->router->fetch_class().'/login'), 'refresh');
			}
		}
	}

	public function index()
	{
		$data['session'] = $this->admin->detail(array('id' => $this->session->userdata(strtolower($this->router->fetch_class()))))->row();
		$this->template->load('home', $data);
	}

	public function dosen()
	{
		$data['session'] = $this->admin->detail(array('id' => $this->session->userdata(strtolower($this->router->fetch_class()))))->row();
		if ($this->uri->segment(3) == 'detail') 
		{
			$detail = $this->dosen->detail(array('id' => $this->uri->segment(4)));
			if ($detail->num_rows() === 1)
			{
				$data['data'] = $this->dosen->detail(array('id' => $this->uri->segment(4)))->row();
			}
			else
			{
				show_404();
			}
		}
		else
		{
			$data['data'] = $this->dosen->ambil_data($this->dosen->total_data());
		}

		$this->template->load('dosen/index', $data);
	}

	public function tambah_dosen()
	{
		$data['session'] = $this->admin->detail(array('id' => $this->session->userdata(strtolower($this->router->fetch_class()))))->row();
		if ($this->input->method() == 'post')
		{
			$this->form_validation->set_rules('nik', 'NIK', 'trim|required|is_unique[dosen.nik]');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'trim|required');
			$this->form_validation->set_rules('nomor_hp', 'Nomor HP', 'trim|required|min_length[12]|max_length[16]');
			$this->form_validation->set_rules('password', 'Password', 'trim|required');
			$this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'trim|required|in_list[laki-laki,perempuan]');

			if ($this->form_validation->run() == TRUE) 
			{
				$this->dosen->tambah(array(
					'nik' => $this->input->post('nik'),
					'email' => $this->input->post('email'),
					'password' => sha1($this->input->post('password')),
					'nama_lengkap' => $this->input->post('nama_lengkap'),
					'nomor_hp' => $this->input->post('nomor_hp'),
					'jenis_kelamin' => $this->input->post('jenis_kelamin'),
					'alamat' => $this->input->post('alamat')
				));

				$this->session->set_flashdata('message', 'Data dosen berhasil ditambahkan');

				redirect(base_url($this->router->fetch_class().'/dosen'), 'refresh');
			}
			else
			{
				$this->template->load('dosen/tambah', $data);
			}
		}
		else
		{
			$this->template->load('dosen/tambah', $data);
		}
	}

	public function sunting_dosen($id = NULL)
	{
		$data['session'] = $this->admin->detail(array('id' => $this->session->userdata(strtolower($this->router->fetch_class()))))->row();
		if ($this->input->method() == 'post')
		{
			$data = array(
				'nik' => $this->input->post('nik'),
				'email' => $this->input->post('email'),
				'nama_lengkap' => $this->input->post('nama_lengkap'),
				'nomor_hp' => $this->input->post('nomor_hp'),
				'jenis_kelamin' => $this->input->post('jenis_kelamin'),
				'alamat' => $this->input->post('alamat')
			);

			if (!empty($this->input->post('password'))) 
			{
				$data['password'] = sha1($this->input->post('password'));
			}

			$this->dosen->sunting(array('id' => $id), $data);

			$this->session->set_flashdata('message', 'Data dosen berhasil diperbaharui');

			redirect(base_url($this->router->fetch_class().'/dosen'), 'refresh');
		}
		else
		{
			$data['data'] = $this->dosen->detail(array('id' => $id));
			$this->template->load('dosen/sunting', $data);
		}
	}

	public function hapus_dosen($id = NUll)
	{
		$this->dosen->hapus(array('id' => $id));
		$this->session->set_flashdata('message', 'Dosen berhasil dihapus');
		redirect(base_url($this->router->fetch_class().'/dosen'),'refresh');
	}

	public function mahasiswa()
	{
		$data['session'] = $this->admin->detail(array('id' => $this->session->userdata(strtolower($this->router->fetch_class()))))->row();
		if ($this->uri->segment(3) == 'detail') 
		{
			$detail = $this->mahasiswa->detail(array('id' => $this->uri->segment(4)));
			if ($detail->num_rows() === 1)
			{
				$data['data'] = $this->mahasiswa->detail(array('id' => $this->uri->segment(4)))->row();
				$data['jadwal'] = $this->jadwal->mahasiswa($this->uri->segment(4))->result_array();
				$data['dokumen_persyaratan_kerja_praktek'] = $this->dokumen_persyaratan->detail(array('mahasiswa' => $this->uri->segment(4), 'tujuan' => 'kerja-praktek'));
				$data['dokumen_persyaratan_tugas_akhir'] = $this->dokumen_persyaratan->detail(array('mahasiswa' => $this->uri->segment(4), 'tujuan' => 'tugas-akhir'));
			}
			else
			{
				show_404();
			}
		}
		else
		{
			$data['data'] = $this->mahasiswa->ambil_data($this->mahasiswa->total_data(), $this->uri->segment(3));
		}

		$this->template->load('mahasiswa/index', $data);
	}

	public function tambah_mahasiswa()
	{
		$data['session'] = $this->admin->detail(array('id' => $this->session->userdata(strtolower($this->router->fetch_class()))))->row();
		if ($this->input->method() == 'post')
		{
			$this->form_validation->set_rules('npm', 'NPM', 'trim|required|is_unique[mahasiswa.npm]');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'trim|required');
			$this->form_validation->set_rules('nomor_hp', 'Nomor HP', 'trim|required|min_length[12]|max_length[16]');
			$this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'trim|required|in_list[laki-laki,perempuan]');

			if ($this->form_validation->run() == TRUE) 
			{
				$this->mahasiswa->tambah(array(
					'npm' => $this->input->post('npm'),
					'email' => $this->input->post('email'),
					'password' => sha1($this->input->post('password')),
					'nama_lengkap' => $this->input->post('nama_lengkap'),
					'nomor_hp' => $this->input->post('nomor_hp'),
					'jenis_kelamin' => $this->input->post('jenis_kelamin'),
					'alamat' => $this->input->post('alamat')
				));

				$this->session->set_flashdata('message', 'Data mahasiswa telah ditambahkan');

				redirect(base_url($this->router->fetch_class().'/mahasiswa'), 'refresh');
			}
			else
			{
				$this->template->load('mahasiswa/tambah', $data);
			}
		}
		else
		{
			$this->template->load('mahasiswa/tambah', $data);
		}
	}

	public function sunting_mahasiswa($id = NULL)
	{
		$data['session'] = $this->admin->detail(array('id' => $this->session->userdata(strtolower($this->router->fetch_class()))))->row();
		if ($this->input->method() == 'post')
		{
			$waktu_seminar_hasil = (!empty($this->input->post('waktu_seminar_hasil')))?date('Y-m-d H:i:s', strtotime($this->input->post('waktu_seminar_hasil'))):FALSE;
			$waktu_sidang = (!empty($this->input->post('waktu_sidang')))?date('Y-m-d H:i:s', strtotime($this->input->post('waktu_sidang'))):FALSE;

			if (!empty($waktu_seminar_hasil))
			{
				$this->form_validation->set_rules('lokasi_jadwal_seminar_hasil', 'Lokasi Jadwal Seminar Hasil', array('required', array(
					'lokasi_check',
					function($lokasi) {
						if ($this->lokasi_jadwal->detail(array('id' => $lokasi))->num_rows() >= 1) {
							return TRUE;
						} else {
							$this->form_validation->set_message('lokasi_check', lang('form_validation_required'));
							return FALSE;
						}
					}
				)));
				$this->form_validation->set_rules('penguji_1_seminar_hasil', 'Penguji 1', array('required', 'differs[penguji_2_seminar_hasil]', array(
					'dosen_check',
					function($dosen_id) {
						if ($this->dosen->detail(array('id' => $dosen_id))->num_rows() >= 1) {
							return TRUE;
						} else {
							$this->form_validation->set_message('dosen_check', lang('form_validation_required'));
							return FALSE;
						}
					}
				)));
				$this->form_validation->set_rules('penguji_2_seminar_hasil', 'Penguji 2', array('required', 'differs[penguji_1_seminar_hasil]', array(
					'dosen_check',
					function($dosen_id) {
						if ($this->dosen->detail(array('id' => $dosen_id))->num_rows() >= 1) {
							return TRUE;
						} else {
							$this->form_validation->set_message('dosen_check', lang('form_validation_required'));
							return FALSE;
						}
					}
				)));
			}

			if (!empty($waktu_sidang))
			{
				$this->form_validation->set_rules('lokasi_jadwal_sidang', 'Lokasi Jadwal Sidang', array('required', array(
					'lokasi_check',
					function($lokasi) {
						if ($this->lokasi_jadwal->detail(array('id' => $lokasi))->num_rows() >= 1) {
							return TRUE;
						} else {
							$this->form_validation->set_message('lokasi_check', lang('form_validation_required'));
							return FALSE;
						}
					}
				)));
				$this->form_validation->set_rules('penguji_1_sidang', 'Penguji 1', array('required', 'differs[penguji_2_sidang]', array(
					'dosen_check',
					function($dosen_id) {
						if ($this->dosen->detail(array('id' => $dosen_id))->num_rows() >= 1) {
							return TRUE;
						} else {
							$this->form_validation->set_message('dosen_check', lang('form_validation_required'));
							return FALSE;
						}
					}
				)));
				$this->form_validation->set_rules('penguji_2_sidang', 'Penguji 2', array('required', 'differs[penguji_1_sidang]', array(
					'dosen_check',
					function($dosen_id) {
						if ($this->dosen->detail(array('id' => $dosen_id))->num_rows() >= 1) {
							return TRUE;
						} else {
							$this->form_validation->set_message('dosen_check', lang('form_validation_required'));
							return FALSE;
						}
					}
				)));
			}

			$this->form_validation->set_rules('npm', 'NPM', 'trim|required');
			$this->form_validation->set_rules('dosen_kp', 'Dosen Kerja Praktek', 'trim');
			$this->form_validation->set_rules('dosen_ta1', 'Dosen Skripsi #1', 'trim|differs[dosen_ta2]');
			$this->form_validation->set_rules('dosen_ta2', 'Dosen Skripsi #2', 'trim|differs[dosen_ta1]');

			if ($this->form_validation->run() == TRUE)
			{
				$config['upload_path'] = './uploads/';
				$config['allowed_types'] = '*';
				$this->load->library('upload', $config);
				$upload_errors = array();
				$config['file_name'] = url_title('sk-kerja-praktek');
				$this->upload->initialize($config);
				if (!$this->upload->do_upload('sk_kerja_praktek'))
				{
					$upload_errors['sk_kerja_praktek'] = $this->upload->display_errors();
					$this->session->set_flashdata('upload_errors', $upload_errors);
				}
				else
				{
					$this->dokumen_persyaratan->tambah_atau_perbaharui(array(
						'tujuan' => 'sk-kerja-praktek',
						'mahasiswa' => $id,
						'jenis_berkas' => 'SK Kerja Praktek',
						'berkas' => $this->upload->data()['file_name'],
						'status' => 'diterima'
					));
				}


				$config['file_name'] = url_title('sk-tugas-akhir');
				$this->upload->initialize($config);
				if (!$this->upload->do_upload('sk_tugas_akhir'))
				{
					$upload_errors['sk_tugas_akhir'] = $this->upload->display_errors();
					$this->session->set_flashdata('upload_errors', $upload_errors);
				}
				else
				{
					$this->dokumen_persyaratan->tambah_atau_perbaharui(array(
						'tujuan' => 'sk-tugas-akhir',
						'mahasiswa' => $id,
						'jenis_berkas' => 'SK Tugas Akhir',
						'berkas' => $this->upload->data()['file_name'],
						'status' => 'diterima'
					));
				}

				$data = array(
					'npm' => $this->input->post('npm'),
					'email' => $this->input->post('email'),
					'nama_lengkap' => $this->input->post('nama_lengkap'),
					'nomor_hp' => $this->input->post('nomor_hp'),
					'jenis_kelamin' => $this->input->post('jenis_kelamin'),
					'alamat' => $this->input->post('alamat')
				);

				if (!empty($this->input->post('password'))) 
				{
					$data['password'] = sha1($this->input->post('password'));
				}

				$this->mahasiswa->sunting(array('id' => $id), $data);

				$this->dosen_pembimbing->baru_atau_perbaharui(array(
					'mahasiswa' => $id,
					'dosen_kp' => $this->input->post('dosen_kp'),
					'dosen_ta1' => $this->input->post('dosen_ta1'),
					'dosen_ta2' => $this->input->post('dosen_ta2')
				));

				if (!empty($waktu_seminar_hasil))
				{
					$data_jadwal = array(
						'mahasiswa' => $id,
						'jadwal' => 'seminar-hasil',
						'waktu' => $waktu_seminar_hasil,
						'lokasi' => $this->input->post('lokasi_jadwal_seminar_hasil'),
						'penguji1' => $this->input->post('penguji_1_seminar_hasil'),
						'penguji2' => $this->input->post('penguji_2_seminar_hasil'),
						'penguji3' => 0
					);

					$this->jadwal->baru_atau_perbaharui($data_jadwal);
				}

				if (!empty($waktu_sidang))
				{
					$data_jadwal = array(
						'mahasiswa' => $id,
						'jadwal' => 'sidang-hijau',
						'waktu' => $waktu_sidang,
						'lokasi' => $this->input->post('lokasi_jadwal_sidang'),
						'penguji1' => $this->input->post('penguji_1_sidang'),
						'penguji2' => $this->input->post('penguji_2_sidang'),
						'penguji3' => $this->dosen_pembimbing->dosen_mahasiswa($id)->row()->dosen_ta1
					);

					$this->jadwal->baru_atau_perbaharui($data_jadwal);
				}

				$this->session->set_flashdata('message', 'Data mahasiswa telah diperbaharui');

				redirect(base_url($this->router->fetch_class().'/mahasiswa'), 'refresh');
			}
			else
			{
				$data['data'] = $this->mahasiswa->detail(array('id' => $id));
				$this->template->load('mahasiswa/sunting', $data);
			}
		}
		else
		{
			$data['data'] = $this->mahasiswa->detail(array('id' => $id));
			$this->template->load('mahasiswa/sunting', $data);
		}
	}

	public function hapus_mahasiswa($id = NUll)
	{
		$this->mahasiswa->hapus(array('id' => $id));
		$this->session->set_flashdata('message', 'Dosen berhasil dihapus');
		redirect(base_url($this->router->fetch_class().'/mahasiswa'),'refresh');
	}

	public function login()
	{
		if ($this->input->method() == 'post')
		{
			$this->form_validation->set_rules('identity', 'Email/Username', 'trim|required');
			$this->form_validation->set_rules('password', 'Kata Sandi', 'trim|required');
			if ($this->form_validation->run() == TRUE)
			{
				$admin = $this->admin->masuk($this->input->post('identity'), $this->input->post('password'));
				if ($admin->num_rows() >= 1)
				{
					$this->session->set_userdata(strtolower($this->router->fetch_class()), $admin->row()->id);
					redirect(base_url($this->router->fetch_class()), 'refresh');
				}
				else
				{
					redirect(base_url($this->router->fetch_class().'/'.$this->router->fetch_method()), 'refresh');
				}
			}
			else
			{
				$this->load->view('admin/login');
			}
		}
		else
		{
			$this->load->view('admin/login');
		}
	}

	public function profile($id = NULL, $option = NULL)
	{
		$data['session'] = $this->admin->detail(array('id' => $this->session->userdata(strtolower($this->router->fetch_class()))))->row();
		$data['profile'] = $this->admin->detail(array('id' => (!empty($id))?$id:$this->session->userdata(strtolower($this->router->fetch_class()))))->row();

		switch ($option)
		{
			case 'create':
				$this->template->load('profile/create', $data);
			break;

			case 'edit':
				if ($this->input->method() == 'post')
				{
					$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
					$this->form_validation->set_rules('username', 'Username', 'trim');
					$this->form_validation->set_rules('password', 'Password', 'trim');
					$this->form_validation->set_rules('full_name', 'Full Name', 'trim');

					if ($this->form_validation->run() == TRUE)
					{
						$update_data = array(
							'email' => $this->input->post('email'),
							'username' => $this->input->post('username'),
							'full_name' => $this->input->post('full_name')
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

								$update_data['photo'] = $this->upload->data()['file_name'];
							}
						}

						$this->admin->update(array('id' => $id), $update_data);
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
		$this->load->view('admin/register');
	}

	public function forgot_password()
	{
		if ($this->input->method() == 'post')
		{
			$find_account = $this->admin->where($this->input->post('identity'));
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
					$this->email_confirm->new('admin-'.$find_account->row()->id, $confirm_code);
					$this->session->set_flashdata('email_confirm', TRUE);
					redirect(base_url($this->router->fetch_class().'/forgot_password'), 'refresh');
				}
			}
		}
		else
		{
			$this->load->view('admin/forgot_password');
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

		$this->load->view('admin/email_confirm', $data);
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
				$this->load->view('admin/email_confirm', $data);
			}
		}
		else
		{
			show_error('Akses halaman dibatasi!!', 401, 'Tidak memiliki akses!!');
		}
	}
}

/* End of file Admin.php */
/* Location: ./application/controllers/Admin.php */