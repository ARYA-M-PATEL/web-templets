<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?= form_hidden('csrf_token', $this->security->get_csrf_hash()); ?>
<?= form_hidden('base_url', base_url()); ?>
<?= form_hidden('device_login', !empty($this->machine) ? true : false); ?>

<!-- Custom -->
<?= script('assets/js/plugins.js'.ASSETS_VERSION); ?>
<?= script('assets/js/script.js'.ASSETS_VERSION); ?>