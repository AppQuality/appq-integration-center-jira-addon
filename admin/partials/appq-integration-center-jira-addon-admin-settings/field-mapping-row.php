<tr class="mapping-role" data-row="<?= $_key ?>">
	<td><?= $_key ?></td>
	<td>
		<?= array_key_exists('value', $item) ? nl2br($item['value']) : '' ?>
	</td>
	<td class="text-center"><?= $sanitize_icon ?></td>
	<td class="text-center"><?= $is_json_icon ?></td>
	<td>
		<button data-toggle="modal" data-target="#add_mapping_field_modal" type="button" class="btn btn-primary btn-icon-toggle edit-mapping-field" data-key="<?= $_key; ?>" data-content="<?= (isset($item['value']) ? ($item['value']) : '') ?>" data-sanitize="<?= (isset($item['sanitize']) ? ($item['sanitize']) : '') ?>" data-json="<?= (isset($item['is_json']) ? ($item['is_json']) : '') ?>">
			<i class="fa fa-pencil"></i>
		</button>
		<button data-toggle="modal" data-target="#delete_mapping_field_modal" type="button" class="btn btn-danger btn-icon-toggle delete-mapping-field" data-key="<?= $_key ?>">
			<i class="fa fa-trash"></i>
		</button>
	</td>
</tr>