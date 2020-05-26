<?php 
/*
 * @Author: Davide Bizzi <clochard>
 * @Date:   26/05/2020
 * @Filename: get_url_model.php
 * @Last modified by:   clochard
 * @Last modified time: 26/05/2020
 */


function appq_ic_jira_get_url_model($bugtracker) {
    $endpoint = json_decode($bugtracker->endpoint);
    
    return $endpoint->endpoint . 'browse/{bugtracker_id}';
}