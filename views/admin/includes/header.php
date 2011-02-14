<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">
<head>
	<base href="<?php echo View::theme_url(); ?>/" />
    <title><?php echo View::get_title('Control Panel', 'Control Panel | '); ?></title>
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.7.2.custom.css" />
	<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.7.2.custom.min.js"></script>
	<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
	<script type="text/javascript" src="js/admin.js"></script>
</head>
<body>
    <div id="header">
        <div class="center">
            <h1>Control Panel</h1>
            <span>
				<?php if(!stristr(Path::current(), 'admin/login')): ?>
					<?php $user = User::get_current(); ?>
					Logged in as <a href="<?php l('admin/admin/user/edit/%d', $user['id']); ?>"><?php echo $user['username']; ?></a> &nbsp;|&nbsp;
					<a href="<?php echo Router::url('admin/logout'); ?>">Logout</a>
				<?php endif; ?>
            </span>

            <div class="tabs">
				<?php if(stristr(Path::current(), 'admin/login')): ?>
					<ul class="left">
						<li class="active"><a href="#">Login</a></li>
						<li><a href="#">Reset Password</a></li>
					</ul>
				<?php else: ?>
					<?php echo Menu::build('admin', 0, array('admin/auth', 'admin/user', 'admin/admin'), 
						array('class' => 'left')); ?>

					<ul class="right">
						<li <?php echo (stristr(Path::current(), 'admin/admin')) ? 'class="active"' : ''; ?>>
							<a href="<?php l('admin/admin'); ?>">Administration</a>
						</li>
					</ul>
				<?php endif; ?>
            </div>
        </div>

        <div class="sub_tabs">
			<div class="center">
				<?php echo Menu::build('admin/%s', 0, array(), array('class' => 'left')); ?>
			</div>
			<div class="clear"></div>
        </div>
    </div>

	<?php Message::display(); ?>
