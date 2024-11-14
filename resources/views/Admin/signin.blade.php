@include('Admin.components.head', ['title' => 'UniOil Raffle Draw'])

<body>
    <script src="{{ asset('./dist/js/demo-theme.min.js?1692870487') }}"></script>
    <div class="page">
        @include('Admin.components.loader')
        <div class="page-wrapper">
            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl d-flex flex-column justify-content-center">

                    <div class="page page-center">
                        <div class="container container-tight py-4">
                            <div class="text-center mb-4">
                                <a href="#" class="navbar-brand navbar-brand-autodark">
                                    <img src="{{ asset('unioil_images/unioil_logo.png') }}" alt="Tabler"
                                        class="navbar-brand-image" style="width: 150px; height: auto;">
                                </a>
                            </div>
                            <div class="card card-md">
                                <div class="card-body">
                                    <h2 class="h2 text-center mb-4">Login to your account</h2>
                                    <form  id="authForm">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Email address</label>
                                            <input type="email" name="email" required class="form-control" placeholder="admin@email.com"
                                                autocomplete="off">
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">
                                                Password
                                                {{-- <span class="form-label-description">
                                                    <a href="./forgot-password.html">I forgot password</a>
                                                </span> --}}
                                            </label>
                                            <div class="input-group input-group-flat">
                                                <input type="password" required name="password" class="form-control" placeholder="Your password"
                                                    autocomplete="off">
                                                <span class="input-group-text">
                                                    <a href="#" class="link-secondary" title="Show password"
                                                        data-bs-toggle="tooltip">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon"
                                                            width="24" height="24" viewBox="0 0 24 24"
                                                            stroke-width="2" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                            <path
                                                                d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                        </svg>
                                                    </a>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="form-footer">
                                            <button type="submit" class="btn btn-primary w-100">Sign in</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('Admin.components.footer')
        </div>
    </div>
    <script src="/js/auth.js"></script>
    @include('Admin.components.scripts')


</body>

</html>
