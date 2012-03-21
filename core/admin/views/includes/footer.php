<!-- start footer -->
<div class="footer container_12">
    <div class="grid_12">
        System Time: <?= date('M jS, Y - h:i A'); ?> (<?= Config::get('system.timezone'); ?>)
        <a style="float: right" href="http://github.com/geekforbrains/caffeine" target="_blank">Caffeine <?= VERSION; ?></a>
    </div>
    <div class="clear">&nbsp;</div>
</div>
<!-- end footer -->


<script type="text/javascript"> 
    $('select').addClass('chzn-select');
    $(".chzn-select").chosen(); 
    $(".chzn-select-deselect").chosen({allow_single_deselect:true}); 
</script>

</body>
</html>
