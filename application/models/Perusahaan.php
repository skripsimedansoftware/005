<?php
/**
 * @package Codeigniter
 * @subpackage Perusahaan
 * @category Model
 * @author Agung Dirgantara <agungmasda29@gmail.com>
 */

namespace Angeli;

class Perusahaan extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function tambah_atau_perbaharui($data) {
		$perusahaan = $this->db->get_where('perusahaan', array('mahasiswa' => $data['mahasiswa']));
		if ($perusahaan->num_rows() >= 1) {
			return $this->db->update('perusahaan', $data, array('id' => $perusahaan->row()->id));
		} else {
			return $this->db->insert('perusahaan', $data);
		}
	}

	public function detail($where) {
		return $this->db->get_where('perusahaan', $where);
	}
}

/* End of file Perusahaan.php */
/* Location : ./application/models/Perusahaan.php */