<?php
$api = new JiraRestApi($campaign_id);
foreach ($api->basic_configuration as $key => $value) {
	if (!in_array($key, array_keys($field_mapping))) {
		$field_mapping[$key] = $value;
	}
}
?>

<div class="row">
	<div class="col-6"><?php printf('<h4 class="title py-3">%s</h4>', __('Field mapping', $this->plugin_name)); ?></div>
	<div class="col-6 text-right actions mt-2">
		<button type="button" class="btn btn-secondary mr-1" data-toggle="modal" data-target="#getBugModal"><?php _e('Get mapping from bug', $this->plugin_name); ?></button>
		<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#addFieldModal"><?php _e('New field', $this->plugin_name); ?></button>
	</div>
</div>
<div class="row mb-2">
    <div class="col-2">
        <?php printf('<small><strong>%s</strong></small>', __('Name', $this->plugin_name)); ?>
    </div>
    <div class="col-4">
        <?php printf('<small><strong>%s</strong></small>', __('Content', $this->plugin_name)); ?>
    </div>
    <div class="col-2 text-center">
        <?php printf('<small><strong>%s</strong></small>', __('Needs sanitizing', $this->plugin_name)); ?>
    </div>
    <div class="col-2 text-center">
        <?php printf('<small><strong>%s</strong></small>', __('Contains JSON', $this->plugin_name)); ?>
    </div>
</div>

<?php foreach ($field_mapping as $key => $item){ ?>
    <div class="row mb-2">
        <?php
        printf(
            '<div class="col-2">%s</div><div class="col-4">%s</div><div class="col-2 text-center"><input type="checkbox" style="cursor: unset;" disabled%s></div><div class="col-2 text-center"><input type="checkbox" style="cursor: unset;" disabled%s></div><div class="col-2 text-right actions">%s</div>',
            $key,
			array_key_exists('value', $item) ? $item['value'] : '',
			array_key_exists('sanitize', $item) && $item['sanitize'] == 'on' ? ' checked="checked"' : '',
			array_key_exists('is_json', $item) && $item['is_json'] == 'on' ? ' checked="checked"' : '',
			'<button data-toggle="modal" data-target="#edit_field_settings_modal" type="button" class="btn btn-secondary mr-1"><i class="fa fa-pencil"></i></button>
			<button data-toggle="modal" data-target="#delete_field_settings_modal" type="button" class="btn btn-secondary"><i class="fa fa-trash"></i></button>'
        );
        ?>
    </div>
<? } ?>

<?php /*
<div class="row">
	<div class="col-sm-9 field_mapping">
		<?php foreach ($field_mapping as $key => $item) : ?>
			<div class="form-group row d-flex">
				<label style="word-break: break-all;" for="field_mapping[<?= $key ?>]" class="col-sm-2 align-self-center text-center"><?= $key ?></label>
				<textarea name="field_mapping[<?= $key ?>][value]" class="col-sm-7 form-control" placeholder="Title: {Bug.title}"><?= array_key_exists('value', $item) ? $item['value'] : '' ?></textarea>
				<div class="custom-control custom-checkbox col-sm-1">
					<input name="field_mapping[<?= $key ?>][sanitize]" class="custom-control-input" type="checkbox" <?= array_key_exists('sanitize', $item) && $item['sanitize'] == 'on' ? 'checked="checked"' : '' ?>>
					<label class="custom-control-label" for="field_mapping[<?= $key ?>][sanitize]">
						Sanitize?
					</label>
				</div>
				<div class="custom-control custom-checkbox col-sm-1">
					<input name="field_mapping[<?= $key ?>][is_json]" class="custom-control-input" type="checkbox" <?= array_key_exists('is_json', $item) && $item['is_json'] == 'on' ? 'checked="checked"' : '' ?>>
					<label class="custom-control-label" for="field_mapping[<?= $key ?>][is_json]">
						JSON?
					</label>
				</div>
				<button class="col-sm-1 remove btn btn-danger"><span class="fa fa-minus"></span></button>
			</div>
		<?php endforeach ?>
		<button type="button" class="add_field_mapping col-sm-12 btn btn-primary">+</button>
	</div>
	<div class="col-sm-3">
		<div class="row">
			<h4 class="col-sm-12"> Add fields </h4>
			<div class="col-sm-12">
				<?php foreach ($api->mappings as $map => $data) : ?>
					<p> <?= $map ?> - <?= $data['description'] ?> </p>
				<?php endforeach ?>
			</div>
		</div>
	</div>
</div>
*/?>
<?php
$this->partial('settings/get-bug-modal', array())
?>