       <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
           <div class="container-fluid">
               <nav class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
               </nav>

               <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">

                   <li class="nav-item topbar-user dropdown hidden-caret">
                       <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#"
                           aria-expanded="false">
                           <div class="avatar-sm">
                               <img src="{{ asset('assets/img/logo_profile.png') }}" alt="..."
                                   class="avatar-img rounded-circle" />
                           </div>
                           <span class="profile-username">
                               <span class="fw-bold">{{ auth()->user()->name }}</span>
                           </span>
                       </a>
                       <ul class="dropdown-menu dropdown-user animated fadeIn">
                           <div class="dropdown-user-scroll scrollbar-outer">
                               <li>
                               </li>
                               <li>
                                   <div class="dropdown-divider"></div>
                                   <form method="POST" action="{{ route('logout') }}">
                                       @csrf
                                       <button type="submit" class="dropdown-item text-danger">Logout</button>
                                   </form>
                               </li>
                           </div>
                       </ul>
                   </li>
               </ul>
           </div>
       </nav>
