<?php
/**
 * @package Codeigniter
 * @subpackage Konsultasi
 * @category Model
 * @author Agung Dirgantara <agungmasda29@gmail.com>
 */

namespace Angeli;

class Konsultasi extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function tambah($data) {
		return $this->db->insert('konsultasi', $data);
	}

	public function detail($where) {
		return $this->db->get_where('konsultasi', $where);
	}

	public function sunting($where, $data) {
		return $this->db->update('konsultasi', $data, $where);
	}

	public function hapus($where) {
		return $this->db->delete('konsultasi', $where);
	}

	public function ambil_data($limit = 10, $judul_id) {
		$total = $this->db->count_all_results('konsultasi');
		$this->db->where('judul_id', $judul_id);
		$this->db->order_by('id', 'asc');
		return $this->db->get('konsultasi', $limit, round($total/$limit));
	}
}

/* End of file Konsultasi.php */
/* Location : ./application/models/Konsultasi.php */