<?php
$api = new JiraRestApi($campaign_id);
foreach ($api->basic_configuration as $key => $value) {
	if (!in_array($key, array_keys($field_mapping))) {
		$field_mapping[$key] = $value;
	}
}
?>

<div class="row">
	<div class="col-sm-6 col-md-4">
		<h4 class="text-primary py-3"><?=  __('Field mapping', 'appq-integration-center') ?></h4>	
	</div>
	<div class="col-sm-6 col-md-8 text-right actions">
		<div class="btn-group">

			<button type="button" class="btn btn-primary-light" data-toggle="modal" data-target="#get_from_bug"><?= __('Inspect your bugtracker data', 'appq-integration-center-jira-addon'); ?></button>
			<button type="button" class="btn btn-primary-light" data-toggle="modal" data-target="#add_mapping_field_modal"><?= __('Add new field mapping', 'appq-integration-center-jira-addon'); ?></button>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<table class="table table-banded">
			<thead>
				<tr>
					<th>
						<?= __("Name", 'appq-integration-center-jira-addon'); ?>
						<i class="fa fa-question-circle" data-toggle="tooltip" title="<?= __('Jira field name', 'appq-integration-center-jira-addon') ?>"></i>
					</th>
					<th class="text-center">
						<?= __("Content", 'appq-integration-center-jira-addon'); ?>
						<i class="fa fa-question-circle" data-toggle="tooltip" title="<?= __('The content you want to set the jira field to. {Bug.*} fields will be replaced with the bug data', 'appq-integration-center-jira-addon') ?>"></i>
					</th>
					<th class="text-center">
						<?= __("Needs sanitizing", 'appq-integration-center-jira-addon'); ?>
						<i class="fa fa-question-circle" data-toggle="tooltip" title="<?= __('Check it if you don\'t want to expand special characters for jira content (e.g. _ for italics, * for bold ...)', 'appq-integration-center-jira-addon') ?>"></i>
					</th>
					<th class="text-center">
						<?= __("Contains JSON", 'appq-integration-center-jira-addon'); ?>
						<i class="fa fa-question-circle" data-toggle="tooltip" title="<?= __('Check it if the content should be interpreted as a json object, useful when setting object or arrays', 'appq-integration-center-jira-addon') ?>"></i>
					</th>
					<th></th>
				</tr>
			</thead>
			<tbody class="fields-list">
				<?php foreach ($field_mapping as $key => $item) {
					$this->partial('settings/field-mapping-row', array(
						'_key' => esc_attr($key),
						'item' => $item,
						'sanitize_icon' => (array_key_exists('sanitize', $item) && $item['sanitize'] == 'on')
							? '<i class="fa fa-check"></i>'
							: '<i class="fa fa-minus"></i>',
						'is_json_icon' => (array_key_exists('is_json', $item) && $item['is_json'] == 'on')
							? '<i class="fa fa-check"></i>'
							: '<i class="fa fa-minus"></i>'
					));
				}
				?>
			</tbody>
		</table>
	</div>
</div>

<script type="text/html" id="tmpl-field_mapping_row">
	<?php $this->partial('settings/field-mapping-row', array(
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

<?php
$this->partial('settings/get-bug-modal', array());
$this->partial('settings/edit-mapping-field-modal', array('campaign_id' => $campaign_id));
$this->partial('settings/delete-mapping-field-modal', array());
?>