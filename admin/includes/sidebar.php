<!-- Sidebar -->
<ul class="navbar-nav bg-card-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index">
        <div class="sidebar-brand-icon">
            <img src="images/logo.png" alt="" style="width:30px">
        </div>
        <div class="sidebar-brand-text mx-2">Kla Computer</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0" />

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?= $page === 'index' ? 'active' : '' ?>">
        <a class="nav-link" href="index">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider" />

    <!-- Heading -->
    <div class="sidebar-heading">Product</div>
    <li class="nav-item <?= $page === 'product' ? 'active' : '' ?>">
        <a class="nav-link" href="product">
            <i class="fas fa-solid fa-list-ol"></i>
            <span>Product</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider" />

    <!-- Heading -->
    <div class="sidebar-heading">Order</div>

    <li class="nav-item <?= $page === 'order' ? 'active' : '' ?>">
        <a class="nav-link" href="order">
            <i class="fas fa-solid fa-newspaper"></i>
            <span>Order</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider" />

    <!-- Heading -->
    <div class="sidebar-heading">User Management</div>
    <li class="nav-item <?= $page === 'user' ? 'active' : '' ?>">
        <a class="nav-link" href="user">
            <i class="fas fa-fw fa-user"></i>
            <span>User</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block" />

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
<!-- End of Sidebar -->