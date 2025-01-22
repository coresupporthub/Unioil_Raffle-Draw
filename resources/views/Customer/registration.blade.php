@include('Customer.components.head', ['title' => 'UniOil Raffle Draw'])
<style nonce="{{ csp_nonce() }}">
    .padding{
        padding: 0.5rem;
    }
    .text-align{
        text-align: center;
    }

    .sizeHeight{
        width: 70%;
        height: auto;
        max-height: 200px;
    }

    .codeClass{
        font-size: 1rem;
        font-weight: 700;
        color: #4CAF50;
        background-color: #e8f5e9;
        padding: 5px 10px;
        border-radius: 5px;
    }
    .codeHeader{
        font-size: 1.2rem;
        font-weight: 500;
        color: #ff5722;
    }

    .hidden{
        display: none;
    }

    .privacyPolicy{
        max-height: 500px;
        overflow-y: auto;
        background-image: url('/unioil_images/successbg.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }

    .agreeBtn{
        background-color: #005f03;
        border-radius: 10px
    }
</style>
<body class="padding">
    <script src="{{ asset('./dist/js/demo-theme.min.js?1692870487') }}"></script>
    @include('Admin.components.loader')
    <div class="page">

        <div class="page-wrapper">
            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl d-flex flex-column justify-content-center">
                    <div class="row w-100 justify-content-center align-items-center text-align">
                        <img src="/unioil_images/unioil_bg.png" alt="unioil logo" class="sizeHeight" class="mb-4">
                    </div>

                    <div class="row w-100 justify-content-center align-items-center">
                        <h1 class="col-12 text-center codeHeader" >Raffle Entry Code:</h1>
                        <h1 class="col-6 text-center codeClass">
                            {{ $code }}
                        </h1>
                    </div>

                </div>
                <div class="container m-auto p-3 md:m-5 md:p-7">
                    <form class="row g-3 needs-validation" id="registrationForm" novalidate>
                        @csrf

                        <input type="hidden" name="qr_code" value="{{ $code }}">
                        <input type="hidden" name="unique_identifier" value="{{ $uuid }}">

                        <div class="col-md-6">
                            <label for="validationCustom01" class="form-label">FULL NAME <span class="text-danger">*</span> </label>
                            <input type="text" name="fullname" placeholder="Full Name Indicated on Valid ID" class="form-control" id="validationCustom01" value="" required>
                            <span>
                                <small class="text-warning">
                                    The name on the raffle entry must match the valid ID presented when claiming the prize.
                                </small>
                            </span>
                                                        <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="validationCustom02" class="form-label">AGE <span class="text-danger">*</span></label>
                            <input type="text" name="age" class="form-control" id="validationCustom02" value="" maxlength="3" placeholder="Enter Age" required />
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>

                        {{-- ADDRESS --}}
                        <div class="col-md-3">
                            <input type="hidden" name="region" id="regionId">
                            <label for="region" class="form-label">REGION <span class="text-danger">*</span></label>
                            <select class="form-control select2" id="region" required>
                                <option value="" disabled selected>Select a Region</option>
                            </select>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>



                        <div class="col-md-3">
                            <input type="hidden" name="province" id="provinceId">
                            <label for="province" id="provinceLable" class="form-label">PROVINCE <span class="text-danger">*</span></label>
                            <select class="form-control select2" id="province" required>
                                <option value="">Select a Province</option>

                            </select>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>

                        <div class="col-md-3">
                            <input type="hidden" name="city" id="cityId">
                            <label for="city" class="form-label">CITY <span class="text-danger">*</span></label>
                            <select class="form-control select2" id="city" required>
                                <option value="">Select a City</option>

                            </select>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>

                        <div class="col-md-3">
                            <input type="hidden" name="baranggay" id="baranggayId">
                            <label for="barangay" class="form-label">BARANGAY <span class="text-danger">*</span></label>
                            <select class="form-control select2" id="baranggay" required>
                                <option value="">Select a Barangay</option>

                            </select>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>

                        <div class="col-md-3">

                            <label for="validationCustom02" class="form-label">STREET ADDRESS <span class="text-danger">*</span></label>
                            <input type="text" name="street" class="form-control" id="validationCustom02" value="" placeholder="Enter Street Address">
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                        {{-- ADDRESS --}}

                        <div class="col-md-6">
                            <label for="validationCustomUsername" class="form-label">MOBILE NUMBER <span class="text-danger">*</span></label>
                            <div class="input-group has-validation">
                                <span class="input-group-text" id="inputGroupPrepend">+63</span>
                                <input name="mobile_number" type="text" class="form-control" id="validationCustomUsername" maxlength="10" aria-describedby="inputGroupPrepend" required  placeholder="Enter your phone number">

                                <div class="invalid-feedback hidden" id="phoneError" >Please enter a
                                    valid phone
                                    number starting with 9.</div>

                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="validationCustomUsername" class="form-label">EMAIL ADDRESS <span class="text-secondary"> (optional) </span></label>
                            <div class="input-group has-validation">
                                <span class="input-group-text" id="inputGroupPrepend">@</span>
                                <input name="email_address" type="text" class="form-control" id="validationCustomUsername" aria-describedby="inputGroupPrepend">
                                <div class="invalid-feedback">
                                    Please enter email.
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="validationCustom04" class="form-label">PRODUCT PURCHASED ({{ $product_type }}) <span class="text-danger">*</span></label>
                            <select type="text" name="product" class="form-select" id="validationCustom02" required>
                                <option value="" selected disabled>Select Product</option>
                                @foreach ($products as $product)
                                <option value="{{ $product->product_id }}">{{ $product->product_name }}
                                    ({{ $product->product_type }})</option>
                                @endforeach
                            </select>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">STORE ALPHANUMERIC CODE <span class="text-danger">*</span></label>
                            <div class="row g-2">
                                <div class="col">
                                    <input type="text" name="store_code" id="store_code" class="form-control" placeholder="" required>
                                </div>

                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="privacyConsent" required>
                                <label class="form-check-label" for="privacyConsent">
                                    I have read and agree to the
                                    <a href="" data-bs-toggle="modal" data-bs-target="#privacypolicy">Privacy Policy.</a>
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
    </div>
    @include('Customer.components.footer')
    </div>
    </div>

    {{-- Modals --}}
    <div class="modal" tabindex="-1" id="privacypolicy" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body privacyPolicy">
                    <div class="container">
                        <div class="col-12">
                            <h1 class="text-center mb-4">Privacy Policy</h1>

                            <p class="lead">Unioil Petroleum Philippines, Inc. (“Unioil”) respects your right to privacy. As part of our "Grand Raffle Promo" running from December 1, 2024, to January 31, 2025, we may collect, use, and process your Personal Data in accordance with the Philippines’ Data Privacy Act, its Implementing Rules and Regulations, and other relevant government regulations. This Privacy Notice outlines our data privacy principles and practices, and we recommend you read it to understand how we manage your Personal Data.</p>

                            <h3 class="mt-4">Consent</h3>
                            <p>By participating in our "Grand Raffle Promo" and providing us your Personal Data, you consent to our collection, use, and processing of your information as outlined in this Privacy Notice. If you do not agree with the terms, please refrain from participating in the promo or contact us for any privacy-related concerns.</p>

                            <p>We may modify this Privacy Notice at any time. Please review this Privacy Notice regularly to stay updated on how we handle your Personal Data.</p>

                            <h3 class="mt-4">Why does UNIOIL collect and process your Personal Data?</h3>
                            <p>We collect and process your Personal Data to facilitate your participation in the "Grand Raffle Promo" and for the following purposes:</p>
                            <ul>
                                <li>To verify your eligibility for the promo;</li>
                                <li>To notify and contact you if you win;</li>
                                <li>To respond to any promo-related inquiries;</li>
                                <li>To comply with legal and regulatory requirements related to raffles and promotions.</li>
                            </ul>

                            <h3 class="mt-4">What Personal Data does UNIOIL collect?</h3>
                            <p>For the "Grand Raffle Promo," we may collect the following Personal Data:</p>
                            <ul>
                                <li>Your name;</li>
                                <li>Your contact details (e.g., phone number and email address);</li>
                                <li>Your address;</li>
                                <li>Other information required for promo registration and verification.</li>
                            </ul>

                            <h3 class="mt-4">How does UNIOIL collect my Personal Data?</h3>
                            <p>Your Personal Data may be collected through the following means:</p>
                            <ul>
                                <li>When you register for the promo via our official promo channels (e.g., website);</li>
                                <li>When you provide your information during prize claiming;</li>
                                <li>When you contact us for promo-related queries or concerns.</li>
                            </ul>

                            <h3 class="mt-4">Where does UNIOIL store your Personal Data?</h3>
                            <p>Your Personal Data is controlled by Unioil Petroleum Philippines, Inc., a company registered under Philippine laws, whose principal office is at 38th flr., Exquadra Tower, 1 Jade Dr., Ortigas Center, Pasig City, Metro Manila, Philippines. We implement appropriate technical and organizational measures to protect your Personal Data against unauthorized access, loss, or misuse.</p>

                            <h3 class="mt-4">Retention of Personal Data</h3>
                            <p>We will retain your Personal Data only for as long as necessary to fulfill the purposes outlined in this Privacy Notice or to comply with legal and regulatory obligations. Afterward, your Personal Data will be securely deleted or anonymized.</p>

                            <h3 class="mt-4">Contact Us</h3>
                            <p>If you have any questions or concerns about this Privacy Notice or your Personal Data, you may contact us at:</p>
                            <p>
                                <strong>Email:</strong> dpo@unioil.com<br>
                                <strong>Address:</strong> 38th flr., Exquadra Tower, 1 Jade Dr., Ortigas Center, Pasig City, Metro Manila, Philippines
                            </p>
                        </div>

                    </div>
                    <button class="btn col-12 text-light agreeBtn" id="agreeButton" data-bs-dismiss="modal">I Agree to the Data Privacy Policy</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="confirmStore" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-success"></div>
                <div class="modal-body text-center py-4">
                    <!-- Download SVG icon from http://tabler-icons.io/i/circle-check -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-green icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                        <path d="M9 12l2 2l4 -4" /></svg>
                    <h3>Please Confirm Retail Store Code</h3>

                    <h4>Retail Station: <span id="retailStationConfirm"></span></h4>
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
    {{-- Modals --}}

    @include('Customer.components.scripts')

    <script src="/js/registration.js"></script>

    <script src="/js/misc/registration.js"></script>

</body>

</html>
