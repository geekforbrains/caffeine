<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">
<head>
    <title>Caffeine</title>
	<?php View::get_css(); ?>
	<?php View::get_js(); ?>
</head>
<body>
    <div id="header">
        <div class="center">
            <h1>CRM</h1>
            <span>
				<?php if(!stristr(Path::current(), 'admin/login')): ?>
					Logged in as <a href="#">Administrator</a> &nbsp;|&nbsp;
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
					<?php echo Menu::build('admin', 0, array('class' => 'left')); ?>
				<?php endif; ?>
            </div>
        </div>

        <div class="sub_tabs">
			<div class="center">
				<?php echo Menu::build('admin/%s', 0, array('class' => 'left')); ?>
			</div>
			<div class="clear"></div>
        </div>
    </div>

	<?php Message::display(); ?>
