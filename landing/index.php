<?php
session_start();

require_once '../core.php';
require_once "../lang/core.php";


?>
<!DOCTYPE html>
<html lang="en">
  <head>

    <title><?= $lang["title"] ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    <link href="https://fonts.googleapis.com/css?family=Muli:300,400,700,900" rel="stylesheet">
    <link rel="stylesheet" href="fonts/icomoon/style.css">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">

    <link rel="stylesheet" href="css/jquery.fancybox.min.css">

    <link rel="stylesheet" href="css/bootstrap-datepicker.css">

    <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">

    <link rel="stylesheet" href="css/aos.css">

    <link rel="stylesheet" href="css/style.css">

    <style>
    form#form-subscribe input.email{
      height: 43px;
    }
    </style>
    
  </head>
  <body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">
  
  <script>
      window.fbAsyncInit = function() {
          FB.init({
              appId      : '226440184828839',
              cookie     : true,
              xfbml      : true,
              version    : 'v3.0'
          });
      };
      (function(d, s, id){
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) {return;}
          js = d.createElement(s); js.id = id;
          js.src = "https://connect.facebook.net/en_US/sdk.js";
          fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));
  </script>

  <div class="site-wrap">

    <div class="site-mobile-menu site-navbar-target">
      <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close mt-3">
          <span class="icon-close2 js-menu-toggle"></span>
        </div>
      </div>
      <div class="site-mobile-menu-body"></div>
    </div>
   
    
    <header class="site-navbar py-4 js-sticky-header site-navbar-target" role="banner">
      
      <div class="container-fluid">
        <div class="d-flex align-items-center">
          <div class="site-logo mr-auto w-25"><a href="/">LazyLearn</a></div>

          <div class="mx-auto text-center">
            <nav class="site-navigation position-relative text-right" role="navigation">
              <ul class="site-menu main-menu js-clone-nav mx-auto d-none d-lg-block  m-0 p-0">
                <li><a href="#home-section" class="nav-link"><?= $lang["landing.head.home"] ?></a></li>
                <li><a href="#programs-section" class="nav-link"><?= $lang["landing.head.program"] ?></a></li>
                <li><a href="#teachers-section" class="nav-link"><?= $lang["landing.head.supervisor"] ?></a></li>
                <li><a href="#contact-section" class="nav-link"><?= $lang["landing.head.contactus"] ?></a></li>
              </ul>
            </nav>
          </div>

          <div class="ml-auto w-25">
            <nav class="site-navigation position-relative text-right" role="navigation">
              <ul class="site-menu main-menu site-menu-dark js-clone-nav mr-auto d-none d-lg-block m-0 p-0">
                <!-- <li class="cta"><a href="#contact-section" class="nav-link"><span><?= $lang["landing.contactus"] ?></span></a></li> -->
                <li class="cta">
                  <a href="#language-section" class="nav-link">
                    <span>
                      <img src="/lang/en_US.png">
                    </span>
                  </a>
                </li>
              </ul>
            </nav>
            <a href="#" class="d-inline-block d-lg-none site-menu-toggle js-menu-toggle text-black float-right"><span class="icon-menu h3"></span></a>
          </div>
        </div>
      </div>
      
    </header>

    <div class="intro-section" id="home-section">
      
      <div class="slide-1" style="background-image: url('images/hero_1.jpg');" data-stellar-background-ratio="0.5">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-12">
              <div class="row align-items-center">
                <div class="col-lg-6 mb-4">
                  <h1  data-aos="fade-up" data-aos-delay="100"><?= $lang["landing.first.title"] ?></h1>
                  <p class="mb-4"  data-aos="fade-up" data-aos-delay="200"><?= $lang["landing.first.content"] ?></p>
                  <p data-aos="fade-up" data-aos-delay="300"><a href="/auth/login.php" class="btn btn-primary py-3 px-5 btn-pill"><?= $lang["landing.first.loginnow"] ?></a></p>

                </div>

                <div class="col-lg-5 ml-auto" data-aos="fade-up" data-aos-delay="500">
                  <form id="form-register" action="" method="post" class="form-box">
                    <h3 class="h4 text-black mb-4"><?= $lang["landing.first.signupform.title"] ?></h3>
                    <div class="form-group">
                      <input class="email" type="text" required class="form-control" placeholder='<?= $lang["landing.first.signupform.email"] ?>'>
                    </div>
                    <div class="form-group">
                      <input class="password1" type="password" required class="form-control" placeholder='<?= $lang["landing.first.signupform.pass"] ?>'>
                    </div>
                    <div class="form-group mb-4">
                      <input class="password2" type="password" required class="form-control" placeholder='<?= $lang["landing.first.signupform.repass"] ?>'>
                    </div>
                    <div class="form-group">
                      <input type="submit" class="btn btn-primary btn-pill" value='<?= $lang["landing.first.signupform.submit"] ?>'>
                      <!-- <button class="facebook-login btn btn-primary btn-pill">
                        <i class="fa fa-facebook-official" aria-hidden="true"></i>
                        <?= $lang["landing.first.signupform.fb"] ?>
                      </button> -->
                    </div>
                  </form>

                </div>
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </div>

    <div class="site-section" id="programs-section">
      <div class="container">
        <div class="row mb-5 justify-content-center">
          <div class="col-lg-7 text-center"  data-aos="fade-up" data-aos-delay="">
            <h2 class="section-title"><?= $lang["landing.program.title"] ?></h2>
            <p><?= $lang["landing.program.content"] ?></p>
          </div>
        </div>
        <div class="row mb-5 align-items-center">
          <div class="col-lg-7 mb-5" data-aos="fade-up" data-aos-delay="100">
            <img src="images/new/flashcard_01.png" alt="Image" class="img-fluid">
          </div>
          <div class="col-lg-4 ml-auto" data-aos="fade-up" data-aos-delay="200">
            <h2 class="text-black mb-4"><?= $lang["landing.program.1.title"] ?></h2>
            <p class="mb-4"><?= $lang["landing.program.1.content"] ?></p>

            <!-- <div class="d-flex align-items-center custom-icon-wrap mb-3">
              <span class="custom-icon-inner mr-3"><span class="icon icon-graduation-cap"></span></span>
              <div><h3 class="m-0">22,931 Yearly Graduates</h3></div>
            </div>

            <div class="d-flex align-items-center custom-icon-wrap">
              <span class="custom-icon-inner mr-3"><span class="icon icon-university"></span></span>
              <div><h3 class="m-0">150 Universities Worldwide</h3></div>
            </div> -->

          </div>
        </div>

        <div class="row mb-5 align-items-center">
          <div class="col-lg-7 mb-5 order-1 order-lg-2" data-aos="fade-up" data-aos-delay="100">
            <img src="images/new/deep_01.jpg" alt="Image" class="img-fluid">
          </div>
          <div class="col-lg-4 mr-auto order-2 order-lg-1" data-aos="fade-up" data-aos-delay="200">
            <h2 class="text-black mb-4"><?= $lang["landing.program.2.title"] ?></h2>
            <p class="mb-4"><?= $lang["landing.program.2.content"] ?></p>

            <!-- <div class="d-flex align-items-center custom-icon-wrap mb-3">
              <span class="custom-icon-inner mr-3"><span class="icon icon-graduation-cap"></span></span>
              <div><h3 class="m-0">22,931 Yearly Graduates</h3></div>
            </div>

            <div class="d-flex align-items-center custom-icon-wrap">
              <span class="custom-icon-inner mr-3"><span class="icon icon-university"></span></span>
              <div><h3 class="m-0">150 Universities Worldwide</h3></div>
            </div> -->

          </div>
        </div>

        <div class="row mb-5 align-items-center">
          <div class="col-lg-7 mb-5" data-aos="fade-up" data-aos-delay="100">
            <img src="images/new/brain_01.png" alt="Image" class="img-fluid">
          </div>
          <div class="col-lg-4 ml-auto" data-aos="fade-up" data-aos-delay="200">
            <h2 class="text-black mb-4"><?= $lang["landing.program.3.title"] ?></h2>
            <p class="mb-4"><?= $lang["landing.program.3.content"] ?></p>

            <!-- <div class="d-flex align-items-center custom-icon-wrap mb-3">
              <span class="custom-icon-inner mr-3"><span class="icon icon-graduation-cap"></span></span>
              <div><h3 class="m-0">22,931 Yearly Graduates</h3></div>
            </div>

            <div class="d-flex align-items-center custom-icon-wrap">
              <span class="custom-icon-inner mr-3"><span class="icon icon-university"></span></span>
              <div><h3 class="m-0">150 Universities Worldwide</h3></div>
            </div> -->

          </div>
        </div>

      </div>
    </div>

    <div class="site-section" id="teachers-section">
      <div class="container">

        <div class="row mb-5 justify-content-center">
          <div class="col-lg-7 mb-5 text-center"  data-aos="fade-up" data-aos-delay="">
            <h2 class="section-title"><?= $lang["landing.supervisor.title"] ?></h2>
            <p class="mb-5"><?= $lang["landing.supervisor.content"] ?></p>
          </div>
        </div>

        <div class="row">

          <div class="offset-lg-2 offset-md-3     col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="100">
            <div class="teacher text-center">
              <img src="images/person_2.jpg" alt="Image" class="img-fluid w-50 rounded-circle mx-auto mb-4">
              <div class="py-2">
                <h3 class="text-black"><?= $lang["landing.supervisor.1.name"] ?></h3>
                <p class="position"><?= $lang["landing.supervisor.1.title"] ?></p>
                <p><?= $lang["landing.supervisor.1.bio"] ?></p>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="100">
            <div class="teacher text-center">
              <img src="images/person_3.jpg" alt="Image" class="img-fluid w-50 rounded-circle mx-auto mb-4">
              <div class="py-2">
                <h3 class="text-black"><?= $lang["landing.supervisor.2.name"] ?></h3>
                <p class="position"><?= $lang["landing.supervisor.2.title"] ?></p>
                <p><?= $lang["landing.supervisor.2.bio"] ?></p>
              </div>
            </div>
          </div>

          <!--
          <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="300">
            <div class="teacher text-center">
              <img src="images/person_3.jpg" alt="Image" class="img-fluid w-50 rounded-circle mx-auto mb-4">
              <div class="py-2">
                <h3 class="text-black">Sadie White</h3>
                <p class="position">Physics Teacher</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Porro eius suscipit delectus enim iusto tempora, adipisci at provident.</p>
              </div>
            </div>
          </div>
          -->

        </div>
      </div>
    </div>

    <div class="site-section bg-image overlay" style="background-image: url('images/hero_1.jpg');">
      <div class="container">
        <div class="row justify-content-center align-items-center">
          <div class="col-md-8 text-center testimony">
            <img src="images/new/marty_01.jpeg" alt="Image" class="img-fluid w-25 mb-4 rounded-circle">
            <h3 class="mb-4"><?= $lang["landing.boss.name"] ?></h3>
            <blockquote>
              <p>&ldquo; <?= $lang["landing.boss.bio"] ?> &rdquo;</p>
            </blockquote>
          </div>
        </div>
      </div>
    </div>

    <!-- Why us
    <div class="site-section pb-0">

      <div class="future-blobs">
        <div class="blob_2">
          <img src="images/blob_2.svg" alt="Image">
        </div>
        <div class="blob_1">
          <img src="images/blob_1.svg" alt="Image">
        </div>
      </div>
      <div class="container">
        <div class="row mb-5 justify-content-center" data-aos="fade-up" data-aos-delay="">
          <div class="col-lg-7 text-center">
            <h2 class="section-title">Why Choose Us</h2>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-4 ml-auto align-self-start"  data-aos="fade-up" data-aos-delay="100">

            <div class="p-4 rounded bg-white why-choose-us-box">

              <div class="d-flex align-items-center custom-icon-wrap custom-icon-light mb-3">
                <div class="mr-3"><span class="custom-icon-inner"><span class="icon icon-graduation-cap"></span></span></div>
                <div><h3 class="m-0">22,931 Yearly Graduates</h3></div>
              </div>

              <div class="d-flex align-items-center custom-icon-wrap custom-icon-light mb-3">
                <div class="mr-3"><span class="custom-icon-inner"><span class="icon icon-university"></span></span></div>
                <div><h3 class="m-0">150 Universities Worldwide</h3></div>
              </div>

              <div class="d-flex align-items-center custom-icon-wrap custom-icon-light mb-3">
                <div class="mr-3"><span class="custom-icon-inner"><span class="icon icon-graduation-cap"></span></span></div>
                <div><h3 class="m-0">Top Professionals in The World</h3></div>
              </div>

              <div class="d-flex align-items-center custom-icon-wrap custom-icon-light mb-3">
                <div class="mr-3"><span class="custom-icon-inner"><span class="icon icon-university"></span></span></div>
                <div><h3 class="m-0">Expand Your Knowledge</h3></div>
              </div>

              <div class="d-flex align-items-center custom-icon-wrap custom-icon-light mb-3">
                <div class="mr-3"><span class="custom-icon-inner"><span class="icon icon-graduation-cap"></span></span></div>
                <div><h3 class="m-0">Best Online Teaching Assistant Courses</h3></div>
              </div>

              <div class="d-flex align-items-center custom-icon-wrap custom-icon-light">
                <div class="mr-3"><span class="custom-icon-inner"><span class="icon icon-university"></span></span></div>
                <div><h3 class="m-0">Best Teachers</h3></div>
              </div>

            </div>


          </div>
          <div class="col-lg-7 align-self-end"  data-aos="fade-left" data-aos-delay="200">
            <img src="images/person_transparent.png" alt="Image" class="img-fluid">
          </div>
        </div>
      </div>
    </div>
    -->
    



    <div class="site-section bg-light" id="contact-section">
      <div class="container">

        <div class="row justify-content-center">
          <div class="col-md-7">


            
            <h2 class="section-title mb-3"><?= $lang["landing.first.contactform.title"] ?></h2>
            <p class="mb-5"><?= $lang["landing.first.contactform.content"] ?></p>
          
            <form id="form-contact" method="post" data-aos="fade">
              <div class="form-group row">
                <div class="col-md-6 mb-3 mb-lg-0">
                  <input type="text" class="firstName form-control" placeholder='<?= $lang["landing.first.contactform.placeholder.first_name"] ?>'>
                </div>
                <div class="col-md-6">
                  <input type="text" class="lastName form-control" placeholder='<?= $lang["landing.first.contactform.placeholder.last_name"] ?>'>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-md-12">
                  <input type="text" class="subject form-control" placeholder='<?= $lang["landing.first.contactform.placeholder.subject"] ?>'>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-md-12">
                  <input type="text" class="email form-control" placeholder='<?= $lang["landing.first.contactform.placeholder.email"] ?>'>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-12">
                  <textarea class="content form-control" id="" cols="30" rows="10" placeholder='<?= $lang["landing.first.contactform.placeholder.content"] ?>'></textarea>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-md-6">
                  
                  <input type="submit" class="btn btn-primary py-3 px-5 btn-block btn-pill" value='<?= $lang["landing.first.contactform.submit"] ?>'>
                </div>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
    
     
    <footer class="footer-section bg-white">
      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <h3><?= $lang["landing.about.title"] ?></h3>
            <p><?= $lang["landing.about.content"] ?></p>
          </div>

          <div class="col-md-3 ml-auto" id="language-section">
            <h3>Language</h3>
            <ul class="list-unstyled footer-links">
              <li class="cta lang" data-lang="vi_VN"><a href="#" class="nav-link"><img src="/lang/vi_VN.png">&nbsp;&nbsp;Tiếng Việt</a></li>
              <li class="cta lang" data-lang="en_US"><a href="#" class="nav-link"><img src="/lang/en_US.png">&nbsp;&nbsp;English</a></li>
            </ul>
          </div>

          <div class="col-md-4">
            <h3><?= $lang["landing.first.subscribeform.title"] ?></h3>
            <p><?= $lang["landing.first.subscribeform.content"] ?></p>
            <form id="form-subscribe" action="#" class="footer-subscribe">
              <div class="d-flex mb-5">
                <input type="text" class="email form-control rounded-0" placeholder='<?= $lang["landing.first.subscribeform.placeholder.email"] ?>'>
                <input type="submit" class="btn btn-primary rounded-0" value='<?= $lang["landing.first.subscribeform.submit"] ?>'>
              </div>
            </form>
          </div>

        </div>

        <div class="row pt-5 mt-5 text-center">
          <div class="col-md-12">
            <div class="border-top pt-5">
            <p>
        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
        Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="icon-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank" >Colorlib</a>
        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
      </p>
            </div>
          </div>
          
        </div>
      </div>
    </footer>

  
    
  </div> <!-- .site-wrap -->

  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/jquery.countdown.min.js"></script>
  <script src="js/bootstrap-datepicker.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/jquery.fancybox.min.js"></script>
  <script src="js/jquery.sticky.js"></script>

  <script src="js/main.js"></script>

  <!-- ======================================================== -->
  <!-- =======================  API  ========================== -->
  <!-- ======================================================== -->
  <script>
    var apiServer = '<?=$API_SERVER?>';
  </script>

  <script src="/node_modules/jstorage/jstorage.min.js"></script>
  <script src="<?=$ASSET?>/Storage.js"></script>

  <script src="/node_modules/axios/dist/axios.min.js"></script>

  <script src="/external/Holdon/HoldOn.min.js"></script>
  <link href="/external/Holdon/HoldOn.min.css" rel="stylesheet">

  <script src="/external/flashjs/dist/flash.min.js"></script>
  <link href="/external/flashjs/dist/flash.min.css" rel="stylesheet">
  <link href="/node_modules/font-awesome/css/font-awesome.min.css" rel="stylesheet">

  <script src="<?=$ASSET?>/Constant.js"></script>
  <script src="<?=$ASSET?>/AppApi.js"></script>
  <script src="<?=$ASSET?>/Auth.js"></script>
  <script src="<?=$ASSET?>/ContactForm.js"></script>

  <script>
    $(document).on('click', 'li.lang', function(event){
        event.preventDefault();
        const lang = $(this).attr('data-lang');
        axios.get("/lang/change.php?lang=" + lang).finally(()=>{
          location.reload();
        });
    });
    $(document).on('submit', 'form#form-register', function(event){
        event.preventDefault();
        const email = $(this).find("input.email").val();
        const password1 = $(this).find("input.password1").val();
        const password2 = $(this).find("input.password2").val();
        if (password1 != password2){
          FlashMessage.error('<?= $lang["landing.first.signupform.msg.password_not_matched"] ?>');
          return;
        }
        Auth.register(email, password1);
    });
    $(document).on('click', 'form#form-register button.facebook-login', function(event){
      event.preventDefault();
      Auth.loginFacebook();
    });
    $(document).on('submit', 'form#form-contact', function(event){
        event.preventDefault();
        const firstName = $(this).find("input.firstName").val();
        const lastName = $(this).find("input.lastName").val();
        const email = $(this).find("input.email").val();
        const subject = $(this).find("input.subject").val();
        const content = $(this).find("textarea.content").val();
        ContactForm.submitContact({ firstName, lastName, email, subject, content }).then(response=>{
          $(this)[0].reset();
          FlashMessage.success('<?= $lang["landing.first.contactform.success_msg"] ?>');
        });
    });
    $(document).on('submit', 'form#form-subscribe', function(event){
        event.preventDefault();
        const email = $(this).find("input.email").val();
        ContactForm.submitSubscribe({ email }).then(response=>{
          $(this)[0].reset();
          FlashMessage.success('<?= $lang["landing.first.subscribeform.success_msg"] ?>');
        });
    });    
  </script>
  <!-- ======================================================== -->
  <!-- ======================================================== -->
  <!-- ======================================================== -->

  </body>
</html>