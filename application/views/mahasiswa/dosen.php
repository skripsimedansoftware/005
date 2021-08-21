<section class="content">
	<div class="row">
		<div class="col-lg-4 col-xs-12">
			<div class="box box-primary">
				<div class="box-body box-profile">
					<?php 

					$foto_default = ($data->jenis_kelamin == 'perempuan')?base_url('assets/adminlte/dist/img/user5-128x128.jpg'):base_url('assets/adminlte/dist/img/user1-128x128.jpg')
					?>
					<img class="profile-user-img img-responsive img-circle" src="<?php echo (!empty($data->foto))?$data->foto:$foto_default ?>" alt="User profile picture">
					<h3 class="profile-username text-center"><?php echo $data->nama_lengkap ?></h3>
					<p class="text-muted text-center">Dosen</p>
					<ul class="list-group list-group-unbordered">
						<li class="list-group-item">
							<b>NIK</b> <a class="pull-right"><?php echo $data->nik ?></a>
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
	</div>
</section>