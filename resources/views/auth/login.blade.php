@extends('auth.layouts')

@section('content')

<!-- Section: Design Block -->
<div class="container-regist">
<section class="text-center-regist">

<div class="card mx-4 mx-md-5 shadow-5-strong" style="margin-top: 100px;width:60%; background: hsla(0, 0%, 100%, 0.8);backdrop-filter: blur(30px);">
  <div class="card-body py-5 px-md-5">

    <div class="row d-flex justify-content-center">
      <div class="col-lg-8">
        <h1 class="fw-bold mb-5">Sign In now</h1>
        <form class="login-form" action="{{ route('authenticate') }}" method="post">
          @csrf
          <!-- Email input -->
          <div class="form-outline mb-4 form-input">
            <div class="form-name">
              <label class="form-label" for="form3Example3">Email address</label>
            </div>
              <div class="col form-input">
                  <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                  @if ($errors->has('email'))
                      <span class="text-danger">{{ $errors->first('email') }}</span>
                  @endif
              </div>
          </div>

          <!-- Password input -->
          <div class="form-outline mb-4 form-input">
            <div class="form-name">
              <label class="form-label" for="form3Example4">Password</label>
            </div>
              <div class="col form-input">
                  <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                  @if ($errors->has('password'))
                      <span class="text-danger">{{ $errors->first('password') }}</span>
                  @endif
              </div>
          </div>

          <!-- Submit button -->
          <div class="mb-3 row" style="justify-content: center;">
              <input type="submit" class="col-md-4 btn btn-primary btn-login" value="Sign In">
          </div>
          <!-- href login -->
          <div class="href-login" style="display:flex;">
            <p style="color: #393f81;">Don't have an account? </p>
            <small> <a href="{{route('register')}}" style="color: #393f81;">Sign Up</a></small>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
</section>
<!-- Section: Design Block -->
</div>
@endsection