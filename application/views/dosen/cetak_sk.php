<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Dosen<small>Nama Pembimbing</small></h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title"></h3>
		</div>
		<div class="box-body">
			<table class="table table-responsive table-bordered table-condensed datatables">
				<thead>
					<th class="text-center">No</th>
					<th class="text-center">NPM</th>
					<th class="text-center">Nama Lengkap</th>
					<th colspan="2" class="text-center">Nama Pembimbing</th>
				</thead>
				<tbody>
					<?php  
						foreach ($data as $key => $dosen_pembimbing) : 
						$mahasiswa = $this->mahasiswa->detail(array('id' => $dosen_pembimbing['mahasiswa']));

						if ($mahasiswa->num_rows() >= 1):
						$sk_kerja_praktek = $this->dokumen_persyaratan->detail(array('mahasiswa' => $mahasiswa->row()->id, 'tujuan' => 'sk-kerja-praktek'));
						$sk_tugas_akhir = $this->dokumen_persyaratan->detail(array('mahasiswa' => $mahasiswa->row()->id, 'tujuan' => 'sk-tugas-akhir'));
					?>
					<tr>
						<td rowspan="2" class="text-center" style="padding-top: 3%;"><?php echo $key+1 ?></td>
						<td rowspan="2" class="text-center" style="padding-top: 3%;"><?php echo ($mahasiswa->num_rows() >= 1)?$mahasiswa->row()->npm:'Mahasiswa tidak ditemukan' ?></td>
						<td rowspan="2" class="text-center" style="padding-top: 3%;"><?php echo ($mahasiswa->num_rows() >= 1)?'<a href="profil_mahasiswa/'.$mahasiswa->row()->id.'">'.$mahasiswa->row()->nama_lengkap.'</a>':'Mahasiswa tidak ditemukan' ?></td>
						<td class="text-center">
							Kerja Praktek
						</td>
						<td>
							<?php
							$doping = $this->dosen->detail(array('id' => $dosen_pembimbing['dosen_kp']));
							if ($doping->num_rows() >= 1) {
								echo '<a href="profil_dosen/'.$doping->row()->id.'">'.$doping->row()->nama_lengkap.'</a>';
								if ($sk_kerja_praktek->num_rows() > 0)
								{
									echo ' | ';
									echo '<a href="'.base_url('uploads/'.$sk_kerja_praktek->row()->berkas).'">Download SK</a>';
								}
							} else {
								echo '<a href="#">Dosen belum ditentukan</a>';
							}
							?>
						</td>
					</tr>
					<tr>
						<td class="text-center">Tugas Akhir</td>
						<td>
							<?php
							$doping = $this->dosen->detail(array('id' => $dosen_pembimbing['dosen_ta1']));
							if ($doping->num_rows() >= 1) {
								echo '1.) <a href="profil_dosen/'.$doping->row()->id.'">'.$doping->row()->nama_lengkap.'</a>&nbsp;&nbsp;&nbsp;&nbsp;';
							} else {
								echo '1.) <a href="#">'.'Dosen belum ditentukan'.'</a>&nbsp;&nbsp;&nbsp;&nbsp;';
							}

							$doping = $this->dosen->detail(array('id' => $dosen_pembimbing['dosen_ta2']));
							if ($doping->num_rows() >= 1) {
								echo '2.) <a href="profil_dosen/'.$doping->row()->id.'">'.$doping->row()->nama_lengkap.'</a>&nbsp;&nbsp;&nbsp;&nbsp;';
							} else {
								echo '2.) <a href="#">'.'Dosen belum ditentukan'.'</a>&nbsp;&nbsp;&nbsp;&nbsp;';
							}

							if ($sk_tugas_akhir->num_rows() > 0)
							{
								echo ' | ';
								echo '<a href="'.base_url('uploads/'.$sk_tugas_akhir->row()->berkas).'">Download SK</a>';
							}
							?>
						</td>
					</tr>
					<?php 
						endif;
						endforeach;
					?>
				</tbody>
			</table>
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
		</div>
	<!-- /.box-footer-->
	</div>
</section>