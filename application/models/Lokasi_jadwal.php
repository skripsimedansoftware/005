<?php
/**
 * @package Codeigniter
 * @subpackage Lokasi_jadwal
 * @category Model
 * @author Agung Dirgantara <agungmasda29@gmail.com>
 */

namespace Angeli;

class Lokasi_jadwal extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function tambah($data) {
		return $this->db->insert('lokasi_jadwal', $data);
	}

	public function detail($where) {
		return $this->db->get_where('lokasi_jadwal', $where);
	}

	public function sunting($where, $data) {
		return $this->db->update('lokasi_jadwal', $data, $where);
	}

	public function hapus($where) {
		return $this->db->delete('lokasi_jadwal', $where);
	}

	public function total_data() {
		return $this->db->count_all('lokasi_jadwal');
	}

	public function ambil_data($limit = 10, $offset = 0) {
		return $this->db->get('lokasi_jadwal', $limit, $offset)->result_array();
	}
}

/* End of file Lokasi_jadwal.php */
/* Location : ./application/models/Lokasi_jadwal.php */