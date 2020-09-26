<?php 
    ini_set('memory_limit', '-1');

    include 'config.php';
    $conn_live = open_conn_live();
    $conn_local = open_conn_local();

    $sql = "SELECT * FROM agency_lightbox";
    $query = $conn_live->query($sql);

    $users = array();
    if($query->num_rows > 0){
        while ($row = $query->fetch_assoc()) {
            // $users[] = $row;
            $sql_lightbox_ins = "INSERT INTO agency_lightbox SET 
                                    lightbox_id = '".$row['lightbox_id']."',
                                    client_id = '".$row['client_id']."',
                                    lightbox_name = '".$row['lightbox_name']."',
                                    lightbox_description = '".$row['lightbox_description']."',
                                    casting_id = '".$row['casting_id']."',
                                    lightbox_type = '',
                                    timecode = '".$row['timecode']."'
                                ";
            $query_lightbox_ins = $conn_local->query($sql_lightbox_ins);
        }
    }