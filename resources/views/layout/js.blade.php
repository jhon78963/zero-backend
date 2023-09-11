<script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
<script src="{{ asset('assets/js/config.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
<script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
<script src="{{ asset('assets/js/pages-account-settings-account.js') }}"></script>
<script async defer src="https://buttons.github.io/buttons.js"></script>
<script>
    $(document).ready(function() {
        var currentUrl = window.location.href;
        if (window.location.href.indexOf('/admin/perfil') === -1 &&
            window.location.href.indexOf('/admin/bienvenido') === -1) {
            var menuLinks = $('.menu-link');
            menuLinks.each(function() {
                var linkUrl = $(this).attr('href');
                if (currentUrl.includes(linkUrl)) {
                    $(this).addClass('active');
                    $(this).parents('li.menu-item').addClass('active open');
                }
            });
        }
    });
</script>
@yield('js')
