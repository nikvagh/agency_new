<?php
    ini_set('memory_limit', '-1');

    include 'config.php';
    $conn_live = open_conn_live();
    $conn_local = open_conn_local();

    $sql = "SELECT * FROM agency_profiles";
    $query = $conn_live->query($sql);

    $users = array();
    if($query->num_rows > 0){
        while ($row = $query->fetch_assoc()) {
            // $users[] = $row;
            // echo "<pre>";
            // print_r($row);
            // exit;

            $sql_check = "select user_id from agency_profiles where user_id = '".$row['user_id']."'";
            $check_query = $conn_local->query($sql_check);

            if($check_query->num_rows == 0){ 
              
                // fans = '".$row['fans']."',
                $sql_profile_ins = "INSERT INTO agency_profiles SET 
                                        user_id = '".$row['user_id']."',
                                        account_type = '".$row['account_type']."',
                                        location = '".$row['location']."',
                                        profession = '".$row['client_profession']."',
                                        company = '".$row['client_company']."',
                                        client_link = '".$row['client_link']."',
                                        client_note = '".$row['client_note']."',
                                        firstname = '".$row['firstname']."',
                                        lastname = '".$row['lastname']."',
                                        phone = '".$row['phone']."',
                                        cell_phone = '',
                                        office_phone = '',
                                        address = '',
                                        address2 = '',
                                        city = '".$row['city']."',
                                        state = '".$row['state']."',
                                        zip = '".$row['zip']."',
                                        country = '".$row['country']."',
                                        gender = '".$row['gender']."',
                                        height_ft = '',
                                        height_inch = '',
                                        height = '".$row['height']."',
                                        waist = '".$row['waist']."',
                                        suit = '".$row['suit']."',
                                        shirt = '".$row['shirt']."',
                                        neck = '".$row['neck']."',
                                        sleeve = '".$row['sleeve']."',
                                        inseam = '".$row['inseam']."',
                                        shoe = '".$row['shoe']."',
                                        weight = '".$row['weight']."',
                                        hair_color = '".$row['hair']."',
                                        hair_length = '',
                                        eye_color = '".$row['eyes']."',
                                        eye_shape = '',
                                        bust = '".$row['bust']."',
                                        cup = '".$row['cup']."',
                                        hips = '".$row['hips']."',
                                        dress = '".$row['dress']."',
                                        kids = '',
                                        glove = '',
                                        jacket = '',
                                        pants = '',
                                        hat = '',
                                        tattoos = '".$row['tattoos']."',
                                        piercings = '".$row['piercings']."',
                                        teleprompter = '".$row['teleprompter']."',
                                        hosting = '".$row['hosting']."',
                                        comedy = '".$row['comedy']."',
                                        experience = '".$row['experience']."',
                                        exp_request = '".$row['exp_request']."',
                                        exp_request_date = '".$row['exp_request_date']."',
                                        agegroup = '".$row['agegroup']."',
                                        birthdate = '".$row['birthdate']."',
                                        bio = '".$row['bio']."',
                                        skills_language = '".$row['skills_language']."',
                                        skills_sports_music = '".$row['skills_sports_music']."',
                                        skills_other = '".$row['skills_other']."',
                                        ethnicity = '".$row['ethnicity']."',
                                        nationality = '',
                                        resume = '".$row['resume']."',
                                        resume_text = '".$row['resume_text']."',
                                        headshot = '".$row['headshot']."',
                                        howheard = '".$row['howheard']."',
                                        visits = '".$row['visits']."',
                                        last_visit_IP = '".$row['last_visit_IP']."',
                                        compcard_type = '',
                                        profile_pic = '".$row['profile_pic']."',
                                        admin = '".$row['admin']."',
                                        registration_date = '".$row['registration_date']."',
                                        payAuthorized = '".$row['payAuthorized']."',
                                        payProcessed = '".$row['payProcessed']."',
                                        payFailed = '".$row['payFailed']."',
                                        payFailedDate = '".$row['payFailedDate']."',
                                        payProcessedDate = '".$row['payProcessedDate']."',
                                        pay_term = '".$row['pay_term']."',
                                        next_payment_date = '',
                                        register_browser = '".$row['register_browser']."',
                                        mentor_id = '".$row['mentor_id']."',
                                        roster_id = '',
                                        discount_code = '".$row['discount_code']."',
                                        account_status = '',
                                        profile_status = '',
                                        created_at = ''
                                    ";
                                    // exit;

                if($query_profile_ins = $conn_local->query($sql_profile_ins)){
                    if($row['account_type'] == 'talent'){

                        $sql_talent_ins = "INSERT INTO agency_talent SET 
                                        user_id = '".$row['user_id']."',
                                        other_region = '',
                                        parent_guardian = '',
                                        pregnant = '',
                                        twin_triplete = '',
                                        identical_twin_triplet = '',
                                        veteran = '',
                                        military_branch = '',
                                        years_in_service = '',
                                        work_extra = '',
                                        cut_hair = '',
                                        play_younger = '',
                                        have_passport = '',
                                        work_permit = '',
                                        full_nudity = ''
                                    ";
                        $conn_local->query($sql_talent_ins);
                    }

                    if($row['account_type'] == 'client'){

                        $sql_client_ins = "INSERT INTO agency_client SET 
                                        user_id = '".$row['user_id']."',
                                        Primary_casting_region = '',
                                        type_of_casting = '',
                                        avg_no_of_project_per_month = '',
                                        how_did_hear_about_us = '',
                                        comment = ''
                                    ";
                        $conn_local->query($sql_client_ins);

                    }
                }

                
            }

        }
    }