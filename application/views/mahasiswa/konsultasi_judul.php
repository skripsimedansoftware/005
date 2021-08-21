<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Halaman<small>Mahasiswa</small></h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title">Konsultasi Judul <?php echo ($judul_mahasiswa->jenis == 'kerja-praktek')?'Kerja Praktek':'Skripsi' ?></h3>
		</div>
		<div class="box-body chat" style="overflow:scroll; height:400px;">
			<table class="table table-hover table-striped">
				<tr>
					<td>Judul</td><td><?php echo $judul_mahasiswa->judul ?></td>
				</tr>
				<tr>
					<td>Dokumen</td><td><?php echo $judul_mahasiswa->dokumen ?></td>
				</tr>
				<tr>
					<td>Keterangan</td><td><?php echo $judul_mahasiswa->keterangan ?></td>
				</tr>
				<tr>
					<td>Status</td>
					<td>
					<?php
					switch ($judul_mahasiswa->status) {
						case 'revisi':
							echo '<button class="btn btn-warning btn-xs">'.ucfirst($judul_mahasiswa->status).'</button>';
						break;

						case 'diterima':
							echo '<button class="btn btn-success btn-xs">'.ucfirst($judul_mahasiswa->status).'</button>';
						break;

						case 'ditolak':
							echo '<button class="btn btn-danger btn-xs">'.ucfirst($judul_mahasiswa->status).'</button>';
						break;

						default:
							echo '<button class="btn btn-primary btn-xs">'.ucfirst($judul_mahasiswa->status).'</button>';
						break;
					}
					?>
					</td>
				</tr>
			</table>

			<?php foreach ($konsultasi as $key => $konsultasi_detail):?>
				<div class="item" <?php echo ($key == 0)?'style="margin-top: 4%;"':'' ?>>
					<?php if ($konsultasi_detail['pengirim'] == 'dosen'): ?>
						<img src="<?php echo base_url('assets/adminlte/') ?>dist/img/user2-160x160.jpg" alt="user image" class="online">
						<?php else: ?>
						<img src="<?php echo base_url('assets/adminlte/') ?>dist/img/user6-128x128.jpg" alt="user image" class="online">	
					<?php endif; ?>
					<p class="message">
						<a href="#" class="name">
						<small class="text-muted pull-right"><i class="fa fa-clock-o"></i> <?php echo nice_date($konsultasi_detail['time'], 'd-m-Y H:i') ?></small>
						<?php
						if ($konsultasi_detail['pengirim'] == 'dosen')
						{
							echo $this->dosen_model->detail(array('id' => $konsultasi_detail['dosen']))->row()->nama_lengkap;
						}
						else
						{
							echo $this->mahasiswa_model->detail(array('id' => $konsultasi_detail['mahasiswa']))->row()->nama_lengkap;
						}
						?>
						</a>
						<?php echo $konsultasi_detail['text']; ?>
					</p>
					<?php if (!empty($konsultasi_detail['dokumen'])): ?>
					<div class="attachment">
						<h4>Attachments:</h4>
						<p class="filename"><?php echo $konsultasi_detail['dokumen'] ?></p>
						<div class="pull-right">
							<a href="<?php echo base_url('uploads/'.$konsultasi_detail['dokumen']) ?>" type="button" class="btn btn-primary btn-sm btn-flat">Open</a>
						</div>
					</div>
					<?php endif; ?>
					<!-- /.attachment -->
				</div>
			<?php endforeach; ?>
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
			<div class="row">
				<form method="post" action="<?php echo base_url($this->router->fetch_class().'/konsultasi_judul/'.$this->uri->segment(3)) ?>" enctype="multipart/form-data">
					<div class="col-lg-3">
						<div class="input-group">
							<input type="file" name="attachment" class="form-control">
						</div>
					</div>
					<div class="col-lg-8" style="margin-left: -4%;">
						<div class="input-group">
							<input class="form-control" placeholder="Type message..." name="message">
							<div class="input-group-btn">
								<button type="submit" class="btn btn-success"><i class="fa fa-send"></i></button>
							</div>
						</div>
					</div>
				</form>
				<div class="col-lg-12" style="margin-top: 2%;">
					<a onclick="window.history.back()" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
				</div>
			</div>
		</div>
	<!-- /.box-footer-->
	</div>
</section>

<script type="text/javascript">
$(document).ready(function() {
	$('.datatables').DataTable();
});
</script>