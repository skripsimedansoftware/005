<?php
/**
 * @package Codeigniter
 * @subpackage Jadwal
 * @category Model
 * @author Agung Dirgantara <agungmasda29@gmail.com>
 */

namespace Angeli;

class Jadwal extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function tambah($data) {
		return $this->db->insert('jadwal', $data);
	}

	public function detail($where) {
		return $this->db->get_where('jadwal', $where);
	}

	public function sunting($where, $data) {
		return $this->db->update('jadwal', $data, $where);
	}

	public function hapus($where) {
		return $this->db->delete('jadwal', $where);
	}

	public function total_data() {
		return $this->db->count_all('jadwal');
	}

	public function ambil_data($limit = 10, $offset = 0) {
		return $this->db->get('jadwal', $limit, $offset)->result_array();
	}

	public function baru_atau_perbaharui($data) {
		$jadwal = $this->db->get_where('jadwal', array('mahasiswa' => $data['mahasiswa'], 'jadwal' => $data['jadwal']));
		if ($jadwal->num_rows() >= 1) {
			return $this->db->update('jadwal', $data, array('id' => $jadwal->row()->id));
		} else {
			return $this->db->insert('jadwal', $data);
		}
	}

	public function dosen($id_dosen) {
		$this->db->or_group_start()
			->or_where('penguji1', $id_dosen)
			->or_where('penguji2', $id_dosen)
			->or_where('penguji3', $id_dosen)
		->group_end();
		$this->db->order_by('waktu', 'desc');
		return $this->db->get('jadwal');
	}

	public function dosen_selesai($id_dosen) {
		$this->db->or_group_start()
			->or_where('penguji1', $id_dosen)
			->or_where('penguji2', $id_dosen)
			->or_where('penguji3', $id_dosen)
		->group_end();
		$this->db->where('status', 'selesai');
		$this->db->order_by('waktu', 'desc');
		return $this->db->get('jadwal');
	}

	public function mahasiswa($id_mahasiswa) {
		$this->db->where('mahasiswa', $id_mahasiswa);
		$this->db->order_by('waktu', 'desc');
		return $this->db->get('jadwal');
	}
}

/* End of file Jadwal.php */
/* Location : ./application/models/Jadwal.php */