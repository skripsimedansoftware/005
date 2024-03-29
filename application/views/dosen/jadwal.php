<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Dosen<small>| Jadwal</small></h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">Jadwal Seminar dan Sidang</h3>
		</div>
		<div class="box-body">
			<table class="table table-hover table-striped datatables">
				<thead>
					<th>No</th>
					<th>Jenis</th>
					<th>Waktu</th>
					<th>Lokasi</th>
					<th>Mahasiswa</th>
					<th>Penguji 1</th>
					<th>Penguji 2</th>
					<th>Penguji 3</th>
					<th>Status</th>
				</thead>
				<tbody>
					<?php  foreach ($data as $key => $jadwal) : ?>
					<tr>
						<td><?php echo $key+1 ?></td>
						<td><?php echo ($jadwal['jadwal'] == 'seminar-hasil')?'Seminar Hasil':'Sidang' ?></td>
						<td><?php echo nice_date($jadwal['waktu'], 'd-m-Y H:i a') ?></td>
						<td>
							<?php
							$lokasi = $this->lokasi_jadwal->detail(array('id' => $jadwal['lokasi']));
							if ($lokasi->num_rows() > 0)
							{
								?><?php echo $lokasi->row()->keterangan ?></a><?php
							}
							else
							{
								echo 'Lokasi tidak ditemukan';
							}
							?>
						</td>
						<td>
							<?php 
								$mahasiswa = $this->mahasiswa->detail(array('id' => $jadwal['mahasiswa']));
								if ($mahasiswa->num_rows() > 0)
								{
									?><a href="<?php echo base_url($this->router->fetch_class().'/profil_mahasiswa/'.$mahasiswa->row()->id) ?>"><?php echo $mahasiswa->row()->npm.' - '.$mahasiswa->row()->nama_lengkap ?></a><?php
								}
								else
								{
									echo 'Mahasiswa tidak ditemukan';
								}
							?>
						</td>
						<td>
							<?php 
							$dosen = $this->dosen->detail(array('id' => $jadwal['penguji1']));
							if ($dosen->num_rows() > 0)
							{
								?><a href="<?php echo base_url($this->router->fetch_class().'/profil_dosen/'.$dosen->row()->id) ?>"><?php echo $dosen->row()->nik.' - '.$dosen->row()->nama_lengkap ?></a><?php
							}
							else
							{
								echo 'Dosen tidak ditemukan';
							}
							?>
						</td>
						<td>
							<?php 
							$dosen = $this->dosen->detail(array('id' => $jadwal['penguji2']));
							if ($dosen->num_rows() > 0)
							{
								?><a href="<?php echo base_url($this->router->fetch_class().'/profil_dosen/'.$dosen->row()->id) ?>"><?php echo $dosen->row()->nik.' - '.$dosen->row()->nama_lengkap ?></a><?php
							}
							else
							{
								echo 'Dosen tidak ditemukan';
							}
							?>
						</td>
						<td>
							<?php 
							$dosen = $this->dosen->detail(array('id' => $jadwal['penguji3']));
							if ($dosen->num_rows() > 0)
							{
								?><a href="<?php echo base_url($this->router->fetch_class().'/dosen/detail/'.$dosen->row()->id) ?>"><?php echo $dosen->row()->nik.' - '.$dosen->row()->nama_lengkap ?></a><?php
							}
							else
							{
								echo ($jadwal['jadwal'] == 'seminar-hasil')?'-':'Dosen tidak ditemukan';
							}
							?>
						</td>
						<td><?php echo ucfirst($jadwal['status']) ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</section>

<script type="text/javascript">
$(document).ready(function() {
	$('.datatables').DataTable();
});
</script>