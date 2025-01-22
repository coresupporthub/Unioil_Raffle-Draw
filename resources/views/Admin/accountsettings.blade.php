@include('Admin.components.head', ['title' => 'Account Settings'])
@php
    $user = auth()->user()->user_type;
@endphp
<style nonce="{{ csp_nonce() }}">
    .list-group-item.active {
        background-image: linear-gradient(45deg, #F75A04, #ffffff) !important;
        color: white !important;
    }

.card-prop{
    max-height: 500px; overflow-y: auto;
}

.action{
    width: 10%;
}

.hidden{
    display: none;
}
</style>

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
                                                <!-- Account Settings Tab (Active by default) -->
                                                <a href="#accountContent" class="list-group-item list-group-item-action d-flex align-items-center rounded-pill active" id="accountTab" data-bs-toggle="list" role="tab" aria-controls="accountContent" aria-selected="true">
                                                    Account Settings
                                                </a>

                                                <!-- Administrators Tab -->
                                                <a href="#administratorTab" class="list-group-item list-group-item-action d-flex align-items-center rounded-pill" id="administratorNav" data-bs-toggle="list" role="tab" aria-controls="Administrator nav" aria-selected="false">
                                                    Administrators
                                                </a>
                                                @if ($user == 'Super Admin')
                                                <a href="#backupTab" id="backuptab" class="list-group-item list-group-item-action d-flex align-items-center rounded-pill" id="backupNav" data-bs-toggle="list" role="tab" aria-controls="Backup navigation" aria-selected="false">
                                                   Back up
                                                </a>
                                                @endif
                                                <!-- Logout Button -->
                                                <button id="adminLogoutBtn" class="list-group-item list-group-item-action d-flex align-items-center">
                                                    Logout
                                                </button>
                                            </div>

                                        </div>
                                    </div>

                                    <!-- Main Content Area -->
                                    <div class="col-12 col-md-9 d-flex flex-column">
                                        <div class="tab-content">
                                            <!-- Account Content (Active by default) -->
                                            <div class="tab-pane fade show active" id="accountContent" role="tabpanel" aria-labelledby="accountTab">
                                                <div class="card-body card-prop" >
                                                    <h2 class="mb-4">My Account @if($user == 'Super Admin') (Super Admin) @endif</h2>
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
                                                    <div class="w-100 d-flex justify-content-between">
                                                        <a href="" data-bs-toggle="modal" data-bs-target="#changepasswordModal" class="btn">Set new password</a>
                                                        @if ($user == 'Super Admin')
                                                        <a href="" id="transferAdminBtn" data-bs-toggle="modal" data-bs-target="#transfer-status" class="btn btn-success">Transfer Super Admin Status</a>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="card-footer bg-transparent mt-auto">
                                                    <div class="btn-list justify-content-end">
                                                        <button id="adminDetailsBtn" class="btn btn-primary">Submit</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="administratorTab" role="tabpanel" aria-labelledby="administrator Tab">
                                                @if ($user == 'Super Admin')
                                                <div class="card-body">
                                                    <h2 class="mb-4">Administrators</h2>
                                                    <div class="row">
                                                        <div class="mb-3 col-5" id="adminNameDiv">
                                                            <label class="form-label required">Name <span class="text-danger d-none" id="m_name_e">(This Field is Required)</span></label>
                                                            <input type="text" class="form-control" id="m_name" placeholder="Enter Name">
                                                        </div>
                                                        <div class="mb-3 col-5" id="adminEmailDiv">
                                                            <label class="form-label required">Email <span class="text-danger d-none" id="m_email_e">(This Field is Required)</span></label>
                                                            <input type="text" class="form-control" id="m_email" placeholder="Enter email">
                                                        </div>

                                                        <div class="mb-3 col-10 d-none" id="newPassDiv">
                                                            <label class="form-label required">Type New Password <span class="text-danger d-none" id="m_newpass_e">(This Field is Required)</span></label>
                                                            <input type="text" class="form-control" id="m_newpass" placeholder="Enter new password">
                                                        </div>

                                                        <div class="mb-3 col-2" id="saveAdminDiv">
                                                            <label class="form-label text-white"> . </label>
                                                            <button id="addAdmin" class="btn btn-primary"> Submit </button>
                                                        </div>
                                                        <div class="mb-3 col-2 d-none" id="updateAdminDiv">
                                                            <label class="form-label text-white"> . </label>
                                                            <button id="updateAdminBtn" class="btn btn-info"> Update </button>
                                                        </div>
                                                        <div class="mb-3 col-2 d-none" id="changePassAdminDiv">
                                                            <label class="form-label text-white"> . </label>
                                                            <button id="changePassAdminBtn" class="btn btn-info"> Change Pass </button>
                                                        </div>
                                                    </div>
                                                    <div id="admin-list-table" class="table-responsive mt-2">
                                                        <table class="table table-hover" id="admin-list">
                                                            <thead>
                                                                <tr>
                                                                    <th>Name</th>
                                                                    <th>Email</th>
                                                                    <th class="action">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="table-tbody">

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                @else
                                                <div class="card-body d-flex justify-content-center align-items-center flex-column gap-4">
                                                    <img src="/unioil_images/warning.svg" alt="not-admin" class="w-25">
                                                    <h4>Only a Super Admin can access the list</h4>
                                                </div>
                                                @endif

                                            </div>

                                            @if ($user == 'Super Admin')
                                            <div class="tab-pane fade" id="backupTab" role="tabpanel" aria-labelledby="Backup Tab">
                                                <div class="card-body">
                                                    <h2 class="mb-4">Backup Settings</h2>
                                                    <div class="w-100 d-flex justify-content-between align-items-center mb-4">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="true" id="enableAutoBackup" checked>
                                                            <label class="form-check-label" for="enableAutoBackup">
                                                                Enable Automatic Backup
                                                            </label>
                                                        </div>
                                                        <button class="btn btn-primary" id="initiateBackup">Initiate Backup</button>
                                                    </div>

                                                    <div class="card">
                                                        <div class="card-header">
                                                          <h3 class="card-title">Backup List</h3>
                                                        </div>
                                                        <div class="list-group list-group-flush" id="backup-list">

                                                        </div>
                                                      </div>
                                                </div>

                                            </div>
                                            @endif

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
                        <input type="password" required name="currentPassword" class="form-control" id="currentPassword" placeholder="Enter current password">
                        <div id="currentPasswordError" class="text-danger mt-2 hidden">Current
                            password is required.</div>
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">New Password</label>
                        <input type="password" required name="newPassword" class="form-control" id="newPassword" placeholder="Enter new password">
                        <div id="newPasswordError" class="text-danger mt-2 hidden">New password
                            is required.</div>
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm New Password</label>
                        <input type="password" required name="confirmPassword" class="form-control" id="confirmPassword" placeholder="Confirm new password">
                        <div id="confirmPasswordError" class="text-danger mt-2 hidden" >Passwords
                            do not match.</div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" id="closeChangePassword" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" disabled id="changePasswordBtn">Save</button>
                </div>
            </div>
        </div>
    </div>

    @if ($user == 'Super Admin')
    <div class="modal modal-blur fade" id="transfer-status" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Transfer Super Admin Status</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="admin-transfer-table" class="table-responsive mt-2">
                    <table class="table table-hover" id="admin-transfer">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th class="action">Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-tbody">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Save changes</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal modal-blur fade" id="need-password" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
          <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" id="closeConfirm" aria-label="Close"></button>
            <div class="modal-status bg-success"></div>
            <div class="modal-body text-center py-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="icon mb-2 text-green icon-lg"  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-lock">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6z" />
                    <path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" />
                    <path d="M8 11v-4a4 4 0 1 1 8 0v4" />
                  </svg>
              <h3>Authenticate this transfer</h3>
              <div class="text-muted">Please enter your password to proceed</div>
              <input type="password" id="authPassInput" class="form-control mt-2" placeholder="Enter you password">
              <small class="text-danger d-none" id="authPassInputE">You did not enter any password</small>
            </div>
            <div class="modal-footer">
              <div class="w-100">
                <div class="row">
                  <div class="col"><a href="#" class="btn w-100" data-bs-toggle="modal" data-bs-target="#transfer-status">
                      Go back
                    </a></div>
                  <div class="col"><a href="#" id="confirmTransferBtn" class="btn btn-success w-100" >
                     Confirm
                    </a></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif
    {{-- modals --}}

    <script src="/js/account_settings.js"></script>
    @if ($user == 'Super Admin')
    <script src="/js/backup.js"></script>
    @endif
</body>

</html>
