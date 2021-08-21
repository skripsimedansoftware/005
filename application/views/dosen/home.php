<section class="content-header">
	<h1>Dosen<small>Home</small></h1>
</section>

<section class="content container-fluid">
	<div class="row">
		<div class="col-lg-6 col-xs-12">
			<div class="small-box bg-navy">
				<div class="inner">
					<h3><?php echo $this->mahasiswa->total_data(); ?></h3>
					<p>Mahasiswa</p>
				</div>
				<div class="icon">
					<i class="fa fa-users"></i>
				</div>
			</div>
		</div>
		<div class="col-lg-6 col-xs-12">
			<div class="small-box bg-yellow">
				<div class="inner">
					<h3><?php echo $this->dosen->total_data(); ?></h3>
					<p>Dosen</p>
				</div>
				<div class="icon">
					<i class="fa fa-users"></i>
				</div>
			</div>
		</div>
		<div class="col-lg-6 col-xs-12">
			<div class="small-box bg-teal">
				<div class="inner">
					<h3><?php echo $this->judul_mahasiswa->total_judul_kerja_praktek(); ?></h3>
					<p>Judul Kerja Praktek</p>
				</div>
				<div class="icon">
					<i class="fa fa-book"></i>
				</div>
			</div>
		</div>
		<div class="col-lg-6 col-xs-12">
			<div class="small-box bg-maroon">
				<div class="inner">
					<h3><?php echo $this->judul_mahasiswa->total_juduk_tugas_akhir(); ?></h3>
					<p>Judul Skripsi</p>
				</div>
				<div class="icon">
					<i class="fa fa-book"></i>
				</div>
			</div>
		</div>
		<div class="col-lg-6 col-xs-12">
			<div class="small-box bg-purple">
				<div class="inner">
					<h3><?php echo $this->jadwal->total_data(); ?></h3>
					<p>Jadwal Seminar Hasil</p>
				</div>
				<div class="icon">
					<i class="fa fa-clock-o"></i>
				</div>
			</div>
		</div>
		<div class="col-lg-6 col-xs-12">
			<div class="small-box bg-green">
				<div class="inner">
					<h3><?php echo $this->jadwal->total_data(); ?></h3>
					<p>Jadwal Sidang</p>
				</div>
				<div class="icon">
					<i class="fa fa-clock-o"></i>
				</div>
			</div>
		</div>
	</div>
	<!-- <div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">Title</h3>
		</div>
		<div class="box-body">
			Start creating your amazing application!
		</div>
		<div class="box-footer">
			Footer
		</div>
	</div> -->
</section>