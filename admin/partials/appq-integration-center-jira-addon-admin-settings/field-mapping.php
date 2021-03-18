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
            <button type="button" class="btn btn-default mr-1" data-toggle="modal" data-target="#get_from_bug"><?php _e('Inspect your bugtracker data', $this->plugin_name); ?></button>
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#add_mapping_field_modal"><?php _e('Add new field mapping', $this->plugin_name); ?></button>
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
    <div class="fields-list">
		<?php foreach ($field_mapping as $key => $item): ?>
            <div class="row py-2" data-row="<?= $key ?>">
                <div class="col-2"><?= $key ?></div>
                <div class="col-4"><?= array_key_exists('value', $item) ? nl2br($item['value']) : '' ?></div>
                <div class="col-2 text-center">
									<?php if (array_key_exists('sanitize', $item) && $item['sanitize'] == 'on') : ?>
										<i class="fa fa-check"></i>
									<?php else: ?>
										<i class="fa fa-minus"></i>
									<?php endif; ?>
                </div>
                <div class="col-2 text-center">
									<?php if (array_key_exists('is_json', $item) && $item['is_json'] == 'on') : ?>
										<i class="fa fa-check"></i>
									<?php else: ?>
										<i class="fa fa-minus"></i>
									<?php endif; ?>
                </div>
                <div class="col-2 text-right actions">
                    <button data-toggle="modal" data-target="#add_mapping_field_modal" type="button" class="btn btn-info btn-sm mr-1 edit-mapping-field"
                            data-key="<?= esc_attr($key); ?>"
                            data-content="<?= (isset($item['value']) ? esc_attr($item['value']) : '') ?>"
                            data-sanitize="<?= (isset($item['sanitize']) ? esc_attr($item['sanitize']) : '') ?>"
                            data-json="<?= (isset($item['is_json']) ? esc_attr($item['is_json']) : '') ?>">
                        <i class="fa fa-pencil"></i>
                    </button>
                    <button data-toggle="modal" data-target="#delete_mapping_field_modal" type="button" class="btn btn-danger btn-sm delete-mapping-field"
                            data-key="<?= esc_attr($key) ?>">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
		<?php endforeach; ?>
    </div>

<?php
$this->partial('settings/get-bug-modal', array());
$this->partial('settings/edit-mapping-field-modal', array());
$this->partial('settings/delete-mapping-field-modal', array());
?>
