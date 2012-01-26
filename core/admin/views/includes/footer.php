			<div class="clear"></div>
			<div class="grid_12" id="site_info">
				<div class="box">
                    <p>
                        System time: <?php echo date('M jS - g:i:s A'); ?> (<?php echo Config::get('system.timezone'); ?>)<br />
                        Powered by 
                        <?php Html::a('Caffeine ' . VERSION, 'http://github.com/geekforbrains/caffeine', array(
                            'title' => 'Caffeine on GitHub',
                            'target' => '_blank'
                        )); ?>
                    </p>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui.js"></script>
		<script type="text/javascript" src="js/jquery-fluid16.js"></script>
	</body>
</html>
