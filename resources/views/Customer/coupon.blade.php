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
<body bgcolor="#f1f3f4" style="font-family: 'Open Sans', sans-serif; text-align:center; padding: 1rem">
  <a href="unioil.com" style="border:0; outline:0;"><img src="/unioil_images/unioil_logo.png" width="151" style="margin:20px auto; display:block; border:0;"></a>
<h2>Great! Your entry has been saved for the raffle draw</h2>
<div style="    font-size: 18px;">We have sent you an email of this serial numbers of your raffle coupons<br>
  Please secure it for reference and ownership</div>
<div style="background-color: #d5dcdf;
    margin: 50px auto;
    padding: 20px 0;">
  <div style="   font-size: 1.5rem; margin-top: -5px;">Full Name: {{ $customers->full_name }}</div>
  @if($entries == 1)
  <div style=" font-weight: 800;
    font-size: 2rem;
    margin-bottom: -10px;
    margin-top: 10px;">{{ $code->serial_number }}</div>
  <div>Serial Number</div>
  @else

  @foreach ($code as $c)
  <div style="    font-weight: 800;
  font-size: 2rem;
  margin-bottom: -10px;
  margin-top: 10px;">{{ $c->serial_number }}</div>
<div>Serial Number</div>
  @endforeach

  @endif



</div>

<div style="font-weight: bold; font-size:2rem" >Grand Prize: New Honda Click 125i</div>
<div style="font-size:1rem">Promo Duration: November 15, 2024 - January 31, 2024</div>

<img src="/unioil_images/hondaclick.png" alt="hondaclick">
@include('Customer.components.scripts')
</body>
</html>


