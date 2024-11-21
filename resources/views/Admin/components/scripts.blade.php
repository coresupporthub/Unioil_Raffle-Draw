    <!-- Libs JS -->
    <script src="{{asset('./dist/libs/apexcharts/dist/apexcharts.min.js?1692870487')}}" defer></script>
    <script src="{{asset('./dist/libs/jsvectormap/dist/js/jsvectormap.min.js?1692870487')}}" defer></script>
    <script src="{{asset('./dist/libs/jsvectormap/dist/maps/world.js?1692870487')}}" defer></script>
    <script src="{{asset('./dist/libs/jsvectormap/dist/maps/world-merc.js?1692870487')}}" defer></script>
    <!-- Tabler Core -->
    <script src="{{asset('./dist/js/tabler.min.js?1692870487')}}" defer></script>
    <script src="{{asset('./dist/js/demo.min.js?1692870487')}}" defer></script>
    <script src="{{asset('/js/helper.js')}}" defer></script>
    <script src="/js/admin-details.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const list = new List('table-default', {
                sortClass: 'table-sort'
                , listClass: 'table-tbody'
                , valueNames: ['sort-name', 'sort-type', 'sort-city', 'sort-score'
                    , {
                        attr: 'data-date'
                        , name: 'sort-date'
                    }
                    , {
                        attr: 'data-progress'
                        , name: 'sort-progress'
                    }
                    , 'sort-quantity'
                ]
            });
        })

    </script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const navbar = document.querySelector(".navbar");
            const navItems = document.querySelectorAll(".nav-item");
            const navbarAfter = navbar.querySelector("::after");

            function updateActiveNav(item) {

                navItems.forEach((nav) => nav.classList.remove("active"));

                item.classList.add("active");

                const itemRect = item.getBoundingClientRect();
                const navbarRect = navbar.getBoundingClientRect();
                const offsetLeft = itemRect.left - navbarRect.left;

                navbar.style.setProperty("--translate-x", `${offsetLeft}px`);
            }

            navItems.forEach((item) => {
                item.addEventListener("click", () => {
                    updateActiveNav(item);
                });
            });
        });

    </script>
