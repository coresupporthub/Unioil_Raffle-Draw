@include('Admin.components.head', ['title' => 'Account Settings'])

<body>
    <script src="{{asset('./dist/js/demo-theme.min.js?1692870487')}}"></script>
    <div class="page">

        @include('Admin.components.header', ['active' => ''])

        <div class="page-wrapper">
            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl d-flex flex-column justify-content-center">

                    <div class="page-body">
                        <div class="container-xl">
                          <div class="card">
                            <div class="row g-0">
                                <div class="col-12 col-md-3 border-end">
                                    <div class="card-body">
                                        <!-- Settings Group -->
                                        <h4 class="subheader">Settings</h4>
                                        <div class="list-group list-group-transparent">
                                            <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                                                General Settings
                                            </a>
                                            <a href="./promo-settings.html" class="list-group-item list-group-item-action d-flex align-items-center">
                                                Promo Settings
                                            </a>
                                            <a href="./user-management.html" class="list-group-item list-group-item-action d-flex align-items-center">
                                                User Management
                                            </a>
                                        </div>
                                
                                        <!-- Divider -->
                                        <div class="dropdown-divider"></div>
                                
                                        <!-- Account Group -->
                                        <h4 class="subheader mt-4">Account</h4>
                                        <div class="list-group list-group-transparent">
                                            <a href="{{ route('accountsettings') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                                                Account Settings
                                            </a>
                                            <a href="./sign-in.html" class="list-group-item list-group-item-action d-flex align-items-center">
                                                Logout
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                              <div class="col-12 col-md-9 d-flex flex-column">
                                <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                                    <h2 class="mb-4">My Account</h2>
                                  <h3 class="card-title">Profile Details</h3>
                                  <div class="row align-items-center">
                                    <div class="col-auto"><span class="avatar avatar-xl" style="background-image: url({{asset('unioil_images/unioil.png')}})"></span>
                                    </div>
                                    <div class="col-auto"><a href="#" class="btn">
                                        Change avatar
                                      </a></div>
                                    <div class="col-auto"><a href="#" class="btn btn-ghost-danger">
                                        Delete avatar
                                      </a></div>
                                  </div>
                                  <h3 class="card-title mt-4">Admin Profile</h3>
                                  <div class="row g-3">
                                    <div class="col-md">
                                      <div class="form-label">Admin Name</div>
                                      <input type="text" class="form-control" value="Tabler">
                                    </div>
                                    <div class="col-md">
                                      <div class="form-label">Admin ID</div>
                                      <input type="text" class="form-control" value="560afc32">
                                    </div>
                                  </div>
                                  <h3 class="card-title mt-4">Email</h3>
                                  <div>
                                    <div class="row g-2">
                                      <div class="col-auto">
                                        <input type="text" class="form-control w-auto" value="paweluna@howstuffworks.com">
                                      </div>
                                      <div class="col-auto"><a href="#" class="btn">
                                          Change
                                        </a></div>
                                        .
                                    </div>
                                  </div>
                                  <h3 class="card-title mt-4">Password</h3>
                                  <div>
                                    <a href="#" class="btn">
                                      Set new password
                                    </a>
                                  </div>
                                </div>
                                <div class="card-footer bg-transparent mt-auto">
                                  <div class="btn-list justify-content-end">
                                    <a href="#" class="btn">
                                      Cancel
                                    </a>
                                    <a href="#" class="btn btn-primary">
                                      Submit
                                    </a>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>


                </div>
            </div>

            @include('Admin.components.footer')

        </div>
    </div>

    @include('Admin.components.scripts')


</body>

</html>
