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
			<?php foreach ($item as $ik => $ii) {
				$item[$ik] = esc_attr($ii);
			} ?>
			<?php $this->partial('settings/field-mapping-row',array(
				'_key' => esc_attr($key),  
				'item' => $item,
				'sanitize_icon' => (array_key_exists('sanitize', $item) && $item['sanitize'] == 'on')  
					? '<i class="fa fa-check"></i>'
					: '<i class="fa fa-minus"></i>',
				'is_json_icon' => (array_key_exists('is_json', $item) && $item['is_json'] == 'on')  
					? '<i class="fa fa-check"></i>'
					: '<i class="fa fa-minus"></i>'
			)); ?>
		<?php endforeach; ?>
		<script type="text/html" id ="tmpl-field_mapping_row">
		<?php $this->partial('settings/field-mapping-row',array(
			'_key' => '{{data.key}}',
			'item' => array(
				'value' => '{{data.content}}',
				'is_json' => '{{data.json}}',
				'sanitize' => '{{data.sanitize}}'
			),
			'sanitize_icon' => '<# if (data.sanitize == "on") {#><i class="fa fa-check"></i><#} else {#><i class="fa fa-minus"></i><#}#>',
			'is_json_icon' => '<# if (data.json == "on") {#><i class="fa fa-check"></i><#} else {#><i class="fa fa-minus"></i><#}#>'
		)); ?>
		</script>
    </div>

<?php
$this->partial('settings/get-bug-modal', array());
$this->partial('settings/edit-mapping-field-modal', array());
$this->partial('settings/delete-mapping-field-modal', array());
?>
