<?php 
    ini_set('memory_limit', '-1');

    include 'config.php';
    $conn_live = open_conn_live();
    $conn_local = open_conn_local();

    $sql = "SELECT * FROM agency_profile_unions";
    $query = $conn_live->query($sql);

    $users = array();
    if($query->num_rows > 0){
        while ($row = $query->fetch_assoc()) {
            $users[] = $row;

            $sql_profile_unions_ins = "INSERT INTO agency_profile_unions SET 
                                    user_id = '".$row['user_id']."',
                                    union_name = '".$row['union_name']."'
                                ";
            $query_profile_unions_ins = $conn_local->query($sql_profile_unions_ins);

        }
    }