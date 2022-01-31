<?php $api = new IntegrationCenterRestApi($campaign_id, null, null); ?>

<!-- Modal -->
<div class="modal" id="add_mapping_field_modal" tabindex="-1" role="dialog" aria-labelledby="add_mapping_field_modal_label" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="add_mapping_field_modal_label">
          <?php _e('Add/edit field mapping', 'appq-integration-center-jira-addon'); ?>
        </h4>
      </div>
      <form class="form" id="jira_mapping_field">
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-8">
              <div class="form-group">
                <label for="custom_mapping_name"><?= __('Name', 'appq-integration-center-jira-addon') ?></label>
                <input type="text" class="form-control" name="name" id="custom_mapping_name" placeholder="<?= __('summary', 'appq-integration-center-jira-addon') ?>">
              </div>
              <div class="form-group">
                <label for="custom_mapping_content"><?= __('Target field', 'appq-integration-center-jira-addon') ?></label>
                <textarea class="form-control" rows="5" name="value" id="custom_mapping_content" placeholder="<?= __('*Type*: {Bug.type} ...', 'appq-integration-center-jira-addon') ?>"></textarea>
              </div>

              <div>
                <label class="checkbox-inline checkbox-styled">
                  <input type="checkbox" name="sanitize"><span><?= __('Needs sanitizing', 'appq-integration-center-jira-addon') ?></span>
                </label>
              </div>
              <div>
                <label class="checkbox-inline checkbox-styled">
                  <input type="checkbox" name="is_json"><span><?= __('Contains JSON', 'appq-integration-center-jira-addon') ?></span>
                </label>
              </div>


            </div>

            <div class="col-sm-4" style="max-height:350px;">
              <h6 class="text-center">Click to copy</h6>
              <ul class="list divider-full-bleed scroll height-6">
                <?php foreach ($api->mappings as $key => $value) : ?>
                  <li class="tile" title="<?= esc_attr($value['description']) ?>">
                    <a class="tile-content ink-reaction copy-to-clipboard" data-clipboard-text="<?= $key ?>">
                      <div class="tile-text" style="font-size: 13px;">
                        <?= $key ?>
                      </div>
                    </a>
                    <a class="btn btn-flat ink-reaction copy-to-clipboard btn-sm" data-clipboard-text="<?= $key ?>">
                      <i class="fa fa-copy"></i>
                    </a>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-link" data-dismiss="modal">
            <?= __('Cancel', 'appq-integration-center-jira-addon') ?>
          </button>
          <button type="submit" id="add_new_mapping_field" class="btn btn-primary">
            <?= __('Save field', 'appq-integration-center-jira-addon'); ?>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>