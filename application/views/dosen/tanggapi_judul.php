<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Halaman<small>Dosen</small></h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title">Tanggapi Judul : <?php echo $data->judul; ?></h3>
		</div>
		<div class="box-body">
			<?php
			echo '<p>'.$data->keterangan.'</p>';
			echo '<p><a href="'.base_url('uploads/'.$data->dokumen).'">Lampiran</a></p>';
			?>
			<?php if ($data->jenis == 'kerja-praktek'): ?>
			<p>
				<?php if ($data->status !== 'revisi'): ?>
					<a class="btn btn-warning" href="<?php echo base_url($this->router->fetch_class().'/tanggapi_judul/'.$this->uri->segment(3).'/revisi'); ?>">Minta Revisi</a>
				<?php endif; ?>
				<?php if ($data->status !== 'diterima'): ?>
					<a class="btn btn-success" href="<?php echo base_url($this->router->fetch_class().'/tanggapi_judul/'.$this->uri->segment(3).'/diterima'); ?>">Terima</a>
				<?php endif; ?>
				<?php if ($data->status !== 'ditolak'): ?>
					<a class="btn btn-danger" href="<?php echo base_url($this->router->fetch_class().'/tanggapi_judul/'.$this->uri->segment(3).'/ditolak'); ?>">Tolak</a>
				<?php endif; ?>
			</p>
			<?php  
			else :
				$dosen = $this->dosen_pembimbing_model->dosen_mahasiswa($data->mahasiswa)->row();
				$pembimbing = '';
				if ($dosen->dosen_ta1 == $this->session->userdata('pengguna')['id'])
				{
					$pembimbing = 1;
				}
				else
				{
					$pembimbing = 2;
				}
			?>
			<p>
				<?php if ($data->{'tanggapan_doping_'.$pembimbing} !== 'revisi'): ?>
					<a class="btn btn-warning" href="<?php echo base_url($this->router->fetch_class().'/tanggapi_judul/'.$this->uri->segment(3).'/revisi'); ?>">Minta Revisi</a>
				<?php endif; ?>
				<?php if ($data->{'tanggapan_doping_'.$pembimbing} !== 'diterima'): ?>
					<a class="btn btn-success" href="<?php echo base_url($this->router->fetch_class().'/tanggapi_judul/'.$this->uri->segment(3).'/diterima'); ?>">Terima</a>
				<?php endif; ?>
				<?php if ($data->{'tanggapan_doping_'.$pembimbing} !== 'ditolak'): ?>
					<a class="btn btn-danger" href="<?php echo base_url($this->router->fetch_class().'/tanggapi_judul/'.$this->uri->segment(3).'/ditolak'); ?>">Tolak</a>
				<?php endif; ?>
			</p>
			<?php endif; ?>
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
		</div>
	<!-- /.box-footer-->
	</div>
</section>