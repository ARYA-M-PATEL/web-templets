<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="ltn__category-area section-bg-1--- ">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area ltn__section-title-2--- text-center">
                    <h1 class="section-title text-white">Login here</h1>
                </div>
            </div>
        </div>
        <div class="row ltn__category-slider-active--- slick-arrow-1">
            <div class="col-lg-3 col-md-3 col-sm-3 col-12"></div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="ltn__category-item ltn__category-item-3 text-center br-15">
                    <div class="ltn__category-item-name">
                        <div class="error-message"><?= empty($this->api_url) ? '<div class="alert alert-danger">Set api url first</div>' : '' ?></div>
                        <?= form_open('', 'class="ajax-form"') ?>
                            <?= form_input([
                                'class' => "form-control",
                                'id' => "serial_number",
                                'name' => "serial_number",
                                'maxlength' => 20,
                                'placeholder' => "Enter Serial number of machine",
                                'autofocus' => ''
                            ]); ?>
                            <?php if(!empty($this->api_url)) { ?>
                            <?= form_button([
                                'type'    => 'submit',
                                'class'   => 'theme-btn-1 btn btn-effect-1 text-uppercase',
                                'content' => 'login'
                            ]); ?>
                            <?php } ?>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>