<?php 
    ini_set('memory_limit', '-1');

    include 'config.php';
    $conn_live = open_conn_live();
    $conn_local = open_conn_local();

    $sql = "SELECT * FROM agency_discounts";
    $query = $conn_live->query($sql);

    $users = array();
    if($query->num_rows > 0){
        while ($row = $query->fetch_assoc()) {
            $users[] = $row;

            $sql_discount_ins = "INSERT INTO agency_discounts SET 
                                    discount_id = '".$row['discount_id']."',
                                    code = '".$row['discount_code']."',
                                    weeks = '',
                                    days = '',
                                    discount_type = '".$row['discount_type']."',
                                    total_days = '',
                                    max_use = '".$row['discount_usage']."',
                                    percentage = '',
                                    status = 'Enable',
                                    created_at = ''
                                ";
            $query_discount_ins = $conn_local->query($sql_discount_ins);

        }
    }

    // echo "<pre>";
    // print_r($users);
    // exit;