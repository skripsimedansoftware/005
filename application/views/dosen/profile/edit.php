<section class="content-header">
	<h1>Dosen<small>Profile Edit</small></h1>
</section>

<section class="content container-fluid">
	<div class="box">
		<form method="POST" action="<?php echo base_url($this->router->fetch_class().'/profile/'.$this->uri->segment(3).'/edit') ?>" enctype="multipart/form-data">
			<div class="box-body">
				<div class="col-lg-6">
					<div class="form-group">
						<label>Nama Lengkap</label>
						<input class="form-control" type="text" name="nama_lengkap" placeholder="Nama Lengkap" value="<?php echo set_value('nama_lengkap', $profile->nama_lengkap) ?>">
						<?php echo form_error('nama_lengkap', '<span class="help-block error">', '</span>'); ?>
					</div>
					<div class="form-group">
						<label>Email</label>
						<input class="form-control" type="text" name="email" placeholder="Email" value="<?php echo set_value('email', $profile->email) ?>">
						<?php echo form_error('email', '<span class="help-block error">', '</span>'); ?>
					</div>
					<div class="form-group">
						<label>Nomor HP</label>
						<input class="form-control" type="text" name="nomor_hp" placeholder="Nomor HP" value="<?php echo set_value('nomor_hp', $profile->nomor_hp) ?>">
						<?php echo form_error('nomor_hp', '<span class="help-block error">', '</span>'); ?>
					</div>
					<div class="form-group">
						<label>Jenis Kelamin</label>
						<select class="form-control" name="jenis_kelamin">
							<option value="laki-laki" <?php echo (!empty($profile->jenis_kelamin))?($profile->jenis_kelamin == 'laki-laki')?'selected':'':'' ?>>Laki-laki</option>
							<option value="perempuan" <?php echo (!empty($profile->jenis_kelamin))?($profile->jenis_kelamin == 'perempuan')?'selected':'':'' ?>>Perempuan</option>
						</select>
						<?php echo form_error('jenis_kelamin', '<span class="help-block error">', '</span>'); ?>
					</div>
					<div class="form-group">
						<label>Password</label>
						<input class="form-control" type="text" name="password" placeholder="Password" value="<?php echo set_value('password') ?>">
						<?php echo form_error('password', '<span class="help-block error">', '</span>'); ?>
					</div>
					<div class="form-group">
						<img  class="img img-responsive img-circle" id="profile-upload-preview" src="<?php echo (!empty($profile->photo))?base_url('uploads/'.$profile->photo):base_url('assets/adminlte/dist/img/user2-160x160.jpg') ?>" alt="your image" style="margin-bottom: 2%; height:160px;width: 160px;">
						<input type="file" onchange="readURL(this);" name="photo">
					</div>
					<div class="form-group">
						<textarea class="form-control" placeholder="Alamat" name="alamat"><?php echo set_value('alamat', $profile->alamat) ?></textarea>
						<?php echo form_error('alamat', '<span class="help-block error">', '</span>'); ?>
					</div>
				</div>
			</div>
			<div class="box-footer">
				<div class="col-lg-2 col-sm-12">
					<button type="submit" class="btn btn-block btn-success">Simpan</button>
				</div>
			</div>
		</form>
	</div>
</section>