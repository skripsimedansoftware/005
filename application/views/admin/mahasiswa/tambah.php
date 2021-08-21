<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Halaman<small>Mahasiswa</small></h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title">Tambah Mahasiswa</h3>
		</div>
		<div class="box-body">
			<form method="POST" action="<?php echo base_url($this->router->fetch_class().'/tambah_mahasiswa') ?>" enctype="multipart/form-data">
				<div class="col-lg-6">
					<div class="form-group">
						<label>NPM</label>
						<input class="form-control" type="text" name="npm" placeholder="NPM" value="<?php echo set_value('npm') ?>">
						<?php echo form_error('npm', '<span class="help-block error">', '</span>'); ?>
					</div>
					<div class="form-group">
						<label>Email</label>
						<input class="form-control" type="text" name="email" placeholder="Email" value="<?php echo set_value('email') ?>">
						<?php echo form_error('email', '<span class="help-block error">', '</span>'); ?>
					</div>
					<div class="form-group">
						<label>Nama Lengkap</label>
						<input class="form-control" type="text" name="nama_lengkap" placeholder="Nama Lengkap" value="<?php echo set_value('nama_lengkap') ?>">
						<?php echo form_error('nama_lengkap', '<span class="help-block error">', '</span>'); ?>
					</div>
					<div class="col-lg-12">
						<div class="form-group col-lg-6">
							<label>Nomor HP</label>
							<input class="form-control" type="text" name="nomor_hp" placeholder="Nomor HP" value="<?php echo set_value('nomor_hp') ?>">
							<?php echo form_error('nomor_hp', '<span class="help-block error">', '</span>'); ?>
						</div>
						<div class="form-group col-lg-6">
							<label>Jenis Kelamin</label>
							<select class="form-control" name="jenis_kelamin">
								<option value="laki-laki">Laki-laki</option>
								<option value="perempuan">Perempuan</option>
							</select>
							<?php echo form_error('jenis_kelamin', '<span class="help-block error">', '</span>'); ?>
						</div>
					</div>
					<div class="form-group">
						<label>Password</label>
						<input class="form-control" type="text" name="password" placeholder="Password" value="<?php echo set_value('password') ?>">
						<?php echo form_error('password', '<span class="help-block error">', '</span>'); ?>
					</div>
					<div class="form-group">
						<textarea class="form-control" placeholder="Alamat" name="alamat"><?php echo set_value('alamat') ?></textarea>
						<?php echo form_error('alamat', '<span class="help-block error">', '</span>'); ?>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-block btn-success">Simpan</button>
					</div>
				</div>
			</form>
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
			<a href="<?php echo base_url($this->router->fetch_class().'/mahasiswa') ?>" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
		</div>
	<!-- /.box-footer-->
	</div>
</section>