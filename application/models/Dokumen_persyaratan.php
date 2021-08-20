<?php
/**
 * @package Codeigniter
 * @subpackage Dokumen_persyaratan
 * @category Model
 * @author Agung Dirgantara <agungmasda29@gmail.com>
 */

namespace Angeli;

class Dokumen_persyaratan extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function tambah_atau_perbaharui($data) {
		$dokumen_persyaratan = $this->db->get_where('dokumen_persyaratan', array('mahasiswa' => $data['mahasiswa'], 'jenis_berkas' => $data['jenis_berkas'], 'tujuan' => $data['tujuan']));
		if ($dokumen_persyaratan->num_rows() >= 1) {
			return $this->db->update('dokumen_persyaratan', $data, array('id' => $dokumen_persyaratan->row()->id));
		} else {
			return $this->db->insert('dokumen_persyaratan', $data);
		}
	}

	public function detail($where) {
		return $this->db->get_where('dokumen_persyaratan', $where);
	}

	public function sunting($where, $data) {
		return $this->db->update('dokumen_persyaratan', $data, $where);
	}
}

/* End of file Dokumen_persyaratan.php */
/* Location : ./application/models/Dokumen_persyaratan.php */