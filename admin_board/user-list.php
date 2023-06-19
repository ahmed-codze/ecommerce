<?php
include '../connect.php';
$serch_placeholder = " ابحث عميل";
$serch_page = 'user-list.php';
include 'admin_header.php';
?>

<div class="page-body">
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-lg-6">
                    <div class="page-header-left">
                        <h3>قائمة العملاء
                        </h3>
                    </div>
                </div>
                <div class="col-lg-6 text-end">
                    <?php

                    if (!(isset($_GET['search']))) {
                        echo '
                    <div class="btn btn-dark show-email-form">ارسال رسالة</div>
                    ';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->

    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="card">

            <div class="card-body order-datatable">
                <table class="display" id="basic-1">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>الرقم</th>
                            <th>الايميل</th>
                            <th>العنوان</th>
                            <th>اخر شراء</th>
                            <th>اجمالي الشراء</th>
                            <th>النظام</th>
                            <th>تفاصيل</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php

                        // search 

                        if (isset($_GET['search'])) {

                            $search = filter_var($_GET['search'], FILTER_SANITIZE_STRING);

                            // search by phone

                            $stmt = $con->prepare("SELECT * FROM users WHERE phone LIKE '%$search%' ");
                            $stmt->execute();
                            $rows = $stmt->fetchAll();
                            $count = $stmt->rowCount();

                            if ($count > 0) {
                                // the loop 
                                foreach ($rows as $row) {
                                    echo '
                                    
                                    <tr>
                                    <td>' . $row["name"] . '</td>
                                    <td>' . $row["phone"] . '</td>
                                    <td>' . $row["email"] . '</td>
                                    <td>' . $row["address1"] . '</td>
                                    <td>' . $row["last_buy"] . '</td>
                                    <td>' . $row["total_buy"] . '</td>
                                    ';
                                    if ($row['trade'] == 0) {
                                        echo '<td style="color:blue">قطاعي</td>';
                                    } else {
                                        echo '<td style="color:red">جملة</td>';
                                    };
                                    echo '
                                    <td><a style="color: #34568B;" href="user_details.php?key=' . $row["user_key"] . '">المزيد  </a></td>
                                  </tr>
                    
                                    ';
                                }
                            } else {
                                // search by email 
                                $stmt = $con->prepare("SELECT * FROM users WHERE email LIKE '%$search%' ");
                                $stmt->execute();
                                $rows = $stmt->fetchAll();
                                $count = $stmt->rowCount();

                                if ($count > 0) {
                                    // the loop 
                                    foreach ($rows as $row) {
                                        echo '
                                      
                                      <tr>
                                      <td>' . $row["name"] . '</td>
                                      <td>' . $row["phone"] . '</td>
                                      <td>' . $row["email"] . '</td>
                                      <td>' . $row["address1"] . '</td>
                                      <td>' . $row["last_buy"] . '</td>
                                      <td>' . $row["total_buy"] . '</td>
                                      ';
                                        if ($row['trade'] == 0) {
                                            echo '<td style="color:blue">قطاعي</td>';
                                        } else {
                                            echo '<td style="color:red">جملة</td>';
                                        };
                                        echo '
                                        <td><a style="color: #34568B;" href="user_details.php?key=' . $row["user_key"] . '">المزيد </a></td>
                                        </tr>
                      
                                      ';
                                    }
                                } else {
                                    // search by name 
                                    $stmt = $con->prepare("SELECT * FROM users WHERE email LIKE '%$search%' ");
                                    $stmt->execute();
                                    $rows = $stmt->fetchAll();
                                    $count = $stmt->rowCount();

                                    if ($count > 0) {
                                        // the loop 
                                        foreach ($rows as $row) {
                                            echo '
                                        
                                        <tr>
                                        <td>' . $row["name"] . '</td>
                                        <td>' . $row["phone"] . '</td>
                                        <td>' . $row["email"] . '</td>
                                        <td>' . $row["address1"] . '</td>
                                        <td>' . $row["last_buy"] . '</td>
                                        <td>' . $row["total_buy"] . '</td>
                                        ';
                                            if ($row['trade'] == 0) {
                                                echo '<td style="color:blue">قطاعي</td>';
                                            } else {
                                                echo '<td style="color:red">جملة</td>';
                                            };
                                            echo '
                                            <td><a style="color: #34568B;" href="user_details.php?key=' . $row["user_key"] . '">المزيد  </a></td>
                                            </tr>
                        
                                        ';
                                        }
                                    } else {
                                        echo '<div class="alert-danger text-center">لا يوجد نتائج</div>';
                                    }
                                }
                            }
                        } else {

                            $users_id = array();

                            // arrange user 

                            //get users 

                            $stmt = $con->prepare("SELECT * FROM users ORDER BY id DESC");
                            $stmt->execute();
                            $rows = $stmt->fetchAll();

                            // the loop 
                            foreach ($rows as $row) {
                                echo '
                
                <tr>
                <td>' . $row["name"] . '</td>
                <td>' . $row["phone"] . '</td>
                <td>' . $row["email"] . '</td>
                <td>' . $row["address1"] . '</td>
                <td>' . $row["last_buy"] . '</td>
                <td>' . $row["total_buy"] . '</td>
                ';
                                if ($row['trade'] == 0) {
                                    echo '<td style="color:blue">قطاعي</td>';
                                } else {
                                    echo '<td style="color:red">جملة</td>';
                                };
                                echo '
                                <td><a style="color: #34568B;" href="user_details.php?key=' . $row["user_key"] . '">المزيد </a></td>
                                </tr>

                ';
                                array_push($users_id, $row['id']);
                            }
                        }


                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="email-form">
            <div class="row">
                <form action="send-email.php" method="POST">

                    <div class="col-12">
                        <p for="subject">عنوان الايميل</p>
                        <input id="subject" type="text" name="subject" class="form-control">
                    </div>

                    <div class="col-12">
                        <p for="body">محتوى الرسالة</p>
                        <style>
                            .simditor-body p {
                                text-align: left;
                            }
                        </style>
                        <textarea dir="ltr" required name="body" id="editor" style="height: 120px;" class="form-control"></textarea>
                    </div>


                    <?php
                    $users_array_id = implode(' ', $users_id);
                    ?>

                    <input type="hidden" value="<?php echo $users_array_id ?>" name="users_array">

                    <div class="col-12">
                        <br>
                        <br>
                        <button class="btn btn-solid" type="submit">ارسال</button>
                    </div>

                </form>
            </div>
            <span class="close-email-form close">X</span>
        </div>

    </div>
    <!-- Container-fluid Ends-->
</div>

<?php include 'admin_footer.php'; ?>

</body>

</html>