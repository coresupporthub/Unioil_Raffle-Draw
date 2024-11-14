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
                                    maxlength="3" placeholder="Enter Age" required>
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
                                    <div data-bs-toggle="modal" data-bs-target="#openQrScanner" class="col-auto">
                                        <a href="" data-bs-toggle="tooltip"
                                            onclick="event.preventDefault(); event.stopPropagation();"
                                            data-bs-placement="top" aria-label="qrcode"
                                            data-bs-original-title="Scan QR" class="btn btn-icon"
                                            aria-label="Button">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-qrcode">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M4 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                                <path d="M7 17l0 .01" />
                                                <path
                                                    d="M14 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                                <path d="M7 7l0 .01" />
                                                <path
                                                    d="M4 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                                <path d="M17 7l0 .01" />
                                                <path d="M14 14l3 0" />
                                                <path d="M20 14l0 .01" />
                                                <path d="M14 14l0 3" />
                                                <path d="M14 20l3 0" />
                                                <path d="M17 17l3 0" />
                                                <path d="M20 17l0 3" />
                                            </svg>
                                        </a>
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

    <div class="modal modal-blur fade" id="openQrScanner" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Scan Retaile Store QR-Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="reader" width="600px"></div>
                </div>
            </div>
        </div>
    </div>

    @include('Customer.components.scripts')

    <script>
        (function() {
            'use strict';
            window.addEventListener('load', function() {

                var forms = document.getElementsByClassName('needs-validation');

                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();

        function validatePhoneNumber(input) {
            const phoneNumber = input.value;
            const phoneError = document.getElementById('phoneError');

            const isValid = /^9\d{9}$/.test(phoneNumber);

            if (!isValid) {
                phoneError.style.display = 'block';
            } else {
                phoneError.style.display = 'none';
            }
        }

        const addressData = {
            "Region I - Ilocos Region": {
                "Ilocos Norte": {
                    "Laoag City": ["Barangay 1 San Lorenzo", "Barangay 2 San Pablo"],
                    "Batac City": ["Barangay 1", "Barangay 2"]
                },
                "Ilocos Sur": {
                    "Vigan City": ["Barangay 1", "Barangay 2"],
                    "Candon City": ["Barangay A", "Barangay B"]
                },
                "La Union": {
                    "San Fernando City": ["Barangay Tanqui", "Barangay Pagdalagan"],
                    "Agoo": ["Barangay Consolacion", "Barangay San Manuel Norte"]
                },
                "Pangasinan": {
                    "Dagupan City": ["Barangay Bonuan Boquig", "Barangay Calmay"],
                    "Alaminos City": ["Barangay Poblacion", "Barangay Baley-a-Daan"]
                }
            },
            "Region II - Cagayan Valley": {
                "Batanes": {
                    "Basco": ["Kaychanarianan", "Chanarian"],
                    "Mahatao": ["Barangay 1", "Barangay 2"]
                },
                "Cagayan": {
                    "Tuguegarao City": ["Barangay Bagay", "Barangay Annafunan East"],
                    "Aparri": ["Barangay Punta", "Barangay Maura"]
                },
                "Isabela": {
                    "Ilagan City": ["Barangay Calamagui", "Barangay San Vicente"],
                    "Cauayan City": ["Barangay District 1", "Barangay District 2"]
                },
                "Nueva Vizcaya": {
                    "Bayombong": ["Barangay Don Mariano Perez", "Barangay Salvacion"],
                    "Solano": ["Barangay Poblacion North", "Barangay Poblacion South"]
                },
                "Quirino": {
                    "Cabarroguis": ["Barangay Burgos", "Barangay Zamora"],
                    "Maddela": ["Barangay San Pedro", "Barangay San Salvador"]
                }
            },
            "NCR - National Capital Region": {
                "Manila": {
                    "Tondo": ["Barangay 1", "Barangay 2"],
                    "Malate": ["Barangay 701", "Barangay 702"]
                },
                "Quezon City": {
                    "Novaliches": ["Barangay Gulod", "Barangay San Bartolome"],
                    "Diliman": ["Barangay Central", "Barangay UP Campus"]
                },
                "Makati": {
                    "Poblacion": ["Barangay Poblacion"],
                    "Bel-Air": ["Barangay Bel-Air"]
                },
                "Pasig": {
                    "Ortigas Center": ["Barangay San Antonio"],
                    "Rosario": ["Barangay Rosario"]
                }
            },
            "Region IV-A - CALABARZON": {
                "Cavite": {
                    "Bacoor City": ["Barangay Molino III", "Barangay Queens Row East"],
                    "Dasmariñas City": ["Barangay Zone I", "Barangay Zone II"]
                },
                "Laguna": {
                    "Calamba City": ["Barangay Real", "Barangay Canlubang"],
                    "Santa Rosa City": ["Barangay Balibago", "Barangay Pulong Santa Cruz"]
                },
                "Batangas": {
                    "Batangas City": ["Barangay Pallocan West", "Barangay Santa Rita Karsada"],
                    "Lipa City": ["Barangay Balintawak", "Barangay Tambo"]
                },
                "Rizal": {
                    "Antipolo City": ["Barangay Cupang", "Barangay San Roque"],
                    "Cainta": ["Barangay San Andres", "Barangay Santo Domingo"]
                },
                "Quezon": {
                    "Lucena City": ["Barangay Dalahican", "Barangay Ilayang Dupay"],
                    "Tayabas City": ["Barangay Angustias Zone I", "Barangay Ipilan"]
                }
            }
        };


        document.addEventListener("DOMContentLoaded", function() {
            const regionSelect = document.getElementById("region");
            const provinceSelect = document.getElementById("province");
            const citySelect = document.getElementById("city");
            const barangaySelect = document.getElementById("baranggay");

            for (const region in addressData) {
                const option = new Option(region, region);
                regionSelect.add(option);
            }

            regionSelect.addEventListener("change", function() {
                provinceSelect.length = 1;
                citySelect.length = 1;
                barangaySelect.length = 1;

                const selectedRegion = regionSelect.value;
                if (selectedRegion && addressData[selectedRegion]) {
                    for (const province in addressData[selectedRegion]) {
                        const option = new Option(province, province);
                        provinceSelect.add(option);
                    }
                }
            });

            provinceSelect.addEventListener("change", function() {
                citySelect.length = 1;
                barangaySelect.length = 1;

                const selectedRegion = regionSelect.value;
                const selectedProvince = provinceSelect.value;
                if (selectedProvince && addressData[selectedRegion][selectedProvince]) {
                    for (const city in addressData[selectedRegion][selectedProvince]) {
                        const option = new Option(city, city);
                        citySelect.add(option);
                    }
                }
            });

            citySelect.addEventListener("change", function() {
                barangaySelect.length = 1;

                const selectedRegion = regionSelect.value;
                const selectedProvince = provinceSelect.value;
                const selectedCity = citySelect.value;
                if (selectedCity && addressData[selectedRegion][selectedProvince][selectedCity]) {
                    addressData[selectedRegion][selectedProvince][selectedCity].forEach(function(barangay) {
                        const option = new Option(barangay, barangay);
                        barangaySelect.add(option);
                    });
                }
            });
        });
    </script>

</body>

</html>
