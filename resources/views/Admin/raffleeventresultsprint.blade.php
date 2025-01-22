@include('Admin.components.head', ['title' => 'Raffle Event Results'])
<style  nonce="{{ csp_nonce() }}">
     @media print {
            .no-print {
                display: none;
            }
        }
        .fontDesigns{
            color: #f75a04; padding-left: 20px; font-family: 'Poppins', sans-serif;
        }

        .maxHeight{
            max-height: 100px; width: auto;
        }

        #title_event_name{
            margin-right: 10px; padding-left: 10px;
        }

        .bg-red{
            background-color: #f75a04;
        }
</style>
<body>
    <script src="{{ asset('./dist/js/demo-theme.min.js?1692870487') }}"></script>
    <div class="page">


        <div class="page-wrapper">

            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl d-flex flex-column justify-content-center">

                    <div class="page-body">
                        <div class="container-xl">

                            <!-- Raffle Draw Details -->
                            <div class="text-center mb-5">

                                <h1 class="fw-bold display-6 position-relative fontDesigns">

                                    <div class="header mb-4">
                                        <img src="/unioil_images/unioil_logo.png" class="maxHeight" alt="Unioil Logo">
                                    </div>

                                    <span
                                       id="title_event_name">
                                    </span>
                                </h1>
                                <p class="text-secondary fs-5">Start Date: <strong id="title_start"> </strong> - End Date: <strong id="title_end"> </strong></p>
                            </div>

                            <!-- Winners Table -->
                            <div class="card shadow mb-4">
                                <div class="card-header text-white d-flex justify-content-center align-items-center bg-red"
                                    >
                                    <h2 class="mb-0">🎉 Winners List 🎉</h2>
                                </div>


                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>Coupon</th>
                                                    <th>Cluster</th>
                                                    <th>Prize</th>
                                                    <th>Winner Name</th>
                                                    <th>Email</th>
                                                </tr>
                                            </thead>
                                            <tbody id="winnerListTable">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow">
                                <div class="card-header text-white d-flex justify-content-center align-items-center bg-red">
                                    <h2 class="mb-0">Redrawn Winners (Unconfirmed Prizes)</h2>
                                </div>


                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>Coupon</th>
                                                    <th>Cluster</th>
                                                    <th>Prize</th>
                                                    <th>Winner Name</th>
                                                    <th>Email</th>
                                                </tr>
                                            </thead>
                                            <tbody id="unclaim-table">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Back to Raffles Button -->
                            <div class="text-center mt-5">
                                <button type="buttom" id="triggerPrint" class="btn btn-primary no-print">Print</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>





        @include('Admin.components.loader')
    </div>
    </div>
    @include('Admin.components.scripts', ['loc'=> 'admin'])
    <script src="{{asset('js/raffleresult/raffleresultlistprint.js')}}"></script>
</body>

</html>
