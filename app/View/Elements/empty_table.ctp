<?php
/**
 * Empty table / list placeholder.
 *
 * @var int $colspan Number of columns (table mode). Omit for block mode.
 * @var string $message Custom message (optional).
 */
$message = !empty($message) ? $message : 'Aucune donnée dans la table.';
$colspan = isset($colspan) ? (int)$colspan : null;
?>
<?php if ($colspan !== null): ?>
	<tr>
		<td colspan="<?php echo $colspan; ?>" class="text-center text-muted py-4 empty-table-msg">
			<?php echo h($message); ?>
		</td>
	</tr>
<?php else: ?>
	<div class="text-center text-muted py-4 empty-table-msg">
		<?php echo h($message); ?>
	</div>
<?php endif; ?>
