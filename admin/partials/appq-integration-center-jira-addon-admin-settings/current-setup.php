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
			printf( '<h6 class="text-secondary">%s</h6>', __( 'Endpoint', $this->plugin_name ) );
			?>
            <span class="text-primary"><?= $endpoint ?></span>
        </div>
    </div>
    <div class="col-3">
        <div>
			<?php $apikey = $campaign->bugtracker->apikey; ?>
            <h6 class="text-secondary">
				<?= __( 'Authentication', $this->plugin_name ) ?>
            </h6>
            <span>
                <span class="text-primary"><?= substr( $apikey, 0, 10 ) . str_repeat( "•", 10 ); ?></span>
                <button data-toggle="modal" data-target="#apiKeyModal" type="button" class="btn btn-link btn-sm pt-0 pb-0">
                    <i class="fa fa-eye"></i>
                </button>
            </span>
        </div>
    </div>
    <div class="col">
        <div>
			<?php
			printf( '<h6 class="text-secondary">%s</h6>', __( 'Project ID', $this->plugin_name ) );
			?>
            <span class="text-primary"><?= $project ?></span>
        </div>
    </div>
    <div class="col">
        <div>
			<?php
			printf( '<h6 class="text-secondary">%s</h6>', __( 'Media upload', $this->plugin_name ) );
			$madiaUploadText = isset( $campaign->bugtracker->upload_media ) && $campaign->bugtracker->upload_media == 1 ? __( 'Yes', $this->plugin_name ) : __( 'No', $this->plugin_name );
			?>
            <span class="text-primary"><?= $madiaUploadText ?></span>
        </div>
    </div>
    <div class="col d-flex-vertical-center">
        <div class="btn-group mr-1" role="group">
			<?php if ( isset( $campaign->bugtracker->default_bug ) ): ?>

                <button id="update_default_bug" class="btn btn-light" title="Click to update the example bug previously uploaded" type="button">
					<?= __( 'Update', $this->plugin_name ) ?>
                </button>
                <a href="<?= $campaign->bugtracker->default_bug; ?>" target="_blank" class="btn btn-light"
                   title="<?= __( 'Show Example Bug uploaded', $this->plugin_name ) ?>">
                    <i class="fa fa-external-link"></i>
                </a>

			<?php else: ?>

                <button id="import_default_bug" type="button" class="btn btn-light">
					<?=  __( 'Import bug', $this->plugin_name ) ?>
                </button>
			<?php endif; ?>
        </div>
        <div class="btn-group" role="group">
            <button data-toggle="modal" data-target="#custom_tracker_settings_modal" type="button" class="btn btn-light">
                <i class="fa fa-pencil"></i>
            </button>
            <button data-toggle="modal" data-target="#reset_tracker_settings" type="button" class="btn btn-light">
                <i class="fa fa-trash"></i>
            </button>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="apiKeyModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Authentication</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <pre><code><?= $apikey; ?></code></pre>
            </div>
        </div>
    </div>
</div>
