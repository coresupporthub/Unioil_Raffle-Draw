@include('Customer.components.head', ['title' => 'UniOil Raffle Draw'])

<body>
    <script src="{{ asset('./dist/js/demo-theme.min.js?1692870487') }}"></script>
    <div class="page">
        <div class="page-wrapper">
            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl d-flex flex-column justify-content-center">
                    <div class="d-flex justify-content-center align-items-center w-100 flex-column gap-1">
                        <img src="/unioil_images/unioil_logo.png" alt="unioil logo" class="mb-4">
                        <h1>Raffle Entry</h1>
                        <p>Code: {{ $code }}</p>
                    </div>
                    <div class="container m-0 p-3 md:m-5 md:p-7">
                        <form class="row g-3 needs-validation" novalidate>
                            <div class="col-md-6">
                                <label for="validationCustom01" class="form-label">FULL NAME</label>
                                <input type="text" placeholder="Full Name Indicated on Valid ID" class="form-control"
                                    id="validationCustom01" value="" required
                                    oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '');">

                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="validationCustom02" class="form-label">AGE</label>
                                <input type="text" class="form-control" id="validationCustom02" value=""
                                maxlength="3" placeholder="Enter Age" required
                                oninput="this.value = this.value.replace(/[^0-9]/g, '');" />
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>

                            {{-- ADDRESS --}}
                            <div class="col-md-3">
                                <label for="region" class="form-label">REGION</label>
                                <select class="form-control select2" id="region" required>
                                    <option value="">Select Region</option>
                                </select>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>



                            <div class="col-md-3">
                                <label for="province" class="form-label">PROVINCE</label>
                                <select class="form-control select2" id="province" required>
                                    <option value="">Select Province</option>
                                    <!-- Add your city options here -->
                                </select>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="city" class="form-label">CITY</label>
                                <select class="form-control select2" id="city" required>
                                    <option value="">Select City</option>
                                    <!-- Add your city options here -->
                                </select>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="barangay" class="form-label">BARANGAY</label>
                                <select class="form-control select2" id="baranggay" required>
                                    <option value="">Select Barangay</option>
                                    <!-- Add your barangay options here -->
                                </select>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="validationCustom02" class="form-label">STREET ADDRESS</label>
                                <input type="text" class="form-control" id="validationCustom02" value=""
                                    placeholder="Enter Street Address" required>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>
                            {{-- ADDRESS --}}

                            <div class="col-md-6">
                                <label for="validationCustomUsername" class="form-label">MOBILE NUMBER</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text" id="inputGroupPrepend">+63</span>
                                    <input type="text" class="form-control" id="validationCustomUsername"
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
                                    <input type="text" class="form-control" id="validationCustomUsername"
                                        aria-describedby="inputGroupPrepend" required>
                                    <div class="invalid-feedback">
                                        Please enter email.
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="validationCustom04" class="form-label">PRODUCT PURCHASED</label>
                                <input type="text" class="form-control" id="validationCustom02"
                                    value="{{ $code }}" placeholder="Enter Street Address" required>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">STORE ALPHANUMERIC CODE</label>
                                <div class="row g-2">
                                    <div class="col">
                                        <input type="text" class="form-control" placeholder="">
                                    </div>

                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="invalidCheck"
                                        required>
                                    <label class="form-check-label" for="invalidCheck">
                                        Agree to terms and conditions
                                    </label>
                                    <div class="invalid-feedback">
                                        You must agree before submitting.
                                    </div>
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
