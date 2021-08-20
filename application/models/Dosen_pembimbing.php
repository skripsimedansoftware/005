<?php
/**
 * @package Codeigniter
 * @subpackage Dosen_pembimbing
 * @category Model
 * @author Agung Dirgantara <agungmasda29@gmail.com>
 */

namespace Angeli;

class Dosen_pembimbing extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function dosen_mahasiswa($id_mahasiswa) {
		return $this->db->get_where('dosen_pembimbing', array('mahasiswa' => $id_mahasiswa));
	}

	public function baru_atau_perbaharui($data) {
		$dosen_pembimbing = $this->db->get_where('dosen_pembimbing', array('mahasiswa' => $data['mahasiswa']));
		if ($dosen_pembimbing->num_rows() >= 1) {
			return $this->db->update('dosen_pembimbing', $data, array('id' => $dosen_pembimbing->row()->id));
		} else {
			return $this->db->insert('dosen_pembimbing', $data);
		}
	}

	public function ambil_data($limit = 10, $offset = 0) {
		return $this->db->get('dosen_pembimbing', $limit, $offset)->result_array();
	}
}

/* End of file Dosen_pembimbing.php */
/* Location : ./application/models/Dosen_pembimbing.php */