<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Halaman<small>Mahasiswa</small></h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title">Pengajuan Judul</h3>
		</div>
		<div class="box-body">
			<?php if ($this->session->userdata('form_error')) : ?>
				<div class="alert alert-warning"><?php echo $this->session->userdata('form_error'); ?></div>
			<?php endif; ?>
			<?php if ($doping_mahasiswa->num_rows() >= 1) : ?>
				<?php if (($jenis == 'kerja_praktek' && $doping_mahasiswa->row()->dosen_kp > 0) OR ($jenis == 'skripsi' && $doping_mahasiswa->row()->dosen_ta1 > 0 && $doping_mahasiswa->row()->dosen_ta2 > 0)) : ?>
					<form method="POST" enctype="multipart/form-data" action="<?php echo base_url($this->router->fetch_class().'/tambah_judul/'.$jenis) ?>">
						<div class="col-lg-6">
							<div class="form-group">
								<label>Judul</label>
								<input class="form-control" type="text" name="judul" placeholder="Judul" value="<?php echo set_value('judul') ?>">
								<?php echo form_error('judul', '<span class="help-block error">', '</span>'); ?>
							</div>
							<div class="form-group">
								<label>Dokumen</label>
								<input class="form-control" type="file" name="dokumen" placeholder="Dokumen">
								<?php echo form_error('dokumen', '<span class="help-block error">', '</span>'); ?>
							</div>
							<div class="form-group">
								<label>Keterangan</label>
								<textarea class="form-control" name="keterangan"><?php echo set_value('keterangan') ?></textarea>
								<?php echo form_error('keterangan', '<span class="help-block error">', '</span>'); ?>
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-block btn-success">Simpan</button>
							</div>
						</div>
					</form>
					<?php else: ?>
					<div class="alert alert-warning">
						<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
						<span class="sr-only">Error:</span>
						Mohon maaf, dosen pembimbing <?php echo str_replace('_', ' ', $jenis) ?> kamu belum di tentukan, silahkan melapor ke admin untuk menentukan dosen pembimbing
					</div>
				<?php endif; ?>
				<?php else: ?>
				<div class="alert alert-warning">
					<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
					<span class="sr-only">Error:</span>
					Mohon maaf, dosen pembimbing kamu belum di tentukan, silahkan melapor ke admin untuk menentukan dosen pembimbing
				</div>
			<?php endif; ?>
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
			<a href="#" onclick="window.history.back()" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
		</div>
	<!-- /.box-footer-->
	</div>
</section>