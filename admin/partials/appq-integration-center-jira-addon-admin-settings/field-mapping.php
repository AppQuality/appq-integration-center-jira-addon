<?php 
$api = new JiraRestApi($campaign_id);
foreach ($api->basic_configuration as $key => $value) {
	if (!in_array($key,array_keys($field_mapping))) {
		$field_mapping[$key] = $value;
	}
}
?>


<h3 class="mb-3"> 
	Field Mapping
	<button  type="button" class="float-right btn btn-primary" data-toggle="modal" data-target="#getBugModal">Get Mappings From Bug</button>
</h3>
<div class="row">
	<div class="col-sm-9 field_mapping">
		<?php foreach ($field_mapping as $key => $item) : ?>
			<div class="form-group row d-flex">
				<label style="word-break: break-all;" for="field_mapping[<?= $key ?>]" class="col-sm-2 align-self-center text-center"><?= $key ?></label>
				<textarea name="field_mapping[<?= $key ?>][value]" class="col-sm-7 form-control" placeholder="Title: {Bug.title}"><?= array_key_exists('value',$item) ? $item['value'] : '' ?></textarea>
				<div class="custom-control custom-checkbox col-sm-1">
				  <input name="field_mapping[<?= $key ?>][sanitize]" class="custom-control-input" type="checkbox" <?= array_key_exists('sanitize',$item) && $item['sanitize'] == 'on' ? 'checked="checked"' : '' ?>>
				  <label class="custom-control-label" for="field_mapping[<?= $key ?>][sanitize]">
				    Sanitize?
				  </label>
				</div>
				<div class="custom-control custom-checkbox col-sm-1">
				  <input name="field_mapping[<?= $key ?>][is_json]" class="custom-control-input" type="checkbox" <?= array_key_exists('is_json',$item) && $item['is_json'] == 'on' ? 'checked="checked"' : '' ?>>
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

<?php
$this->partial('settings/get-bug-modal', array(
)) 
?>
