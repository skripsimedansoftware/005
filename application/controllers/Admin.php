<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use iio\libmergepdf\Merger;
use iio\libmergepdf\Pages;

class Admin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('template', ['module' => 'admin']);
		$this->load->library('fpdf183/fpdf');
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

	public function upload_syarat_dokumen($mahasiswa_id = NULL, $jenis = NULL)
	{
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = '*';
		$this->load->library('upload', $config);
		$upload_errors = array();
		$jenis = ($jenis == 'kerja_praktek')?'kerja-praktek':'tugas-akhir';
		$config['file_name'] = url_title('surat-pengantar-perusahaan');
		$this->upload->initialize($config);
		if (!$this->upload->do_upload('surat_pengantar_perusahaan'))
		{
			$upload_errors['surat_pengantar_perusahaan'] = $this->upload->display_errors();
			$this->session->set_flashdata('upload_errors', $upload_errors);
		}
		else
		{
			$this->dokumen_persyaratan->tambah_atau_perbaharui(array(
				'tujuan' => $jenis,
				'mahasiswa' => $mahasiswa_id,
				'jenis_berkas' => 'Surat Pengantar Perusahaan',
				'berkas' => $this->upload->data()['file_name'],
				'status' => 'diterima'
			));
		}

		redirect(base_url($this->router->fetch_class().'/mahasiswa/detail/'.$mahasiswa_id) ,'refresh');
	}

	public function set_status_berkas_persyaratan($id, $status = 'diterima')
	{
		$detail = $this->dokumen_persyaratan->detail(array('id' => $id));
		$this->dokumen_persyaratan->sunting(array('id' => $id), array('status' => $status));
		redirect(base_url($this->router->fetch_class().'/mahasiswa/detail/'.$detail->row()->mahasiswa), 'refresh');
	}

	public function judul_kerja_praktek($id = NULL, $status = 'diterima')
	{
		$data['session'] = $this->admin->detail(array('id' => $this->session->userdata(strtolower($this->router->fetch_class()))))->row();
		$data['data'] = $this->judul_mahasiswa->ambil_data(100, 0, array('jenis' => 'kerja-praktek'));
		$this->template->load('judul_kerja_praktek', $data);
	}

	public function judul_skripsi($id = NULL)
	{
		$data['session'] = $this->admin->detail(array('id' => $this->session->userdata(strtolower($this->router->fetch_class()))))->row();
		$data['data'] = $this->judul_mahasiswa->ambil_data(100, 0, array('jenis' => 'tugas-akhir'));
		$this->template->load('judul_skripsi', $data);
	}

	public function jadwal_seminar_hasil()
	{
		$data['session'] = $this->admin->detail(array('id' => $this->session->userdata(strtolower($this->router->fetch_class()))))->row();
		$data['data'] = $this->jadwal->detail(array('jadwal' => 'seminar-hasil'));
		$this->template->load('jadwal_seminar_hasil', $data);
	}

	public function jadwal_sidang()
	{
		$data['session'] = $this->admin->detail(array('id' => $this->session->userdata(strtolower($this->router->fetch_class()))))->row();
		$data['data'] = $this->jadwal->detail(array('jadwal' => 'sidang-hijau'));
		$this->template->load('jadwal_sidang', $data);
	}

	public function lokasi() 
	{
		$data['session'] = $this->admin->detail(array('id' => $this->session->userdata(strtolower($this->router->fetch_class()))))->row();
		$data['data'] = $this->lokasi_jadwal->ambil_data($this->lokasi_jadwal->total_data());
		$this->template->load('lokasi/index', $data);
	}

	public function tambah_lokasi()
	{
		$data['session'] = $this->admin->detail(array('id' => $this->session->userdata(strtolower($this->router->fetch_class()))))->row();
		if ($this->input->method() == 'post')
		{
			$this->lokasi_jadwal->tambah(array(
				'kode' => $this->input->post('kode'),
				'keterangan' => $this->input->post('lokasi')
			));

			$this->session->set_flashdata('message', 'Data lokasi telah ditambahkan');

			redirect(base_url($this->router->fetch_class().'/lokasi'), 'refresh');
		}
		else
		{
			$this->template->load('lokasi/tambah', $data);
		}
	}

	public function sunting_lokasi($id)
	{
		$data['session'] = $this->admin->detail(array('id' => $this->session->userdata(strtolower($this->router->fetch_class()))))->row();
		if ($this->input->method() == 'post')
		{
			$this->lokasi_jadwal->sunting(array('id' => $id), array(
				'kode' => $this->input->post('kode'),
				'keterangan' => $this->input->post('lokasi')
			));

			$this->session->set_flashdata('message', 'Data lokasi telah diperbaharui');

			redirect(base_url($this->router->fetch_class().'/lokasi'), 'refresh');
		}
		else
		{
			$data['data'] = $this->lokasi_jadwal->detail(array('id' => $id));
			$this->template->load('lokasi/sunting', $data);
		}
	}

	public function hapus_lokasi($id)
	{
		$this->lokasi_jadwal->hapus(array('id' => $id));
		$this->session->set_flashdata('message', 'Lokasi berhasil dihapus');
		redirect(base_url($this->router->fetch_class().'/lokasi'),'refresh');
	}

	public function cetak_sk()
	{
		$data['session'] = $this->admin->detail(array('id' => $this->session->userdata(strtolower($this->router->fetch_class()))))->row();
		$data['data'] = $this->dosen_pembimbing->ambil_data(100);
		$this->template->load('cetak_sk', $data);
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

	public function generate_pdf($mahasiswa, $dokumen = 'kerja-praktek')
	{
		$mahasiswa = $this->mahasiswa->detail(array('id' => $mahasiswa));
		$dosen_pembimbing = $this->dosen_pembimbing->dosen_mahasiswa($mahasiswa->row()->id);

		if ($dosen_pembimbing->num_rows() >= 1)
		{
			$dosen_kp = 'BELUM DITENTUKAN';
			$dosen_ta_1 = 'BELUM DITENTUKAN';
			$dosen_ta_2 = 'BELUM DITENTUKAN';

			if (!empty($dosen_pembimbing->row()->dosen_kp))
			{
				$dosen = $this->dosen->detail(array('id' => $dosen_pembimbing->row()->dosen_kp));
				if ($dosen->num_rows() >= 1)
				{
					$dosen_kp = $dosen->row();
				}
			}

			if (!empty($dosen_pembimbing->row()->dosen_ta1))
			{
				$dosen = $this->dosen->detail(array('id' => $dosen_pembimbing->row()->dosen_ta1));
				if ($dosen->num_rows() >= 1)
				{
					$dosen_ta_1 = $dosen->row();
				}
			}

			if (!empty($dosen_pembimbing->row()->dosen_ta2))
			{
				$dosen = $this->dosen->detail(array('id' => $dosen_pembimbing->row()->dosen_ta2));
				if ($dosen->num_rows() >= 1)
				{
					$dosen_ta_2 = $dosen->row();
				}
			}

			$table_headers = array(
				array('label' => 'NPM', 'length' => 30, 'align' => 'C'),
				array('label' => 'NAMA LENGKAP', 'length' => 80, 'align' => 'L'),
				array('label' => 'NAMA PEMBIMBING', 'length' => 80, 'align' => 'L')
			);

			$pdf = new FPDF();
			$pdf->AddPage('P', 'Legal', 'C');

			// HEADER
			$pdf->SetFont('arial', 'B', '15');
			$pdf->Image(FCPATH.'assets/unhar-logo.png', 26, 6, 30);
			$pdf->Cell(202, 0, 'UNIVERSITAS HARAPAN MEDAN', NULL, 200, 'C');
			$pdf->Cell(200, 14, 'FAKULTAS TEKNIK DAN KOMPUTER', NULL, 200, 'C');

			// DESCRIPTION
			$pdf->SetFont('arial', '', '10');
			$pdf->Cell(200, -2, 'JL.H.M JONI NO 70C MEDAN', NULL, 200, 'C');
			$pdf->Cell(200, 10, 'Telp. Fax. (061) 7366804 - 7349455', NULL, 200, 'C');
			$pdf->Cell(200, 0, 'Website : http://www.ftkunhar.ac.id Email : biro.ftk.unhar.ac.id', NULL, 200, 'C');

			// LINE
			$pdf->Rect(1, 40, 212, 1);

			if ($dokumen == 'kerja-praktek')
			{
				$pdf->SetFillColor(255, 255, 255, 0);
				$pdf->Cell(0, 20,'', 0);
				$pdf->Ln();
				$pdf->Cell(10, 6, 'No', 0);
				$pdf->Cell(0, 6, ': 001/SPM-I/VIII/SI. FTK/2021', 0);
				$pdf->Cell(0, 6, 'Medan,                                  ', 0, NULL, 'R');
				$pdf->Ln();
				$pdf->Cell(10, 6, 'Lamp', 0);
				$pdf->Cell(0, 6, ': 1 (satu) lembar', 0);
				$pdf->Ln();
				$pdf->Cell(10, 6, 'Hal', 0);
				$pdf->Cell(0, 6, ': Permohonan Pengantar Kerja Praktik', 0);

				$pdf->Cell(0, 14,'', 0);
				$pdf->Ln();

				$pdf->Cell(10, 5, 'Kepada Yth', 0);
				$pdf->Ln();
				$pdf->Cell(0, 5, 'Bapak Dekan Fakultas Teknik dan Komputer', 0);
				$pdf->Ln();
				$pdf->Cell(0, 5, 'Universitas Harapan Medan', 0);

				$pdf->Cell(0, 14,'', 0);
				$pdf->Ln();
				$pdf->Cell(0, 5, 'Assalamu\'alaikum Wr Wb.', 0);
				$pdf->Ln();
				$pdf->Cell(0, 5, 'Dengan hormat, bersama ini kami kirim nama dan tempat kerja praktik mahasiswa Program Studi Sistem Informasi', 0);
				$pdf->Ln();
				$pdf->Cell(0, 5, 'dalam memenuhi persyaratan pelaksanaan Kerja Praktik. Untuk itu kami mohon Kepada Bapak', 0);
				$pdf->Ln();
				$pdf->Cell(0, 5, 'untuk membuat surat pengantar Kerja Praktik.', 0);

				$perusahaan = array();
				$query = $this->perusahaan->detail(array('mahasiswa' => $this->session->userdata(strtolower($this->router->fetch_class()))));
				if ($query->num_rows() >= 1)
				{
					$perusahaan = $query->row_array();
				}

				$pdf->Cell(0, 12,'', 0);
				$pdf->Ln();

				$table_headers = array(
					array('label' => 'NPM', 'length' => 30, 'align' => 'C'),
					array('label' => 'NAMA LENGKAP', 'length' => 80, 'align' => 'L'),
					array('label' => 'NAMA PEMBIMBING', 'length' => 80, 'align' => 'L')
				);
				$pdf->SetFont('arial', 'B');
				foreach ($table_headers as $column)
				{
					$pdf->Cell($column['length'], 8, $column['label'], 1, NULL, $column['align']);
				}

				$pdf->Ln();
				$pdf->SetFont('arial', '', '10');
				$pdf->Cell(30, 6, $mahasiswa->row()->npm, 1, NULL, 'C');
				$pdf->Cell(80, 6, $mahasiswa->row()->nama_lengkap, 1, NULL, 'L');

				$pdf->Ln();
				$pdf->Cell(0, 8,'', 0);
				$pdf->Ln();
				$pdf->Cell(40, 6, 'Nama Perusahaan', 0);
				$pdf->Cell(0, 6, ': '.(!empty($perusahaan))?$perusahaan['nama']:'', 0);
				$pdf->Ln();
				$pdf->Cell(40, 6, 'Alamat', 0);
				$pdf->Cell(0, 6, ': '.(!empty($perusahaan))?$perusahaan['alamat']:'', 0);
				$pdf->Ln();

				$pdf->Cell(0, 10,'', 0);
				$pdf->Ln();
				$pdf->Cell(0, 5, 'Demikian permohonan ini kami sampaikan dan atas perkenan Bapak Kami Ucapkan terima Kasih.', 0);
				$pdf->Cell(0, 12,'', 0);
				$pdf->Ln();

				$pdf->Ln();
				$pdf->Cell(0, 5, 'Program Studi Sistem Informasi', 0, NULL, 'R');
				$pdf->Ln();
				$pdf->Cell(0, 5, 'Sekretaris,                                  ', 0, NULL, 'R');

				$pdf->Cell(0, 16,'', 0);
				$pdf->Ln();

				$pdf->Ln();
				$pdf->SetFont('arial', 'B');
				$pdf->Cell(0, 5, 'Ahmad Zakir ST. M.Kom         ', 0, NULL, 'R');
				$pdf->Ln();
			}
			else
			{
				$pdf->SetFillColor(255, 255, 255, 0);
				$pdf->Cell(0, 20,'', 0);
				$pdf->Ln();
				$pdf->Cell(20, 6, 'Lampiran No', 0);
				$pdf->Cell(0, 6, ' : 113/SK-N/III/FTK UnHar/2021', 0);
				$pdf->Cell(0, 12,'', 0);
				$pdf->Ln();

				$pdf->Cell(0, 4,'', 0);
				$pdf->Ln();

				$pdf->SetFont('arial', 'B');
				foreach ($table_headers as $column)
				{
					$pdf->Cell($column['length'], 8, $column['label'], 1, NULL, $column['align']);
				}

				$pdf->Ln();

				$pdf->SetFont('arial', '', '10');
				$pdf->Cell(30, 12, $mahasiswa->row()->npm, 1, NULL, 'C');
				$pdf->Cell(80, 12, $mahasiswa->row()->nama_lengkap, 1, NULL, 'L');
				$pdf->MultiCell(80, 6, '1.) '.(is_object($dosen_ta_1)?$dosen_ta_1->nama_lengkap:$dosen_ta_1), 1, 'L', FALSE);
				$pdf->Cell(110, 0, '', 0, NULL);
				$pdf->MultiCell(80, 6, '2.) '.(is_object($dosen_ta_2)?$dosen_ta_2->nama_lengkap:$dosen_ta_2), 1, 'L', FALSE);

				$pdf->Ln();
				$pdf->Cell(0, 5, 'Ditetapkan : Medan                  ', 0, NULL, 'R');
				$pdf->Ln();
				$pdf->Cell(0, 5, 'Pada tanggal : '.date('d').' '.date('F').' 2021', 0, NULL, 'R');
				$pdf->Ln();
				$pdf->Cell(0, 5, 'Dekan                                       ', 0, NULL, 'R');

				$pdf->Cell(0, 14,'', 0);
				$pdf->Ln();

				$pdf->Ln();
				$pdf->SetFont('arial', 'BU');
				$pdf->Cell(0, 5, 'Abdul Jabbar Lubis, ST. M.Kom', 0, NULL, 'R');
				$pdf->Ln();
				$pdf->SetFont('arial', 'BU', 8);
				$pdf->Cell(0, 5, 'Tebusan : ', 0, NULL, 'L');
				$pdf->Ln();
				$pdf->SetFont('arial', '', 8);
				$pdf->Cell(0, 5, '1. Arsip', 0, NULL, 'L');

				$file_name = FCPATH.'uploads/surat-permohonan-skripsi-'.$mahasiswa->row()->npm.'.pdf';

				if (file_exists($file_name))
				{
					unlink($file_name);
				}

				$pdf->Output('F', $file_name);
				$merger = new Merger;
				$merger->addIterator([FCPATH.'assets/SKP-TA.pdf', $file_name]);
				$mergedPDF = $merger->merge();

				file_put_contents($file_name, $mergedPDF);
				$this->output->set_content_type('application/pdf')->set_output($mergedPDF);
			}

			$pdf->Output();
		}
		else
		{
			show_404();
		}
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