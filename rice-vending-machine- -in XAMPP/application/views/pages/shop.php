<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="ltn__category-area section-bg-1--- page_content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area ltn__section-title-2--- text-center">
                    <h1 class="section-title text-white">Quantity</h1>
                    <p class="text-white">Select from below options.</p>
                </div>
            </div>
        </div>
        <div class="row ltn__category-slider-active--- slick-arrow-1">
            <div class="col-1"></div>
            <?php if(!empty($quantities)): foreach($quantities as $quantity) { ?>
                <div class="col-lg-2 col-md-2 col-sm-2 col-6">
                    <div class="ltn__category-item ltn__category-item-3 text-center br-15">
                        <div class="ltn__category-item-img">
                            <?= anchor("summary/{$quantity['v_id']}/{$quantity['id']}", img($this->images_url.$quantity['image'])) ?>
                        </div>
                        <div class="ltn__category-item-name">
                            <h5><?= "{$quantity['currency']} {$quantity['price']}" ?></h5>
                            <p>For <?= "{$quantity['unit_value']} {$quantity['unit_id']}" ?></p>
                            <?= anchor("summary/{$quantity['v_id']}/{$quantity['id']}", 'select', 'class="theme-btn-1 btn btn-effect-1 text-uppercase"') ?>
                        </div>
                    </div>
                </div>
            <?php } else: ?>
                <div class="col-lg-10 col-md-10 col-sm-10 col-10">
                    <div class="ltn__category-item ltn__category-item-3 text-center br-15">
                        <div class="ltn__category-item-name">
                            <h3>Sorry for inconvenience</h3>
                            <p>No Quantity option is available for <?= $variety['v_name']; ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-11">
                <div class="section-title-area ltn__section-title-2--- mt-5">
                    <?= anchor('varieties', 'Back', 'class="theme-btn-1 btn btn-effect-1 text-uppercase"') ?>
                </div>
            </div>
        </div>
    </div>
</div>