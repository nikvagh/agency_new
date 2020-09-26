<?php 
    ini_set('memory_limit', '-1');

    include 'config.php';
    $conn_live = open_conn_live();
    $conn_local = open_conn_local();

    $sql = "SELECT * FROM agency_friends";
    $query = $conn_live->query($sql);

    $users = array();
    if($query->num_rows > 0){
        while ($row = $query->fetch_assoc()) {
            // $users[] = $row;
            $sql_friends_ins = "INSERT INTO agency_friends SET 
                                    user_id = '".$row['user_id']."',
                                    friend_id = '".$row['friend_id']."',
                                    confirmed = '".$row['confirmed']."',
                                    denied = '".$row['denied']."'
                                ";
            $query_friends_ins = $conn_local->query($sql_friends_ins);
        }
    }