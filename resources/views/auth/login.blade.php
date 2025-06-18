@extends('layouts.app')

@section('content')
<section class="vh-100 bg-light d-flex align-items-center justify-content-center" style="background-image: url('/images/bg_warehouse_blur.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 col-md-10">
        <div class="card shadow-lg rounded-4 overflow-hidden">
          <div class="row g-0">
            <!-- Gambar di sisi kiri -->
            <div class="col-md-5 d-none d-md-block">
              <img src="{{ asset('images/gudang_image_for_login_page.jpg') }}" alt="Login Image"
                   class="img-fluid h-100 w-100" style="object-fit: cover;">
            </div>

            <!-- Form Login -->
            <div class="col-md-7 d-flex align-items-center">
              <div class="card-body px-4 py-5">
                <div class="text-center mb-4">
                  <i class="fas fa-cubes fa-2x mb-2" style="color: #ff6219;"></i>
                  <h4 class="fw-bold">PD Karya Citra Mandiri</h4>
                  <p class="text-muted mb-0">Masuk ke Akun anda</p>
                </div>

                <form method="POST" action="#">
                  @csrf

                  <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" class="form-control" placeholder="you@example.com" required>
                  </div>

                  <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" class="form-control" placeholder="Password" required>
                  </div>

                  <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-warning text-white fw-bold">Login</button>
                  </div>

                  <div class="text-center">
                    <a href="#" class="text-decoration-none text-muted small">Forgot password?</a>
                  </div>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
