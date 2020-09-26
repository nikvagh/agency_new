<?php 
    ini_set('memory_limit', '-1');

    include 'config.php';
    $conn_live = open_conn_live();
    $conn_local = open_conn_local();

    $sql = "SELECT * FROM forum_users";
    $query = $conn_live->query($sql);

    $users = array();
    if($query->num_rows > 0){
        while ($row = $query->fetch_assoc()) {
            $users[] = $row;

            $sql_user_ins = "INSERT INTO forum_users SET 
                                    user_id = ".$row['user_id'].",
                                    user_type = ".$row['user_type'].",
                                    group_id = ".$row['group_id'].",
                                    user_permissions = '".$row['user_permissions']."',
                                    user_perm_from = '".$row['user_perm_from']."',
                                    user_ip = '".$row['user_ip']."',
                                    user_regdate = '".$row['user_regdate']."',
                                    username = '".$row['username']."',
                                    username_clean = '".$row['username_clean']."',
                                    user_password = '".$row['user_password']."',
                                    user_passchg = '".$row['user_passchg']."',
                                    user_pass_convert = '".$row['user_pass_convert']."',
                                    user_email = '".$row['user_email']."',
                                    user_email_hash = '".$row['user_email_hash']."',
                                    user_birthday = '".$row['user_birthday']."',
                                    user_lastvisit = '".$row['user_lastvisit']."',
                                    user_lastmark = '".$row['user_lastmark']."',
                                    user_lastpost_time = '".$row['user_lastpost_time']."',
                                    user_lastpage = '".$row['user_lastpage']."',
                                    user_last_confirm_key = '".$row['user_last_confirm_key']."',
                                    user_last_search = '".$row['user_last_search']."',
                                    user_warnings = '".$row['user_warnings']."',
                                    user_last_warning = '".$row['user_last_warning']."',
                                    user_login_attempts = '".$row['user_login_attempts']."',
                                    user_inactive_reason = '".$row['user_inactive_reason']."',
                                    user_inactive_time = '".$row['user_inactive_time']."',
                                    user_posts = '".$row['user_posts']."',
                                    user_lang = '".$row['user_lang']."',
                                    user_timezone = '".$row['user_timezone']."',
                                    user_dst = '".$row['user_dst']."',
                                    user_dateformat = '".$row['user_dateformat']."',
                                    user_style = '".$row['user_style']."',
                                    user_rank = '".$row['user_rank']."',
                                    user_colour = '".$row['user_colour']."',
                                    user_new_privmsg = '".$row['user_new_privmsg']."',
                                    user_unread_privmsg = '".$row['user_unread_privmsg']."',
                                    user_last_privmsg = '".$row['user_last_privmsg']."',
                                    user_message_rules = '".$row['user_message_rules']."',
                                    user_full_folder = '".$row['user_full_folder']."',
                                    user_emailtime = '".$row['user_emailtime']."',
                                    user_topic_show_days = '".$row['user_topic_show_days']."',
                                    user_topic_sortby_type = '".$row['user_topic_sortby_type']."',
                                    user_topic_sortby_dir = '".$row['user_topic_sortby_dir']."',
                                    user_post_show_days = '".$row['user_post_show_days']."',
                                    user_post_sortby_type = '".$row['user_post_sortby_type']."',
                                    user_post_sortby_dir = '".$row['user_post_sortby_dir']."',
                                    user_notify = '".$row['user_notify']."',
                                    user_notify_pm = '".$row['user_notify_pm']."',
                                    user_notify_type = '".$row['user_notify_type']."',
                                    user_allow_pm = '".$row['user_allow_pm']."',
                                    user_allow_viewonline = '".$row['user_allow_viewonline']."',
                                    user_allow_viewemail = '".$row['user_allow_viewemail']."',
                                    user_allow_massemail = '".$row['user_allow_massemail']."',
                                    user_options = '".$row['user_options']."',
                                    user_avatar = '".$row['user_avatar']."',
                                    user_avatar_type = '".$row['user_avatar_type']."',
                                    user_avatar_width = '".$row['user_avatar_width']."',
                                    user_avatar_height = '".$row['user_avatar_height']."',
                                    user_sig = '".$row['user_sig']."',
                                    user_sig_bbcode_uid = '".$row['user_sig_bbcode_uid']."',
                                    user_sig_bbcode_bitfield = '".$row['user_sig_bbcode_bitfield']."',
                                    user_from = '".$row['user_from']."',
                                    user_icq = '".$row['user_icq']."',
                                    user_aim = '".$row['user_aim']."',
                                    user_yim = '".$row['user_yim']."',
                                    user_msnm = '".$row['user_msnm']."',
                                    user_jabber = '".$row['user_jabber']."',
                                    user_website = '".$row['user_website']."',
                                    user_occ = '".$row['user_occ']."',
                                    user_interests = '".$row['user_interests']."',
                                    user_actkey = '".$row['user_actkey']."',
                                    user_newpasswd = '".$row['user_newpasswd']."',
                                    user_form_salt = '".$row['user_form_salt']."'
                                ";
            $query_user_ins = $conn_local->query($sql_user_ins);

        }
    }

    // echo "<pre>";
    // print_r($users);
    // exit;