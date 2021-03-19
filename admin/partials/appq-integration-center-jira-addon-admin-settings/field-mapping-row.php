
				<div class="row py-2" data-row="<?= $_key ?>">
						<div class="col-2"><?= $_key ?></div>
						<div class="col-4"><?= array_key_exists('value', $item) ? $item['value'] : '' ?></div>
						<div class="col-2 text-center">
							<?= $sanitize_icon ?>
						</div>
						<div class="col-2 text-center">
							<?= $is_json_icon ?>
						</div>
						<div class="col-2 text-right actions">
								<button data-toggle="modal" data-target="#add_mapping_field_modal" type="button" class="btn btn-info btn-sm mr-1 edit-mapping-field"
												data-key="<?= $_key; ?>"
												data-content="<?= (isset($item['value']) ? ($item['value']) : '') ?>"
												data-sanitize="<?= (isset($item['sanitize']) ? ($item['sanitize']) : '') ?>"
												data-json="<?= (isset($item['is_json']) ? ($item['is_json']) : '') ?>">
										<i class="fa fa-pencil"></i>
								</button>
								<button data-toggle="modal" data-target="#delete_mapping_field_modal" type="button" class="btn btn-danger btn-sm delete-mapping-field"
												data-key="<?= $_key ?>">
										<i class="fa fa-trash"></i>
								</button>
						</div>
				</div>
