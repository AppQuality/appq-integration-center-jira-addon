
<h3> Field Mapping</h3>
<div class="row">
	<div class="col-sm-9 field_mapping">
		<?php foreach ($field_mapping as $key => $value) : ?>
			<div class="form-group row">
				<label for="field_mapping[<?= $key ?>]" class="col-sm-2"><?= $key ?></label>
				<input name="field_mapping[<?= $key ?>]" type="text" class="col-sm-9 form-control" id="value" value="<?= $value ?>" placeholder="Title: {Bug.title}">
				<button class="col-sm-1 remove btn btn-danger">-</button>
			</div>
		<?php endforeach ?>
		<button type="button" class="add_field_mapping col-sm-12 btn btn-primary">+</button>
	</div>
	<div class="col-sm-3">
		<div class="row">
			<button type="button" class="col-sm-6 btn btn-secondary">Title</button>
			<button type="button" class="col-sm-6 btn btn-secondary">Status</button>
		</div>
	</div>
</div>