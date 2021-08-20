<?php
/**
 * @package Codeigniter
 * @subpackage Judul_mahasiswa
 * @category Model
 * @author Agung Dirgantara <agungmasda29@gmail.com>
 */

namespace Angeli;

class Judul_mahasiswa extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function total_judul_kerja_praktek() {
		$this->db->where('jenis', 'kerja-praktek');
		$this->db->from('judul_mahasiswa');
		return $this->db->count_all_results();
	}

	public function total_juduk_tugas_akhir() {
		$this->db->where('jenis', 'tugas-akhir');
		$this->db->from('judul_mahasiswa');
		return $this->db->count_all_results();
	}

	public function tambah($data) {
		return $this->db->insert('judul_mahasiswa', $data);
	}

	public function ambil_data($limit = 10, $offset = 0, $where = array()) {
		if (!empty($where)) {
			$this->db->where($where);
		}
		return $this->db->get('judul_mahasiswa', $limit, $offset)->result_array();
	}

	public function detail($where) {
		return $this->db->get_where('judul_mahasiswa', $where);
	}

	public function sunting($where, $data) {
		return $this->db->update('judul_mahasiswa', $data, $where);
	}

	public function hapus($where) {
		return $this->db->delete('judul_mahasiswa', $where);
	}
}

/* End of file Judul_mahasiswa.php */
/* Location : ./application/models/Judul_mahasiswa.php */