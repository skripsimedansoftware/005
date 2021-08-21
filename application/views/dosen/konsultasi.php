<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Halaman<small>Dosen</small></h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title">Daftar Konsultasi</h3>
		</div>
		<div class="box-body">
			<table class="table table-hover table-striped datatables">
				<thead>
					<th>No</th>
					<th>Mahasiswa</th>
					<th>Jenis</th>
					<th>Posisi</th>
					<th>Tanggapan Saya</th>
					<th>Tanggapan Doping Lain</th>
					<th>Status</th>
					<th>Konsultasi</th>
					<th>Opsi</th>
				</thead>
				<tbody>
					<?php  foreach ($konsultasi as $key => $judul) : ?>
					<tr>
						<td><?php echo $key+1 ?></td>
						<td>
							<?php 
								$mahasiswa = $this->mahasiswa->detail(array('id' => $judul['mahasiswa']));
								if ($mahasiswa->num_rows() >= 1)
								{
									echo $mahasiswa->row()->npm.' - '.$mahasiswa->row()->nama_lengkap;
								}
							?>
						</td>
						<td><?php echo ($judul['jenis'] == 'kerja-praktek')?'Kerja Praktek':'Tugas Akhir' ?></td>
						<?php
						if ($judul['jenis'] == 'kerja-praktek')
						{
							echo '<td>PEMBIMBING</td>';
							if ($judul['status'] == 'proses')
							{
								echo '<td><b>BELUM DITANGGAPI</b></td>';
								echo '<td>-</td>';
							}
							else
							{
								echo '<td>';
								switch ($judul['status']) {
									case 'revisi':
										echo '<button class="btn btn-warning btn-xs">'.ucfirst($judul['status']).'</button>';
									break;

									case 'diterima':
										echo '<button class="btn btn-success btn-xs">'.ucfirst($judul['status']).'</button>';
									break;

									case 'ditolak':
										echo '<button class="btn btn-danger btn-xs">'.ucfirst($judul['status']).'</button>';
									break;

									default:
										echo '<button class="btn btn-primary btn-xs">'.ucfirst($judul['status']).'</button>';
									break;
								}
								echo '</td>';
								echo '<td>-</td>';
							}
						}
						else
						{
							$nomor_pembimbing = $this->dosen->nomor_pembimbing($judul['id'], $this->session->userdata('pengguna')['id'])->row();
							if ($nomor_pembimbing->dosen_ta1 == $this->session->userdata('pengguna')['id'])
							{
								echo '<td>PEMBIMBING 1</td>';
							}
							else
							{
								echo '<td>PEMBIMBING 2</td>';
							}
						}
						?>
						<td>
						<?php
						if ($judul['jenis'] == 'kerja-praktek')
						{
							switch ($judul['status']) {
								case 'revisi':
									echo '<button class="btn btn-warning btn-xs">'.ucfirst($judul['status']).'</button>';
								break;

								case 'diterima':
									echo '<button class="btn btn-success btn-xs">'.ucfirst($judul['status']).'</button>';
								break;

								case 'ditolak':
									echo '<button class="btn btn-danger btn-xs">'.ucfirst($judul['status']).'</button>';
								break;

								default:
									echo '<button class="btn btn-primary btn-xs">'.ucfirst($judul['status']).'</button>';
								break;
							}
						}
						else
						{
							$nomor_pembimbing = $this->dosen->nomor_pembimbing($judul['id'], $this->session->userdata('pengguna')['id'])->row();
							if ($nomor_pembimbing->dosen_ta1 == $this->session->userdata('pengguna')['id'])
							{
								$nomor_pembimbing = 1;
							}
							else
							{
								$nomor_pembimbing = 2;
							}

							switch ($judul['tanggapan_doping_'.$nomor_pembimbing]) {
								case 'revisi':
									echo '<button class="btn btn-warning btn-xs">'.ucfirst($judul['tanggapan_doping_'.$nomor_pembimbing]).'</button>';
								break;

								case 'diterima':
									echo '<button class="btn btn-success btn-xs">'.ucfirst($judul['tanggapan_doping_'.$nomor_pembimbing]).'</button>';
								break;

								case 'ditolak':
									echo '<button class="btn btn-danger btn-xs">'.ucfirst($judul['tanggapan_doping_'.$nomor_pembimbing]).'</button>';
								break;

								default:
									echo '<button class="btn btn-primary btn-xs">'.ucfirst($judul['tanggapan_doping_'.$nomor_pembimbing]).'</button>';
								break;
							}

							$pembimbing_lainnya = ($nomor_pembimbing == 1)?2:1;
							echo '<td>';
							switch ($judul['tanggapan_doping_'.$pembimbing_lainnya]) {
								case 'revisi':
									echo '<button class="btn btn-warning btn-xs">'.ucfirst($judul['tanggapan_doping_'.$pembimbing_lainnya]).'</button>';
								break;

								case 'diterima':
									echo '<button class="btn btn-success btn-xs">'.ucfirst($judul['tanggapan_doping_'.$pembimbing_lainnya]).'</button>';
								break;

								case 'ditolak':
									echo '<button class="btn btn-danger btn-xs">'.ucfirst($judul['tanggapan_doping_'.$pembimbing_lainnya]).'</button>';
								break;

								default:
									echo '<button class="btn btn-primary btn-xs">'.ucfirst($judul['tanggapan_doping_'.$pembimbing_lainnya]).'</button>';
								break;
							}
							echo '</td>';

							echo '<td>';
							switch ($judul['status']) {
								case 'revisi':
									echo '<button class="btn btn-warning btn-xs">'.ucfirst($judul['status']).'</button>';
								break;

								case 'diterima':
									echo '<button class="btn btn-success btn-xs">'.ucfirst($judul['status']).'</button>';
								break;

								case 'ditolak':
									echo '<button class="btn btn-danger btn-xs">'.ucfirst($judul['status']).'</button>';
								break;

								default:
									echo '<button class="btn btn-primary btn-xs">'.ucfirst($judul['status']).'</button>';
								break;
							}
							echo '</td>';
						}
						?>
						</td>

						<td><a class="btn btn-sm btn-success btn-xs" href="<?php echo base_url($this->router->fetch_class().'/konsultasi/'.$judul['id'])  ?>"><i class="fa fa-comments-o"></i></a></td>
						<td><a href="<?php echo base_url($this->router->fetch_class().'/tanggapi_judul/'.$judul['id'])  ?>" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
			<a onclick="window.history.back()" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
		</div>
	<!-- /.box-footer-->
	</div>
</section>

<script type="text/javascript">
$(document).ready(function() {
	$('.datatables').DataTable();
});
</script>