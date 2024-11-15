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
                                        <input type="text" name="store_code" class="form-control" placeholder="">
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

    @include('Customer.components.scripts')

    <script src="/js/registration.js"></script>

</body>

</html>
