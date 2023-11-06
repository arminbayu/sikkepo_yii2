  <nav id="navbar-fixed" class="navbar navbar-static-top">
    <div id="progress-bar" style="display: none; z-index: 10; width: 100%; height: 100%; position: fixed; background-color: rgba(255, 255, 255, 0.7); color: #fff;"><div style="position: relative; width: 10%; top: 40%; left: 45%; right: 45%; text-align: center"><img style="width: 60%" src="admin/img/double-ring.svg" /></div></div>
    <div class="header">
      <div class="header-content">
        <div class="navbar-brand">
          <img style="height: 50px; width: 40px" src="<?= L ?>" />
        </div>
        <span style="font-size: 3em; color: #fff"><?= T ?></span><span style="font-size: 1.5em; color: #fff; margin-left: 8px"><?= K ?></span>
        <div id="nav-logout">
          <?= Html::a('<div class="navbar-right-logout" ><div class="logout-icon"><i class="fa fa-sign-out"></i></div><div class="logout-text">Logout</div></div>', ['site/logout'], ['data'=>['method'=>'post', 'confirm'=>'Logout?']]) ?>
        </div>
        <div id="usermenu">
          <a class="dropdown dropdown-toggle" aria-expanded="false" role="button" data-toggle="dropdown" >
            <img class="navbar-right-img img-rounded" id="profile-pic" name="profile-pic" src="admin/img/pixel.jpg" />
            <div id="loginName" class="navbar-right-user user-label text-right ellipsis">Admin</div>
            <div class="navbar-right-last user-label-info text-right">Last online: <?= date('D, d-m-Y h:i:s') ?></div>
          </a>
          <ul class="dropdown-menu" role="menu">
            <li>
              <a href="">
                <div class="clearfix">
                  <div class="menu-icon"><i class="fa fa-sign-out"></i></div>
                  <div class="menu-text">Logout</div>
                </div>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div id="alert"></div>

    <div class="navigation">
      <div id="mainmenu" class="row">
        <ul id="mainmenu-group" class="nav navbar-nav">
          <li id="mainmenu-0" class="rootmenu active ">
            <a class="homeicon" href="#" onClick="window.location.href=toHomeURL;">
              <i class="fa fa-institution"></i>
            </a>
          </li>
          <li class="rootmenu dropdown">
            <a class="dropdown-toggle clearfix" data-toggle="dropdown" role="button" aria-expanded="false">
              Administrator<span class="arrow menu-arrow"></span>
            </a> 
            <ul id="menu-administrator" class="dropdown-menu" role="menu">
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-users"></i></div><div class="menu-text">Kelola Pengguna</div></div>', ['site/pengguna']) ?>
              </li>
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-users"></i></div><div class="menu-text">Kelola Kepala Unit</div></div>', ['site/ka-unit']) ?>
              </li>
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-fax"></i></div><div class="menu-text">Kelola Terminal</div></div>', ['site/terminal']) ?>
              </li>
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-file-text"></i></div><div class="menu-text">No Absen</div></div>', ['site/no-absen']) ?>
              </li>
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-download"></i></div><div class="menu-text">Tarik Data</div></div>', ['site/daftar-terminal']) ?>
              </li>
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-file"></i></div><div class="menu-text">Data Absen</div></div>', ['site/data-absen']) ?>
              </li>
            </ul>
          </li>
          <li class="rootmenu dropdown">
            <a class="dropdown-toggle clearfix" data-toggle="dropdown" role="button" aria-expanded="false">
              Aparatur Sipil Negara<span class="arrow menu-arrow"></span>
            </a> 
            <ul class="dropdown-menu" role="menu">
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-user"></i></div><div class="menu-text">Data Pegawai</div></div>', ['pegawai/data-pegawai']) ?>
              </li>
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-user-plus"></i></div><div class="menu-text">Cari Pegawai</div></div>', ['pegawai/cari-pegawai']) ?>
              </li>
            </ul>
          </li>
          <li class="rootmenu dropdown">
            <a class="dropdown-toggle clearfix" data-toggle="dropdown" role="button" aria-expanded="false">
              Edit Kehadiran<span class="arrow menu-arrow"></span>
            </a> 
            <ul id="menu-kehadiran" class="dropdown-menu" role="menu">
              <li class="col-xs-6 first">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-file-o"></i></div><div class="menu-text">Upacara</div></div>', ['kehadiran/upacara']) ?>
              </li>
              <li class="col-xs-6 first">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-pencil"></i></div><div class="menu-text">Edit Upacara</div></div>', ['kehadiran/edit-upacara']) ?>
              </li>
              <!--
              <li class="col-xs-6 first">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-file-o"></i></div><div class="menu-text">Rapat</div></div>', ['kehadiran/rapat']) ?>
              </li>
              <li class="col-xs-6 first">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-pencil"></i></div><div class="menu-text">Edit Rapat</div></div>', ['kehadiran/edit-rapat']) ?>
              </li>
              -->
              <li class="col-xs-6">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-file-o"></i></div><div class="menu-text">Ketidakhadiran</div></div>', ['kehadiran/ketidakhadiran']) ?>
              </li>
              <li class="col-xs-6">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-pencil"></i></div><div class="menu-text">Edit Ketidakhadiran</div></div>', ['kehadiran/edit-ketidakhadiran']) ?>
              </li>
            </ul>
          </li>
          <li class="rootmenu dropdown">
            <a class="dropdown-toggle clearfix" data-toggle="dropdown" role="button" aria-expanded="false">
              Transaksi<span class="arrow menu-arrow"></span>
            </a> 
            <ul class="dropdown-menu" role="menu">  
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-gears"></i></div><div class="menu-text">Proses Kehadiran</div></div>', ['kehadiran/kehadiran-per-unit-kerja']) ?>
              </li>
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-gears"></i></div><div class="menu-text">Proses TPP</div></div>', ['tpp/tpp-per-unit-kerja']) ?>
              </li>
            </ul>
          </li>
          <li class="rootmenu dropdown">
            <a class="dropdown-toggle clearfix" data-toggle="dropdown" role="button" aria-expanded="false">
              Tabel Referensi<span class="arrow menu-arrow"></span>
            </a> 
            <ul class="dropdown-menu" role="menu">  
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-file-text-o"></i></div><div class="menu-text">Hari Libur Nasional</div></div>', ['data/hari-libur']) ?>
              </li>
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-file-text-o"></i></div><div class="menu-text">Satuan Kerja</div></div>', ['data/unit-kerja']) ?>
              </li>
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-file-text-o"></i></div><div class="menu-text">Bobot TPP</div></div>', ['data/tpp']) ?>
              </li>
            </ul>
          </li>
          <li class="rootmenu dropdown">
            <a class="dropdown-toggle clearfix" data-toggle="dropdown" role="button" aria-expanded="false">
              Laporan<span class="arrow menu-arrow"></span>
            </a> 
            <ul id="menu-laporan" class="dropdown-menu" role="menu">  
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-file-text"></i></div><div class="menu-text">Kehadiran per Pegawai</div></div>', ['laporan/kehadiran-per-pegawai']) ?>
              </li>
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-file-text"></i></div><div class="menu-text">Kehadiran Pegawai per SKPD</div></div>', ['laporan/kehadiran-per-unit-kerja']) ?>
              </li>
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-file-text"></i></div><div class="menu-text">TPP per Pegawai</div></div>', ['laporan/tpp-per-pegawai']) ?>
              </li>
              <li class="col-xs-12">
                <?= Html::a('<div class="clearfix"><div class="menu-icon"><i class="fa fa-file-text"></i></div><div class="menu-text">TPP per SKPD</div></div>', ['laporan/tpp-per-unit-kerja']) ?>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>