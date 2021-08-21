<?php
/**
 * @package Codeigniter
 * @subpackage Mahasiswa
 * @category Model
 * @author Agung Dirgantara <agungmasda29@gmail.com>
 */

namespace Angeli;

class Mahasiswa extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function tambah($data) {
		return $this->db->insert('mahasiswa', $data);
	}

	public function detail($where) {
		return $this->db->get_where('mahasiswa', $where);
	}

	public function sunting($where, $data) {
		return $this->db->update('mahasiswa', $data, $where);
	}

	public function hapus($where) {
		return $this->db->delete('mahasiswa', $where);
	}

	public function masuk($identity, $password) {
		$this->db->group_start()
			->where('npm', $identity)
			->or_group_start()
				->where('email', $identity)
			->group_end()
		->group_end()
		->where('password', sha1($password));
		return $this->db->get('mahasiswa');
	}

	public function where($identity) {
		$this->db->group_start()
			->where('npm', $identity)
			->or_group_start()
				->where('email', $identity)
			->group_end()
		->group_end();
		return $this->db->get('mahasiswa');
	}

	public function total_data() {
		return $this->db->count_all('mahasiswa');
	}

	public function ambil_data($limit = 10, $offset = 0) {
		return $this->db->get('mahasiswa', $limit, $offset)->result_array();
	}
}

/* End of file Mahasiswa.php */
/* Location : ./application/models/Mahasiswa.php */