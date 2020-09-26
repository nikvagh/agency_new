<?php include('header_code.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('head.php'); ?>
    <?php include('common_css.php'); ?>
</head>

<body>
    <?php include('header.php'); ?>

    <?php
    if (isset($_POST['submitclient'])) {
        // Check for a username.
        if (eregi('^[[:alnum:]]{4,30}$', stripslashes(trim($_POST['username'])))) {
            $un = escape_data($_POST['username']);
            $sql = "SELECT user_id FROM forum_users WHERE username='$un'";
            if (mysql_num_rows(mysql_query($sql)) != 0) {
                $un = FALSE;
                $message_client .= '<p><font color="red">The Username you selected has already been taken by another member.  Please select a different Username.</font></p>';
            }
        } else {
            $un = FALSE;
            $message_client .= '<p><font color="red">Please enter a valid username (between 4 and 30 alphanumeric characters, no spaces).</font></p>';
        }
        // Check for an email address.
        if (eregi('^[[:alnum:]][a-z0-9_\.\-]*@[a-z0-9\.\-]+\.[a-z]{2,4}$', stripslashes(trim($_POST['email'])))) {
            $e = escape_data($_POST['email']);
            /*if($_POST['email'] == $_POST['confirmemail']) {
                $e = escape_data($_POST['email']);
                $sql = "SELECT user_id FROM forum_users WHERE user_email='$e'";
                if(mysql_num_rows(mysql_query($sql)) != 0) {
                    $e = FALSE;
                    $message_client .= '<p><font color="red">The Email you entered is already being used by another account.  Each account must have a unique email.  If you have forgotten your password, you may retrieve it <a href="forgotpassword.php">here</a>.</font></p>';
                }
            } else {
                $e = FALSE;
                $message_client .=  '<p><font color="red">Your confirmation email did not match.</font></p>';
            }*/
        } else {
            $e = FALSE;
            $message_client .=  '<p><font color="red">Please enter a valid email address.</font></p>';
        }
        // Check for a password and match against the confirmed password.
        if (eregi('^[[:alnum:]]{6,20}$', stripslashes(trim($_POST['joinpassword'])))) {
            $p = escape_data($_POST['joinpassword']);
        } else {
            $p = FALSE;
            $message_client .=  '<p><font color="red">Please enter a valid password (between 6 and 20 alphanumeric characters)</font></p>';
        }
        // Check for a first name.
        if (!empty($_POST['firstname'])) {
            $fn = escape_data($_POST['firstname']);
        } else {
            $fn = FALSE;
            $message_client .=  '<p><font color="red">Please fill the First Name field.</font></p>';
        }

        if ($fn && $un && $e && $p) {

            $pass_orig = $p;

            $p = _hash($p);
            $user_type = 1;
            $user_ip = getRealIpAddr();
            $user_regdate = time();

            $query = "INSERT INTO forum_users (username, username_clean, user_email, user_password, user_type, user_ip, user_regdate) VALUES ('$un', '$un', '$e', '$p', '$user_type', '$user_ip', '$user_regdate')";
            mysql_query($query);
            if (mysql_affected_rows() == 1) {
                // Register user...
                $user_id = mysql_insert_id();

                if (is_int($user_id)) {
                    // place firstname and lastname (profile vars) in agency_users
                    $firstname = request_var('firstname', '', true);
                    $lastname = request_var('lastname', '', true);
                    $company = request_var('company', '', true);
                    $profession = request_var('profession', '', true);

                    $type = 'client';
                    $registration_date = time();

                    // echo "<br/>";
                    // echo "INSERT INTO agency_profiles ('user_id', firstname, lastname, account_type, location, client_profession, client_company, registration_date) VALUES ('$user_id', '$firstname', '$lastname', '$type', '$location', '$profession', '$company', '$registration_date')";

                    mysql_query("INSERT INTO agency_profiles (user_id, firstname, lastname, account_type, location, registration_date) VALUES ('$user_id', '$firstname', '$lastname', 'client', '$location', '$registration_date')");
                    // create default lightbox

                    // exit;
                    $timecode = strtotime("NOW");
                    $query = "INSERT INTO agency_lightbox (client_id, lightbox_name, lightbox_description, timecode) VALUES ('$user_id', 'my lightbox', 'This is your first lightbox', '$timecode')";
                    mysql_query($query);
                }


                // SEND WELCOME EMAIL!
                $subject = 'Welcome to The Agency Online';
                $message = file_get_contents('./adminXYZ/email_templates/admin_client_welcome_inactive.txt');
                $message = str_replace("{USERNAME}", $un, $message);
                $message = str_replace("{PASSWORD}", $pass_orig, $message);
                // echo $message;
                $headers = 'From: info@theagencyonline.com' . "\r\n" .
                    'Reply-To: info@theagencyonline.com' . "\r\n";

                mail($e, $subject, $message, $headers);

                $_SESSION['user_id'] = $user_id;
                echo '<script>window.location.href = "home.php";</script>';

                /* $url = 'myaccount.php';
                ob_end_clean(); // Delete the buffer.
                header("Location: $url");
                exit(); // Quit the script.
                */
            } else {
                $message_client .= 'THERE WAS A PROBLEM CREATING YOUR ACCOUNT.  PLEASE BE SURE TO ENTER VALID INFORMATION INTO THE REGISTRATION FORM.  IF YOU CONTINUE TO EXPERIENCE PROBLEMS PLEASE CONTACT US.';
            }
        } else {
            $message_client .= '<p><font color="red">Something went wrong.  Sorry.</font></p>';
        }
    }
    ?>
    <div class="menu">
        <ul>
            <li><a href="talent_signup.php">TALENT</a></li>
            <li><a href="client_signup.php" class="active">CLIENT</a></li>
            <li><a href="agent-signup.php">TALENT MANAGER/AGENT</a></li>
        </ul>
    </div>

    <div class="signup-content">
        <div class="container">
            <div class="signup-form2">
                <h2>WELCOME</h2>
                <hr class="welcome">
                <h3>CREATE ACCOUNT – DIRECTOR</h3>
                <?php if (isset($message_client)) echo $message_client; ?>
                <form action="client_signup.php" method="post" id="clientform" name="clientform">
                    <div class="reg">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text-area" placeholder="FIRST NAME:" required="required" name="firstname" maxlength="50" value="<?php if (isset($_POST['firstname'])) echo $_POST['firstname']; ?>">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text-area" placeholder="LAST NAME:" required="required" name="lastname" maxlength="50" value="<?php if (isset($_POST['lastname'])) echo $_POST['lastname']; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text-area" placeholder="EMAIL ADDRESS:" required="required" name="email" maxlength="100" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text-area" placeholder="USER NAME:" required="required" name="username" maxlength="30" value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="password" placeholder="PASSWORD:" required="required" name="joinpassword" maxlength="30" value="<?php if (isset($_POST['joinpassword'])) echo $_POST['joinpassword']; ?>">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="password" placeholder="CONFORM PASSWORD:" required="required" name="confirmpassword" maxlength="30" value="<?php if (isset($_POST['confirmpassword'])) echo $_POST['confirmpassword']; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <input type="text-area" placeholder="COMPANY NAME:" required="required" name="company" maxlength="50" value="<?php if (isset($_POST['company'])) echo $_POST['company']; ?>">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <input type="text-area" placeholder="ADDRESS:" required="required" name="address" value="<?php if (isset($_POST['address'])) echo $_POST['address']; ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text-area" placeholder="CITY:" required="required" name="city" value="<?php if (isset($_POST['city'])) echo $_POST['city']; ?>">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <select name="location">
                                            <option value="Alabama">Alabama</option>
                                            <option value="Alaska">Alaska</option>
                                            <option value="Arizona">Arizona</option>
                                            <option value="Arkansas">Arkansas</option>
                                            <option value="California">California</option>
                                            <option value="Colorado">Colorado</option>
                                            <option value="Connecticut">Connecticut</option>
                                            <option value="Delaware">Delaware</option>
                                            <option value="District Of Columbia">District Of Columbia</option>
                                            <option value="Florida">Florida</option>
                                            <option value="Georgia">Georgia</option>
                                            <option value="Hawaii">Hawaii</option>
                                            <option value="Idaho">Idaho</option>
                                            <option value="Illinois">Illinois</option>
                                            <option value="Indiana">Indiana</option>
                                            <option value="Iowa">Iowa</option>
                                            <option value="Kansas">Kansas</option>
                                            <option value="Kentucky">Kentucky</option>
                                            <option value="Louisiana">Louisiana</option>
                                            <option value="Maine">Maine</option>
                                            <option value="Maryland">Maryland</option>
                                            <option value="Massachusetts">Massachusetts</option>
                                            <option value="Michigan">Michigan</option>
                                            <option value="Minnesota">Minnesota</option>
                                            <option value="Mississippi">Mississippi</option>
                                            <option value="Missouri">Missouri</option>
                                            <option value="Montana">Montana</option>
                                            <option value="Neb raska">Nebraska</option>
                                            <option value="Nevada">Nevada</option>
                                            <option value="New Hampshire">New Hampshire</option>
                                            <option value="New Jersey">New Jersey</option>
                                            <option value="New Mexico">New Mexico</option>
                                            <option value="New York">New York</option>
                                            <option value="North Carolina">North Carolina</option>
                                            <option value="North Dakota">North Dakota</option>
                                            <option value="Ohio">Ohio</option>
                                            <option value="Oklahoma">Oklahoma</option>
                                            <option value="Oregon">Oregon</option>
                                            <option value="Pennsylvania">Pennsylvania</option>
                                            <option value="Rhode">Rhode Island</option>
                                            <option value="South Carolina">South Carolina</option>
                                            <option value="Tennessee">Tennessee</option>
                                            <option value="Texas">Texas</option>
                                            <option value="Utah">Utah</option>
                                            <option value="Vermont">Vermont</option>
                                            <option value="Virginia">Virginia</option>
                                            <option value="Washington">Washington</option>
                                            <option value="West Virginia">West Virginia</option>
                                            <option value="Wisconsin">Wisconsin</option>
                                            <option value="Wyoming">Wyoming</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <input type="text-area" placeholder="ZIP:" required="required" name="zip" value="<?php if (isset($_POST['zip'])) echo $_POST['zip']; ?>">
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <select name="state">
                                            <option value="USA">USA</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <input type="text-area" placeholder="CELL PHONE NUMBER" required="required" name="phone" pattern="[1-9]{1}[0-9]{9}" value="<?php if (isset($_POST['phone'])) echo $_POST['phone']; ?>">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group demo-form account-row">
                                <label class="control-label">ADD YOUR SOCIAL MEDIA ACCOUNTS:</label>
                                <input type="text-area" placeholder="Add your social media accounts" name="social" value="<?php if (isset($_POST['social'])) echo $_POST['social']; ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-6">
                                    <div class="form-group demo-form">
                                        <label class="control-label">CHOOSE A PRIMARY REGION <span>*</span></label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <select name="region">
                                            <option value="">New York</option>
                                            <option value="">California</option>
                                            <option value="">Florida</option>
                                            <option value="">DC</option>
                                            <option value="">Nashville</option>
                                            <option value="">Mid­ Atlantic</option>
                                            <option value="">Southeast</option>
                                            <option value="">Northwest</option>
                                            <option value="">Pacific</option>
                                            <option value="">Nationwide</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-7">
                                    <div class="form-group demo-form">
                                        <label class="control-label">PLEASE SPECIFY TYPE(S) OF CASTING <span>*</span></label>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <select name="Commercial">
                                            <?php
                                            $sql = "SELECT job_type FROM agency_castings_drop_job ORDER BY job_type";
                                            $result = mysql_query($sql);

                                            $num_results = mysql_num_rows($result);

                                            if ($num_results > 0) {
                                                while ($row = sql_fetchrow($result)) {

                                                    $job = $row['job_type'];

                                            ?>
                                                    <option value="<?= $job ?>"><?= $job ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-8">
                                    <div class="form-group demo-form">
                                        <label class="control-label">AVERAGE NUMBER OF PROJECTS PER MONTH <span>*</span></label>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <select name="Commercial">
                                            <option value="">1-4</option>
                                            <option value="">5-9</option>
                                            <option value="">10+</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-7">
                                    <div class="form-group demo-form">
                                        <label class="control-label">HOW DID YOU HEAR ABOUT US? <span>*</span></label>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <select name="hear">
                                            <option value="Agency Employee">Agency Employee</option>
                                            <option value="Facebook">Facebook</option>
                                            <option value="Google">Google</option>
                                            <option value="Twitter">Twitter</option>
                                            <option value="Instagram">Instagram</option>
                                            <option value="Referred by casting director">Referred by casting director</option>
                                            <option value="Ad Agency">Ad Agency</option>
                                            <option value="Production Company">Production Company</option>
                                            <option value="Received a personal invite">Received a personal invite</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-txt">
                            <h3>INDUSTRY REFERENCE INFORMATION <span>(Must be different than your own or it will not be considered.)</span></h3>
                            <hr>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-6">
                                    <div class="form-group demo-form">
                                        <label class="control-label">INDUSTRY REFERENCE #1 <span>*</span></label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text-area" placeholder="NAME:" required="required" name="name" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-6">
                                    <div class="form-group demo-form">
                                        <label class="control-label"> INDUSTRY REFERENCE #1 <span>*</span></label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text-area" placeholder="PHONE NUMBER:" required="required" name="lphone" value="<?php if (isset($_POST['lphone'])) echo $_POST['lphone']; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-6">
                                    <div class="form-group demo-form">
                                        <label class="control-label">INDUSTRY REFERENCE #1 <span>*</span></label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text-area" placeholder="TITLE:" required="required" name="title" value="<?php if (isset($_POST['title'])) echo $_POST['title']; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sign-submit">
                            <input type="hidden" value="true" name="submitclient" />
                            <input type="submit" value="JOIN">
                        </div>


                    </div>
                </form>


            </div>
        </div>
    </div>

    <?php

    include('footer.php');
    include('footer_js.php');

    ?>

</body>

</html>