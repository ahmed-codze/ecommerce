<?php

// quick view modal 

if (isset($_POST['quick_view'])) {
    include 'connect.php';

    $product_id = filter_var($_POST['product_id'], FILTER_SANITIZE_NUMBER_INT);

    $stmt = $con->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute(array($product_id));
    $rows = $stmt->fetchAll();

    foreach ($rows as $row) {
        $images = explode(',', $row['images']);
        echo '
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <div class="row">
            <div class="col-lg-6 col-xs-12">
                <div class="quick-view-img"><img src= "assets/img/products/' . $images[1] . '" alt="" class="img-fluid blur-up lazyload"></div>
            </div>
            <div class="col-lg-6 rtl-text">
                <div class="product-right">
                    <h2>' . $row['title'] . '</h2>
                    ';
        if (isset($_COOKIE['trade'])) {
            echo '
                        <h4 > LE ' . $row['trade_price'] . ' </h4>
                        ';
        } elseif ($row['discount'] == 0) {
            echo '
                    <h4>' . $row['price'] . ' جنيه</h4>
                    ';
        } else {
            echo '
                    <h4> <del>' . $row['price'] . ' </del> ' . $row['discount'] . ' جنيه </h4>
                    ';
        }
        echo '
                    <ul class="color-variant">
                    ';

        $stmt = $con->prepare("SELECT color_code FROM prod_color WHERE color_name = ?");
        $stmt->execute(array($row['color']));
        $colors = $stmt->fetchAll();

        foreach ($colors as $color) {
            echo '
            <br>
            <li class="bg-light0" style= "background-color: ' . $color['color_code'] . ';" ></li>';
        }

        echo '
                    </ul>
                    <div class="border-product">
                        <h6 class="product-title">product details</h6>
                        <p>' . $row['description'] . '</p>
                    </div>
                    <div class="product-description border-product">
                    ';
        if ($row['size'] != '') {
            echo '
                        <div class="size-box">
                            <ul class="size-ul selected">
                            ';
            $sizes = explode(',', $row['size']);

            foreach ($sizes as $size) {
                $stmt = $con->prepare("SELECT size FROM prod_size WHERE size = ? AND status != 0");
                $stmt->execute(array($size));
                $count = $stmt->rowCount();
                if ($count > 0) {
                    echo '
                    <li data-size="' . $size . '"><a href="javascript:void(0)" >' . $size . '</a></li>
                ';
                }
            }
            echo '
                            </ul>
                        </div>
                        ';
        }
        echo '
                        <h6 class="product-title">quantity</h6>
                        <div class="qty-box">
                            <div class="input-group"><span class="input-group-prepend"><button type="button" class="btn quantity-left-minus" data-type="minus" data-field=""><i class="ti-angle-left"></i></button> </span>
                                <input type="text" name="quantity" data-max=' . $row['quantity'] . ' maxlength="' . $row['quantity'] . '" class="form-control input-number qty-input" value="1"> <span class="input-group-prepend"><button type="button" class="btn quantity-right-plus" data-type="plus" data-field=""><i class="ti-angle-right"></i></button></span>
                            </div>
                        </div>
                    </div>
                    <div class="product-buttons add-to-cart-btn" data-id=' . $row['id'] . '><a href="javascript:void(0)" class="btn btn-solid">add to cart</a> <a href="product-details.php?id=' . $row['id'] . '" class="btn btn-solid">view detail</a></div>
                </div>
            </div>
        </div>
        <script src="assets/js/jquery-3.3.1.min.js"></script>
        <script>
        $(".size-ul li:first-child").addClass("active");
        $(".quantity-right-plus").on("click", function () {
            var $qty = $(".qty-input");
            var currentVal = parseInt($qty.val());
            if (!isNaN(currentVal) && currentVal < $qty.data("max")) {
                $qty.val(currentVal + 1);
            }
        });
        $(".quantity-left-minus").on("click", function () {
            var $qty = $(".qty-input");
            var _val = $($qty).val();

            var currentVal = parseInt($qty.val());
            if (!isNaN(currentVal) && currentVal > 1) {
                $qty.val(currentVal - 1);
            }
        });
        $(".qty-input").change(function() {
            if ($(this).val() > $(this).data("max")) {
                $(this).val($(this).data("max"));
            }
        })
        $(".size-box ul li").on("click", function (e) {
            $(".size-box ul li").removeClass("active");
            $("#selectSize").removeClass("cartMove");
            $(this).addClass("active");
            $(this).parent().addClass("selected");
        });

        ';

        echo "
        $('.add-to-cart-btn').click(function() {
        // add to cart 
        $('.sk-circle').show();
          $.post('cart-request.php', {
            add: 1,
            id: $(this).data('id'),
            quantity: $('.qty-input').val(),
            size: $('.size-ul li.active').data('size')
          }, function(data) {
            $('.sk-circle').fadeOut();
            $('.add-to-cart-text').html(data);
            $('.added-notification').addClass('show');
            setTimeout(function () {
                $('.added-notification').removeClass('show');
            }, 5000);
            // update mini cart 
            $.post('cart-request.php', {
                update_mini_cart: true
            }, function(data) {
                $('.mini-cart-content').html(data);
            })
          });

        });
          </script>
    ";
    }
}
