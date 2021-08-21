<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Administrator<small>Daftar Judul</small></h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title">Judul Kerja Praktek</h3>
		</div>
		<div class="box-body">
			<table class="table table-hover table-striped datatables">
				<thead>
					<th>No</th>
					<th>Judul</th>
					<th>Dokumen</th>
					<th>Keterangan</th>
					<th>Status</th>
				</thead>
				<tbody>
					<?php  foreach ($data as $key => $judul) : ?>
					<tr>
						<td><?php echo $key+1 ?></td>
						<td><?php echo $judul['judul'] ?></td>
						<td><a href="<?php echo base_url('uploads/'.$judul['dokumen']) ?>"><?php echo $judul['dokumen'] ?></a></td>
						<td><?php echo $judul['keterangan'] ?></td>
						<td>
							<?php
							switch ($judul['status']) {
								case 'revisi':
									echo '<button class="btn btn-xs btn-warning">'.ucfirst($judul['status']).'</button>';
								break;

								case 'diterima':
									echo '<button class="btn btn-xs btn-success">'.ucfirst($judul['status']).'</button>';
								break;

								case 'ditolak':
									echo '<button class="btn btn-xs btn-danger">'.ucfirst($judul['status']).'</button>';
								break;

								default:
									echo '<button class="btn btn-xs btn-primary">'.ucfirst($judul['status']).'</button>';
								break;
							}
							?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
		</div>
	<!-- /.box-footer-->
	</div>
</section>