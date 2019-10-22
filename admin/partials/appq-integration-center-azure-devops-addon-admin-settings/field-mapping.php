
<h3> Field Mapping</h3>
<div class="row">
	<div class="col-sm-9">
		<?php foreach ($field_mapping as $key => $value) : ?>
			<div class="form-group row">
				<label for="value" class="col-sm-2"><?= $key ?></label>
				<input type="text" class="col-sm-10 form-control" id="value" value="<?= $value ?>" placeholder="Title: {Bug.title}">
			</div>
		<?php endforeach ?>
	</div>
	<div class="col-sm-3">
		<div class="row">
			<button type="button" class="col-sm-6 btn btn-secondary">Title</button>
			<button type="button" class="col-sm-6 btn btn-secondary">Status</button>
		</div>
	</div>
</div>
<div class="row">
	<button type="button" class="col-sm-2 offset-sm-10 btn btn-primary">Save</button>
</div>
