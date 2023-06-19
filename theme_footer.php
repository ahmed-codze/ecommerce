<!-- footer -->
<footer class="footer-light">
    <div class="light-layout">
        <div class="container">
            <section class="small-section border-section border-top-0">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="subscribe">
                            <div>
                                <h4>KNOW IT ALL FIRST!</h4>
                                <p>Never Miss Anything From Multikart By Signing Up To Our Newsletter.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <form action="https://pixelstrap.us19.list-manage.com/subscribe/post?u=5a128856334b598b395f1fc9b&amp;id=082f74cbda" class="form-inline subscribe-form auth-form needs-validation" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" target="_blank">
                            <div class="form-group mx-sm-3">
                                <input type="text" class="form-control" name="EMAIL" id="mce-EMAIL" placeholder="Enter your email" required="required">
                            </div>
                            <button type="submit" class="btn btn-solid" id="mc-submit">subscribe</button>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <section class="section-b-space light-layout">
        <div class="container">
            <div class="row footer-theme partition-f">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-title footer-mobile-title">
                        <h4>about</h4>
                    </div>
                    <div class="footer-contant">
                        <div class="footer-logo"><img src="assets/img/logo/<?php echo $logo; ?>" alt="<?php echo $title; ?>"></div>
                        <p><?php echo $description; ?></p>
                        <div class="footer-social">
                            <ul>
                                <li><a href="mailto:<?php echo $email ?>"><i class="fa fa-envelope" aria-hidden="true"></i></a></li>
                                <?php
                                if ($facebook != '') {
                                    echo '
                                    <li><a href="' . $facebook . '"><i class="fa fa-facebook-f"></i></a></li>
                                    ';
                                }
                                if ($instagram != '') {
                                    echo '
                                    <li><a href="' . $instagram . '"><i class="fa fa-instagram"></i></a></li>
                                    ';
                                }

                                if ($twitter != '') {
                                    echo '
                                    <li><a href="' . $twitter . '"><i class="fa fa-twitter"></i></a></li>
                                    ';
                                }
                                if ($whatsapp != '') {
                                    echo '
                                    <li><a href="https://wa.me/' . $whatsapp . '"><i class="fa fa-whatsapp" aria-hidden="true"></i></a></li>
                                    ';
                                }
                                ?>




                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col offset-xl-1">
                    <div class="sub-title">
                        <div class="footer-title">
                            <h4>Shipping Info </h4>
                        </div>
                        <div class="footer-contant">
                            <ul>
                                <li><?php echo $shiiping; ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="sub-title">
                        <div class="footer-title">
                            <h4>why we choose</h4>
                        </div>
                        <div class="footer-contant">
                            <ul>
                                <li><a href="#">shipping & return</a></li>
                                <li><a href="#">secure shopping</a></li>
                                <li><a href="#">gallary</a></li>
                                <li><a href="#">affiliates</a></li>
                                <li><a href="#">contacts</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="sub-title">
                        <div class="footer-title">
                            <h4>store information</h4>
                        </div>
                        <div class="footer-contant">
                            <ul class="contact-list">
                                <?php
                                if ($address != '') {
                                    echo '
                                        <li><i class="fa fa-map-marker"></i>' . $address . '</li>
                                        ';
                                }
                                ?>

                                <li><i class="fa fa-phone"></i>Call Us: <a href="tel:<?php echo $phone ?>"> <?php echo $phone ?></a></li>
                                <li><i class="fa fa-envelope"></i>Email Us: <a href="mailto:<?php echo $email ?>"><?php echo $email ?></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="sub-footer">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-md-6 col-sm-12">
                    <div class="footer-end">
                        <p><i class="fa fa-copyright" aria-hidden="true"></i> 2017-18 themeforest powered by
                            pixelstrap</p>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-12">
                    <div class="payment-card-bottom">
                        <ul>
                            <li>
                                <a href="#"><img src="assets/images/icon/visa.png" alt=""></a>
                            </li>
                            <li>
                                <a href="#"><img src="assets/images/icon/mastercard.png" alt=""></a>
                            </li>
                            <li>
                                <a href="#"><img src="assets/images/icon/paypal.png" alt=""></a>
                            </li>
                            <li>
                                <a href="#"><img src="assets/images/icon/american-express.png" alt=""></a>
                            </li>
                            <li>
                                <a href="#"><img src="assets/images/icon/discover.png" alt=""></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- footer end -->

<!-- theme setting -->
<div class="theme-settings">
    <ul>

        <li class="input-picker">
            <input id="ColorPicker1" type="color" value="#ff4c3b" name="Background">
        </li>
    </ul>
</div>

<!-- theme setting -->


<!-- tap to top -->
<div class="tap-top top-cls">
    <div>
        <i class="fa fa-angle-double-up"></i>
    </div>
</div>
<!-- tap to top end -->


<!-- latest jquery-->
<script src="assets/js/jquery-3.3.1.min.js"></script>

<!-- fly cart ui jquery-->
<script src="assets/js/jquery-ui.min.js"></script>

<!-- exitintent jquery-->
<script src="assets/js/jquery.exitintent.js"></script>
<script src="assets/js/exit.js"></script>

<!-- slick js-->
<script src="assets/js/slick.js"></script>

<!-- menu js-->
<script src="assets/js/menu.js"></script>

<!-- lazyload js-->
<script src="assets/js/lazysizes.min.js"></script>

<!-- Bootstrap js-->
<script src="assets/js/bootstrap.bundle.min.js"></script>

<!-- Bootstrap Notification js-->
<script src="assets/js/bootstrap-notify.min.js"></script>

<!-- Theme js-->
<script src="assets/js/theme-setting.js"></script>
<script src="assets/js/script.js"></script>

<script>
    function openSearch() {
        document.getElementById("search-overlay").style.display = "block";
    }

    function closeSearch() {
        document.getElementById("search-overlay").style.display = "none";
    }

    // mini add to cart 

    $('.mini-add-to-cart').click(function() {

        $('.sk-circle').show();

        $.post('cart-request.php', {
            mini_add_to_cart: 1,
            id: $(this).data('id'),

        }, function(data) {
            $('.sk-circle').fadeOut();
            $('.add-to-cart-text').html(data);
            $('.added-notification').addClass('show');
            setTimeout(function() {
                $('.added-notification').removeClass('show');
            }, 5000);
            // update mini cart 
            $.post('cart-request.php', {
                update_mini_cart: true
            }, function(data) {
                $('.mini-cart-content').html(data);
            })
        })

    });

    // delete mini cart item 

    $('.delete-mini-cart-item').click(function() {
        $(this).parentsUntil('ul').fadeOut();
        $('.cart_qty_cls').text($('.cart_qty_cls').text() - 1)
        $.post('cart-request.php', {
            delete_cart: true,
            cart_id: $(this).data('id'),
            cart_quantity: $(this).data('quantity')
        }, function(data) {
            $('.mini-cart-total').text(data);
        })
    })

    // add to whishlist 

    $('.whishlist-button').click(function() {
        $('.sk-circle').show();

        $.post('wishlist-request.php', {
            add_to_wishlist: 1,
            id: $(this).data('id'),

        }, function(data) {
            $('.sk-circle').fadeOut();
            $('.add-to-cart-text').html(data);
            $('.added-notification').addClass('show');
            setTimeout(function() {
                $('.added-notification').removeClass('show');
            }, 5000);

        })
    })

    // switch light and dark button 

    $('.theme-light-mood').click(function() {
        $(this).toggleClass('dark-icon').toggleClass('light-icon');
        $('body').toggleClass('dark');
        $.post('theme_header.php', {
            switch_mood: true
        })
    })
</script>