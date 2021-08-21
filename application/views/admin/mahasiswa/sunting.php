<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Administrator<small>Mahasiswa</small></h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title">Sunting Mahasiswa</h3>
		</div>
		<div class="box-body">
			<form method="POST" action="<?php echo base_url($this->router->fetch_class().'/sunting_mahasiswa/'.$this->uri->segment(3)) ?>" enctype="multipart/form-data">
				<div class="col-lg-6">
					<h3>Data Mahasiswa</h3>
					<div class="form-group">
						<label>NPM</label>
						<input class="form-control" type="text" name="npm" placeholder="NPM" value="<?php echo set_value('npm', $data->row()->npm) ?>">
						<?php echo form_error('npm', '<span class="help-block error">', '</span>'); ?>
					</div>
					<div class="form-group">
						<label>Email</label>
						<input class="form-control" type="text" name="email" placeholder="Email" value="<?php echo set_value('email', $data->row()->email) ?>">
						<?php echo form_error('email', '<span class="help-block error">', '</span>'); ?>
					</div>
					<div class="form-group">
						<label>Nama Lengkap</label>
						<input class="form-control" type="text" name="nama_lengkap" placeholder="Nama Lengkap" value="<?php echo set_value('nama_lengkap', $data->row()->nama_lengkap) ?>">
						<?php echo form_error('nama_lengkap', '<span class="help-block error">', '</span>'); ?>
					</div>
					<div class="col-lg-12">
						<div class="form-group col-lg-6">
							<label>Jenis Kelamin</label>
							<select class="form-control" name="jenis_kelamin">
								<option value="laki-laki" <?php echo (!empty($data->row()->jenis_kelamin))?($data->row()->jenis_kelamin == 'laki-laki')?'selected':'':'' ?>>Laki-laki</option>
								<option value="perempuan" <?php echo (!empty($data->row()->jenis_kelamin))?($data->row()->jenis_kelamin == 'perempuan')?'selected':'':'' ?>>Perempuan</option>
							</select>
							<?php echo form_error('jenis_kelamin', '<span class="help-block error">', '</span>'); ?>
						</div>
						<div class="form-group col-lg-6">
							<label>Nomor HP</label>
							<input class="form-control" type="text" name="nomor_hp" placeholder="Nomor HP" value="<?php echo set_value('nomor_hp', $data->row()->nomor_hp) ?>">
							<?php echo form_error('nomor_hp', '<span class="help-block error">', '</span>'); ?>
						</div>
					</div>
					<div class="form-group">
						<label>Password</label>
						<input class="form-control" type="text" name="password" placeholder="Password">
						<?php echo form_error('password', '<span class="help-block error">', '</span>'); ?>
					</div>
					<div class="form-group">
						<textarea class="form-control" placeholder="Alamat" name="alamat"><?php echo $data->row()->alamat ?></textarea>
						<?php echo form_error('alamat', '<span class="help-block error">', '</span>'); ?>
					</div>
					<?php  
					$dosen_mahasiswa = $this->dosen_pembimbing->dosen_mahasiswa($data->row()->id);
					$jadwal_seminar_hasil = $this->jadwal->detail(array('mahasiswa' => $data->row()->id, 'jadwal' => 'seminar-hasil'));
					$jadwal_sidang = $this->jadwal->detail(array('mahasiswa' => $data->row()->id, 'jadwal' => 'sidang-hijau'));

					$berkas_persyaratan_kp = $this->dokumen_persyaratan->detail(array('mahasiswa' => $data->row()->id, 'tujuan' => 'kerja-praktek', 'status' => 'diterima'));

					$berkas_persyaratan_ta = $this->dokumen_persyaratan->detail(array('mahasiswa' => $data->row()->id, 'tujuan' => 'tugas-akhir', 'status' => 'diterima'));
					?>

					<?php if ($berkas_persyaratan_kp->num_rows() === 4): ?>
					<hr>
					<div class="form-group">
						<label>Pembimbing Kerja Praktek</label>
						<select class="form-control" name="dosen_kp">
							<option value="" <?php echo ($dosen_mahasiswa->num_rows() === 0)?'selected':'' ?>>Tentukan Dosen</option>
							<?php foreach ($this->dosen->ambil_data(1000) as $key => $list_dosen): ?>
								<?php $dosen = $this->dosen->detail(array('id' => $dosen_mahasiswa->row()->dosen_kp)); ?>
								<option value="<?php echo $list_dosen['id'] ?>" <?php echo ($dosen->num_rows() >= 1 && $dosen->row()->id == $list_dosen['id'])?'selected':''?>><?php echo $list_dosen['nik'].' - '.$list_dosen['nama_lengkap'] ?></option>
							<?php endforeach; ?>
						</select>
						<?php echo form_error('dosen_kp', '<span class="help-block error">', '</span>'); ?>
					</div>
					<?php endif; ?>

					<?php if ($berkas_persyaratan_ta->num_rows() === 4): ?>
					<div class="form-group">
						<label>Pembimbing Skripsi #1</label>
						<select class="form-control" name="dosen_ta1">
							<option value="" <?php echo ($dosen_mahasiswa->num_rows() === 0)?'selected':'' ?>>Tentukan Dosen</option>
							<?php foreach ($this->dosen->ambil_data(1000) as $key => $list_dosen): ?>
								<?php $dosen = $this->dosen->detail(array('id' => $dosen_mahasiswa->row()->dosen_ta1)); ?>
								<option value="<?php echo $list_dosen['id'] ?>" <?php echo ($dosen->num_rows() >= 1 && $dosen->row()->id == $list_dosen['id'])?'selected':''?>><?php echo $list_dosen['nik'].' - '.$list_dosen['nama_lengkap'] ?></option>
							<?php endforeach; ?>
						</select>
						<?php echo form_error('dosen_ta1', '<span class="help-block error">', '</span>'); ?>
					</div>
					<div class="form-group">
						<label>Pembimbing Skripsi #2</label>
						<select class="form-control" name="dosen_ta2">
							<option value="" <?php echo ($dosen_mahasiswa->num_rows() === 0)?'selected':'' ?>>Tentukan Dosen</option>
							<?php foreach ($this->dosen->ambil_data(1000) as $key => $list_dosen): ?>
								<?php $dosen = $this->dosen->detail(array('id' => $dosen_mahasiswa->row()->dosen_ta2)); ?>
								<option value="<?php echo $list_dosen['id'] ?>" <?php echo ($dosen->num_rows() >= 1 && $dosen->row()->id == $list_dosen['id'])?'selected':''?>><?php echo $list_dosen['nik'].' - '.$list_dosen['nama_lengkap'] ?></option>
							<?php endforeach; ?>
						</select>
						<?php echo form_error('dosen_ta2', '<span class="help-block error">', '</span>'); ?>
					</div>
					<div class="form-group">
						<label>SK Tugas Akhir</label>
						<input type="file" name="sk_tugas_akhir" class="form-control">
						<?php echo form_error('sk_tugas_akhir', '<span class="help-block error">', '</span>'); ?>
					</div>
					<?php endif; ?>
					<div class="form-group">
						<button type="submit" class="btn btn-block btn-primary">Simpan</button>
					</div>
				</div>

				<?php
				$judul_kerja_praktek = $this->judul_mahasiswa->detail(array('mahasiswa' => $data->row()->id, 'jenis' => 'kerja-praktek', 'status' => 'diterima'));
				if ($judul_kerja_praktek->num_rows() > 0) :
				?>
				<div class="col-lg-6" style="margin-top:0%;margin-bottom: 1%;">
					<h3>Jadwalkan Untuk Seminar Hasil</h3>
					<div class="form-group">
						<label>Waktu</label>
						<input class="form-control datetimepicker" type="text" name="waktu_seminar_hasil" placeholder="Waktu" value="<?php echo set_value('waktu_seminar_hasil', ($jadwal_seminar_hasil->num_rows() > 0)?nice_date($jadwal_seminar_hasil->row()->waktu, 'd-m-Y H:i'):'') ?>">
						<?php echo form_error('waktu_seminar_hasil', '<span class="help-block error">', '</span>'); ?>
					</div>
					<div class="form-group">
						<label>Lokasi</label>
						<select class="form-control" name="lokasi_jadwal_seminar_hasil">
							<option>- PILIH LOKASI -</option>
							<?php foreach ($this->lokasi_jadwal->ambil_data(1000) as $key => $lokasi_jadwal): ?>
								<option value="<?php echo $lokasi_jadwal['id'] ?>" <?php echo ($jadwal_seminar_hasil->num_rows() >= 1 && $jadwal_seminar_hasil->row()->lokasi == $lokasi_jadwal['id'])?'selected':''?>><?php echo $lokasi_jadwal['keterangan'] ?></option>
							<?php endforeach; ?>
						</select>
						<?php echo form_error('lokasi_jadwal_seminar_hasil', '<span class="help-block error">', '</span>'); ?>
					</div>
					<div class="form-group">
						<label>Penguji 1</label>
						<select class="form-control" name="penguji_1_seminar_hasil">
							<option value="" <?php echo ($jadwal_seminar_hasil->num_rows() === 0)?'selected':'' ?>>- Tentukan Penguji 1 -</option>
							<?php foreach ($this->dosen->ambil_data(1000) as $key => $list_dosen): ?>
								<?php $penguji = ($jadwal_seminar_hasil->num_rows() === 0)?0:$jadwal_seminar_hasil->row()->penguji1; ?>
								<option value="<?php echo $list_dosen['id'] ?>" <?php echo ($penguji !== 0 && $penguji == $list_dosen['id'])?'selected':''?>><?php echo $list_dosen['nik'].' - '.$list_dosen['nama_lengkap'] ?></option>
							<?php endforeach; ?>
						</select>
						<?php echo form_error('penguji_1_seminar_hasil', '<span class="help-block error">', '</span>'); ?>
					</div>
					<div class="form-group">
						<label>Penguji 2</label>
						<select class="form-control" name="penguji_2_seminar_hasil">
							<option value="" <?php echo ($jadwal_seminar_hasil->num_rows() === 0)?'selected':'' ?>>- Tentukan Penguji 2 -</option>
							<?php foreach ($this->dosen->ambil_data(1000) as $key => $list_dosen): ?>
								<?php $penguji = ($jadwal_seminar_hasil->num_rows() === 0)?0:$jadwal_seminar_hasil->row()->penguji2; ?>
								<option value="<?php echo $list_dosen['id'] ?>" <?php echo ($penguji !== 0 && $penguji == $list_dosen['id'])?'selected':''?>><?php echo $list_dosen['nik'].' - '.$list_dosen['nama_lengkap'] ?></option>
							<?php endforeach; ?>
						</select>
						<?php echo form_error('penguji_2_seminar_hasil', '<span class="help-block error">', '</span>'); ?>
					</div>
				</div>
				<?php endif; ?>

				<?php
				$judul_tugas_akhir = $this->judul_mahasiswa->detail(array('mahasiswa' => $data->row()->id, 'jenis' => 'tugas-akhir', 'status' => 'diterima'));
				if ($judul_tugas_akhir->num_rows() > 0) :
				?>
				<div class="col-lg-6" style="margin-top:0%;margin-bottom: 1%;">
					<h3>Jadwalkan Untuk Sidang</h3>
					<div class="form-group">
						<label>Waktu</label>
						<input class="form-control datetimepicker" type="text" name="waktu_sidang" placeholder="Waktu" value="<?php echo set_value('waktu_sidang', ($jadwal_sidang->num_rows() > 0)?nice_date($jadwal_sidang->row()->waktu, 'd-m-Y H:i'):'' ) ?>">
						<?php echo form_error('waktu_sidang', '<span class="help-block error">', '</span>'); ?>
					</div>
					<div class="form-group">
						<label>Lokasi</label>
						<select class="form-control" name="lokasi_jadwal_sidang">
							<option>- PILIH LOKASI -</option>
							<?php foreach ($this->lokasi_jadwal->ambil_data(1000) as $key => $lokasi_jadwal): ?>
								<option value="<?php echo $lokasi_jadwal['id'] ?>" <?php echo ($jadwal_sidang->num_rows() >= 1 && $jadwal_sidang->row()->lokasi == $lokasi_jadwal['id'])?'selected':''?>><?php echo $lokasi_jadwal['keterangan'] ?></option>
							<?php endforeach; ?>
						</select>
						<?php echo form_error('lokasi_jadwal_sidang', '<span class="help-block error">', '</span>'); ?>
					</div>
					<div class="form-group">
						<label>Penguji 1</label>
						<select class="form-control" name="penguji_1_sidang">
							<option value="" <?php echo ($jadwal_sidang->num_rows() === 0)?'selected':'' ?>>- Tentukan Penguji 1 -</option>
							<?php foreach ($this->dosen->ambil_data_kecuali($this->dosen_pembimbing->dosen_mahasiswa($this->uri->segment(3))->row()->dosen_ta1)->result_array() as $key => $list_dosen): ?>
								<?php $penguji = ($jadwal_sidang->num_rows() === 0)?0:$jadwal_sidang->row()->penguji1; ?>
								<option value="<?php echo $list_dosen['id'] ?>" <?php echo ($penguji !== 0 && $penguji == $list_dosen['id'])?'selected':''?>><?php echo $list_dosen['nik'].' - '.$list_dosen['nama_lengkap'] ?></option>
							<?php endforeach; ?>
						</select>
						<?php echo form_error('penguji_1_sidang', '<span class="help-block error">', '</span>'); ?>
					</div>
					<div class="form-group">
						<label>Penguji 2</label>
						<select class="form-control" name="penguji_2_sidang">
							<option value="" <?php echo ($jadwal_sidang->num_rows() === 0)?'selected':'' ?>>- Tentukan Penguji 2 -</option>
							<?php foreach ($this->dosen->ambil_data_kecuali($this->dosen_pembimbing->dosen_mahasiswa($this->uri->segment(3))->row()->dosen_ta1)->result_array() as $key => $list_dosen): ?>
								<?php $penguji = ($jadwal_sidang->num_rows() === 0)?0:$jadwal_sidang->row()->penguji2; ?>
								<option value="<?php echo $list_dosen['id'] ?>" <?php echo ($penguji !== 0 && $penguji == $list_dosen['id'])?'selected':''?>><?php echo $list_dosen['nik'].' - '.$list_dosen['nama_lengkap'] ?></option>
							<?php endforeach; ?>
						</select>
						<?php echo form_error('penguji_2_sidang', '<span class="help-block error">', '</span>'); ?>
					</div>
				</div>
				<?php endif; ?>
			</form>
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
			<a href="<?php echo base_url($this->router->fetch_class().'/mahasiswa') ?>" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
		</div>
	<!-- /.box-footer-->
	</div>
</section>

<script type="text/javascript">
 $('.datetimepicker').datetimepicker({
 	format: "dd-mm-yyyy hh:ii",
 	autoclose: true,
});
</script>