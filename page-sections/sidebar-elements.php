<div class="container">
  <div class="row">
      <div class="col-md-3">
        <div class="border-box sidebar">
              <h2 class="heading heading__3">Hello <?=$_SESSION['name'];?></h2>
              <p class="prompt">Not you ?  Click <a href="#">here</a></p>
              <p class="last-login"><span>Last Login</span><?=$lastlogin;?></p>
              <a class="button button__raised" href="settings.php">Account Settings</a>
              <a class="button button__raised" href="#" data-toggle="modal" data-target="#logoutModal">Log Out</a>
              <a class="button button__raised" href="#"> Download as PDF</a>
        </div>
    </div>
