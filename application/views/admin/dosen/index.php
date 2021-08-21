<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Halaman<small>Dosen</small></h1>
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
			<h3 class="box-title">Daftar Dosen</h3>
		</div>
		<div class="box-body">
			<table class="table table-hover table-striped datatables" style="width:100%">
				<thead>
					<th>No</th>
					<th>NIK</th>
					<th>Email</th>
					<th>Nama Lengkap</th>
					<th>Jenis Kelamin</th>
					<th>Opsi</th>
				</thead>
				<tbody>
					<?php foreach ($data as $key => $value) : ?>
					<tr>
						<td><?php echo $key+1 ?></td>
						<td><?php echo $value['nik'] ?></td>
						<td><?php echo $value['email'] ?></td>
						<td><?php echo $value['nama_lengkap'] ?></td>
						<td><?php echo $value['jenis_kelamin'] ?></td>
						<td>
							<a href="<?php echo base_url($this->router->fetch_class().'/sunting_dosen/'.$value['id']) ?>" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a>
							<a href="<?php echo base_url($this->router->fetch_class().'/dosen/detail/'.$value['id']) ?>" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a>
							<a href="<?php echo base_url($this->router->fetch_class().'/hapus_dosen/'.$value['id']) ?>" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
			<a href="<?php echo base_url($this->router->fetch_class().'/tambah_dosen') ?>" class="btn btn-primary">Tambah Dosen <i class="fa fa-user-plus"></i></a>
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
	<?php endif ?>
</section>

<script type="text/javascript">
$(document).ready(function() {
	$('.datatables').DataTable();
});
</script>