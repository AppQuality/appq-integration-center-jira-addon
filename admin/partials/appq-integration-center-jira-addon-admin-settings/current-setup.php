<?php
$endpoint = isset(json_decode($campaign->bugtracker->endpoint)->endpoint) ? json_decode($campaign->bugtracker->endpoint)->endpoint : $campaign->bugtracker->endpoint;
$project  = isset(json_decode($campaign->bugtracker->endpoint)->project) ? json_decode($campaign->bugtracker->endpoint)->project : false;

?>
<div class="row prevent-cols-breaking">
    <div class="col-lg-1 align-items-center justify-content-center hidden-xs visible-xl">
        <?php
        printf(
            '<img src="%s" alt="%s" class="img-responsive" style="margin:auto">',
            APPQ_INTEGRATION_CENTER_JIRA_URL . 'admin/images/icon.svg',
            $campaign->bugtracker->integration
        );
        ?>
    </div>
    <div class="col-sm-6 col-lg-3 col-xl-3 align-items-center justify-content-center">
        <h6 class="text-primary"><?= __('Endpoint', 'appq-integration-center-jira-addon') ?></h6>
        <span title="<?= $endpoint ?>" class="text-info text-truncate full-width"><?= $endpoint ?></span>
    </div>
    <div class="col-sm-6 col-lg-3 align-items-center justify-content-center">
        <?php $apikey = $campaign->bugtracker->apikey; ?>
        <h6 class="text-primary">
            <?= __('Authentication', 'appq-integration-center-jira-addon') ?>
        </h6>
        <span>
            <span class="text-info"><?= substr($apikey, 0, 10) . str_repeat("•", 10); ?></span>
            <button data-toggle="modal" data-target="#apiKeyModal" type="button" class="btn btn-default btn-sm pt-0 pb-0">
                <i class="fa fa-eye"></i>
            </button>
        </span>
    </div>
    <div class="col-sm-6 col-md-3 col-lg-1 align-items-center justify-content-center">
        <h6 class="text-primary"><?= __('Project ID', 'appq-integration-center-jira-addon') ?></h6>
        <span class="text-info"><?= $project ?></span>
    </div>
    <div class="col-sm-6 col-md-3 col-lg-1 align-items-center justify-content-center">
        <h6 class="text-primary"><?= __('Media upload', 'appq-integration-center-jira-addon') ?></h6>
        <?php
        $madiaUploadText = isset($campaign->bugtracker->upload_media) && $campaign->bugtracker->upload_media == 1 ? __('Yes', 'appq-integration-center-jira-addon') : __('No', 'appq-integration-center-jira-addon');
        ?>
        <span class="text-info"><?= $madiaUploadText ?></span>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 align-items-center justify-content-center">
        <?php
        $admin = new AppQ_Integration_Center_Admin('appq-integration-center', APPQ_INTEGRATION_CENTERVERSION);
        $admin->current_setup_edit_buttons($campaign)
        ?>
    </div>
</div>