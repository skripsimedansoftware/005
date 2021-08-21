<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Administrator<small>Mahasiswa</small></h1>
</section>

<!-- Main content -->
<section class="content">
	<?php if ($this->session->has_userdata('message')) : ?>
		<div class="alert alert-info alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<h4><i class="icon fa fa-warning"></i> Info!</h4>
			<?php echo $this->session->flashdata('message') ?>
		</div>
	<?php endif; ?>
	<?php if (is_array($data)): ?>
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">Daftar Mahasiswa</h3>
		</div>
		<div class="box-body">
			<table class="table table-hover table-striped datatables">
				<thead>
					<th>No</th>
					<th>NPM</th>
					<th>Jenis Kelamin</th>
					<th>Nama Lengkap</th>
					<th>Dosen KP</th>
					<th>Dosen TA1</th>
					<th>Dosen TA2</th>
					<th>Opsi</th>
				</thead>
				<tbody>
					<?php foreach ($data as $key => $value) : 
						$dosen_mahasiswa = $this->dosen_pembimbing->dosen_mahasiswa($value['id']);
					?>
					<tr>
						<td><?php echo $key+1 ?></td>
						<td><?php echo $value['npm'] ?></td>
						<td><?php echo $value['jenis_kelamin'] ?></td>
						<td><?php echo $value['nama_lengkap'] ?></td>
						<?php if ($dosen_mahasiswa->num_rows() === 0): ?>
						<td>Belum ditentukan</td>
						<td>Belum ditentukan</td>
						<td>Belum ditentukan</td>
						<?php else: ?>
						<td>
							<?php 
							$dosen = $this->dosen->detail(array('id' => $dosen_mahasiswa->row()->dosen_kp));
							if ($dosen->num_rows() > 0)
							{
								?><a href="<?php echo base_url($this->router->fetch_class().'/dosen/detail/'.$dosen->row()->id) ?>"><?php echo $dosen->row()->nik.' - '.$dosen->row()->nama_lengkap ?></a><?php
							}
							else
							{
								echo 'Dosen tidak ditemukan';
							}
							?>
						</td>
						<td>
							<?php 
							$dosen = $this->dosen->detail(array('id' => $dosen_mahasiswa->row()->dosen_ta1));
							if ($dosen->num_rows() > 0)
							{
								?><a href="<?php echo base_url($this->router->fetch_class().'/dosen/detail/'.$dosen->row()->id) ?>"><?php echo $dosen->row()->nik.' - '.$dosen->row()->nama_lengkap ?></a><?php
							}
							else
							{
								echo 'Belum di tentukan';
							}
							?>
						</td>
						<td>
							<?php 
							$dosen = $this->dosen->detail(array('id' => $dosen_mahasiswa->row()->dosen_ta2));
							if ($dosen->num_rows() > 0)
							{
								?><a href="<?php echo base_url($this->router->fetch_class().'/dosen/detail/'.$dosen->row()->id) ?>"><?php echo $dosen->row()->nik.' - '.$dosen->row()->nama_lengkap ?></a><?php
							}
							else
							{
								echo 'Belum di tentukan';
							}
							?>
						</td>
						<?php endif; ?>
						<td>
							<a href="<?php echo base_url($this->router->fetch_class().'/sunting_mahasiswa/'.$value['id']) ?>" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a>
							<a href="<?php echo base_url($this->router->fetch_class().'/mahasiswa/detail/'.$value['id']) ?>" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a>
							<a href="<?php echo base_url($this->router->fetch_class().'/hapus_mahasiswa/'.$value['id']) ?>" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
			<a href="<?php echo base_url($this->router->fetch_class().'/tambah_mahasiswa') ?>" class="btn btn-primary">Tambah Mahasiswa <i class="fa fa-user-plus"></i></a>
		</div>
	<!-- /.box-footer-->
	</div>
	<?php 
	else :
	?>
	<div class="row">
		<div class="col-lg-4 col-xs-12">
			<div class="box box-primary">
				<div class="box-body box-profile">
					<?php 

					$foto_default = ($data->jenis_kelamin == 'perempuan')?base_url('assets/adminlte/dist/img/user5-128x128.jpg'):base_url('assets/adminlte/dist/img/user1-128x128.jpg')
					?>
					<img class="profile-user-img img-responsive img-circle" src="<?php echo (!empty($data->foto))?base_url('uploads/'.$data->foto):$foto_default ?>" alt="User profile picture">
					<h3 class="profile-username text-center"><?php echo $data->nama_lengkap ?></h3>
					<p class="text-muted text-center"><?php echo ($data->jenis_kelamin == 'perempuan')?'Mahasiswi':'Mahasiswa' ?></p>
					<ul class="list-group list-group-unbordered">
						<li class="list-group-item">
							<b>NPM</b> <a class="pull-right"><?php echo $data->npm ?></a>
						</li>
						<li class="list-group-item">
							<b>Email</b> <a class="pull-right"><?php echo $data->email ?></a>
						</li>
						<li class="list-group-item">
							<b>Nomor HP</b> <a class="pull-right"><?php echo (!empty($data->nomor_hp))?$data->nomor_hp:'-' ?></a>
						</li>
						<li class="list-group-item">
							<b>Jenis Kelamin</b> <a class="pull-right"><?php echo $data->jenis_kelamin ?></a>
						</li>
						<li class="list-group-item">
							<b>Alamat</b> <a class="pull-right"><?php echo (!empty($data->alamat))?$data->alamat:'-' ?></a>
						</li>
					</ul>
				</div>
			</div>
			<a href="#" class="btn btn-primary btn-block" onclick="window.history.back()"><b><i class="fa fa-arrow-left"></i> Kembali</b></a>
		</div>
		<?php 
		if (!empty($jadwal)):
			foreach ($jadwal as $jadwal_mahasiswa) :
			?>
			<div class="col-lg-4 col-xs-12">
				<div class="box box-primary">
					<div class="box-body box-profile">
						<h3 class="profile-username text-center">Jadwal <?php echo ($jadwal_mahasiswa['jadwal'] == 'seminar-hasil')?'Seminar Hasil':'Sidang'; ?></h3>
						<p class="text-muted text-center"><?php echo $data->nama_lengkap ?></p>
						<ul class="list-group list-group-unbordered">
							<li class="list-group-item">
								<b>Waktu</b> <a class="pull-right"><?php echo nice_date($jadwal_mahasiswa['waktu'], 'd-m-Y H:i A') ?></a>
							</li>
							<li class="list-group-item">
								<b>Lokasi</b> <a class="pull-right"><?php echo $jadwal_mahasiswa['lokasi'] ?></a>
							</li>
							<?php
							$penguji1 = $this->dosen->detail(array('id' => $jadwal_mahasiswa['penguji1']));
							if ($penguji1->num_rows() > 0)
							{
								?>
								<li class="list-group-item">
									<b>Penguji 1</b> <a class="pull-right"><?php echo $penguji1->row()->nama_lengkap?></a>
								</li>
								<?php
							}
							?>
							<?php
							$penguji2 = $this->dosen->detail(array('id' => $jadwal_mahasiswa['penguji2']));
							if ($penguji2->num_rows() > 0)
							{
								?>
								<li class="list-group-item">
									<b>Penguji 2</b> <a class="pull-right"><?php echo $penguji2->row()->nama_lengkap?></a>
								</li>
								<?php
							}
							?>
							<?php
							$penguji3 = $this->dosen->detail(array('id' => $jadwal_mahasiswa['penguji3']));
							if ($penguji3->num_rows() > 0)
							{
								?>
								<li class="list-group-item">
									<b>Penguji 3</b> <a class="pull-right"><?php echo $penguji3->row()->nama_lengkap?></a>
								</li>
								<?php
							}
							?>
							<li class="list-group-item">
								<b>Status</b> <a class="pull-right"><?php echo ($jadwal_mahasiswa['status'] == 'selesai')?'SELESAI <i class="fa fa-check"></i>':'DI JADWALKAN <i class="fa fa-clock-o"></i>' ?></a>
							</li>
							<br>
							<?php if ($jadwal_mahasiswa['status'] !== 'selesai') : ?>
							<a class="btn btn-xs btn-block btn-success" href="<?php echo base_url($this->router->fetch_class().'/jadwal_selesai/'.$jadwal_mahasiswa['id']) ?>"><i class="fa fa-check"></i></a>
							<?php endif; ?>
						</ul>
					</div>
				</div>
			</div>
			<?php
			endforeach;
		?>
		<?php endif; ?>
	</div>
	<div class="row" style="margin-top: 4%;">
		<div class="col-lg-6">
			<div class="box box-info">
				<div class="box-header with-border">
					<h3 class="box-title">Dokumen Persyaratan Kerja Praktek</h3>
				</div>
				<div class="box-body">
					<table class="table table-hover table-striped datatables">
						<thead>
							<th>Berkas Persyaratan</th>
							<th>Dokumen</th>
							<th>Status</th>
							<th>Opsi</th>
						</thead>
						<tbody>
							<?php foreach ($dokumen_persyaratan_kerja_praktek->result_array() as $dokumen) :?>
							<tr>
								<td><?php echo $dokumen['jenis_berkas'] ?></td>
								<td><a href="<?php echo base_url('uploads/'.$dokumen['berkas']) ?>"><?php echo $dokumen['berkas'] ?></a></td>
								<td><?php echo ($dokumen['jenis_berkas'] !== 'Surat Pengantar Perusahaan')?$dokumen['status']:''; ?></td>
								<td>
									<?php
									if ($dokumen['jenis_berkas'] !== 'Surat Pengantar Perusahaan')
									{
										if (in_array($dokumen['status'], ['ditinjau', 'ditolak']))
										{
											echo '<a class="btn btn-xs btn-success" href="'.base_url($this->router->fetch_class().'/set_status_berkas_persyaratan/'.$dokumen['id'].'/diterima').'">Terima</a>';
										}

										if (in_array($dokumen['status'], ['ditinjau', 'diterima']))
										{
											echo '<a class="btn btn-xs btn-danger" href="'.base_url($this->router->fetch_class().'/set_status_berkas_persyaratan/'.$dokumen['id'].'/ditolak').'">Tolak</a>';
										}
									}
									?>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<div class="box-footer">
				<?php
				if ($dokumen_persyaratan_kerja_praktek->num_rows() === 3)
				{
					$acc_step1 = array();
					foreach ($dokumen_persyaratan_kerja_praktek->result_array() as $dokumen)
					{
						if ($dokumen['status'] == 'diterima')
						{
							array_push($acc_step1, $dokumen['berkas']);
						}
					}

					if (count($acc_step1) === 3)
					{
						?>
						<a href="#" class="pull-right btn btn-info" data-toggle="modal" data-target="#modal-surat-pengantar-kp">Unggah Berkas Surat Pengantar</a>
						<?php
					}
				}
				?>
				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="box box-info">
				<div class="box-header with-border">
					<h3 class="box-title">Dokumen Persyaratan Tugas Akhir</h3>
				</div>
				<div class="box-body">
					<table class="table table-hover table-striped datatables">
						<thead>
							<th>Berkas Persyaratan</th>
							<th>Dokumen</th>
							<th>Status</th>
							<th>Opsi</th>
						</thead>
						<tbody>
							<?php foreach ($dokumen_persyaratan_tugas_akhir->result_array() as $dokumen) :?>
							<tr>
								<td><?php echo $dokumen['jenis_berkas'] ?></td>
								<td><a href="<?php echo base_url('uploads/'.$dokumen['berkas']) ?>"><?php echo $dokumen['berkas'] ?></a></td>
								<td><?php echo $dokumen['status'] ?></td>
								<td>
									<?php
									if (in_array($dokumen['status'], ['ditinjau', 'ditolak']))
									{
										echo '<a class="btn btn-xs btn-success" href="'.base_url($this->router->fetch_class().'/set_status_berkas_persyaratan/'.$dokumen['id'].'/diterima').'">Terima</a>';
									}
									?>
									<?php
									if (in_array($dokumen['status'], ['ditinjau', 'diterima']))
									{
										echo '<a class="btn btn-xs btn-danger" href="'.base_url($this->router->fetch_class().'/set_status_berkas_persyaratan/'.$dokumen['id'].'/ditolak').'">Tolak</a>';
									}
									?>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<?php endif ?>
</section>

<script type="text/javascript">
$(document).ready(function() {
	$('.datatables').DataTable();
});
</script>