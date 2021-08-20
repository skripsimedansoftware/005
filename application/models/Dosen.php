<?php
/**
 * @package Codeigniter
 * @subpackage Dosen
 * @category Model
 * @author Agung Dirgantara <agungmasda29@gmail.com>
 */

namespace Angeli;

class Dosen extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function tambah($data) {
		return $this->db->insert('dosen', $data);
	}

	public function detail($where) {
		return $this->db->get_where('dosen', $where);
	}

	public function sunting($where, $data) {
		return $this->db->update('dosen', $data, $where);
	}

	public function hapus($where) {
		return $this->db->delete('dosen', $where);
	}

	public function masuk($identity, $password) {
		$this->db->group_start()
			->where('nik', $identity)
			->or_group_start()
				->where('email', $identity)
			->group_end()
		->group_end()
		->where('password', sha1($password));
		return $this->db->get('dosen');
	}

	public function total_data() {
		return $this->db->count_all('dosen');
	}

	public function ambil_data($limit = 10, $offset = 0) {
		return $this->db->get('dosen', $limit, $offset)->result_array();
	}

	public function pembimbing_mahasiswa($id_dosen) {
		$this->db->group_start()
			->or_group_start()
				->or_where('dosen_kp', $id_dosen)
				->or_where('dosen_ta1', $id_dosen)
				->or_where('dosen_ta2', $id_dosen)
			->group_end()
		->group_end();
		return $this->db->get('dosen_pembimbing');
	}

	public function dosen_kerja_praktek($id_dosen) {
		$this->db->where('dosen_kp', $id_dosen);
		return $this->db->get('dosen_pembimbing');
	}

	public function dosen_tugas_akhir($id_dosen) {
		$this->db->where('dosen_ta1', $id_dosen);
		$this->db->or_where('dosen_ta2', $id_dosen);
		return $this->db->get('dosen_pembimbing');
	}

	public function konsultasi_mahasiswa($id_dosen, $status = '') {
		$judul_kerja_praktek = array();
		$judul_tugas_akhir = array();
		$dosen_kerja_praktek = $this->dosen_kerja_praktek($id_dosen);
		if ($dosen_kerja_praktek->num_rows() > 0) {
			foreach ($dosen_kerja_praktek->result_array() as $doping) {
				$where = array('jenis' => 'kerja-praktek', 'mahasiswa' => $doping['mahasiswa']);
				if (!empty($status)) {
					$where = array_merge($where, array('status' => $status));
				}

				$result = $this->db->get_where('judul_mahasiswa', $where)->row_array();
				if (!empty($result)) {
					$judul_kerja_praktek[] = $result;
				}
			}
		}

		$dosen_tugas_akhir = $this->dosen_tugas_akhir($id_dosen);
		if ($dosen_tugas_akhir->num_rows() > 0) {
			foreach ($dosen_tugas_akhir->result_array() as $doping) {
				$where = array('jenis' => 'tugas-akhir', 'mahasiswa' => $doping['mahasiswa']);
				if (!empty($status)) {
					$where = array_merge($where, array('status' => $status));
				}

				$result = $this->db->get_where('judul_mahasiswa', $where)->row_array();
				if (!empty($result)) {
					$judul_kerja_praktek[] = $result;
				}
			}
		}

		return array('dosen_kerja_praktek' => $judul_kerja_praktek, 'dosen_tugas_akhir' => $judul_tugas_akhir);
	}

	public function nomor_pembimbing($id_judul, $id_dosen) {
		$judul = $this->db->get_where('judul_mahasiswa', array('id' => $id_judul))->row();
		$this->db->group_start()
			->or_group_start()
				->or_where('dosen_ta1', $id_dosen)
				->or_where('dosen_ta2', $id_dosen)
			->group_end()
		->group_end();
		$this->db->where('mahasiswa', $judul->mahasiswa);
		return $this->db->get('dosen_pembimbing');
	}

	public function ambil_data_kecuali($id)
	{
		$this->db->where_not_in('id', $id);
		return $this->db->get('dosen');
	}
}

/* End of file Dosen.php */
/* Location : ./application/models/Dosen.php */