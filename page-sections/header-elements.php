<div class="container">
  <div class="row">
      <nav class="col-md-12">
          <div class="row">
              <div id="logo" class="col-md-2">
                  <?php define('__ROOT__', dirname(dirname(__FILE__)));
                  include(__ROOT__.'/client/images/fs-logo.php'); ?>
              </div>
              <div id="topmenu" class="col-md-10">
                  <div id="menuitems" class="main-menu">
                    <?php // get current page name
                    function PageName() {
                      return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
                    }
                    $current_page = PageName();?>
                      <a class="main-menu__item <?php echo $current_page == 'home.php' ? 'active':NULL ?>" href="home.php">Daily Valuation Data</a>
                      <a class="main-menu__item <?php echo $current_page == 'assets.php' ? 'active':NULL ?>" href="assets.php">Holdings &amp; Asset Allocation</a>
                      <a class="main-menu__item <?php echo $current_page == 'current_investment.php' ? 'active':NULL ?>" href="current_investment.php">Current Investment Themes</a>
                      <a class="main-menu__item <?php echo $current_page == 'peer_groups.php' ? 'active':NULL ?>" href="peer_groups.php">Peer Group Comparison</a>
                  </div>
              </div>
          </div>
      </nav>
  </div>
</div>
