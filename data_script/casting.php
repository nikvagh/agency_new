<?php 
    ini_set('memory_limit', '-1');

    include 'config.php';
    $conn_live = open_conn_live();
    $conn_local = open_conn_local();

    $sql = "SELECT * FROM agency_castings";
    $query = $conn_live->query($sql);

    $users = array();
    if($query->num_rows > 0){
        while ($row = $query->fetch_assoc()) {
            $users[] = $row;

            // shoot_date_start = '".$row['shoot_date_start']."',
            // shoot_date_end = '".$row['shoot_date_end']."',
            $sql_casting_ins = "INSERT INTO agency_castings SET 
                                    casting_id = '".$row['casting_id']."',
                                    posted_by = '".$row['posted_by']."',
                                    casting_director = '".$row['casting_director']."',
                                    artist = '".$row['artist']."',
                                    company = '".$row['company']."',
                                    job_title = '".$row['job_title']."',
                                    location_casting = '".$row['location_casting']."',
                                    location_shoot = '".$row['location_shoot']."',
                                    location = '".$row['location']."',
                                    rate_day = '".$row['rate_day']."',
                                    rate_usage = '".$row['rate_usage']."',
                                    usage_type = '".$row['usage_type']."',
                                    usage_time = '".$row['usage_time']."',
                                    usage_location = '".$row['usage_location']."',
                                    shoot_date = '".$row['shoot_date']."',
                                    casting_date = '".$row['casting_date']."',
                                    notes = '".$row['notes']."',
                                    tags = '',
                                    attachment = '".$row['attachment']."',
                                    post_date = '".$row['post_date']."',
                                    clientalert = '".$row['clientalert']."',
                                    live = '".$row['live']."',
                                    deleted = '".$row['deleted']."'
                                ";
            $query_casting_ins = $conn_local->query($sql_casting_ins);

        }
    }

    // echo "<pre>";
    // print_r($users);
    // exit;