<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="ltn__category-area section-bg-1--- pt-2000 pt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area ltn__section-title-2--- text-center">
                    <h1 class="section-title text-white">Varieties</h1>
                    <p class="text-white">Maintenance mode is activated.</p>
                </div>
            </div>
        </div>
        <div class="row ltn__category-slider-active--- slick-arrow-1">
            <?php if(!empty($varieties)): foreach($varieties as $variety): ?>
                <div class="col-lg-4 col-md-4 col-sm-4 col-6">
                    <div class="ltn__category-item ltn__category-item-3 text-center br-15">
                        <div class="ltn__category-item-img">
                            <?= img($this->images_url.$variety['image']) ?>
                        </div>
                        <div class="ltn__category-item-name">
                            <h5><?= $variety['v_name'] ?></h5>
                            <h6>Available quantity : <?= $variety['avail_qty'] ?> <br> Container # : <?= $variety['container'] ?></h6>
                            <div class="error-message"><?= empty($this->api_url) ? '<div class="alert alert-danger">Set api url first</div>' : '' ?></div>
                            <?= form_open('', 'class="ajax-form"', ['v_id' => $variety['id']]); ?>
                                <?= form_input([
                                    'class' => "form-control",
                                    'id' => "quantity",
                                    'name' => "quantity",
                                    'maxlength' => 3,
                                    'placeholder' => "Enter number of quantity added",
                                    'autofocus' => ''
                                ]); ?>
                                <?php if(!empty($this->api_url)) { ?>
                                <?= form_button([
                                    'type'    => 'submit',
                                    'class'   => 'theme-btn-1 btn btn-effect-1 text-uppercase',
                                    'content' => 'add quantity'
                                ]); ?>
                                <?php } ?>
                            <?= form_close() ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; else:  ?>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="ltn__category-item ltn__category-item-3 text-center br-15">
                        <div class="ltn__category-item-name">
                            <h3>Sorry for inconvenience</h3>
                            <p>No varieties are available right now</p>
                        </div>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>