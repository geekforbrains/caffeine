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
