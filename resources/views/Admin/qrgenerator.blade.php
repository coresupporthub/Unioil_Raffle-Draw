@include('Admin.components.head', ['title' => 'QR Generator'])

<body>
    <script src="{{asset('./dist/js/demo-theme.min.js?1692870487')}}"></script>
    <div class="page">

        @include('Admin.components.header')

        <div class="page-wrapper">
           
            

            @include('Admin.components.footer')

        </div>
    </div>

    @include('Admin.components.scripts')


</body>

</html>
