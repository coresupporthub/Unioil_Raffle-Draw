@include('Customer.components.head', ['title' => 'UniOil Raffle Draw'])
@php
    $productList = App\Models\ProductList::all();
@endphp

<body>
    <script src="{{ asset('./dist/js/demo-theme.min.js?1692870487') }}"></script>
    @include('Admin.components.loader')
    <div class="page">
        <div class="page-wrapper">
            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl d-flex flex-column justify-content-center">
                    <div class="d-flex justify-content-center align-items-center w-100 flex-column gap-1">
                        <img src="/unioil_images/unioil_logo.png" alt="unioil logo" class="mb-4">
                        <h1>Raffle Entry</h1>
                        <h1>{{ $code }}</h1>
                    </div>
                    <div class="container m-0 p-3 md:m-5 md:p-7">
                        <form class="row g-3 needs-validation" id="registrationForm" novalidate>
                            @csrf

                            <input type="hidden" name="qr_code" value="{{ $code }}">
                            <input type="hidden" name="unique_identifier" value="{{ $uuid }}">

                            <div class="col-md-6">
                                <label for="validationCustom01" class="form-label">FULL NAME</label>
                                <input type="text" name="fullname" placeholder="Full Name Indicated on Valid ID" class="form-control"
                                    id="validationCustom01" value="" required
                                    oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '');">

                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="validationCustom02"  class="form-label">AGE</label>
                                <input type="text" name="age" class="form-control" id="validationCustom02" value=""
                                    maxlength="3" placeholder="Enter Age" required
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '');" />
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>

                            {{-- ADDRESS --}}
                            <div class="col-md-3">
                                <input type="hidden" name="region" id="regionId">
                                <label for="region" class="form-label">REGION</label>
                                <select class="form-control select2" id="region" required>
                                    <option value="" disabled selected>Select a Region</option>
                                </select>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>



                            <div class="col-md-3">
                                <input type="hidden" name="province" id="provinceId">
                                <label for="province" class="form-label">PROVINCE</label>
                                <select class="form-control select2" id="province" required>
                                    <option value="">Select a Province</option>
                                    <!-- Add your city options here -->
                                </select>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>

                            <div class="col-md-3">
                                <input type="hidden" name="city" id="cityId">
                                <label for="city" class="form-label">CITY</label>
                                <select class="form-control select2" id="city" required>
                                    <option value="">Select a City</option>
                                    <!-- Add your city options here -->
                                </select>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>

                            <div class="col-md-3">
                                <input type="hidden" name="baranggay" id="baranggayId">
                                <label for="barangay" class="form-label">BARANGAY</label>
                                <select class="form-control select2" id="baranggay" required>
                                    <option value="">Select a Barangay</option>
                                    <!-- Add your barangay options here -->
                                </select>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>

                            <div class="col-md-3">

                                <label for="validationCustom02" class="form-label">STREET ADDRESS</label>
                                <input type="text" name="street" class="form-control" id="validationCustom02" value=""
                                    placeholder="Enter Street Address" required>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>
                            {{-- ADDRESS --}}

                            <div class="col-md-6">
                                <label for="validationCustomUsername"  class="form-label">MOBILE NUMBER</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text" id="inputGroupPrepend">+63</span>
                                    <input name="mobile_number" type="text" class="form-control" id="validationCustomUsername"
                                        maxlength="10" aria-describedby="inputGroupPrepend" required
                                        oninput="validatePhoneNumber(this)" placeholder="Enter your phone number">

                                    <div class="invalid-feedback" id="phoneError" style="display: none;">Please enter a
                                        valid phone
                                        number starting with 9.</div>

                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="validationCustomUsername" class="form-label">EMAIL ADDRESS</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text" id="inputGroupPrepend">@</span>
                                    <input name="email_address" type="text" class="form-control" id="validationCustomUsername"
                                        aria-describedby="inputGroupPrepend" required>
                                    <div class="invalid-feedback">
                                        Please enter email.
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="validationCustom04" class="form-label">PRODUCT PURCHASED</label>
                                <select type="text" name="product" class="form-select" id="validationCustom02" required>
                                    <option value="" selected disabled>Select Product</option>
                                    @foreach ($productList as $product)
                                        <option value="{{ $product->product_id }}">{{ $product->product_name }}
                                            ({{ $product->product_type }})</option>
                                    @endforeach
                                </select>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">STORE ALPHANUMERIC CODE</label>
                                <div class="row g-2">
                                    <div class="col">
                                        <input type="text" name="store_code" id="store_code" class="form-control" placeholder="">
                                    </div>

                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="privacyConsent" required>
                                    <label class="form-check-label" for="privacyConsent">
                                        I have read and agree to the <a href="/privacy/policy">Privacy Policy. </a>
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary col-12" type="submit">Submit form</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @include('Customer.components.footer')
        </div>
    </div>

    <div class="modal modal-blur fade" id="confirmStore" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
          <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-success"></div>
            <div class="modal-body text-center py-4">
              <!-- Download SVG icon from http://tabler-icons.io/i/circle-check -->
              <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-green icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg>
              <h3>Please Confirm Retail Store Code</h3>

              <h4>Retail Station: <span id="retailStationConfirm"></span></h4>
              <h4>Distributor: <span id="distributorConfirm"></span></h4>
              <h4>RTO CODE: <span id="rtoCodeConfirm"></span></h4>
              <div class="text-muted">If you verify that this is the right store code you may now press confirm to proceed with the registration or press return if not.</div>
            </div>
            <div class="modal-footer">
              <div class="w-100">
                <div class="row">
                  <div class="col"><a href="#" class="btn w-100" data-bs-dismiss="modal">
                      Return
                    </a></div>
                  <div class="col"><button id="confirmRegistrationBtn" class="btn btn-success w-100" data-bs-dismiss="modal">
                      Confirm
                    </button></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    @include('Customer.components.scripts')

    <script src="/js/registration.js"></script>

</body>

</html>
