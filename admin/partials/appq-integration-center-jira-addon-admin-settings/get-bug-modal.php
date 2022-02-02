<!-- Modal -->
<div class="modal" id="get_from_bug" tabindex="-1" role="dialog" aria-labelledby="getFromBugLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="text-primary" id="getFromBugLabel"><?= __('Inspect your bugtracker data', 'appq-integration-center-jira-addon'); ?></h5>
      </div>
      <div class="modal-body form px-4">
        <div class="search-bug">
          <div class="modal-form pb-4">
            <input class="form-control" type="text" id="issue_id" placeholder="<?php _e('Search for a bug (Eg. ABC-01)', 'appq-integration-center-jira-addon'); ?>" />
          </div>
        </div>
        <ul class="list" id="retrieved_mappings">
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link" data-dismiss="modal">
          <?= __('Cancel', 'appq-integration-center-jira-addon') ?>
        </button>
        <button type="button" id="retrieve_mappings" class="btn btn-primary confirm">
          <?= __('Inspect now', 'appq-integration-center-jira-addon') ?>
        </button>
      </div>
    </div>
  </div>
</div>
</div>