@include('Customer.components.head', ['title' => 'Raffle Entry Coupon'])

<style nonce="{{ csp_nonce() }}">
    .btn_cont a {
        display: inline-block;
        margin: 10px;
    }

    @media only screen and (min-width: 768px) {
        .btn_cont a.whatsapp {
            display: none !important;
        }
    }

body{
    font-family: 'Open Sans', sans-serif;
    text-align:center; padding: 1rem;
    background-image: url('/unioil_images/successbg.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    background-color: #f1f3f4;
}

.border-outline{
    border:0; outline:0;
}

.imgClass{
    width: 100vh;
    height: auto;
    max-height: 200px;
}

.font-18{
    font-size: 18px;
}

.customerDetails{
    background-color: #ffc38b;
    margin: 10px auto;
    padding: 20px 0;
    border-radius: 10px;
    max-width: 800px;
}

.customerName{
    padding: 3%;
    font-size: 1.5rem;
    margin-top: -5px;
}

.serialCode{
    font-weight: 800; font-size: 1.5rem; margin-top: 10px; color: #048500;
}


.entryDetails{
    font-size: 1rem; color: #555;
}

.grandPrice{
    font-weight: bold; font-size:2rem
}

.promoDuration{
    font-size:1rem
}

.disclaimer{
    margin-top: 2rem; color: #ccc
}

</style>
<body>
    <a href="unioil.com" class="border-outline">

        <div class="row w-100 justify-content-center align-items-center text-center">
            <img src="data:image/png;base64,{{ $banner }}" alt="unioil logo"  class="mb-4 imgClass">
        </div>
    </a>
    <h2>Great! Your entry has been saved for the raffle draw</h2>
    <div class="font-18">If you provided an email, we have sent you the serial numbers of your raffle coupons. <br>
        If not, please ensure you secure the serial numbers for reference and ownership.</div>
    <p class="m-3">We recommend taking a screenshot of your entry coupon serial numbers for reference.</p>

    <div class="customerDetails">

        <div class="customerName"> <strong> {{ $customers->full_name }}</strong></div>
        @if($entries == 1)
        <div class="serialCode">
            {{ $code->serial_number }}
        </div>
        <div class="entryDetails">Raffle Entry Details</div>
        @else
        @foreach ($code as $c)
        <div class="serialCode">
            {{ $c->serial_number }}
        </div>
        <div class="entryDetails">Raffle Entry Details</div>
        @endforeach
        @endif
    </div>

    <div class="grandPrice">Grand Prize: {{ $prize }}</div>
    <div class="promoDuration"> <strong>Promo Duration: <br> </strong> {{ $duration }}</div>

    <img src="data:image/png;base64,{{ $prize_image }}" alt="hondaclick">

    @if($disclaimer)
        <p class="disclaimer">Disclaimer: {{ $disclaimer }}</p>
    @endif

    @include('Customer.components.scripts')
</body>
</html>
