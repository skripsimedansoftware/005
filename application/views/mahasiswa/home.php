<section class="content-header">
	<h1>Mahasiswa<small>Home</small></h1>
</section>

<section class="content container-fluid">
	<div class="row">
	<?php 
		if (!empty($jadwal)):
			foreach ($jadwal as $jadwal_mahasiswa) :
			?>
			<div class="col-lg-4 col-xs-12">
				<div class="box box-primary">
					<div class="box-body box-profile">
						<h3 class="profile-username text-center">Jadwal <?php echo ($jadwal_mahasiswa['jadwal'] == 'seminar-hasil')?'Seminar Hasil':'Sidang'; ?></h3>
						<p class="text-muted text-center"><?php //echo $data->nama_lengkap ?></p>
						<ul class="list-group list-group-unbordered">
							<li class="list-group-item">
								<b>Waktu</b> <a class="pull-right"><?php echo nice_date($jadwal_mahasiswa['waktu'], 'd-m-Y H:i A') ?></a>
							</li>
							<li class="list-group-item">
								<b>Lokasi</b> <a class="pull-right"><?php echo $this->lokasi_jadwal->detail(array('id' => $jadwal_mahasiswa['lokasi']))->row()->keterangan ?></a>
							</li>
							<?php
							$penguji1 = $this->dosen->detail(array('id' => $jadwal_mahasiswa['penguji1']));
							if ($penguji1->num_rows() > 0)
							{
								?>
								<li class="list-group-item">
									<b>Penguji 1</b> <a class="pull-right"><?php echo $penguji1->row()->nama_lengkap?></a>
								</li>
								<?php
							}
							?>
							<?php
							$penguji2 = $this->dosen->detail(array('id' => $jadwal_mahasiswa['penguji2']));
							if ($penguji2->num_rows() > 0)
							{
								?>
								<li class="list-group-item">
									<b>Penguji 2</b> <a class="pull-right"><?php echo $penguji2->row()->nama_lengkap?></a>
								</li>
								<?php
							}
							?>
							<?php
							$penguji3 = $this->dosen->detail(array('id' => $jadwal_mahasiswa['penguji3']));
							if ($penguji3->num_rows() > 0)
							{
								?>
								<li class="list-group-item">
									<b>Penguji 3</b> <a class="pull-right"><?php echo $penguji3->row()->nama_lengkap?></a>
								</li>
								<?php
							}
							?>
							<li class="list-group-item">
								<b>Status</b> <a class="pull-right"><?php echo ($jadwal_mahasiswa['status'] == 'selesai')?'SELESAI':'DI JADWALKAN' ?></a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<?php
			endforeach;
		?>
		<?php endif; ?>
	</div>
	<div class="row">
		<div class="col-lg-12">
		<?php
		$dokumen_persyaratan_kerja_praktek = $this->dokumen_persyaratan->detail(array('mahasiswa' => $session->id, 'tujuan' => 'kerja-praktek'));
		if ($this->dokumen_persyaratan->detail(array('mahasiswa' => $session->id, 'tujuan' => 'kerja-praktek'))->num_rows() === 3)
		{
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
				<a href="#" class="btn btn-lg bg-maroon btn-primary" data-toggle="modal" data-target="#modal-surat-balasan-perusahaan"><i class="fa fa-upload"></i> Unggah Berkas Balasan Perusahaan</a>
				<?php
			}
		}
		?>
		</div>
	</div>
</section>