<?php 
    ini_set('memory_limit', '-1');

    include 'config.php';
    $conn_live = open_conn_live();
    $conn_local = open_conn_local();

    $sql = "SELECT * FROM agency_vo";
    $query = $conn_live->query($sql);

    $users = array();
    if($query->num_rows > 0){
        while ($row = $query->fetch_assoc()) {
            // $users[] = $row;

            $sql_vo_ins = "INSERT INTO agency_vo SET 
                                    user_id = '".$row['user_id']."',
                                    vo_name = '".$row['vo_name']."',
                                    vo_file = ''
                                ";
            $query_vo_ins = $conn_local->query($sql_vo_ins);

        }
    }