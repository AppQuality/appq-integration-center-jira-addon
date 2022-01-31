<?php
$endpoint = isset( json_decode( $campaign->bugtracker->endpoint )->endpoint ) ? json_decode( $campaign->bugtracker->endpoint )->endpoint : $campaign->bugtracker->endpoint;
$project  = isset( json_decode( $campaign->bugtracker->endpoint )->project ) ? json_decode( $campaign->bugtracker->endpoint )->project : false;

?>
<div class="row prevent-cols-breaking">
    <div class="col-sm-6 col-md-1 align-items-center justify-content-center">
		<?php
		printf(
			'<img src="%s" alt="%s">',
			APPQ_INTEGRATION_CENTER_JIRA_URL . 'admin/images/icon.svg',
			$campaign->bugtracker->integration
		);
		?>
    </div>
    <div class="col-sm-6 col-md-3 align-items-center justify-content-center">
        <div>
			<?php
			printf( '<h6 class="text-primary">%s</h6>', __( 'Endpoint', 'appq-integration-center-jira-addon' ) );
			?>
            <span class="text-info"><?= $endpoint ?></span>
        </div>
    </div>
    <div class="col-sm-12 col-md-3 align-items-center justify-content-center">
        <div>
			<?php $apikey = $campaign->bugtracker->apikey; ?>
            <h6 class="text-primary">
				<?= __( 'Authentication', 'appq-integration-center-jira-addon' ) ?>
            </h6>
            <span>
                <span class="text-info"><?= substr( $apikey, 0, 10 ) . str_repeat( "•", 10 ); ?></span>
                <button data-toggle="modal" data-target="#apiKeyModal" type="button" class="btn btn-default btn-sm pt-0 pb-0">
                    <i class="fa fa-eye"></i>
                </button>
            </span>
        </div>
    </div>
    <div class="col-sm-6 col-md-1 align-items-center justify-content-center">
        <div>
			<?php
			printf( '<h6 class="text-primary">%s</h6>', __( 'Project ID', 'appq-integration-center-jira-addon' ) );
			?>
            <span class="text-info"><?= $project ?></span>
        </div>
    </div>
    <div class="col-sm-6 col-md-1 align-items-center justify-content-center">
        <div>
			<?php
			printf( '<h6 class="text-primary">%s</h6>', __( 'Media upload', 'appq-integration-center-jira-addon' ) );
			$madiaUploadText = isset( $campaign->bugtracker->upload_media ) && $campaign->bugtracker->upload_media == 1 ? __( 'Yes', 'appq-integration-center-jira-addon' ) : __( 'No', 'appq-integration-center-jira-addon' );
			?>
            <span class="text-info"><?= $madiaUploadText ?></span>
        </div>
    </div>
    <div class="col-sm-12 col-md-3 align-items-center justify-content-center">
			<?php
      $admin = new AppQ_Integration_Center_Admin('appq-integration-center', APPQ_INTEGRATION_CENTERVERSION);
      $admin->current_setup_edit_buttons($campaign) 
      ?>
    </div>
</div>
