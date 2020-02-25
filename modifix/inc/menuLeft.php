<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?= ucfirst(Session::get("usuario")); ?></p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form (Optional) -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form>
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">HEADER</li>
            <!-- Optionally, you can add icons to the links -->
            <li class="active"><a href="<?= BASE_URL_ADMIN; ?>"><i class="fa fa-home"></i><span>DashBoard</span></a></li>
            <li><a href="<?= BASE_URL_ADMIN; ?>pages/usuarios/index.php"><i class="fa fa-users"></i><span>Usuarios</span></a></li>
            <li><a href="<?= BASE_URL_ADMIN; ?>pages/tracks/?page=1"><i class="fa fa-play-circle-o"></i><span>Tracks</span></a></li>
            <li><a href="<?= BASE_URL_ADMIN; ?>pages/ventas/?page=1"><i class="fa fa-credit-card"></i><span>Ventas</span></a></li>
            <li class="treeview">
                <a href="<?= BASE_URL_ADMIN; ?>pages/promociones/"><i class="fa fa-credit-card"></i><span>Promociones</span><i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="<?= BASE_URL_ADMIN . "pages/promociones/insert.php" ?>">Crear Promocion</a></li>
                    <!--                    <li><a href="#">Link in level 2</a></li>-->
                </ul>
            </li>
            <li class="treeview">
                <a href="#"><i class="fa fa-link"></i> <span>Generate</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="<?= BASE_URL_ADMIN . "pages/generate/index.php" ?>">Generate Tracks</a></li>
                    <!--                    <li><a href="#">Link in level 2</a></li>-->
                </ul>
            </li>
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>