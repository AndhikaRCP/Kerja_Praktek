<!-- partials/navbar.blade.php -->
<nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
  <div class="container-fluid">
    <form class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
      <div class="input-group">
        <div class="input-group-prepend">
          <button type="submit" class="btn btn-search pe-1">
            <i class="fa fa-search search-icon"></i>
          </button>
        </div>
        <input type="text" placeholder="Search ..." class="form-control" />
      </div>
    </form>

    <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" role="button">
          <div class="avatar-sm">
            <img src="{{ asset('assets/img/profile.jpg') }}" alt="..." class="avatar-img rounded-circle" />
          </div>
          <span class="profile-username">
            <span class="op-7">Hi,</span>
            <span class="fw-bold">User</span>
          </span>
        </a>
        <ul class="dropdown-menu dropdown-user animated fadeIn">
          <li>
            <div class="user-box">
              <div class="avatar-lg">
                <img src="{{ asset('assets/img/profile.jpg') }}" alt="image profile" class="avatar-img rounded" />
              </div>
              <div class="u-text">
                <h4>User</h4>
                <p class="text-muted">user@example.com</p>
                <a href="#" class="btn btn-xs btn-secondary btn-sm">View Profile</a>
              </div>
            </div>
          </li>
          <li>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Logout</a>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>
