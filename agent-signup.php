<?php include('header_code.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('head.php'); ?>
    <?php include('common_css.php'); ?>
    <style type="text/css">
        #html_element {
            padding: 10px 0px 25px 0px;
        }

        #html_element>div {
            margin: auto;
        }
    </style>
</head>

<body>
    <?php include('header.php'); ?>

    <?php
    $notification = array();
    if (isset($_POST['submit']) && $_POST['submit'] == "SUBMIT") {
        // echo "<pre>";
        // print_r($_POST);
        // echo "</pre>";

        // echo "<pre>";
        // print_r($_FILES);
        // echo "</pre>";
        // exit;
        $err = "N";

        $password = _hash($_POST['password']);
        $user_type = 1;
        $user_ip = getRealIpAddr();
        $user_regdate = time();

        $sql_tm_ins = "INSERT into forum_users 
                                SET
                                username = '" . $_POST['username'] . "',
                                username_clean = '" . $_POST['username'] . "',
                                user_email = '" . $_POST['email'] . "',
                                user_password = '" . $password . "',
                                user_ip = '" . $user_ip . "',
                                user_regdate = '" . $user_regdate . "'
                            ";
        if (mysql_query($sql_tm_ins)) {

            $user_id_ins = mysql_insert_id();

            $license_copy = "";
            $folder_license_copy = 'uploads/users/' . $user_id_ins . '/license_copy/';
            if (isset($_FILES['license_copy']) && $_FILES['license_copy']['name'] != "") {
                // if (in_array($_FILES['profile_pic']['type'], $allowed_profie_pic)) {

                // if(!is_dir($folder_profile_pic)) {
                //     mkdir($folder_profile_pic, 0777, true);
                // }

                if (!is_dir($folder_license_copy)) {
                    mkdir($folder_license_copy, 0777, true);
                }

                // Move the file over.
                $filename_license_copy = filename_new_front($_FILES['license_copy']['name']);
                $destination_license_copy = $folder_license_copy . $filename_license_copy;
                if (move_uploaded_file($_FILES['license_copy']['tmp_name'], "$destination_license_copy")) {
                    $license_copy = $filename_license_copy;
                } else {
                    $err = "Y";
                    $delete_user = "DELETE FROM forum_users WHERE user_id = " . $user_id_ins;
                    if (mysql_query($delete_user)) {
                        $notification['error'] = "Something Wrong With license Copy. Please Try Again!";
                    }
                }

                // } else { // Invalid type.
                //     $notification['error'][] = "Something Wrong With Profile Picture.";
                // }
            }

            if ($err == "N") {

                $sql_tm_profile_ins = "INSERT into agency_profiles 
                                    SET
                                    user_id = '" . $user_id_ins . "',
                                    account_type = 'talent_manager',
                                    firstname = '" . $_POST['firstname'] . "',
                                    lastname = '" . $_POST['lastname'] . "',
                                    office_phone = '" . $_POST['office_phone'] . "',
                                    phone = '" . $_POST['phone'] . "',
                                    company = '" . $_POST['company'] . "',
                                    address = '" . $_POST['address'] . "',
                                    address2 = '" . $_POST['address2'] . "',
                                    city = '" . $_POST['city'] . "',
                                    state = '" . $_POST['state'] . "',
                                    zip = '" . $_POST['zip'] . "'
                                ";
                if (mysql_query($sql_tm_profile_ins)) {

                    foreach ($_POST['industry_ref']['name'] as $key => $val) {
                        $ref_name = $_POST['industry_ref']['name'][$key];
                        $ref_phone = $_POST['industry_ref']['phone'][$key];
                        $ref_title = $_POST['industry_ref']['title'][$key];

                        if ($ref_name != "" && $ref_phone != "" && $ref_title != "") {
                            $sql_mt_ind_ref_ins = "INSERT into agency_industry_ref 
                                    SET
                                    user_id = '" . $user_id_ins . "',
                                    ref_name = '" . $ref_name . "',
                                    ref_phone = '" . $ref_phone . "',
                                    ref_title = '" . $ref_title . "'
                                ";
                        }
                        mysql_query($sql_mt_ind_ref_ins);
                    }

                    $reg_city_represent_talent = "";
                    if (isset($_POST['reg_city_represent_talent'])) {
                        $reg_city_represent_talent = implode(',', $_POST['reg_city_represent_talent']);
                    }
                    $how_many_talent = $_POST['how_many_talent'];
                    $company_in_business = $_POST['company_in_business'];

                    if (isset($_POST['dont_require_license'])) {
                        $dont_require_license = "Y";
                    } else {
                        $dont_require_license = "N";
                    }
                    $license_number = $_POST['license_number'];
                    $type_of_project = "";
                    if (isset($_POST['type_of_project'])) {
                        $type_of_project = implode(',', $_POST['type_of_project']);
                    }

                    $sql_tm_final_ins = "INSERT into agency_tm 
                                    SET
                                    user_id = '" . $user_id_ins . "',
                                    reg_city_represent_talent = '" . $reg_city_represent_talent . "',
                                    how_many_talent = '" . $how_many_talent . "',
                                    company_in_business = '" . $company_in_business . "',
                                    dont_require_license = '" . $dont_require_license . "',
                                    license_number = '" . $license_number . "',
                                    license_copy = '" . $license_copy . "',
                                    type_of_project = '" . $type_of_project . "'
                                ";

                    if (mysql_query($sql_tm_final_ins)) {
                        $notification['success'] = 'Your Profile Created Successfully.';
                        // <a href="login.php" class="color-theme">Click Here</a> TO Login
                    }
                }
            } else {
            }
        }
    }

    // echo "<pre>";
    // print_r($reg_city_tm);
    // print_r($how_many_talent);
    // print_r($company_in_business);
    // print_r($type_of_project);
    // echo "</pre>";
    ?>

    <div class="menu">
        <ul>
            <li><a href="talent_signup.php">TALENT</a></li>
            <li><a href="client_signup.php">CLIENT</a></li>
            <li><a href="agent-signup.php" class="active">TALENT MANAGER/AGENT</a></li>
        </ul>
    </div>

    <div class="signup-content">
        <div class="container">
            <div class="signup-form2">
                <h2>WELCOME</h2>
                <hr class="welcome">
                <h3>NEW AGENT / MANAGER SIGNUP </h3>
                <div class="col-sm-12">
                    <div class="form-box">
                        <h3> To create your Agent or Talent Manager account, please complete the form then allow up to 24hrs for processing and approval. <span>We will contact you shortly after your submission is complete.</span> </h3>
                    </div>
                    <br />

                    <!-- <div class="alert alert-success clearfix text-left" role="alert">
                   test test 
                </div>

                <div class="alert alert-danger clearfix text-left" role="alert">
                    test test tset 
                </div> -->

                    <?php if (isset($notification['success'])) { ?>
                        <div class="alert alert-success clearfix text-left" role="alert">
                            <?php echo $notification['success']; ?>
                        </div>
                    <?php } ?>
                    <?php if (isset($notification['error'])) { ?>
                        <div class="alert alert-danger clearfix text-left" role="alert">
                            <?php echo $notification['error']; ?>
                        </div>
                    <?php } ?>
                </div>

                <div>

                    <form action="" method="post" id="agent_reg_frm" enctype="multipart/form-data">

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="container-fluid text-left">

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input type="text" name="firstname" id="firstname" class="form-control-custom" placeholder="FIRST NAME:*" value="">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input type="text" name="lastname" id="lastname" class="form-control-custom" placeholder="LAST NAME:*" value="">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input type="text" name="email" id="email" class="form-control-custom" placeholder="EMAIL ADDRESS:*" value="">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input type="text" name="username" id="username" class="form-control-custom" placeholder="USER NAME:*" value="">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input type="password" name="password" id="password" class="form-control-custom" placeholder="PASSWORD:*" value="">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input type="password" name="confirm_password" id="confirm_password" class="form-control-custom" placeholder="CONFORM PASSWORD:*" value="">
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <input type="text" name="company" id="company" class="form-control-custom" placeholder="COMPANY NAME:*" value="">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input type="text" name="office_phone" id="office_phone" class="form-control-custom" placeholder="OFFICE PHONE:*" value="">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input type="text" name="phone" id="phone" class="form-control-custom" placeholder="CELL PHONE:*" value="">
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <input type="text" name="address" id="address" class="form-control-custom" placeholder="Company Address (Street Address):" value="">
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <input type="text" name="address2" id="address2" class="form-control-custom" placeholder="Address Line 2:" value="">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <input type="text" name="city" id="city" class="form-control-custom" placeholder="CITY:" value="">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <select name="state" class="form-control-custom">
                                                <option value="">Select State</option>
                                                <?php foreach ($stateList['US'] as $key => $val) { ?>
                                                    <option value="<?php echo $val; ?>"><?php echo $val; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <input type="text" name="zip" id="zip" class="form-control-custom" placeholder="ZIP Code:" value="">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="form-txt">
                            <hr>
                        </div>

                        <div class="container-fluid color-white text-left">
                            <div class="col-sm-12">
                                <div class="form-txt">
                                    <h4>ACCEPTABLE REFERENCES; CASTING DIRECTOR, PRODUCTION, AD AGENCY, AGENCY OR MANAGER.</h4>
                                    <br />
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label weight-normal">INDUSTRY REFERENCE #1 <span>*</span></label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="text" name="industry_ref[name][0]" class="form-control-custom" placeholder="NAME:*" value="">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label weight-normal"> INDUSTRY REFERENCE #1 <span>*</span></label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="text" name="industry_ref[phone][0]" class="form-control-custom" placeholder="PHONE NUMBER:*" value="">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label weight-normal">INDUSTRY REFERENCE #1 <span>*</span></label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="text" name="industry_ref[title][0]" class="form-control-custom" placeholder="TITLE:*" value="">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label weight-normal">INDUSTRY REFERENCE #2</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="text" name="industry_ref[name][1]" class="form-control-custom" placeholder="NAME:" value="">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label weight-normal"> INDUSTRY REFERENCE #2</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="text" name="industry_ref[phone][1]" class="form-control-custom" placeholder="PHONE NUMBER:" value="">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label weight-normal">INDUSTRY REFERENCE #2</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="text" name="industry_ref[title][1]" class="form-control-custom" placeholder="TITLE:" value="">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label weight-normal">WHAT REGION/CITIES DO YOU REPRESENT TALENT IN? </label>
                                </div>
                            </div>
                            <div class="col-sm-6 text-left">
                                <div class="form-group">
                                    <?php foreach ($reg_city_tm_ary as $val) { ?>
                                        <label class="color-white weight-normal">
                                            <input type="checkbox" name="reg_city_represent_talent[]" value="<?php echo $val; ?>" /> <?php echo $val; ?>
                                        </label> <br />
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label weight-normal text-left">HOW MANY TALENT DO YOU CURRENTLY REPRESENT? <span>*</span></label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <select name="how_many_talent" id="how_many_talent" class="form-control-custom">
                                        <option value="">Select</option>
                                        <?php foreach ($how_many_talent_ary as $val) { ?>
                                            <option value="<?php echo $val; ?>"><?php echo $val; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label weight-normal">HOW LONG HAS YOUR COMPANY BEEN IN BUSINESS? <span>*</span></label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <select name="company_in_business" id="company_in_business" class="form-control-custom">
                                        <option value="">Select</option>
                                        <?php foreach ($company_in_business_ary as $val) { ?>
                                            <option value="<?php echo $val; ?>"><?php echo $val; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-txt">
                            <hr>
                        </div>

                        <div class="container-fluid color-white text-left">
                            <div class="col-sm-12">
                                <label class="control-label weight-normal">I DO NOT REQUIRE A TALENT AGENCY LICENSE IN MY REGION:
                                    <input type="checkbox" name="dont_require_license" id="dont_require_license" value="" />
                                </label>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group account-row">
                                    <label class="control-label weight-normal">Your talent agency License number or bond number?
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="text" name="license_number" id="license_number" placeholder="License Number" value="" class="form-control-custom">
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <label class="control-label weight-normal">
                                    (All managers within California and several other states must have a surety bond or belong to Talent Managers Association to receive breakdowns or negotiate on behalf of talent.<span>*</span>)
                                </label>
                                <br /><br /><br />
                            </div>

                            <div class="col-sm-6">
                                <label class="control-label weight-normal">Please attach a copy of your license or bond. <span>*</span> </label>
                            </div>
                            <div class="col-sm-6">
                                <input type="file" name="license_copy" id="license_copy" class="form-control-custom" />
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label weight-normal">Specify which type of projects you represent talent for: <span>*</span></label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <?php foreach ($type_of_project_ary as $val) { ?>
                                        <label class="color-white weight-normal">
                                            <input type="checkbox" name="type_of_project[]" value="<?php echo $val; ?>" /> <?php echo $val; ?>
                                        </label> <br />
                                    <?php } ?>
                                    <span class="checkbox_err"></span>
                                </div>
                            </div>

                            <div class="col-sm-12 text-center">
                                <div class="col-sm-12 text-center" id="html_element">
                                </div>

                                <input type="submit" value="SUBMIT" name="submit" class="btn btn-theme btn-flat btn-lg">

                                <h4 class="red-txt">Once your agency has been added, all additional changes and requests can be sent to support@theagencyonline.com</h4>
                                <h4 class="red-txt"> All phone numbers, usernames and passwords on a talent’s profile can only be changed by their representation after receiving the talent’s permission.</h4>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
        <?php include('footer.php'); ?>

        <?php include('footer_js.php'); ?>
        <script src="js/jquery.validate.js"></script>

        <script>
            $.validator.addMethod("license_check_number", function(value, element) {
                if ($("#dont_require_license").is(":checked")) {
                    return true;
                } else {
                    if ($("#license_number").val() == "") {
                        return false;
                    } else {
                        return true;
                    }
                }
            }, "This field is required.");

            $.validator.addMethod("license_check_file", function(value, element) {
                if ($("#dont_require_license").is(":checked")) {
                    return true;
                } else {
                    if ($('#license_copy').val()) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }, "This field is required.");

            $("#agent_reg_frm").validate({
                rules: {
                    firstname: "required",
                    lastname: "required",
                    email: {
                        required: true,
                        email: true,
                        remote: {
                            url: "ajax/front_request.php",
                            type: "post",
                            data: {
                                name: 'user_email_unique_insert'
                            }
                        }
                    },
                    username: {
                        required: true,
                        remote: {
                            url: "ajax/front_request.php",
                            type: "post",
                            data: {
                                name: 'user_username_unique_insert'
                            }
                        }
                    },
                    password: {
                        required: true,
                        minlength: 6,
                        maxlength: 20,
                    },
                    confirm_password: {
                        required: true,
                        equalTo: "#password"
                    },
                    company: "required",
                    phone: {
                        required: true,
                        digits: true,
                    },
                    office_phone: {
                        required: true,
                        digits: true,
                    },
                    "industry_ref[name][0]": {
                        required: true,
                    },
                    "industry_ref[phone][0]": {
                        required: true,
                    },
                    "industry_ref[title][0]": {
                        required: true,
                    },
                    how_many_talent: {
                        required: true,
                    },
                    company_in_business: {
                        required: true,
                    },
                    license_number: {
                        license_check_number: true,
                    },
                    license_copy: {
                        license_check_file: true,
                    },
                    "type_of_project[]": {
                        required: true,
                    }
                },
                messages: {
                    email: {
                        remote: "Email already exist.",
                    },
                    username: {
                        remote: "Username already exist.",
                    }
                },
                errorElement: "em",
                errorPlacement: function(error, element) {
                    // Add the `help-block` class to the error element
                    error.addClass("err-jq");

                    if (element.prop("type") === "checkbox") {
                        // error.insertAfter(element.parent("label"));
                        error.insertAfter(element.parents('label').siblings('.checkbox_err'));
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).parents(".col-sm-5").addClass("has-error").removeClass("has-success");
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).parents(".col-sm-5").addClass("has-success").removeClass("has-error");
                },
                submitHandler: function() {
                    var response = grecaptcha.getResponse();
                    // console.log(response);
                    if (response == "") {
                        alert("Please Check Chapcha To Submit");
                        return false;
                    } else {
                        return true;
                    }
                    // alert( "submitted!" );
                }
            });
        </script>
        <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
        <script type="text/javascript">
            var onloadCallback = function() {
                grecaptcha.render('html_element', {
                    'sitekey': '<?php echo $recaptcha_site_key; ?>'
                });
            };
        </script>
        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
</body>

</html>