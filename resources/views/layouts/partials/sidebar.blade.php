      <!-- Sidebar -->
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
            <a href="index.html" class="logo">
              <img
                src="assets/img/logo-perusahaan-text.png"
                alt="navbar brand"
                class="navbar-brand"
                height="90"
              />
            </a>
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
              </button>
            </div>
            <button class="topbar-toggler more">
              <i class="gg-more-vertical-alt"></i>
            </button>
          </div>
          <!-- End Logo Header -->
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <ul class="nav nav-secondary">
              <li class="nav-item active">
                <a
                  data-bs-toggle="collapse"
                  href="#dashboard"
                  class="collapsed"
                  aria-expanded="false"
                >
                  <i class="fas fa-home"></i>
                  <p>Dashboard</p>
                </a>
              </li>
              {{-- Bagian Master Data --}}
              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Master Data</h4>
              </li>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#barang">
                  <i class="fas fa-layer-group"></i>
                  <p>Barang</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="barang">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="components/avatars.html">
                        <span class="sub-item">Data Barang</span>
                      </a>
                    </li>
                    <li>
                      <a href="components/buttons.html">
                        <span class="sub-item">Tambah Data Barang</span>
                      </a>
                    </li>
                    <li>
                      <a href="components/gridsystem.html">
                        <span class="sub-item">Data Kategori</span>
                      </a>
                    </li>
                    <li>
                      <a href="components/panels.html">
                        <span class="sub-item">Tambah Data Kategori</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#pelanggan">
                  <i class="fas fa-th-list"></i>
                  <p>Pelanggan</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="pelanggan">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="sidebar-style-2.html">
                        <span class="sub-item">Data Pelanggan</span>
                      </a>
                    </li>
                    <li>
                      <a href="icon-menu.html">
                        <span class="sub-item">Tambah Data Pelanggan</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#supplier">
                  <i class="fas fa-pen-square"></i>
                  <p>Supplier</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="supplier">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="forms/forms.html">
                        <span class="sub-item">Data Supplier</span>
                      </a>
                    </li>
                     <li>
                      <a href="forms/forms.html">
                        <span class="sub-item">Tambah Data Supplier</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>

            {{-- END Bagian Master Data --}}

            {{-- Bagian Transaksi --}}
              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Transaksi</h4>
              </li>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#penjualan">
                  <i class="fas fa-layer-group"></i>
                  <p>Penjualan</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="penjualan">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="components/avatars.html">
                        <span class="sub-item">Riwayat Transaksi Penjualan</span>
                      </a>
                    </li>
                    <li>
                      <a href="components/buttons.html">
                        <span class="sub-item">Tambah Nota Penjualan</span>
                      </a>
                    </li>
                     <li>
                      <a href="components/buttons.html">
                        <span class="sub-item">Riwajyat Pembayaran Penjualan</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#pembelian">
                  <i class="fas fa-th-list"></i>
                  <p>Pembelian</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="pembelian">
                  <ul class="nav nav-collapse">
                   <li>
                      <a href="components/avatars.html">
                        <span class="sub-item">Riwayat Transaksi Pembelian</span>
                      </a>
                    </li>
                    <li>
                      <a href="components/buttons.html">
                        <span class="sub-item">Tambah Nota Pembelian</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              {{-- END BAGIAN TRANSAKSI --}}

            {{-- LAPORAN --}}
              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">LAPORAN</h4>
              </li>
                <li class="nav-item">
                <a
                  data-bs-toggle="collapse"
                  href="#laporan-penjualan"
                  class="collapsed"
                  aria-expanded="false"
                >
                  <i class="fas fa-th-list"></i>
                  <p>Laporan Penjualan</p>
                </a>
               </li>
                <li class="nav-item">
                <a
                  data-bs-toggle="collapse"
                  href="#laporan-pembelian"
                  class="collapsed"
                  aria-expanded="false"
                >
                  <i class="fas fa-th-list"></i>
                  <p>Laporan Pembelian</p>
                </a>
               </li>


            {{-- PENGATURAN --}}
              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">PENGATURAN</h4>
              </li>
               <li class="nav-item">
                <a
                  data-bs-toggle="collapse"
                  href="#manajemen-akun"
                  class="collapsed"
                  aria-expanded="false"
                >
                  <i class="fas fa-home"></i>
                  <p>Manajemen Akun</p>
                </a>
               </li>
              <li class="nav-item">
                <a
                  data-bs-toggle="collapse"
                  href="#log-aktivitas"
                  class="collapsed"
                  aria-expanded="false"
                >
                  <i class="fas fa-home"></i>
                  <p>Log Aktivitas</p>
                </a>
              </li>

            </ul>
          </div>
        </div>
      </div>
      <!-- End Sidebar -->
