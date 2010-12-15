<? if($messages): ?>
	<div id="messages">
	
		<? foreach($messages as $type => $type_messages): ?>
			<div class="message-<? echo $type ?>">

				<? foreach($type_messages as $message): ?>
					<span><? echo $message ?></span>
				<? endforeach; ?>

			</div>
		<? endforeach; ?>

	</div>
<? endif; ?>
