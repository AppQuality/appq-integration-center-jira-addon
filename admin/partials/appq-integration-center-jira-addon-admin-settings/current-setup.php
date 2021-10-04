<?php
$endpoint = isset( json_decode( $campaign->bugtracker->endpoint )->endpoint ) ? json_decode( $campaign->bugtracker->endpoint )->endpoint : $campaign->bugtracker->endpoint;
$project  = isset( json_decode( $campaign->bugtracker->endpoint )->project ) ? json_decode( $campaign->bugtracker->endpoint )->project : false;

?>
<div class="row d-flex">
    <div class="col-1 d-flex-vertical-center">
		<?php
		printf(
			'<img src="%s" alt="%s">',
			APPQ_INTEGRATION_CENTER_JIRA_URL . 'admin/images/icon.svg',
			$campaign->bugtracker->integration
		);
		?>
    </div>
    <div class="col-3">
        <div>
			<?php
			printf( '<h6 class="text-secondary">%s</h6>', __( 'Endpoint', 'appq-integration-center-jira-addon' ) );
			?>
            <span class="text-primary"><?= $endpoint ?></span>
        </div>
    </div>
    <div class="col-3">
        <div>
			<?php $apikey = $campaign->bugtracker->apikey; ?>
            <h6 class="text-secondary">
				<?= __( 'Authentication', 'appq-integration-center-jira-addon' ) ?>
            </h6>
            <span>
                <span class="text-primary"><?= substr( $apikey, 0, 10 ) . str_repeat( "•", 10 ); ?></span>
                <button data-toggle="modal" data-target="#apiKeyModal" type="button" class="btn btn-default btn-sm pt-0 pb-0">
                    <i class="fa fa-eye"></i>
                </button>
            </span>
        </div>
    </div>
    <div class="col">
        <div>
			<?php
			printf( '<h6 class="text-secondary">%s</h6>', __( 'Project ID', 'appq-integration-center-jira-addon' ) );
			?>
            <span class="text-primary"><?= $project ?></span>
        </div>
    </div>
    <div class="col">
        <div>
			<?php
			printf( '<h6 class="text-secondary">%s</h6>', __( 'Media upload', 'appq-integration-center-jira-addon' ) );
			$madiaUploadText = isset( $campaign->bugtracker->upload_media ) && $campaign->bugtracker->upload_media == 1 ? __( 'Yes', 'appq-integration-center-jira-addon' ) : __( 'No', 'appq-integration-center-jira-addon' );
			?>
            <span class="text-primary"><?= $madiaUploadText ?></span>
        </div>
    </div>
    <div class="col d-flex-vertical-center">
			<?php
      $admin = new AppQ_Integration_Center_Admin('appq-integration-center', APPQ_INTEGRATION_CENTERVERSION);
      $admin->current_setup_edit_buttons($campaign) 
      ?>
    </div>
</div>
