<?php $api = new IntegrationCenterRestApi($campaign_id, null, null); ?>

<!-- Modal -->
<div class="modal" style="z-index: 99999;" id="add_mapping_field_modal" tabindex="-1" role="dialog" aria-labelledby="add_mapping_field_modal_label" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div style="z-index: 99999;" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="add_mapping_field_modal_label"><?php _e('Add/edit field mapping', $this->plugin_name); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body px-4">
        <div class="row">
          <div class="col-8">
            <form id="jira_mapping_field">
              <div class="form-group">
                <?php
                printf('<label for="custom_mapping_name">%s</label>', __('Name', $this->plugin_name));
                printf('<input type="text" class="form-control" name="name" id="custom_mapping_name" placeholder="%s">', __('summary', $this->plugin_name));
                ?>
              </div>
              <div class="form-group">
                <?php
                printf('<label for="custom_mapping_content">%s</label>', __('Target field', $this->plugin_name));
                printf('<textarea class="form-control" name="value" id="custom_mapping_content" placeholder="%s"></textarea>', __('*Type*: {Bug.type} ...', $this->plugin_name));
                ?>
              </div>
              <div class="row">
                <div class="col-12">
                  <div class="form-group pull-left mr-3 col-12">
                    <label class="d-flex align-items-center">
                      <input type="checkbox" class="form-control col-1 mr-2" name="sanitize">
                      <?=  __('Needs sanitizing', $this->plugin_name) ?>
                    </label>
                  </div>
                  <div class="form-group pull-left mr-3 col-12">
                    <label class="d-flex align-items-center">
                      <input type="checkbox" class="form-control col-1 mr-2" name="is_json">
                      <?=  __('Contains JSON', $this->plugin_name) ?>
                    </label>
                  </div>
                </div>
              </div>
              <div class="row mt-5 pb-4">
                <div class="col-6 col-lg-4 offset-lg-2 text-right">
                  <?php printf(
                    '<button type="submit" id="add_new_mapping_field" class="btn btn-primary">%s</button>',
                    __('Save field', $this->plugin_name)
                  ); ?>
                </div>
                <div class="col-6 col-lg-4">
                  <?php printf('<button type="button" class="btn btn-link" data-dismiss="modal" aria-label="%1$s">%1$s</button>', __('Cancel', $this->plugin_name)); ?>
                </div>
              </div>
            </form>
          </div>
          <div class="col-4" style="max-height:350px;overflow-y:scroll">
            <h6 class="text-center">Click to copy</h6>
            <ul style="list-style: none;">
            <?php foreach ($api->mappings as $key => $value): ?>
              <li class="mb-1" title="<?= esc_attr($value['description']) ?>">
                <button style="text-transform: initial;" data-copy-to-clipboard class="btn btn-block btn-secondary"><?= $key ?></button>
              </li>
            <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
