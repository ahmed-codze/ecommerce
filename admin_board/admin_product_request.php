<?php

// get new sub categories 

if (isset($_POST['change_category'])) {
    $new_category = $_POST['new_category'];
    include '../connect.php';


    $stmt = $con->prepare("SELECT * FROM sub_category WHERE category = ?");
    $stmt->execute(array($new_category));
    $count = $stmt->rowCount();
    if ($count > 0) {
        echo '
        <div class="box">
        <select name="sub_category">
            ';
        $rows = $stmt->fetchAll();
        // the loop 
        foreach ($rows as $row) {
            if ($row['status'] != 0) {
                echo '
        <option value="' . $row['sub_category'] . '">' . $row['sub_category'] . '</option>
        ';
            }
        }
        echo '
    </select>
</div>
    ';
    } else {
        echo '<p style="font-size: 22px;">لا توجد أقسام فرعية لهذا القسم</p>';
    }
}
