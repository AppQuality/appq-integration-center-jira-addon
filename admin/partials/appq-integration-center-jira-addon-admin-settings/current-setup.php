<?php
printf('<h4 class="title py-3">%s</h4>', __('Current setup', $this->plugin_name));
?>
<div class="row mb-3">
    <div class="col-1">
        <?php printf('<small>%s</small>', __('Tracker', $this->plugin_name)); ?>
        Jira logo
    </div>
    <div class="col-3">
        <?php
        printf('<small>%s</small>', __('Endpoint', $this->plugin_name));
        echo $endpoint_data['endpoint'];
        ?>
    </div>
    <div class="col-3">
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
    <div class="col-3 text-right actions">
        <?php
        if (property_exists($campaign->bugtracker, 'default_bug')) {
            printf(
                '<button id="update_default_bug" type="button" class="btn btn-secondary mr-1">%s</button>',
                __('Update bug', $this->plugin_name)
            );
            printf(
                '<a href="%s" target="_blank">%s <i class="fa fa-external"></i></a>',
                $campaign->bugtracker->default_bug,
                __('Show bug', $this->plugin_name)
            );
        } else {
            printf(
                '<button id="import_default_bug" type="button" class="btn btn-secondary mr-1">%s</button>',
                __('Import bug', $this->plugin_name)
            );
        }
        ?>
        <button data-toggle="modal" data-target="#custom_tracker_settings_modal" type="button" class="btn btn-secondary mr-1"><i class="fa fa-pencil"></i></button>
        <button data-toggle="modal" data-target="#reset_tracker_settings" type="button" class="btn btn-secondary"><i class="fa fa-trash"></i></button>
    </div>
</div>