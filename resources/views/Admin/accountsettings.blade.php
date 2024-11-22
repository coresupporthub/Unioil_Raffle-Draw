@include('Admin.components.head', ['title' => 'Account Settings'])

<body>
    <script src="{{ asset('./dist/js/demo-theme.min.js?1692870487') }}"></script>
    <div class="page">

        @include('Admin.components.header', ['active' => ''])
        @include('Admin.components.loader')
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

                                            <!-- Divider -->
                                            <div class="dropdown-divider"></div>

                                            <!-- Account Group -->
                                            <h4 class="subheader mt-4">Account</h4>
                                            <div class="list-group list-group-transparent">
                                                <!-- Active "Account Settings" -->
                                                <a href="{{ route('accountsettings') }}"
                                                    class="list-group-item list-group-item-action d-flex align-items-center active rounded-pill"
                                                    style="background-image: linear-gradient(45deg, #F75A04, #ffffff); color: white;">
                                                    Account Settings
                                                </a>
                                                <button onclick="adminLogout()"
                                                    class="list-group-item list-group-item-action d-flex align-items-center">
                                                    Logout
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-9 d-flex flex-column">
                                        <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                                            <h2 class="mb-4">My Account</h2>
                                            <h3 class="card-title">Profile Details</h3>
                                            <form id="adminDetailsForm" class="row g-3">
                                                @csrf
                                                <div class="col-md-6">
                                                    <div class="form-label"> Name</div>
                                                    <input type="text" disabled id="adminName" name="name" class="form-control" value="Loading...">
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-label"> Email</div>
                                                    <input type="email" disabled id="adminEmail" name="email" class="form-control" value="Loading...">
                                                </div>
                                            </form>

                                            <h3 class="card-title mt-4">Password</h3>
                                            <div>
                                                <a href="" data-bs-toggle="modal"
                                                    data-bs-target="#changepasswordModal" class="btn">Set new
                                                    password</a>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent mt-auto">
                                            <div class="btn-list justify-content-end">
                                                <button id="adminDetailsBtn" class="btn btn-primary">Submit</button>
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
    @include('Admin.components.scripts', ['loc'=> 'admin'])

    {{-- modals --}}

    {{-- change admin password --}}
    <div class="modal modal-blur fade" id="changepasswordModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-header Unioil-header">
                    <h5 class="modal-title">Change Admin Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>

                <form id="changePasswordForm" class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label for="currentPassword" class="form-label">Current Password</label>
                        <input type="password" required name="currentPassword" class="form-control" id="currentPassword"
                            placeholder="Enter current password">
                        <div id="currentPasswordError" class="text-danger mt-2" style="display: none;">Current
                            password is required.</div>
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">New Password</label>
                        <input type="password" required oninput="checkPass(this, 'confirmPassword')" name="newPassword" class="form-control" id="newPassword" placeholder="Enter new password">
                        <div id="newPasswordError" class="text-danger mt-2" style="display: none;">New password
                            is required.</div>
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm New Password</label>
                        <input type="password" required oninput="checkPass(this, 'newPassword')" name="confirmPassword" class="form-control" id="confirmPassword"
                            placeholder="Confirm new password">
                        <div id="confirmPasswordError" class="text-danger mt-2" style="display: none;">Passwords
                            do not match.</div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" id="closeChangePassword" class="btn btn-link link-secondary me-auto"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" disabled id="changePasswordBtn">Save</button>
                </div>
            </div>
        </div>
    </div>

        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-title">Upload New Profile Picture</div>
                    <div class="mb-3">
                        <label for="profileImage" class="form-label">Choose an image</label>
                        <input type="file" class="form-control" id="profileImage" accept="image/*"
                            onchange="previewImage(event)">
                    </div>
                    <div id="imagePreviewContainer" class="mt-3" style="display: none;">
                        <h5>Preview</h5>
                        <img id="imagePreview" class="img-fluid" src="" alt="Image Preview" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary me-auto"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveProfileImage"
                        data-bs-dismiss="modal">Save</button>
                </div>

            </div>
        </div>

        {{-- modals --}}

        <script src="/js/account_settings.js"></script>

</body>

</html>
