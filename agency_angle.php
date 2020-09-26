<?php
include('header_code.php');
?>

<!DOCTYPE html>
<html>

<head>
  <title>Agency Angle</title>
  <?php include('head.php'); ?>
  <?php include('common_css.php'); ?>
</head>

<body>
  <?php include('header.php'); ?>
  <div class="container-fluid breadcrumb-box text-center">
    <ul class="btn-group breadcrumb">
      <li><a href="<?php echo $base_url; ?>" class="">Home</a></li>
      <li><a class="">Agency Angle</a></li>
  </div>
  </div>

  <!-- <div class="trap">
      <div class="trapezoid2">
        <div class="casting">
          <a class="call-button" href="#">NEW FEATURES:</a>
          <a class="call-button2" href="#">THE BASICS:</a>
        </div>
      </div>
      <div class="trapezoid3">
        <div class="casting2">

        </div>
      </div>
    </div> -->

  <div style="" class="right_cross_img_box">
    <div class="container-fluid">
      <h1>Agency Angle</h1>
    </div>
  </div>

  <br/><br/><br/>
  
  <div class="conatiner-fluid border-box-wrapper sp-box">
    <div class="col-sm-12">

        <!-- Feature Video Posts Area -->
        <div class="feature-video-posts mb-30">
            <!-- Section Title -->
            <div class="section-heading">
                <h5></h5>
            </div>

            <div class="featured-video-posts">
                <div class="row">
                    <div class="col-12 col-lg-2">
                    </div>
                    <div class="col-12 col-lg-8">
                        <!-- Single Featured Post -->
                        <?php
                          $article_sql = "SELECT * FROM agency_article WHERE status = 'approved' ORDER BY article_id DESC LIMIT 20";
                          $article_query = mysql_query($article_sql);
                          if (mysql_num_rows($article_query) > 0) {
                            while ($row = mysql_fetch_assoc($article_query)) {
                        ?>
                        
                        <div class="single-featured-post">
                            <!-- Thumbnail -->
                            <div class="post-thumbnail mb-50">
                                <a href="<?php echo 'post_single.php?post_id='.$row['article_id']; ?>">
                                    <?php if($row['featured_image'] != '' &&  file_exists('./uploads/featured_image/' . $row['featured_image'])){ ?>
                                        <img src="<?php echo 'uploads/featured_image/' . $row['featured_image']; ?>" class="img-responsive"/>
                                    <?php } ?>
                                </a>
                            </div>
                            <!-- Post Contetnt -->
                            <div class="post-content">
                                <div class="post-meta">
                                  <h4> <?php echo date('M d, Y',strtotime($row['publish_date'])); ?> </h4>
                                  <!-- <a href="#">lifestyle</a> -->
                                </div>
                                <h3 class="post-title">
                                    <a href="<?php echo 'post_single.php?post_id=article_id'.$row; ?>">
                                        <?php echo $row['title']; ?>
                                    </a>
                                </h3>
                                <p><?php echo $row['content']; ?></p>
                            </div>
                            <!-- Post Share Area -->
                            <div class="post-share-area d-flex align-items-center justify-content-between">
                                <!-- Post Meta -->
                                <!-- <div class="post-meta pl-3">
                                    <a href="#"><i class="fa fa-eye" aria-hidden="true"></i> 1034</a>
                                    <a href="#"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> 834</a>
                                    <a href="#"><i class="fa fa-comments-o" aria-hidden="true"></i> 234</a>
                                </div> -->
                                <!-- Share Info -->
                                <!-- <div class="share-info"> -->
                                    <!-- <a href="#" class="sharebtn"><i class="fa fa-share-alt" aria-hidden="true"></i></a> -->
                                    <!-- All Share Buttons -->
                                    <!-- <div class="all-share-btn d-flex">
                                        <a href="#" class="facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                                        <a href="#" class="twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                                        <a href="#" class="google-plus"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
                                        <a href="#" class="instagram"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                                    </div> -->
                                <!-- </div> -->
                            </div>
                        </div>

                        <hr/> 

                        <?php 
                            } 
                          }
                        ?>
                    </div>

                    <!-- <div class="col-12 col-lg-3">
                        <div class="featured-video-posts-slide owl-carousel">

                            <div class="single--slide">
                                <div class="single-blog-post d-flex style-3">
                                    <div class="post-thumbnail">
                                        <img src="img/bg-img/23.jpg" alt="">
                                    </div>
                                    <div class="post-content">
                                        <a href="single-post.html" class="post-title">Global Resorts Network Grn Putting Timeshares To Shame</a>
                                        <div class="post-meta d-flex">
                                            <a href="#"><i class="fa fa-eye" aria-hidden="true"></i> 1034</a>
                                            <a href="#"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> 834</a>
                                            <a href="#"><i class="fa fa-comments-o" aria-hidden="true"></i> 234</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-blog-post d-flex style-3">
                                    <div class="post-thumbnail">
                                        <img src="img/bg-img/24.jpg" alt="">
                                    </div>
                                    <div class="post-content">
                                        <a href="single-post.html" class="post-title">A Guide To Rocky Mountain Vacations</a>
                                        <div class="post-meta d-flex">
                                            <a href="#"><i class="fa fa-eye" aria-hidden="true"></i> 1034</a>
                                            <a href="#"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> 834</a>
                                            <a href="#"><i class="fa fa-comments-o" aria-hidden="true"></i> 234</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-blog-post d-flex style-3">
                                    <div class="post-thumbnail">
                                        <img src="img/bg-img/25.jpg" alt="">
                                    </div>
                                    <div class="post-content">
                                        <a href="single-post.html" class="post-title">American Standards And European Culture How To Avoid</a>
                                        <div class="post-meta d-flex">
                                            <a href="#"><i class="fa fa-eye" aria-hidden="true"></i> 1034</a>
                                            <a href="#"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> 834</a>
                                            <a href="#"><i class="fa fa-comments-o" aria-hidden="true"></i> 234</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-blog-post d-flex style-3">
                                    <div class="post-thumbnail">
                                        <img src="img/bg-img/26.jpg" alt="">
                                    </div>
                                    <div class="post-content">
                                        <a href="single-post.html" class="post-title">Mother Earth Hosts Our Travels</a>
                                        <div class="post-meta d-flex">
                                            <a href="#"><i class="fa fa-eye" aria-hidden="true"></i> 1034</a>
                                            <a href="#"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> 834</a>
                                            <a href="#"><i class="fa fa-comments-o" aria-hidden="true"></i> 234</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-blog-post d-flex style-3">
                                    <div class="post-thumbnail">
                                        <img src="img/bg-img/27.jpg" alt="">
                                    </div>
                                    <div class="post-content">
                                        <a href="single-post.html" class="post-title">From Wetlands To Canals And Dams Amsterdam Is Alive</a>
                                        <div class="post-meta d-flex">
                                            <a href="#"><i class="fa fa-eye" aria-hidden="true"></i> 1034</a>
                                            <a href="#"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> 834</a>
                                            <a href="#"><i class="fa fa-comments-o" aria-hidden="true"></i> 234</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single--slide">
                                <div class="single-blog-post d-flex style-3">
                                    <div class="post-thumbnail">
                                        <img src="img/bg-img/23.jpg" alt="">
                                    </div>
                                    <div class="post-content">
                                        <a href="single-post.html" class="post-title">Global Resorts Network Grn Putting Timeshares To Shame</a>
                                        <div class="post-meta d-flex">
                                            <a href="#"><i class="fa fa-eye" aria-hidden="true"></i> 1034</a>
                                            <a href="#"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> 834</a>
                                            <a href="#"><i class="fa fa-comments-o" aria-hidden="true"></i> 234</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-blog-post d-flex style-3">
                                    <div class="post-thumbnail">
                                        <img src="img/bg-img/24.jpg" alt="">
                                    </div>
                                    <div class="post-content">
                                        <a href="single-post.html" class="post-title">A Guide To Rocky Mountain Vacations</a>
                                        <div class="post-meta d-flex">
                                            <a href="#"><i class="fa fa-eye" aria-hidden="true"></i> 1034</a>
                                            <a href="#"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> 834</a>
                                            <a href="#"><i class="fa fa-comments-o" aria-hidden="true"></i> 234</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-blog-post d-flex style-3">
                                    <div class="post-thumbnail">
                                        <img src="img/bg-img/25.jpg" alt="">
                                    </div>
                                    <div class="post-content">
                                        <a href="single-post.html" class="post-title">American Standards And European Culture How To Avoid</a>
                                        <div class="post-meta d-flex">
                                            <a href="#"><i class="fa fa-eye" aria-hidden="true"></i> 1034</a>
                                            <a href="#"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> 834</a>
                                            <a href="#"><i class="fa fa-comments-o" aria-hidden="true"></i> 234</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-blog-post d-flex style-3">
                                    <div class="post-thumbnail">
                                        <img src="img/bg-img/26.jpg" alt="">
                                    </div>
                                    <div class="post-content">
                                        <a href="single-post.html" class="post-title">Mother Earth Hosts Our Travels</a>
                                        <div class="post-meta d-flex">
                                            <a href="#"><i class="fa fa-eye" aria-hidden="true"></i> 1034</a>
                                            <a href="#"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> 834</a>
                                            <a href="#"><i class="fa fa-comments-o" aria-hidden="true"></i> 234</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-blog-post d-flex style-3">
                                    <div class="post-thumbnail">
                                        <img src="img/bg-img/27.jpg" alt="">
                                    </div>
                                    <div class="post-content">
                                        <a href="single-post.html" class="post-title">From Wetlands To Canals And Dams Amsterdam Is Alive</a>
                                        <div class="post-meta d-flex">
                                            <a href="#"><i class="fa fa-eye" aria-hidden="true"></i> 1034</a>
                                            <a href="#"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> 834</a>
                                            <a href="#"><i class="fa fa-comments-o" aria-hidden="true"></i> 234</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div> -->

                </div>
            </div>
        </div>

    </div>
  </div>

  <span class="clearfix"></span>
  <br/>
  
  <?php
  include('footer_js.php');
  include('footer.php');
  ?>

</body>

</html>