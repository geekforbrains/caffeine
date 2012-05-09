<? View::insert('includes/header'); ?>

<div class="row-fluid">
    <? if(is_array($adminContent) && $adminContent): ?>
        <? if(isset($adminContent[0])): ?>

            <? foreach($adminContent as $content): ?>
                <div class="span12">
                    <div class="page-header">
                        <h2><?= $content['title']; ?></h2>
                    </div>

                    <? if(isset($content['topright'])): ?>
                        <div class="topright">
                            <?= $content['topright']; ?>
                        </div>
                    <? endif; ?>

                    <?= $content['content']; ?>
                </div>
            <? endforeach; ?>

        <? else: ?>
                
            <div class="span12">
                <div class="page-header">
                    <h2><?= $adminContent['title']; ?></h2>
                </div>

                <? if(isset($adminContent['topright'])): ?>
                    <div class="topright">
                        <?= $adminContent['topright']; ?>
                    </div>
                <? endif; ?>

                <?= $adminContent['content']; ?>
            </div>

        <? endif; ?>
    <? else: ?>

        <div class="span12">
            <p><em>Nothing to display</em></p>
        </div>

    <? endif; ?>
</div>

<? View::insert('includes/footer'); ?>
