<?php

include 'connect.php';


// add products to wishlist 

if (isset($_POST['add_to_wishlist'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    // check login 

    if (isset($_COOKIE['key'])) {

        $key = filter_var($_COOKIE['key'], FILTER_SANITIZE_STRING);

        // check if account is exit  

        $stmt = $con->prepare("SELECT user_key FROM users WHERE user_key = ?");
        $stmt->execute(array($key));
        $count = $stmt->rowCount();

        if ($count > 0) {

            // check id 

            // check if id is right

            $stmt = $con->prepare("SELECT id FROM products WHERE id = ? ");
            $stmt->execute(array($id));
            $count = $stmt->rowCount();
            if (($count > 0)) {

                // check if exist in wishlist 

                $stmt = $con->prepare("SELECT product_id, user_key FROM wishlist WHERE product_id = ? AND user_key = ?");
                $stmt->execute(array($id, $key));
                $count = $stmt->rowCount();
                if ($count > 0) {
                    // delete if exist
                    $stmt = $con->prepare("DELETE FROM wishlist WHERE product_id = ? AND user_key = ?");
                    $stmt->execute(array($id, $key));
                    $count = $stmt->rowCount();
                    echo 'Deleted From Wishlist!';
                    exit();
                } else {
                    // add if not exist
                    $stmt = $con->prepare('INSERT INTO wishlist (product_id, user_key) VALUES (:id, :key)');
                    $stmt->execute(array(
                        'id' => $id,
                        'key' => $key
                    ));
                    echo 'added to your wishlist <i class="fa fa-check" aria-hidden="true"></i>          
                    ';
                    exit();
                }
            } else {
                // if  product_id doesn't exist 
                echo '<script>location.href = "error.php";</script>';
                exit();
            }
        } else {
            // if login is wrong 
            echo 'please, log in first!           
                ';
            exit();
        }
    } else {
        // if login is wrong 
        echo 'please, log in first!           
        ';
        exit();
    }
}


if (isset($_POST['delete_wishlist'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    // check id 

    $stmt = $con->prepare("SELECT id FROM wishlist WHERE id = ? AND user_key = ?");
    $stmt->execute(array($id, $_COOKIE['key']));
    $count = $stmt->rowCount();
    if ($count > 0) {
        // delete wishlist

        $stmt = $con->prepare("DELETE FROM `wishlist` WHERE `wishlist`.`id` = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $count = $stmt->rowCount();
        echo 'Product has removed from wishlist';
        exit();
    } else {
        echo '
                    <script>
                        window.location.href = "error.php";
                  </script>
              
                    ';
        exit();
    }
}
