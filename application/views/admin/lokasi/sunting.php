<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Administrator<small>Lokasi</small></h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title">Sunting Lokasi</h3>
		</div>
		<div class="box-body">
			<form method="POST" action="<?php echo base_url($this->router->fetch_class().'/sunting_lokasi/'.$this->uri->segment(3)) ?>">
				<div class="col-lg-6">
					<div class="form-group">
						<label>Kode</label>
						<input class="form-control" type="text" name="kode" placeholder="Kode" value="<?php echo set_value('kode', $data->row()->kode) ?>">
						<?php echo form_error('kode', '<span class="help-block error">', '</span>'); ?>
					</div>
					<div class="form-group">
						<label>Lokasi</label>
						<textarea class="form-control" placeholder="Detail lokasi" name="lokasi"><?php echo set_value('lokasi', $data->row()->keterangan) ?></textarea>
						<?php echo form_error('lokasi', '<span class="help-block error">', '</span>'); ?>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-block btn-success">Simpan</button>
					</div>
				</div>
			</form>
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
			<a href="<?php echo base_url($this->router->fetch_class().'/dosen') ?>" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
		</div>
	<!-- /.box-footer-->
	</div>
</section>