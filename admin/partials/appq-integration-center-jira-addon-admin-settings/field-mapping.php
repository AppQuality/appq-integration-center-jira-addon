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
		<button type="button" class="btn btn-secondary mr-1" data-toggle="modal" data-target="#get_from_bug"><?php _e('Get mapping from bug', $this->plugin_name); ?></button>
		<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#add_mapping_field_modal"><?php _e('New field', $this->plugin_name); ?></button>
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
			'<button data-toggle="modal" data-target="#add_mapping_field_modal" type="button" class="btn btn-secondary mr-1"><i class="fa fa-pencil"></i></button>
			<button data-toggle="modal" data-target="#delete_field_settings_modal" type="button" class="btn btn-secondary"><i class="fa fa-trash"></i></button>'
        );
        ?>
    </div>
<? } ?>

<?php
$this->partial('settings/get-bug-modal', array());
$this->partial('settings/edit-mapping-field-modal', array());
?>