<?php 
    ini_set('memory_limit', '-1');

    include 'config.php';
    $conn_live = open_conn_live();
    $conn_local = open_conn_local();

    $sql = "SELECT * FROM agency_photos";
    $query = $conn_live->query($sql);

    $users = array();
    if($query->num_rows > 0){
        while ($row = $query->fetch_assoc()) {
            $users[] = $row;

            $sql_photos_ins = "INSERT INTO agency_photos SET 
                                    image_id = '".$row['image_id']."',
                                    user_id = '".$row['user_id']."',    
                                    filename = '".$row['filename']."',
                                    card_position = '".$row['card_position']."',
                                    order_id = '".$row['order_id']."',
                                    headshot_thumb = 'N'
                                ";
            $query_photos_ins = $conn_local->query($sql_photos_ins);

        }
    }