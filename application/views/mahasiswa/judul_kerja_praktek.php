<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Halaman<small>Mahasiswa</small></h1>
</section>

<?php
$dokumen_persyaratan_all = array(
	'total' => $dokumen_persyaratan->num_rows(),
	'diterima' => 0
);
?>

<!-- Main content -->
<section class="content">
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
				</thead>
				<tbody>
					<?php foreach ($dokumen_persyaratan->result_array() as $dokumen) :?>
						<?php
						if ($dokumen['status'] == 'diterima')
						{
							$dokumen_persyaratan_all['diterima'] = ($dokumen_persyaratan_all['diterima']+1);
						}
						?>
					<tr>
						<td><?php echo $dokumen['jenis_berkas'] ?></td>
						<td><a href="<?php echo base_url('uploads/'.$dokumen['berkas']) ?>"><?php echo $dokumen['berkas'] ?></a></td>
						<td><?php echo $dokumen['status'] ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
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
					<th>Konsultasi</th>
					<th>Status</th>
					<th>Opsi</th>
				</thead>
				<tbody>
					<?php  foreach ($data->result_array() as $key => $judul) : ?>
					<tr>
						<td><?php echo $key+1 ?></td>
						<td><?php echo $judul['judul'] ?></td>
						<td><a href="<?php echo base_url('uploads/'.$judul['dokumen']) ?>"><?php echo $judul['dokumen'] ?></a></td>
						<td><?php echo $judul['keterangan'] ?></td>
						<td><a class="btn btn-xs btn-success" href="<?php echo base_url($this->router->fetch_class().'/konsultasi_judul/'.$judul['id'])  ?>"><i class="fa fa-comments-o"></i></a></td>
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
						<td>
							<a href="<?php echo base_url($this->router->fetch_class().'/hapus_judul/'.$judul['id']) ?>" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
			<?php 
				$is_accepted_rule = ($dokumen_persyaratan_all['total'] === 4);
				$acc_all = ($dokumen_persyaratan_all['diterima'] == $dokumen_persyaratan_all['total'])?'hidden':'';
				$dokumen_persyaratan_kerja_praktek = $this->dokumen_persyaratan->detail(array('mahasiswa' => $session->id, 'tujuan' => 'kerja-praktek'));
			?>
			<?php if  ($is_accepted_rule) : ?>
				<?php if ($acc_all == 'hidden') : ?>
				<a href="<?php echo base_url($this->router->fetch_class().'/tambah_judul/kerja_praktek') ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Buat Pengajuan</a>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ($dokumen_persyaratan_all['total'] < 3 OR $acc_all == '') :?>
			<a href="#" class="pull-right btn btn-info <?php echo ($is_accepted_rule)?$acc_all:''  ?>" data-toggle="modal" data-target="#modal-syarat-kerja-praktek">Unggah Berkas Persyaratan</a>
			<?php elseif ($this->dokumen_persyaratan->detail(array('mahasiswa' => $session->id, 'tujuan' => 'kerja-praktek'))->num_rows() === 3) : ?>
			<?php
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
				<a href="#" class="pull-right btn btn-info" data-toggle="modal" data-target="#modal-surat-balasan-perusahaan">Unggah Berkas Balasan Perusahaan</a>
				<?php
			}
			?>
			<?php endif; ?>
		</div>
	<!-- /.box-footer-->
	</div>
</section>