<?php   
    $page_selected = "edit_profile";
    $page = "edit_profile";
    include('header.php');
    include('../includes/agency_dash_functions.php');

    use \Gumlet\ImageResize;
    use \Gumlet\ImageResizeException;
    include('../ImageResize/ImageResize.php');

   $loggedin = $userid = $user_id = $profileid = $_SESSION['user_id'];

    function dlt_profile_pic($id,$folder,$thumb){
        $old_file = mysql_result(mysql_query("SELECT user_avatar FROM forum_users WHERE user_id =".$id.""), 0, 'user_avatar');
        $old_file_link = $folder. $old_file;
        if(unlink($old_file_link)){
            foreach($thumb as $height=>$width){
                unlink($folder.'thumb/'.$height.'x'.$width.'_'. $old_file);
            }
        }
        mysql_query("UPDATE forum_users SET user_avatar='' WHERE user_id=".$id."");
    }

    $notification = array();

    // delete profile pic
    $folder_profile_pic = '../uploads/users/' . $user_id . '/profile_pic/';
    $folder_profile_pic_thumb = $folder_profile_pic . 'thumb/';

    $filename_profile_pic_db = "";
    if(isset($_POST['profile_pic_del'])){
        dlt_profile_pic($user_id,$folder_profile_pic,$profile_pic_thumb);
    }else{
        if(isset($_POST['profile_pic_old'])){
            $filename_profile_pic_db = $_POST['profile_pic_old'];
        }
    }

    if(isset($_POST['photo_tab']) && $_POST['photo_tab'] == 'photo_tab'){

        // profile pictures ==============
        $allowed_profie_pic = array ('image/jpeg', 'image/jpg', 'image/pjpeg', 'image/JPG');
        
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['name'] != "") {
            if (in_array($_FILES['profile_pic']['type'], $allowed_profie_pic)) {

                // if(!is_dir($folder_profile_pic)) {
                //  mkdir($folder_profile_pic, 0777, true);
                // }
                if(!is_dir($folder_profile_pic_thumb)) {
                    mkdir($folder_profile_pic_thumb, 0777, true);
                }
                dlt_profile_pic($user_id,$folder_profile_pic,$profile_pic_thumb);

                // Move the file over.
                $filename_profile_pic = filename_new($_FILES['profile_pic']['name']);
                $destination_profile_pic = $folder_profile_pic.$filename_profile_pic;
                if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], "$destination_profile_pic")) {
                    foreach($profile_pic_thumb as $height=>$width){
                        $image = new ImageResize($destination_profile_pic);
                        $image->resizeToHeight($height);
                        $image->save($folder_profile_pic_thumb.$height.'x'.$width.'_'. $filename_profile_pic);
                    }
                    $filename_profile_pic_db = $filename_profile_pic;
                }else{
                    $notification['error'] = "Something Wrong With Profile Picture.";
                }

            } else { // Invalid type.
                $notification['error'] = "Something Wrong With Profile Picture.";
            }
        }

        $sql_photo_update = "UPDATE forum_users 
            SET
            user_avatar = '".$filename_profile_pic_db."'
            WHERE  
            user_id = ".$user_id."
        ";
        mysql_query($sql_photo_update);
        if(mysql_query($sql_photo_update)){
            $notification['success'] = "Profile Updated Successfully.";
        }

    }

    if (isset($_POST['general_tab']) && $_POST['general_tab'] == 'general_tab') { // Handle the form.

        $sql_tm_edit = "UPDATE forum_users 
                            SET
                            user_email = '".$_POST['email']."'
                            WHERE
                            user_id = '".$user_id."'
                        ";
        if(mysql_query($sql_tm_edit)){

            $sql_tm_profile_edit = "UPDATE agency_profiles 
                            SET
                            firstname = '".$_POST['firstname']."',
                            lastname = '".$_POST['lastname']."',
                            office_phone = '".$_POST['office_phone']."',
                            phone = '".$_POST['phone']."',
                            company = '".$_POST['company']."',
                            address = '".$_POST['address']."',
                            address2 = '".$_POST['address2']."',
                            city = '".$_POST['city']."',
                            state = '".$_POST['state']."',
                            zip = '".$_POST['zip']."'
                            WHERE
                            user_id = '".$user_id."'
                        ";
            if(mysql_query($sql_tm_profile_edit)){

                $reg_city_represent_talent = "";
                if(isset($_POST['reg_city_represent_talent'])){
                    $reg_city_represent_talent = implode(',',$_POST['reg_city_represent_talent']);
                }
                $how_many_talent = $_POST['how_many_talent'];
                $company_in_business = $_POST['company_in_business'];

                if(isset($_POST['dont_require_license'])){
                    $dont_require_license = "Y";
                }else{
                    $dont_require_license = "N";
                }
                $license_number = $_POST['license_number'];
                $type_of_project = "";
                if(isset($_POST['type_of_project'])){
                    $type_of_project = implode(',',$_POST['type_of_project']);
                }

                $sql_tm_final_edit = "UPDATE agency_tm 
                            SET
                            reg_city_represent_talent = '".$reg_city_represent_talent."',
                            how_many_talent = '".$how_many_talent."',
                            company_in_business = '".$company_in_business."',
                            dont_require_license = '".$dont_require_license."',
                            license_number = '".$license_number."',
                            type_of_project = '".$type_of_project."'
                            WHERE
                            user_id = '".$user_id."'
                        ";

                if(mysql_query($sql_tm_final_edit)){
                    $notification['success'] = 'Profile Details Upadted Successfully.';
                    // <a href="login.php" class="color-theme">Click Here</a> TO Login
                }
            }
            

        }
    }

    if(isset($_POST['social_link_tab']) && $_POST['social_link_tab'] == 'social_link_tab'){
        // echo "<pre>";
        // print_r($_POST['social_link']);
        // echo "</pre>";

        foreach($_POST['social_link'] as $key=>$link){
            $sql_social_check = "SELECT * from agency_profile_links 
                                        WHERE user_id='$user_id' AND social_media = '".$key."'
                                    ";
                        
            $query_social_check = mysql_query($sql_social_check);
            if(mysql_num_rows($query_social_check) > 0){
                $query_social_update = "UPDATE agency_profile_links
                                        SET 
                                        link = '".$link."'
                                        WHERE
                                        user_id='$user_id' AND social_media = '".$key."'

                                    ";
                mysql_query($query_social_update);
            }else{
                $query_social_insert = "INSERT INTO agency_profile_links
                                        SET 
                                        user_id = ".$user_id.",
                                        social_media = '".$key."',
                                        link = '".$link."'
                                    ";
                mysql_query($query_social_insert);
            }
        }
        $notification['success'] = 'Social Links Updated Successfully';
    }

    /*if (!is_active()) {
      echo '<div class="AGENCYsubmitmessage" style="text-align:left">';
      if(mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_profile_castings WHERE user_id='$loggedin'"),0) == 0) { // if myaccount form has never been submitted
        echo mysql_result(mysql_query("SELECT varvalue FROM agency_vars WHERE varname='waitingClient'"), 0, 'varvalue');
      } else {
        echo mysql_result(mysql_query("SELECT varvalue FROM agency_vars WHERE varname='waitingClient2'"), 0, 'varvalue');
      }
      echo '</div>';
    }*/

    // $userInfo = $user = get_talent_byId($_SESSION['user_id']);

    $sql = "select ap.*,fu.*,apt.*,at.* from forum_users fu
            LEFT JOIN agency_profiles ap ON ap.user_id = fu.user_id 
            LEFT JOIN agency_payment_term apt ON ap.pay_term = apt.payment_term_id
            LEFT JOIN agency_tm at ON ap.user_id = at.user_id
            WHERE fu.user_id = ".$user_id."";

    $query = mysql_query($sql);
    $userInfo = array();
    if (mysql_num_rows($query) > 0) {
        while ($row = mysql_fetch_assoc($query)) {
            $userInfo = $user = $row;
        }
    }

?>

<?php 
    $social = array();
    $sql_social_link = "SELECT * FROM agency_profile_links WHERE user_id=".$user_id." 
                        GROUP BY social_media";
    $result_social = mysql_query($sql_social_link);
    while ($row = sql_fetchrow($result_social)) {
        $social[$row['social_media']] = $row['link'];
    }
    // print_r($social);
?>

<div id="page-wrapper">
    <div class="" id="main">

        <div class="row">
            <div class="col-md-12">
                <h3>Edit Profile</h3>

                <?php if(isset($notification['success'])){ ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $notification['success']; ?>
                    </div>
                <?php } ?>
                <?php if(isset($notification['error'])){ ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $notification['error']; ?>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-5">

                <div class="box box-widget widget-user-2">
                    <div class="widget-user-header bg-theme text-white">
                        <div class="widget-user-image">
                            <?php 
                                if(file_exists('../uploads/users/' . $user['user_id'] . '/profile_pic/thumb/128x128_'.$user['user_avatar']) ){
                                    $image = '../uploads/users/' . $user['user_id'] . '/profile_pic/thumb/128x128_'.$user['user_avatar'];
                                }else{
                                    $image = '../images/friend.gif';
                                }
                            ?>
                            <img class="img-circle" src="<?php echo $image; ?>" alt="User Avatar" style="width: 65px;height: 65px;">
                        </div>
                        <h3 class="widget-user-username text-capitalize"><?php echo $user['firstname'].' '.$user['lastname']; ?></h3>
                        <h5 class="widget-user-desc">Talent Manager</h5>
                    </div>
                    <div class="box-footer no-padding no-border">
                        <ul class="nav nav-stacked">
                          <li><a><span class="text-uppercase text-bold">Name: </span> <?php echo $user['firstname'].' '.$user['lastname']; ?></a></li>
                          <li><a><span class="text-uppercase text-bold">Email: </span> <?php echo $user['user_email']; ?></a></li>
                          <li><a><span class="text-uppercase text-bold">Phone: </span> <?php echo $user['phone']; ?></a></li>
                          <li><a><span class="text-uppercase text-bold">Company: </span> <?php echo $user['company']; ?></a></li>
                          <li><a><span class="text-uppercase text-bold">Address: </span> <?php echo $user['address'].' '.$user['address2'].' '.$user['city'].' '.$user['state']; ?> </a></li>
                        </ul>
                    </div>
                </div>

                <div class="box box-default box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Social Media</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php if(isset($social['facebook']) && $social['facebook'] != ""){ ?>
                            <a href="<?php echo $social['facebook']; ?>" class="padding-5 text-theme" target="_blank"><i class="fa fa-facebook fa-2x"></i></a>
                        <?php } ?>
                        <?php if(isset($social['twitter']) && $social['twitter'] != ""){ ?>
                            <a href="<?php echo $social['twitter']; ?>" class="padding-5 text-theme" target="_blank"><i class="fa fa-twitter fa-2x"></i></a>
                        <?php } ?>
                        <?php if(isset($social['instagram']) && $social['instagram'] != ""){ ?>
                            <a href="<?php echo $social['instagram']; ?>" class="padding-5 text-theme" target="_blank"><i class="fa fa-instagram fa-2x"></i></a>
                        <?php } ?>
                        <?php if(isset($social['youtube']) && $social['youtube'] != ""){ ?>
                            <a href="<?php echo $social['youtube']; ?>" class="padding-5 text-theme" target="_blank"><i class="fa fa-youtube-play fa-2x"></i></a>
                        <?php } ?>
                        <?php if(isset($social['linkedin']) && $social['linkedin'] != ""){ ?>
                            <a href="<?php echo $social['linkedin']; ?>" class="padding-5 text-theme" target="_blank"><i class="fa fa-linkedin fa-2x"></i></a>
                        <?php } ?>
                        <?php if(isset($social['google_plus']) && $social['google_plus'] != ""){ ?>
                            <a href="<?php echo $social['google_plus']; ?>" class="padding-5 text-theme" target="_blank"><i class="fa fa-google-plus fa-2x"></i></a>
                        <?php } ?>
                        <?php if(isset($social['pinterest']) && $social['pinterest'] != ""){ ?>
                            <a href="<?php echo $social['pinterest']; ?>" class="padding-5 text-theme" target="_blank"><i class="fa fa-pinterest fa-2x"></i></a>
                        <?php } ?>
                        <?php if(isset($social['snapchat']) && $social['snapchat'] != ""){ ?>
                            <a href="<?php echo $social['snapchat']; ?>" class="padding-5 text-theme" target="_blank"><i class="fa fa-snapchat-ghost fa-2x"></i></a>
                        <?php } ?>
                        <?php if(isset($social['website']) && $social['website'] != ""){ ?>
                            <a href="<?php echo $social['website']; ?>" class="padding-5 text-theme" target="_blank"><i class="fa fa-globe fa-2x"></i></a>
                        <?php } ?>
                    </div>
                </div>

            </div>

            <div class="col-sm-7">
                <form method="post" action="" name="photo_frm" id="photo_frm" enctype="multipart/form-data">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Profile Picture</h3>
                            <!-- <p>Profile Photo</p> -->

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-sm btn-warning" data-widget="collapse"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label>Profile Picture</label>
                                <label class="file-box">
                                    <span class="name-box">Drag and Drop Files</span>
                                    <input type="hidden" name="profile_pic_old" id="profile_pic_old" value="<?php echo $userInfo['user_avatar']; ?>"/>
                                    <input type="file" name="profile_pic" class="form-control" />
                                </label>

                                <?php if($userInfo['user_avatar'] != ""){ ?>
                                    <a href="<?php echo '../uploads/users/' . $userInfo['user_id'] . '/profile_pic/' . $userInfo['user_avatar']; ?>" target="_blank" >View Profile Image</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <label><input type="checkbox" name="profile_pic_del"> check to delete</label>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="hidden" name="photo_tab" value="photo_tab"/>
                            <input type="submit" name="submit" value="Save" class="btn btn-success pull-right"/>
                        </div>
                    </div>
                </form>

                <form method="post" action="" name="social_link_frm" id="social_link_frm" enctype="multipart/form-data" class="form-horizontal">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Social Links</h3>
                            <!-- <p>Instagram, Facebook, Twitter, Youtube, Linkedin, Custom Link</p> -->

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-sm btn-warning" data-widget="collapse"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        
                        <div class="box-body">

                            <div class="form-group">
                                <div class="col-md-2 text-right">
                                    <span class="btn btn-default btn-block"><i class="fa fa-facebook"></i></span>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="social_link[facebook]" id="" value="<?php if(isset($social['facebook'])){ echo $social['facebook']; } ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-2 text-right">
                                    <span class="btn btn-default btn-block"><i class="fa fa-twitter"></i></span>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="social_link[twitter]" id="" value="<?php if(isset($social['twitter'])){ echo $social['twitter']; } ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-2 text-right">
                                    <span class="btn btn-default btn-block"><i class="fa fa-instagram"></i></span>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="social_link[instagram]" id="" value="<?php if(isset($social['instagram'])){ echo $social['instagram']; } ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-2 text-right">
                                    <span class="btn btn-default btn-block"><i class="fa fa-youtube"></i></span>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="social_link[youtube]" id="" value="<?php if(isset($social['youtube'])){ echo $social['youtube']; } ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-2 text-right">
                                    <span class="btn btn-default btn-block"><i class="fa fa-linkedin"></i></span>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="social_link[linkedin]" id="" value="<?php if(isset($social['linkedin'])){ echo $social['linkedin']; } ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-2 text-right">
                                    <span class="btn btn-default btn-block"><i class="fa fa-google-plus"></i></span>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="social_link[google_plus]" id="" value="<?php if(isset($social['google_plus'])){ echo $social['google_plus']; } ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-2 text-right">
                                    <span class="btn btn-default btn-block"><i class="fa fa-pinterest"></i></span>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="social_link[pinterest]" id="" value="<?php if(isset($social['pinterest'])){ echo $social['pinterest']; }; ?>" />
                                </div>
                            </div>
                                
                            <div class="form-group">
                                <div class="col-md-2 text-right">
                                    <span class="btn btn-default btn-block"><i class="fa fa-snapchat-ghost"></i></span>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="social_link[snapchat]" id="" value="<?php if(isset($social['snapchat'])){ echo $social['snapchat']; } ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-2 text-right">
                                    <span class="btn btn-default btn-block"><i class="fa fa-globe"></i></span>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="social_link[website]" id="" value="<?php if(isset($social['website'])){ echo $social['website']; } ?>" />
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <input type="hidden" name="social_link_tab" value="social_link_tab"/>
                            <input type="submit" name="submit" value="Save" class="btn btn-success pull-right"/>
                        </div>

                    </div>
                </form>

                <form class="form" action="" method="post" id="edit_general_frm" name="">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">General Info</h3>
                            <!-- <p>Profile Photo</p> -->
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-sm btn-warning" data-widget="collapse"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label>First Name *</label>
                                <input type="text" class="form-control" name="firstname" id="firstname" value="<?php echo $userInfo['firstname']; ?>" />
                            </div>

                            <div class="form-group">
                                <label>Last Name *</label>
                                <input type="text" class="form-control" name="lastname" id="lastname" value="<?php echo $userInfo['lastname']; ?>" />
                            </div>

                            <div class="form-group">
                                <label>Email *</label>
                                <input type="text" class="form-control" name="email" id="email" value="<?php echo $userInfo['user_email']; ?>" />
                            </div>

                            <div class="form-group">
                                <label>Office Phone *</label>
                                <input type="text" class="form-control" name="office_phone" id="office_phone" value="<?php echo $userInfo['office_phone']; ?>" />
                            </div>

                            <div class="form-group">
                                <label>Phone *</label>
                                <input type="text" class="form-control" name="phone" id="phone" value="<?php echo $userInfo['phone']; ?>" />
                            </div>

                            <div class="form-group">
                                <label>Company *</label>
                                <input type="text" class="form-control" name="company" id="company" value="<?php echo $userInfo['company']; ?>" />
                            </div>

                            <div class="form-group">
                                <label>Company Address (Street Address) </label>
                                <input type="text" class="form-control" name="address" id="address" value="<?php echo $userInfo['address']; ?>" />
                            </div>

                            <div class="form-group">
                                <label>Address Line 2 </label>
                                <input type="text" class="form-control" name="address2" id="address2" value="<?php echo $userInfo['address2']; ?>" />
                            </div>

                            <div class="form-group">
                                <label>City </label>
                                <input type="text" class="form-control" name="city" id="city" value="<?php echo $userInfo['city']; ?>" />
                            </div>

                            <div class="form-group">
                                <label>State </label>
                                <select name="state" class="form-control">
                                    <option value="">Select State</option>
                                    <?php foreach($stateList['US'] as $key => $val){ ?>
                                        <option value="<?php echo $val; ?>" <?php if($userInfo['state'] == $val){ echo "selected"; } ?> ><?php echo $val; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Zip </label>
                                <input type="text" class="form-control" name="zip" id="zip" value="<?php echo $userInfo['zip']; ?>" />
                            </div>

                            <div class="form-group">
                                <label>What region/cities do you represent talent in? </label>
                                <br/>
                                <?php
                                    $reg_city_represent_talent_old = array();
                                    if(!empty($userInfo['reg_city_represent_talent'])){
                                        $reg_city_represent_talent_old = explode(',', $userInfo['reg_city_represent_talent']);
                                    }
                                ?>
                                <?php foreach($reg_city_tm_ary as $val){ ?>
                                    <label class="color-white weight-normal">
                                        <input type="checkbox" name="reg_city_represent_talent[]" value="<?php echo $val; ?>" <?php if(in_array($val, $reg_city_represent_talent_old)){ echo "checked"; } ?> /> <?php echo $val; ?> 
                                    </label> <br/>
                                <?php } ?>
                            </div>

                            <div class="form-group">
                                <label>How many talent do you currently represent? *</label>
                                <select name="how_many_talent" id="how_many_talent" class="form-control">
                                    <option value="">Select</option>
                                    <?php foreach($how_many_talent_ary as $val){ ?>
                                        <option value="<?php echo $val; ?>" <?php if($userInfo['how_many_talent'] == $val){ echo "selected"; } ?> ><?php echo $val; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>How long has your company been in business? *</label>
                                <select name="company_in_business" id="company_in_business" class="form-control">
                                    <option value="">Select</option>
                                    <?php foreach($company_in_business_ary as $val){ ?>
                                        <option value="<?php echo $val; ?>" <?php if($userInfo['company_in_business'] == $val){ echo "selected"; } ?> ><?php echo $val; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Specify which type of projects you represent talent for *</label>
                                <br/>
                                <?php
                                    $type_of_project_old = array();
                                    if(!empty($userInfo['type_of_project'])){
                                        $type_of_project_old = explode(',', $userInfo['type_of_project']);
                                    }
                                ?>
                                <?php foreach($type_of_project_ary as $val){ ?>
                                    <label class="color-white weight-normal">
                                        <input type="checkbox" name="type_of_project[]" value="<?php echo $val; ?>" <?php if(in_array($val, $type_of_project_old)){ echo "checked"; } ?>/> <?php echo $val; ?> 
                                    </label> <br/>
                                <?php } ?>
                                <span class="checkbox_err"></span>
                            </div>

                            <div class="form-group">
                                <label class="control-label weight-normal"> I do not require a talent agency license in my region 
                                    <input type="checkbox" name="dont_require_license" id="dont_require_license" value="" <?php if($userInfo['dont_require_license'] == 'Y'){ echo "checked"; } ?> />
                                </label>
                            </div>

                            <div class="form-group">
                                <label>Your talent agency License number or bond number? <input type="text" class="form-control" name="license_number" id="license_number" value="<?php echo $userInfo['license_number']; ?>" />
                                     (All managers within California and several other states must have a surety bond or belong to Talent Managers Association to receive breakdowns or negotiate on behalf of talent.*)
                                </label>
                            </div>
                        </div>

                        <div class="box-footer">
                            <div class="form-group">
                                <input type="hidden" name="general_tab" value="general_tab"/>
                                <input type="submit" name="submit" value="Save Changes" class="btn btn-success pull-right"/>
                                <!--<button class="btn btn-lg" type="reset"><i class="glyphicon glyphicon-repeat"></i> Reset</button>-->
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>

    </div>
</div>


<?php include('footer_js.php'); ?>
<script src="../dashboard/assets/DataTables/datatables.min.js"></script> 
<script src="../dashboard/assets/DataTables/dataTables.bootstrap.min.js"></script>

<script src="../dashboard/assets/js/app.min.js"></script>
<script src="../dashboard/assets/fileStyle/fileStyle.js"></script>
<script src="../dashboard/assets/fancybox/jquery.fancybox.min.js"></script>

<!-- <script type="text/javascript" src="https://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-sliderAccess.js"></script> -->

<script type="text/javascript" src="../dashboard/assets/js/jquery.validate.js"></script>
<script>

    $.validator.addMethod("license_check_number", function(value, element) {
        if($("#dont_require_license").is(":checked")){
            return true;
        }else{
            if($("#license_number").val() == ""){
                return false;
            }else{
                return true;
            }
        }
    }, "This field is required.");

    // $.validator.addMethod("license_check_file", function(value, element) {
    //     if($("#dont_require_license").is(":checked")){
    //         return true;
    //     }else{
    //         if ($('#license_copy').val()) {
    //             return true;
    //         }else{
    //             return false;
    //         }
    //     }
    // }, "This field is required.");
    user_id = '<?php echo $user_id; ?>';

    $("#edit_general_frm").validate({
        rules: {
            firstname: "required",
            lastname: "required",
            email: {
                required : true,
                email : true,
                remote: {
                    url: "../ajax/dashboard_request.php",
                    type: "post",
                    data: {
                        name:'user_email_unique_upadte',
                        user_id:user_id
                    }
                }
            },
            company: "required",
            phone: {
                required : true,
                digits : true,
            },
            office_phone: {
                required : true,
                digits : true,
            },
            how_many_talent: {
                required : true,
            },
            company_in_business: {
                required : true,
            },
            license_number: {
                license_check_number : true,
            },
            license_copy: {
                license_check_file : true,
            },
            "type_of_project[]": {
                required:true,
            }
        },
        messages: {
            // email: { 
            //     remote: "Email already exist.",
            // },
            // username: { 
            //     remote: "Username already exist.",
            // }
        },
        errorElement: "em",
        errorPlacement: function ( error, element ) {
            // Add the `help-block` class to the error element
            error.addClass( "help-block" );

            if ( element.prop("type") === "checkbox") {
                // error.insertAfter(element.parent("label"));
                error.insertAfter(element.parents('label').siblings('.checkbox_err'));
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function ( element, errorClass, validClass ) {
            $( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
        },
        unhighlight: function (element, errorClass, validClass) {
            $( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
        }
        // submitHandler: function (){
        //     var response = grecaptcha.getResponse();
        //     // console.log(response);
        //     if(response == ""){
        //         alert("Please Check Chapcha To Submit");
        //         return false;
        //     }else{
        //         return true;
        //     }
        //     // alert( "submitted!" );
        // }
    });

    $("#social_link_frm").validate({
        rules: {
            "social_link[facebook]": {
                url: true,
            },
            "social_link[twitter]": {
                url: true,
            },
            "social_link[instagram]": {
                url: true,
            },
            "social_link[youtube]": {
                url: true,
            },
            "social_link[linkedin]": {
                url: true,
            },
            "social_link[google_plus]": {
                url: true,
            },
            "social_link[pinterest]": {
                url: true,
            },
            "social_link[snapchat]": {
                url: true,
            },
            "social_link[website]": {
                url: true,
            }
        },
        messages: {
            email: { 
                remote: "Email already exist.",
            },
            // username: { 
            //     remote: "Username already exist.",
            // }
        },
        errorElement: "em",
        errorPlacement: function ( error, element ) {
            // Add the `help-block` class to the error element
            error.addClass( "help-block" );

            if ( element.prop("type") === "checkbox") {
                // error.insertAfter(element.parent("label"));
                error.insertAfter(element.parents('label').siblings('.checkbox_err'));
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function ( element, errorClass, validClass ) {
            $( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
        },
        unhighlight: function (element, errorClass, validClass) {
            $( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
        }
        // submitHandler: function (){
        //     var response = grecaptcha.getResponse();
        //     // console.log(response);
        //     if(response == ""){
        //         alert("Please Check Chapcha To Submit");
        //         return false;
        //     }else{
        //         return true;
        //     }
        //     // alert( "submitted!" );
        // }
    });
</script>
<script>
    if (window.history.replaceState) {
      window.history.replaceState( null, null, window.location.href );
    }
</script>
<?php include('footer.php'); ?>