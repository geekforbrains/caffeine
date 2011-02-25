<?php if(isset($images[0])): ?>
	<!-- THUMBS -->
	<table width="100%" border="1" cellpadding="0" cellspacing="0">
	<tr>
	<td width="100" valign="top">

		<table border="0" cellpadding="5" cellspacing="0"> 
			<?php foreach($images as $image): ?>
				<tr>
					<td>
						<?php /*<a href="#" onclick="inject('<?php l('media/image/%d', $image['cid']); ?>')">*/ ?>
						<a class="img_thumb" name="<?php echo $image['cid']; ?>" href="<?php l('media/image/%d/0/500/0', $image['cid']); ?>">
							<img src="<?php l('media/image/%d/0/75/75', $image['cid']); ?>" />
						</a>
					</td>
				</tr>
			<?php endforeach; ?>
		</table>

	</td>

	<!-- MAIN -->
	<td valign="top">

		<table border="0" cellpadding="5" cellspacing="0"> 
			<tr>
				<td>
					<input id="width" type="text" name="width" value="0" /> x
					<input id="height" type="text" name="height" value="0" />
					<input id="resize" type="button" value="Resize" />
					<a id="original" href="#">Original</a>
					<a id="reset" href="#">Reset</a>
					<a id="rotate_left" href="#">Left</a>
					<a id="rotate_right" href="#">Right</a>
				</td>
			</tr>
			<tr>
				<td>
					<a id="main_href" href="<?php l('media/image/%d/0/500/0', $images[0]['cid']); ?>">
						<img id="main_img" src="<?php l('media/image/%d/0/500/0', $images[0]['cid']); ?>" />
					</a>
				</td>
			</tr>
		</table>

	</td>
	</tr>
	</table>
<?php else: ?>
	<p><em>No images. Use the form above to upload some.</em></p>
<?php endif; ?>
