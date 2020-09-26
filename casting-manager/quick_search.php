<div class="info-box casted">
  <div class="box-body no-padding">
    <label>QUICK TALENT SEARCH</label>

    <!-- <p style="float: right; color: #00BCD4;"><i class="fa fa-circle-o" aria-hidden="true"></i></p>
    <p style="float: right; padding-right: 20px; color: #00BCD4; font-weight: 700;">
      Location <i class="fa fa-angle-down"> </i> &nbsp;
    </p> -->

    <form action="clienthome.php" method="post" name="searchform">
      <div class="row">
        <div class="col-sm-6">
          <label class="font-weight-normal" style="margin:10px 0 0 0;">Age</label>
          <select class="form-control" name="age_start">
            <option value="">From</option>
            <?php for ($i = 1; $i <= 120; $i++) { ?>
              <option value="<?php echo $i; ?>" <?php if (isset($_SESSION['t_search']['age_start']) && $_SESSION['t_search']['age_start'] == $i) {
                                                  echo "selected";
                                                } ?>><?php echo $i; ?></option>
            <?php } ?>
          </select>
        </div>

        <div class="col-sm-6">
          <label class="font-weight-normal" style="margin:10px 0 0 0;">&nbsp;</label>
          <select class="form-control" name="age_end">
            <option value="">To</option>
            <?php for ($i = 1; $i <= 120; $i++) { ?>
              <option value="<?php echo $i; ?>" <?php if (isset($_SESSION['t_search']['age_end']) && $_SESSION['t_search']['age_end'] == $i) {
                                                  echo "selected";
                                                } ?>><?php echo $i; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>

      <div style="width:100%;">
        <label class="font-weight-normal" style="margin:10px 0 0 0;">Gender</label>
        <select name="gender" class="form-control">
          <option value=""> Any</option>
          <option value="M" <?php if (isset($_SESSION['t_search']['gender']) && $_SESSION['t_search']['gender'] == "M") {
                              echo "selected";
                            } ?>> Male</option>
          <option value="F" <?php if (isset($_SESSION['t_search']['gender']) && $_SESSION['t_search']['gender'] == "F") {
                              echo "selected";
                            } ?>> Female</option>
          <option value="Transgender" <?php if (isset($_SESSION['t_search']['gender']) && $_SESSION['t_search']['gender'] == "Transgender") {
                                        echo "selected";
                                      } ?>> Transgender</option>
        </select>
      </div>

      <div style="width:100%;">
        <label class="font-weight-normal" style="margin:10px 0 0 0;">Ethnicity</label>
        <select name="ethnicity" class="form-control">
          <option value="">Any</option>
          <?php foreach ($ethnicityarray as $val) { ?>
            <option value="<?php echo $val; ?>" <?php if (isset($_SESSION['t_search']['ethnicity']) && $_SESSION['t_search']['ethnicity'] == $val) {
                                                  echo "selected";
                                                } ?>> <?php echo $val; ?></option>
          <?php } ?>
        </select>
      </div>
      <div style="width:100%;">
        <label class="font-weight-normal" style="margin:10px 0 0 0;">Location</label>
        <select name="location" class="form-control">
          <option value="">Any</option>
          <?php foreach ($locationarray as $val) { ?>
            <option value="<?php echo $val; ?>" <?php if (isset($_SESSION['t_search']['location']) && $_SESSION['t_search']['location'] == $val) {
                                                  echo "selected";
                                                } ?>> <?php echo $val; ?></option>
          <?php } ?>
        </select>
      </div>
      <br style="clear:both" />
      <div style="width:100%;">
        <input value="Search" name="quick_serach" type="submit" class="serch-btn">
      </div>
    </form>
  </div>
</div>