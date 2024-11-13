@include('Admin.components.head', ['title' => 'Retail Outlet Management'])

<body>
    <script src="{{ asset('./dist/js/demo-theme.min.js?1692870487') }}"></script>
    <div class="page">

        @include('Admin.components.header', ['active' => 'retailoutlets'])

        <div class="page-wrapper">
            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl d-flex flex-column justify-content-center">

                    <div class="page-body">
                        <div class="container-xl">

                            <div class="card">
                                <div class="card-body">
                                    <div id="table-default" class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th><button class="table-sort" data-sort="sort-name">Name</button>
                                                    </th>
                                                    <th><button class="table-sort" data-sort="sort-city">City</button>
                                                    </th>
                                                    <th><button class="table-sort" data-sort="sort-type">Type</button>
                                                    </th>
                                                    <th><button class="table-sort" data-sort="sort-score">Score</button>
                                                    </th>
                                                    <th><button class="table-sort" data-sort="sort-date">Date</button>
                                                    </th>
                                                    <th><button class="table-sort"
                                                            data-sort="sort-quantity">Quantity</button></th>
                                                    <th><button class="table-sort"
                                                            data-sort="sort-progress">Progress</button></th>
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
                                                                        role="progressbar" aria-valuenow="30"
                                                                        aria-valuemin="0" aria-valuemax="100"
                                                                        aria-label="30% Complete">
                                                                        <span class="visually-hidden">30%
                                                                            Complete</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="sort-name">Wicked Cyclone</td>
                                                    <td class="sort-city">Six Flags New England, United States</td>
                                                    <td class="sort-type">RMC Hybrid</td>
                                                    <td class="sort-score">98,2%</td>
                                                    <td class="sort-date" data-date="1568819813">September 18, 2019</td>
                                                    <td class="sort-quantity">174</td>
                                                    <td class="sort-progress" data-progress="3">
                                                        <div class="row align-items-center">
                                                            <div class="col-12 col-lg-auto">3%</div>
                                                            <div class="col">
                                                                <div class="progress" style="width: 5rem">
                                                                    <div class="progress-bar" style="width: 3%"
                                                                        role="progressbar" aria-valuenow="3"
                                                                        aria-valuemin="0" aria-valuemax="100"
                                                                        aria-label="3% Complete">
                                                                        <span class="visually-hidden">3% Complete</span>
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

                </div>
            </div>

            @include('Admin.components.footer')

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const list = new List('table-default', {
                sortClass: 'table-sort',
                listClass: 'table-tbody',
                valueNames: ['sort-name', 'sort-type', 'sort-city', 'sort-score',
                    {
                        attr: 'data-date',
                        name: 'sort-date'
                    },
                    {
                        attr: 'data-progress',
                        name: 'sort-progress'
                    },
                    'sort-quantity'
                ]
            });
        })
    </script>

    @include('Admin.components.scripts')

</body>

</html>
