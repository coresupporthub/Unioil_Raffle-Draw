@include('Admin.components.head', ['title' => 'Privacy Policy'])

<body>
    <script src="{{ asset('./dist/js/demo-theme.min.js?1692870487') }}"></script>
    <div class="page">


        <div class="page-wrapper">
            <div class="page-body">
                <div class="container my-5">
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow-sm border-light rounded">
                                <div class="card-body">
                                    <h1 class="card-title text-center mb-4">Privacy Notice</h1>

                                    <p class="lead">We value your privacy and are committed to protecting your personal
                                        information. This Privacy Notice outlines how we collect, use, and safeguard the
                                        data you provide when participating in our program or raffle activities. By
                                        submitting your information, you consent to the practices described herein.</p>

                                    <h3 class="mt-4">Information We Collect</h3>
                                    <p>We collect the following information to facilitate your participation:</p>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5>Personal Information:</h5>
                                            <ul>
                                                <li>Full Name</li>
                                                <li>Mobile Number</li>
                                                <li>Email Address</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h5>Demographic Information:</h5>
                                            <ul>
                                                <li>Age</li>
                                                <li>Address (Region, Province, City, Barangay, Street Address)</li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5>Transactional Information:</h5>
                                            <ul>
                                                <li>Product Purchased</li>
                                                <li>Store Alphanumeric Code</li>
                                            </ul>
                                        </div>
                                    </div>

                                    <h3 class="mt-4">How We Use Your Information</h3>
                                    <p>The information you provide is used for the following purposes:</p>
                                    <ul>
                                        <li>To verify your eligibility for participation.</li>
                                        <li>To notify winners and distribute prizes.</li>
                                        <li>For statistical analysis to improve our services and offerings.</li>
                                    </ul>

                                    <h3 class="mt-4">Data Sharing and Security</h3>
                                    <p><strong>Third-Party Sharing:</strong> We will not share your data with third
                                        parties, except as required by law or for the purpose of prize distribution.</p>
                                    <p><strong>Data Security:</strong> Your information is stored securely using
                                        industry-standard measures to prevent unauthorized access, use, or disclosure.
                                    </p>

                                    <h3 class="mt-4">Retention Period</h3>
                                    <p>We retain your personal data only for as long as necessary to fulfill the
                                        purposes outlined above. Afterward, it will be securely deleted or anonymized.
                                    </p>

                                    <h3 class="mt-4">Your Rights</h3>
                                    <p>You have the right to:</p>
                                    <ul>
                                        <li>Access and request a copy of your data.</li>
                                        <li>Request correction or deletion of your data.</li>
                                        <li>Withdraw your consent at any time.</li>
                                    </ul>
                                    <p>To exercise these rights, please contact us at <a
                                            href="mailto:contact@example.com">contact@example.com</a>.</p>

                                    <h3 class="mt-4">Contact Information</h3>
                                    <p>For questions or concerns about this Privacy Notice or how your data is handled,
                                        please reach out to us at:</p>
                                    <ul>
                                        <li><strong>Company Name:</strong> Your Company</li>
                                        <li><strong>Email Address:</strong> <a
                                                href="mailto:contact@example.com">contact@example.com</a></li>
                                        <li><strong>Phone Number:</strong> (123) 456-7890</li>
                                    </ul>

                                    <!-- Terms and Conditions Agreement -->
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="termsCheckbox" required>
                                        <label class="form-check-label" for="termsCheckbox">
                                            I have read and agree to the Privacy Policy.
                                        </label>
                                    </div>

                                    <!-- Submit Button -->
                                    <button type="submit" class="btn btn-primary mt-3" id="submitBtn"
                                        disabled>Submit</button>
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
    <script>
        document.getElementById('termsCheckbox').addEventListener('change', function() {
            document.getElementById('submitBtn').disabled = !this.checked;
        });
    </script>

    @include('Admin.components.scripts')


</body>

</html>
