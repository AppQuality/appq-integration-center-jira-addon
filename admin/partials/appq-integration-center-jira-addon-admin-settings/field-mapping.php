
<h3> 
	Field Mapping
	<button  type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Get Mappings From Bug</button>
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
				<p> {Bug.message} - Titolo del bug </p> 
				<p> {Bug.steps} - Step by step description del bug </p> 
				<p> {Bug.expected} - Expected result del bug </p> 
				<p> {Bug.actual} - Actual result del bug </p> 
				<p> {Bug.note} - Note del bug </p> 
				<p> {Bug.id} - ID del bug </p> 
				<p> {Bug.internal_id} - Internal id del bug </p> 
				<p> {Bug.status_id} - Status id del bug </p> 
				<p> {Bug.status} - Status name del bug </p> 
				<p> {Bug.severity_id} - Severity id del bug </p> 
				<p> {Bug.severity} - Severity name del bug </p> 
				<p> {Bug.replicability_id} - Replicability id del bug </p> 
				<p> {Bug.replicability} - Replicability name del bug </p> 
				<p> {Bug.type_id} - Bug Type id id del bug </p> 
				<p> {Bug.type} - Bug Type name del bug </p> 
				<p> {Bug.manufacturer} - Manufacturer del device del bug </p> 
				<p> {Bug.model} - Modello del device del bug </p> 
				<p> {Bug.os} - OS del device del bug </p> 
				<p> {Bug.os_version} - OS version del device del bug </p>
				<p> {Bug.media} - Media del bug, le immagini verranno mostrate nel contenuto </p>
				<p> {Bug.media_links} - Link ai media del bug </p>
				<?php foreach ($additional_fields as $additional_field) : ?>
					<p> {Bug.field.<?=$additional_field->slug ?>} - Additional field <?=$additional_field->title ?> </p>
				<?php endforeach ?>
			</div>
		</div>
	</div>
</div>

<?php
$this->partial('settings/get-bug-modal', array(
)) 
?>