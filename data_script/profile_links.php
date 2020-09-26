<?php 
    ini_set('memory_limit', '-1');

    include 'config.php';
    $conn_live = open_conn_live();
    $conn_local = open_conn_local();

    $sql = "SELECT * FROM agency_profile_links";
    $query = $conn_live->query($sql);

    $users = array();
    if($query->num_rows > 0){
        while ($row = $query->fetch_assoc()) {
            $users[] = $row;

            $sql_profile_links_ins = "INSERT INTO agency_profile_links SET 
                                    user_id = '".$row['user_id']."',
                                    social_media = '',
                                    link = '".$row['link']."',
                                    link_desc = '".$row['link_desc']."'
                                ";
            $query_profile_links_ins = $conn_local->query($sql_profile_links_ins);

        }
    }