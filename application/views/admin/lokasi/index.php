<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Administrator<small>Lokasi</small></h1>
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
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">Daftar Lokasi</h3>
		</div>
		<div class="box-body">
			<table class="table table-hover table-striped datatables" style="width:100%">
				<thead>
					<th>No</th>
					<th>Kode</th>
					<th>Lokasi</th>
					<th>Opsi</th>
				</thead>
				<tbody>
					<?php foreach ($data as $key => $value) : ?>
					<tr>
						<td><?php echo $key+1 ?></td>
						<td><?php echo $value['kode'] ?></td>
						<td><?php echo $value['keterangan'] ?></td>
						<td>
							<a href="<?php echo base_url($this->router->fetch_class().'/sunting_lokasi/'.$value['id']) ?>" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a>
							<a href="<?php echo base_url($this->router->fetch_class().'/hapus_lokasi/'.$value['id']) ?>" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
			<a href="<?php echo base_url($this->router->fetch_class().'/tambah_lokasi') ?>" class="btn btn-primary">Tambah Lokasi <i class="fa fa-plus"></i></a>
		</div>
	<!-- /.box-footer-->
	</div>
</section>

<script type="text/javascript">
$(document).ready(function() {
	$('.datatables').DataTable();
});
</script>