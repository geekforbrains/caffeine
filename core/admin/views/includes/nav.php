<? if(User::current()->hasPermission('admin.access')): ?>
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>

          <div class="btn-group pull-right">
            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
              <i class="icon-user"></i> <?= User::current()->email; ?>
              <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <li><a href="#">Profile</a></li>
              <li class="divider"></li>
              <li><a href="<?= Url::to('admin/logout'); ?>">Logout</a></li>
            </ul>
          </div>

          <div class="nav-collapse">
            <?= Menu::build(1, 'admin', array('dropdowns' => true, 'attributes' => array('class' => 'nav'))); ?>
          </div><!--/.nav-collapse -->

        </div>
      </div>
    </div>
<? endif; ?>
