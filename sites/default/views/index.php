<h1>Index</h1>

<pre>
Url::scheme: <?php echo Url::scheme(); ?><br />
Url::host: <?php echo Url::host(); ?><br />
Url::base: <?php echo Url::base(); ?><br />
Url::current: <?php echo Url::current(); ?><br />
Url::segments: <?php print_r(Url::segments()); ?><br />
Url::segment: <?php echo Url::segment(0); ?><br />
Url::to: <?php echo Url::to('blog'); ?><br />
Url::to: <?php echo Url::to('/'); ?><br />
Url::to: <?php echo Url::to('/page/some-page'); ?><br />
Url::isCurrent('blog'): <?php echo (Url::isCurrent('blog')) ? 'True' : 'False'; ?><br />
Url::isCurrent('/'): <?php echo (Url::isCurrent('/')) ? 'True' : 'False'; ?><br />
Url::isIndex: <?php echo (Url::isIndex()) ? 'True' : 'False'; ?><br />
Url::toLang: <?php echo Url::toLang(null); ?><br />
Url::toLang: <?php echo Url::toLang(null, 'page/about-us'); ?><br />
Url::toLang: <?php echo Url::toLang('spa'); ?><br />
Url::toLang: <?php echo Url::toLang('spa', 'page/about-us'); ?><br />
<br />
Router::currentRoute: <?php print_r(Router::getCurrentRoute()); ?><br />
Router::getParams: <?php print_r(Router::getParams()); ?><br />
Router::getParam: <?php echo Router::getParam(0); ?><br />
</pre>
