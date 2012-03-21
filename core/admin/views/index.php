<? View::insert('includes/header'); ?>

<!-- start content -->
<div class="content container_12">

    <? if($subNav = Menu::build(-1, 'admin/%s', array('attributes' => array('class' => 'menu')))): ?>
        <div class="grid_3 spacer">
            <h2>Navigation</h2>
            <?= $subNav; ?>
        </div>
    <? endif; ?>

    <div class="grid_<?= ($subNav) ? '9' : '12'; ?>">
        <? if(is_array($adminContent) && $adminContent): ?>
            <? if(isset($adminContent[0])): ?>

                <? foreach($adminContent as $content): ?>
                    <div class="grid_12 spacer">
                        <h1><?= $content['title']; ?></h1>

                        <? if(isset($content['topright'])): ?>
                            <div class="topright">
                                <?= $content['topright']; ?>
                            </div>
                        <? endif; ?>

                        <?= $content['content']; ?>
                    </div>
                <? endforeach; ?>

            <? else: ?>
                    
                <div class="grid_12 spacer">
                    <h2><?= $adminContent['title']; ?></h2>

                    <? if(isset($adminContent['topright'])): ?>
                        <div class="topright">
                            <?= $adminContent['topright']; ?>
                        </div>
                    <? endif; ?>

                    <?= $adminContent['content']; ?>
                </div>

            <? endif; ?>
        <? else: ?>

            <div class="grid_12 spacer">
                <p><em>Nothing to display</em></p>
            </div>

        <? endif; ?>
    </div>

    <div class="clear">&nbsp;</div>
</div>
<!-- end content -->

<? View::insert('includes/footer'); ?>
