@include('Admin.components.head', ['title' => 'QR Generator'])

<body>
    <script src="{{ asset('./dist/js/demo-theme.min.js?1692870487') }}"></script>
    <div class="page">

        @include('Admin.components.header')

        <div class="page-wrapper">
            <div class="page-body">
                <div class="container-xl">
                    <div class="card">
                        <div class="card-body">
                            <div id="table-default" class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><button class="table-sort" data-sort="sort-name">Name</button></th>
                                            <th><button class="table-sort" data-sort="sort-city">City</button></th>
                                            <th><button class="table-sort" data-sort="sort-type">Type</button></th>
                                            <th><button class="table-sort" data-sort="sort-score">Score</button></th>
                                            <th><button class="table-sort" data-sort="sort-date">Date</button></th>
                                            <th><button class="table-sort" data-sort="sort-quantity">Quantity</button>
                                            </th>
                                            <th><button class="table-sort" data-sort="sort-progress">Progress</button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-tbody">
                                        <tr>
                                            <td class="sort-name">Steel Vengeance</td>
                                            <td class="sort-city">Cedar Point, United States</td>
                                            <td class="sort-type">RMC Hybrid</td>
                                            <td class="sort-score">100,0%</td>
                                            <td class="sort-date" data-date="1628071164">August 04, 2021</td>
                                            <td class="sort-quantity">74</td>
                                            <td class="sort-progress" data-progress="30">
                                                <div class="row align-items-center">
                                                    <div class="col-12 col-lg-auto">30%</div>
                                                    <div class="col">
                                                        <div class="progress" style="width: 5rem">
                                                            <div class="progress-bar" style="width: 30%"
                                                                role="progressbar" aria-valuenow="30" aria-valuemin="0"
                                                                aria-valuemax="100" aria-label="30% Complete">
                                                                <span class="visually-hidden">30% Complete</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="sort-name">Fury 325</td>
                                            <td class="sort-city">Carowinds, United States</td>
                                            <td class="sort-type">B&M Giga, Hyper, Steel</td>
                                            <td class="sort-score">99,3%</td>
                                            <td class="sort-date" data-date="1546512137">January 03, 2019</td>
                                            <td class="sort-quantity">49</td>
                                            <td class="sort-progress" data-progress="48">
                                                <div class="row align-items-center">
                                                    <div class="col-12 col-lg-auto">48%</div>
                                                    <div class="col">
                                                        <div class="progress" style="width: 5rem">
                                                            <div class="progress-bar" style="width: 48%"
                                                                role="progressbar" aria-valuenow="48" aria-valuemin="0"
                                                                aria-valuemax="100" aria-label="48% Complete">
                                                                <span class="visually-hidden">48% Complete</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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
