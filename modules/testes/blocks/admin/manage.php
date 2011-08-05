<div class="area left short">
	<h3>Navigation</h3>
	<?php echo Menu::build('admin/testes', 0); ?>
</div>

<div class="area right">
	<div class="area">
		<h2>Testimonals</h2>

		<table class="stripe" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<th>Author</th>
				<th style="text-align: right">Created</th>
				<th style="text-align: right" width="20">Delete</th>
			</tr>

			<?php if($testes): ?>
				<?php foreach($testes as $teste): ?>
					<tr>
						<td>
							<a href="<?php l('admin/testes/edit/%d', $teste['id']); ?>">
								<?php echo $teste['author']; ?>
							</a>
						</td>
						<td align="right"><?php echo date('M jS, Y', $teste['created']); ?></a></td>
						<td align="right">
							<a href="<?php l('admin/testes/delete/%d', $teste['id']); ?>">
								Delete
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<tr><td colspan="3"><em>No testimonials found.</em></td></tr>
			<?php endif; ?>
		</table>
	</div>

</div>