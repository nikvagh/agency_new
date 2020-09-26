<?php 
    ini_set('memory_limit', '-1');

    include 'config.php';
    $conn_live = open_conn_live();
    $conn_local = open_conn_local();

    $sql = "SELECT * FROM agency_castings_roles";
    $query = $conn_live->query($sql);

    $users = array();
    if($query->num_rows > 0){
        while ($row = $query->fetch_assoc()) {
            $users[] = $row;

            // shoot_date_start = '".$row['shoot_date_start']."',
            // shoot_date_end = '".$row['shoot_date_end']."',
            $sql_casting_role_ins = "INSERT INTO agency_castings_roles SET 
                                    role_id = '".$row['role_id']."',
                                    casting_id = '".$row['casting_id']."',
                                    name = '".$row['name']."',
                                    description = '".$row['description']."',
                                    age_lower = '".$row['age_lower']."',
                                    age_upper = '".$row['age_upper']."',
                                    height_lower = '',
                                    height_upper = '',
                                    requirement = '',
                                    language = '',
                                    accent = '',
                                    special_skills = '',
                                    reference_photo = '',
                                    sides = '',
                                    required_materials = '',
                                    attachment = '".$row['attachment']."'
                                ";
            $query_casting_role_ins = $conn_local->query($sql_casting_role_ins);

        }
    }

    // echo "<pre>";
    // print_r($users);
    // exit;