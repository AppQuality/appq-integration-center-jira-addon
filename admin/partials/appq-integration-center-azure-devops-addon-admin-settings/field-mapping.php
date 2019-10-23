
<h3> Field Mapping</h3>
<div class="row">
	<div class="col-sm-9 field_mapping">
		<?php foreach ($field_mapping as $key => $value) : ?>
			<div class="form-group row">
				<label for="field_mapping[<?= $key ?>]" class="col-sm-2"><?= $key ?></label>
				<textarea name="field_mapping[<?= $key ?>]" class="col-sm-9 form-control" placeholder="Title: {Bug.title}"><?= $value ?></textarea>
				<button class="col-sm-1 remove btn btn-danger">-</button>
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
				<?php foreach ($additional_fields as $additional_field) : ?>
					<p> {Bug.field.<?=$additional_field->slug ?>} - Additional field <?=$additional_field->title ?> </p>
				<?php endforeach ?>
			</div>
		</div>
	</div>
</div>