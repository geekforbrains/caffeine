        <? if(User::current()->hasPermission('admin.access')): ?>
            <hr>
            <footer>
                <p><a href="http://github.com/geekforbrains/caffeine">Caffeine v<?= VERSION; ?></a></p>
            </footer>
        <? endif; ?>

    </div><!--/.fluid-container-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script type="text/javascript" src="assets/js/bootstrap-dropdown.js"></script>
    <script type="text/javascript" src="assets/js/bootstrap-alert.js"></script>

    <!--
    <script src="assets/js/bootstrap-transition.js"></script>
    <script src="assets/js/bootstrap-modal.js"></script>
    <script src="assets/js/bootstrap-scrollspy.js"></script>
    <script src="assets/js/bootstrap-tab.js"></script>
    <script src="assets/js/bootstrap-tooltip.js"></script>
    <script src="assets/js/bootstrap-popover.js"></script>
    <script src="assets/js/bootstrap-button.js"></script>
    <script src="assets/js/bootstrap-collapse.js"></script>
    <script src="assets/js/bootstrap-carousel.js"></script>
    <script src="assets/js/bootstrap-typeahead.js"></script>
    -->

	<script src="assets/redactor/redactor.js"></script>
	<script type="text/javascript"> 
        $(document).ready(function() {
            $('.wysiwyg').redactor({ focus: true });
        });
	</script>				
  </body>
</html>
