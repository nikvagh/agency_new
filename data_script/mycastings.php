<?php 
    ini_set('memory_limit', '-1');

    include 'config.php';
    $conn_live = open_conn_live();
    $conn_local = open_conn_local();

    $sql = "SELECT * FROM agency_mycastings";
    $query = $conn_live->query($sql);

    $users = array();
    if($query->num_rows > 0){
        while ($row = $query->fetch_assoc()) {
            // $users[] = $row;
            $sql_mycasting_ins = "INSERT INTO agency_mycastings SET 
                                    submission_id = '".$row['submission_id']."',
                                    user_id = '".$row['user_id']."',
                                    role_id = '".$row['role_id']."',
                                    message = '".$row['message']."',
                                    date = '".$row['date']."',
                                    new_submission = '".$row['new_submission']."',
                                    removed = '".$row['removed']."',
                                    audition_list = '',
                                    audition_book = '',
                                    fitting_date = '',
                                    fitting_time = '',
                                    message_tm_2_talent = ''
                                ";
            $query_mycasting_ins = $conn_local->query($sql_mycasting_ins);
        }
    }