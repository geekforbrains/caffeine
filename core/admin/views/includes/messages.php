<? if(strstr(Url::current(), 'admin/login') || strstr(Url::current(), 'admin/reset') || strstr(Url::current(), 'admin/set-password')): ?>
    <div class="row-fluid">
        <div class="span4"></div>
        <div class="span4">
<? endif; ?>
    
<? if($messages = Message::get()): ?>
    <? foreach($messages as $type => $typeMessages): ?>

        <div class="alert alert-<?= $type; ?>">
            <a class="close" data-dismiss="alert" href="#">×</a>
            <? if(count($typeMessages) > 1): ?>
                <ul>
                    <? foreach($typeMessages as $message): ?>
                        <li><?= $message; ?></li>
                    <? endforeach; ?>
                </ul>
            <? elseif(isset($typeMessages[0])): ?>
                <?= $typeMessages[0]; ?>
            <? endif; ?>
        </div>

    <? endforeach; ?>
<? endif; ?>

<? if(strstr(Url::current(), 'admin/login') || strstr(Url::current(), 'admin/reset') || strstr(Url::current(), 'admin/set-password')): ?>
        </div>
        <div class="span4"></div>
    </div>
<? endif; ?>
