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
                            <form class="card card-md" id="verifyForm" autocomplete="off" >
                                @csrf
                                <div class="card-body">
                                    <h2 class="card-title card-title-lg text-center mb-4">Authenticate Your Account</h2>
                                    <p class="my-4 text-center">Please confirm your account by entering the
                                        authorization code sent to <strong id="userEmail">Loading....</strong>.</p>
                                    <div class="my-5">
                                        <div class="row g-4">
                                            <div class="col">
                                                <div class="row g-2">
                                                    <div class="col">
                                                        <input type="text"
                                                            class="form-control form-control-lg text-center py-3"
                                                            maxlength="1" name="code1" required inputmode="numeric" pattern="[0-9]*"
                                                            data-code-input />
                                                    </div>
                                                    <div class="col">
                                                        <input type="text"
                                                            class="form-control form-control-lg text-center py-3"
                                                            maxlength="1" required name="code2" inputmode="numeric" pattern="[0-9]*"
                                                            data-code-input />
                                                    </div>
                                                    <div class="col">
                                                        <input type="text"
                                                            class="form-control form-control-lg text-center py-3"
                                                            maxlength="1" required name="code3" inputmode="numeric" pattern="[0-9]*"
                                                            data-code-input />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="row g-2">
                                                    <div class="col">
                                                        <input type="text"
                                                            class="form-control form-control-lg text-center py-3"
                                                            maxlength="1" required name="code4" inputmode="numeric" pattern="[0-9]*"
                                                            data-code-input />
                                                    </div>
                                                    <div class="col">
                                                        <input type="text"
                                                            class="form-control form-control-lg text-center py-3"
                                                            maxlength="1" required name="code5" inputmode="numeric" pattern="[0-9]*"
                                                            data-code-input />
                                                    </div>
                                                    <div class="col">
                                                        <input type="text"
                                                            class="form-control form-control-lg text-center py-3"
                                                            maxlength="1" required name="code6" inputmode="numeric" pattern="[0-9]*"
                                                            data-code-input />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="my-4">
                                        <label class="form-check">
                                            <input type="checkbox" class="form-check-input" />
                                            Dont't ask for codes again on this device
                                        </label>
                                    </div> --}}
                                    <div class="form-footer">
                                        <div class="btn-list flex-nowrap">
                                            <button type="button" id="cancelVerify" class="btn w-100">
                                                Cancel
                                            </button>
                                            <button type="submit" id="verifySubmit" class="btn btn-primary w-100">
                                                Verify
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="text-center text-secondary mt-3">
                                It may take a minute to receive your code. Haven't received it?<a href="#" id="resendCode" onclick="event.preventDefault()">Resend a new code.</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            @include('Admin.components.footer')
            <script src="/js/verify.js"></script>
        </div>
    </div>
    @include('Admin.components.scripts', ['loc'=> 'signin'])


</body>

</html>
