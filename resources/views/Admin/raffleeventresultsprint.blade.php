@include('Admin.components.head', ['title' => 'Raffle Event Results'])
<style>
     @media print {
            .no-print {
                display: none;
            }
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

                                <h1 class="fw-bold display-6 position-relative"
                                    style="color: #f75a04; padding-left: 20px; font-family: 'Poppins', sans-serif;">
                                    <span
                                        style=" margin-right: 10px; padding-left: 10px;" id="title_event_name">

                                    </span>
                                </h1>
                                <p class="text-secondary fs-5">Start Date: <strong id="title_start"> </strong> - End Date: <strong id="title_end"> </strong></p>
                            </div>

                            <!-- Winners Table -->
                            <div class="card shadow">
                                <div class="card-header text-white d-flex justify-content-center align-items-center"
                                    style="background-color: #f75a04;">
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

                            <!-- Back to Raffles Button -->
                            <div class="text-center mt-5">
                                <button type="buttom" class="btn btn-primary no-print" onclick="triggerPrint()">Print</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>





        @include('Admin.components.loader')
    </div>
    </div>

    @include('Admin.components.scripts')
    <script src="{{asset('js/raffleresult/raffleresultlistprint.js')}}"></script>
</body>

</html>
