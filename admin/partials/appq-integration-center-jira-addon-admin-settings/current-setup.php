<?php
printf('<h4 class="title">%s</h4>', __('Current setup', $this->plugin_name));
?>
<div class="row">
    <div class="col-2">
        <?php printf('<small>%s</small>', __('Tracker', $this->plugin_name)); ?>
        Jira logo
    </div>
    <div class="col-2">
        <?php
        printf('<small>%s</small>', __('Endpoint', $this->plugin_name));
        echo $endpoint_data['endpoint'];
        ?>
    </div>
    <div class="col-2">
        <?php
        printf('<small>%s</small>', __('Authentication', $this->plugin_name));
        echo $config->apikey;
        ?>
    </div>
    <div class="col-1">
        <?php
        printf('<small>%s</small>', __('Project ID', $this->plugin_name));
        echo $endpoint_data['project'];
        ?>
    </div>
    <div class="col-1">
        <?php
        printf('<small>%s</small>', __('Media upload', $this->plugin_name));
        echo ($config->upload_media == 1 ? __('Yes', $this->plugin_name) : __('No', $this->plugin_name));
        ?>
    </div>
    <div class="col-3 text-center">
        <?php
        if (property_exists($campaign->bugtracker, 'default_bug')) {
            printf(
                '<button id="update_default_bug" type="button" class="btn btn-primary">%s</button><br>',
                __('Update default bug', $this->plugin_name)
            );
            printf(
                '<a href="%s" target="_blank">%s <i class="fa fa-external"></i></a>',
                $campaign->bugtracker->default_bug,
                __('Show default bug', $this->plugin_name)
            );
        } else {
            printf(
                '<button id="import_default_bug" type="button" class="btn btn-primary">%s</button><br>',
                __('Import default bug', $this->plugin_name)
            );
        }
        ?>
    </div>
    <div class="col-1 text-right actions">
        <button data-toggle="modal" data-target="#setup_manually_cp_modal" type="button" class="btn btn-secondary mr-1"><i class="fa fa-pencil"></i></button>
        <button id="delete_tracker_settings" type="button" class="btn btn-secondary mr-1"><i class="fa fa-trash"></i></button>
    </div>
</div>