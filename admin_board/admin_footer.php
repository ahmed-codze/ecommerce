<!-- footer start-->
<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 footer-copyright text-start">
                <p class="mb-0">Copyright 2019 © Multikart All rights reserved.</p>
            </div>
            <div class="col-md-6 pull-right text-end">
                <p class=" mb-0">Hand crafted & made with<i class="fa fa-heart"></i></p>
            </div>
        </div>
    </div>
</footer>
<!-- footer end-->
</div>
</div>

<!-- latest jquery-->
<script src="assets/js/jquery-3.3.1.min.js"></script>

<!-- Datatable js-->
<script src="assets/js/datatables/jquery.dataTables.min.js"></script>
<script src="assets/js/datatables/custom-basic.js"></script>

<!-- Bootstrap js-->
<script src="assets/js/bootstrap.bundle.min.js"></script>



<!-- feather icon js-->
<script src="assets/js/icons/feather-icon/feather.min.js"></script>
<script src="assets/js/icons/feather-icon/feather-icon.js"></script>

<!-- Sidebar jquery-->
<script src="assets/js/sidebar-menu.js"></script>

<!--chartist js-->
<script src="assets/js/chart/chartist/chartist.js"></script>

<!--chartjs js-->
<script src="assets/js/chart/chartjs/chart.min.js"></script>

<!-- lazyload js-->
<script src="assets/js/lazysizes.min.js"></script>

<!--copycode js-->
<script src="assets/js/prism/prism.min.js"></script>
<script src="assets/js/clipboard/clipboard.min.js"></script>
<script src="assets/js/custom-card/custom-card.js"></script>

<!--counter js-->
<script src="assets/js/counter/jquery.waypoints.min.js"></script>
<script src="assets/js/counter/jquery.counterup.min.js"></script>
<script src="assets/js/counter/counter-custom.js"></script>

<!--peity chart js-->
<script src="assets/js/chart/peity-chart/peity.jquery.js"></script>

<!-- Apex Chart Js -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<!--sparkline chart js-->
<script src="assets/js/chart/sparkline/sparkline.js"></script>

<!--Customizer admin-->
<script src="assets/js/admin-customizer.js"></script>

<!--dashboard custom js-->
<script src="assets/js/dashboard/default.js"></script>

<!--right sidebar js-->
<script src="assets/js/chat-menu.js"></script>

<!--height equal js-->
<script src="assets/js/height-equal.js"></script>

<!-- lazyload js-->
<script src="assets/js/lazysizes.min.js"></script>

<!--script admin-->
<script src="assets/js/admin-script.js"></script>

<!-- simditor -->

<script type="text/javascript" src="simditor/module.js"></script>
<script type="text/javascript" src="simditor/hotkeys.js"></script>
<script type="text/javascript" src="simditor/uploader.js"></script>
<script type="text/javascript" src="simditor/toolbar.js"></script>
<script type="text/javascript" src="simditor/simditor.js"></script>

<script>
    Simditor.locale = 'en-US';
    var editor = new Simditor({
        textarea: $('#editor'),
        toolbar: [
            'title',
            'bold',
            'italic',
            'underline',
            'strikethrough',
            'fontScale',
            'color',
            'blockquote',
            'table',
            'link',
            'hr',
            'alignment',
            'indent',
            'outdent'
        ],
    });

    // show customer data

    $('.show-email-form').click(function() {
        $('.email-form').toggleClass('show');
    })
    $('.close-email-form').click(function() {
        $('.email-form').removeClass('show');
    })
</script>