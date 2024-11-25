@include('Customer.components.head', ['title' => 'Raffle Entry Coupon'])

<style>
    .btn_cont a {
        display: inline-block;
        margin: 10px;
    }

    @media only screen and (min-width: 768px) {
        .btn_cont a.whatsapp {
            display: none !important;
        }
    }

</style>
<body bgcolor="#f1f3f4" style="font-family: 'Open Sans', sans-serif; text-align:center; padding: 1rem; background-image: url('/unioil_images/successbg.jpg'); background-size: cover; background-position: center; background-attachment: fixed;">
    <a href="unioil.com" style="border:0; outline:0;">
        {{-- <img src="/unioil_images/unioil_logo.png" width="151" style="margin:20px auto; display:block; border:0;"> --}}
        <div class="row w-100 justify-content-center align-items-center" style="text-align: center;">
            <img src="/unioil_images/unioil_bg.png" alt="unioil logo" style="width: 100vh; height: auto; max-height: 200px;" class="mb-4">
        </div>
    </a>
    <h2>Great! Your entry has been saved for the raffle draw</h2>
    <div style="font-size: 18px;">If you provided an email, we have sent you the serial numbers of your raffle coupons. <br>
        If not, please ensure you secure the serial numbers for reference and ownership.</div>
    <p class="m-3">We recommend taking a screenshot of your entry coupon serial numbers for reference.</p>

    <div style="background-color: #ffc38b; margin: 10px auto; padding: 20px 0; border-radius: 10px; max-width: 800px;">

        <div style="padding: 3%; font-size: 1.5rem; margin-top: -5px;"> <strong> {{ $customers->full_name }}</strong></div>
        @if($entries == 1)
        <div style="font-weight: 800; font-size: 1.5rem; margin-top: 10px; color: #048500;">
            {{ $code->serial_number }}
        </div>
        <div style="font-size: 1rem; color: #555;">Raffle Entry Details</div>
        @else
        @foreach ($code as $c)
        <div style="font-weight: 800; font-size: 1.5rem; margin-top: 10px; color: #048500;">
            {{ $c->serial_number }}
        </div>
        <div style="font-size: 1rem; color: #555;">Raffle Entry Details</div>
        @endforeach
        @endif
    </div>

    <div style="font-weight: bold; font-size:2rem">Grand Prize: New Honda Click 125 (Standard)</div>
    <div style="font-size:1rem"> <strong>Promo Duration: <br> </strong> December 1, 2024 - January 31, 2025</div>

    <img src="/unioil_images/hondaclick.png" alt="hondaclick">
    @include('Customer.components.scripts')
</body>
</html>
