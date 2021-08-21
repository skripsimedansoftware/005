<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sistem Administrasi TA|KP - ADMIN</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="<?php echo base_url('assets/adminlte/') ?>bower_components/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url('assets/adminlte/') ?>bower_components/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo base_url('assets/adminlte/') ?>bower_components/Ionicons/css/ionicons.min.css">
	<link rel="stylesheet" href="<?php echo base_url('assets/adminlte/') ?>dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="<?php echo base_url('assets/adminlte/') ?>dist/css/skins/_all-skins.min.css">
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<!-- Google Font -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	<style type="text/css">
	.help-block.error {
		color: red;
	}

	.user-panel > .image > img {
		width: 45px;
		height: 45px;
		/*height: auto;*/
	}
	</style>
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition skin-red sidebar-mini fixed">
<div class="wrapper">

	<!-- Main Header -->
	<header class="main-header">

		<!-- Logo -->
		<a href="<?php echo base_url($this->router->fetch_class()) ?>" class="logo">
			<!-- mini logo for sidebar mini 50x50 pixels -->
			<span class="logo-mini"><b>M</b>S</span>
			<!-- logo for regular state and mobile devices -->
			<span class="logo-lg"><b>admin</b></span>
		</a>

		<!-- Header Navbar -->
		<nav class="navbar navbar-static-top" role="navigation">
			<!-- Sidebar toggle button-->
			<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
				<span class="sr-only">Toggle navigation</span>
			</a>
			<!-- Navbar Right Menu -->
			<div class="navbar-custom-menu">
				<ul class="nav navbar-nav">
					<!-- User Account Menu -->
					<li class="dropdown user user-menu">
						<!-- Menu Toggle Button -->
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<!-- The user image in the navbar-->
							<img src="<?php echo (!empty($session->photo))?base_url('uploads/'.$session->photo):base_url('assets/adminlte/dist/img/user2-160x160.jpg') ?>" class="user-image" alt="User Image">
							<!-- hidden-xs hides the username on small devices so only the image appears. -->
							<span class="hidden-xs"><?php echo $session->full_name ?></span>
						</a>
						<ul class="dropdown-menu">
							<!-- The user image in the menu -->
							<li class="user-header">
								<img src="<?php echo (!empty($session->photo))?base_url('uploads/'.$session->photo):base_url('assets/adminlte/dist/img/user2-160x160.jpg') ?>" class="img-circle" alt="User Image">

								<p>
									<?php echo $session->full_name ?>
									<small>admin</small>
								</p>
							</li>
							<!-- Menu Footer-->
							<li class="user-footer">
								<div class="pull-left">
									<a href="<?php echo base_url($this->router->fetch_class().'/profile') ?>" class="btn btn-default btn-flat">Profil</a>
								</div>
								<div class="pull-right">
									<a href="<?php echo base_url($this->router->fetch_class().'/logout') ?>" class="btn btn-default btn-flat">Keluar</a>
								</div>
							</li>
						</ul>
					</li>
				</ul>
			</div>
		</nav>
	</header>
	<!-- Left side column. contains the logo and sidebar -->
	<aside class="main-sidebar">

		<!-- sidebar: style can be found in sidebar.less -->
		<section class="sidebar">

			<!-- Sidebar user panel (optional) -->
			<div class="user-panel">
				<div class="pull-left image">
					<img src="<?php echo (!empty($session->photo))?base_url('uploads/'.$session->photo):base_url('assets/adminlte/dist/img/user2-160x160.jpg') ?>" class="img-circle" alt="User Image">
				</div>
				<div class="pull-left info">
					<p><?php echo $session->full_name ?></p>
					<!-- Status -->
					<a href="#"><i class="fa fa-circle text-success"></i> online</a>
				</div>
			</div>

			<!-- Sidebar Menu -->
			<ul class="sidebar-menu" data-widget="tree">
				<li class="header">Sistem Administrasi - TA | KP</li>
				<!-- Optionally, you can add icons to the links -->
				<li class="<?php echo ($this->router->fetch_method() == 'index')?'active':'' ?>"><a href="<?php echo base_url($this->router->fetch_class()) ?>"><i class="fa fa-home"></i> <span>Beranda</span></a></li>
				<li class="treeview <?php echo (in_array($this->router->fetch_method(), ['dosen', 'mahasiswa']))?'active':'' ?>">
					<a href="#"><i class="fa fa-users"></i> <span>Kelola Pengguna</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo ($this->router->fetch_method() == 'dosen')?'active':'' ?>"><a href="<?php echo base_url($this->router->fetch_class().'/dosen') ?>">Dosen</a></li>
						<li class="<?php echo ($this->router->fetch_method() == 'mahasiswa')?'active':'' ?>"><a href="<?php echo base_url($this->router->fetch_class().'/mahasiswa') ?>">Mahasiswa</a></li>
					</ul>
				</li>
				<li class="treeview <?php echo (in_array($this->router->fetch_method(), ['judul_kerja_praktek', 'judul_skripsi']))?'active':'' ?>">
					<a href="#"><i class="fa fa-book"></i> <span>Daftar Judul</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo ($this->router->fetch_method() == 'judul_kerja_praktek')?'active':'' ?>"><a href="<?php echo base_url($this->router->fetch_class().'/judul_kerja_praktek') ?>">Kerja Praktek</a></li>
						<li class="<?php echo ($this->router->fetch_method() == 'judul_skripsi')?'active':'' ?>"><a href="<?php echo base_url($this->router->fetch_class().'/judul_skripsi') ?>">Skripsi</a></li>
					</ul>
				</li>
				<li class="treeview <?php echo (in_array($this->router->fetch_method(), ['jadwal_seminar_hasil', 'jadwal_sidang']))?'active':'' ?>">
					<a href="#"><i class="fa fa-clock-o"></i> <span>Kelola Jadwal</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo ($this->router->fetch_method() == 'jadwal_seminar_hasil')?'active':'' ?>"><a href="<?php echo base_url($this->router->fetch_class().'/jadwal_seminar_hasil') ?>">Seminar Hasil</a></li>
						<li class="<?php echo ($this->router->fetch_method() == 'jadwal_sidang')?'active':'' ?>"><a href="<?php echo base_url($this->router->fetch_class().'/jadwal_sidang') ?>">Sidang</a></li>
					</ul>
				</li>
				<li class="<?php echo ($this->router->fetch_method() == 'lokasi')?'active':'' ?>"><a href="<?php echo base_url($this->router->fetch_class().'/lokasi') ?>"><i class="fa fa-map-marker"></i> <span>Kelola Lokasi</span></a></li>
				<li class="<?php echo ($this->router->fetch_method() == 'cetak_sk')?'active':'' ?>"><a href="<?php echo base_url($this->router->fetch_class().'/cetak_sk') ?>"><i class="fa fa-print"></i> <span>Nama Pembimbing</span></a></li>
			</ul>
			<!-- /.sidebar-menu -->
		</section>
		<!-- /.sidebar -->
	</aside>

	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<?php echo $page ?>
	</div>
	<!-- /.content-wrapper -->

	<!-- Main Footer -->
	<footer class="main-footer">
		<!-- To the right -->
		<div class="pull-right hidden-xs">
			skripsi
		</div>
		<!-- Default to the left -->
		<strong>Copyright &copy; <?php echo date('Y') ?> <a href="#">Sistem Administrasi TA|KP</a> | </strong> All rights reserved.
	</footer>

	<!-- /.control-sidebar -->
	<!-- Add the sidebar's background. This div must be placed
	immediately after the control sidebar -->
	<div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
<script src="<?php echo base_url('assets/adminlte/') ?>bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url('assets/adminlte/') ?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('assets/adminlte/') ?>dist/js/adminlte.min.js"></script>
<script type="text/javascript">
function readURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('#profile-upload-preview').attr('src', e.target.result);
		}
		reader.readAsDataURL(input.files[0]);
	}
}
</script>
</body>
</html>
