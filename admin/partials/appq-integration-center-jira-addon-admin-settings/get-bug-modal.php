<!-- Modal -->
<div class="modal" style="z-index: 99999;" id="get_from_bug" tabindex="-1" role="dialog" aria-labelledby="get_from_bug" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div style="z-index: 99999;" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php _e('Inspect your bugtracker data', 'appq-integration-center-jira-addon'); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body form px-4">
        <div class="search-bug">
          <div class="modal-form pb-4">
            <input class="form-control" type="text" id="issue_id" placeholder="<?php _e('Search for a bug (Eg. ABC-01)', 'appq-integration-center-jira-addon'); ?>" />
          </div>
          <div class="row">
            <div class="col-6 col-lg-4 offset-lg-2 text-right">
              <?php printf(
                '<button type="button" id="retrieve_mappings" class="btn btn-primary confirm">%s</button>',
                __('Inspect now', 'appq-integration-center-jira-addon')
              ); ?>
            </div>
            <div class="col-6 col-lg-4">
              <button type="button" class="btn btn-link" data-dismiss="modal">
                <?= __('Cancel', 'appq-integration-center-jira-addon') ?>
              </button>
            </div>
          </div>
        </div>
        <ul id="retrieved_mappings">
        </ul>
      </div>
    </div>
  </div>
</div>