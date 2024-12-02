@include('Admin.components.head', ['title' => 'UniOil Raffle Draw'])

<style>
    .banner {
        height: 50vh;
        background: url('./unioil_images/unioil_bg.png') no-repeat center;
        display: flex;
        object-fit: contain;
        justify-content: center;
        align-items: center;
    }

    h1 {
        font-size: 4.5rem;
        font-weight: bold;
        position: relative;
        /* overflow: hidden; */
        display: inline-block;
        color: rgb(192, 61, 0);
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        animation: fadeInUp 4s ease-out forwards;
    }

    p {
        font-size: 2rem;
        margin-top: 15px;
        opacity: 0;
        animation: fadeIn 2s 1.5s ease-out forwards;
    }

    /* Animation keyframes */
    @keyframes fadeInUp {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        0% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }

    h1 span {
        display: inline-block;
        background: linear-gradient(to right, #ff7e5f, #feb47b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>

<body>
    <script src="{{ asset('./dist/js/demo-theme.min.js?1692870487') }}"></script>
    <div class="page">

        <div class="page-wrapper">
            <!-- Page body -->

            <div class="banner" style="background-image: url('/unioil_images/successbg.jpg'); background-size: cover;">

                <img src="{{ asset('./unioil_images/unioil_bg.png') }}" alt="">

            </div>

            <div class="text-center mt-8">
                <h1>Empowering the Future of Energy</h1>
                <p class="text-secondary">Delivering reliable and sustainable energy solutions.</p>
            </div>

            <!-- Bootstrap JS -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>


@include('Admin.components.footer')

</div>
</div>

</body>

</html>
