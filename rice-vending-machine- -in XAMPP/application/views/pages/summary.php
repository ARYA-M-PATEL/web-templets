<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="ltn__category-area section-bg-1--- page_content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area ltn__section-title-2--- text-center">
                    <h1 class="section-title text-white">Summary</h1>
                    <p class="text-white">Please confirm your order.</p>
                </div>
            </div>
        </div>
        <div class="row ltn__category-slider-active--- slick-arrow-1 sticker">
            <div class="col-2"></div>
            <div class="col-lg-8 col-md-8 col-sm-8 col-12">
                <div class="ltn__category-item ltn__category-item-3 br-15">
                    <div class="row">
                        <div class="col-4 text-center">
                            <div class="ltn__category-item-img p-4">
                                <?php if($this->machine['m_type'] === 'Print voucher') {
                                    echo anchor("print/{$quantity['v_id']}/{$quantity['id']}", img($this->images_url.$variety['image']));
                                } else {
                                    echo anchor("scan/{$quantity['v_id']}/{$quantity['id']}", img($this->images_url.$variety['image']));
                                } ?>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="ltn__category-item-name ">
                                <h3>
                                    <?= ucfirst($variety['v_name']) ?>
                                </h3>
                                <p>
                                    <?= $variety['description'] ?>
                                </p>
                                <h5><?= "{$quantity['currency']} {$quantity['price']}" ?>
                                    (<?= "{$quantity['unit_value']} {$quantity['unit_id']}" ?>)</h5>
                                <?php if($this->machine['m_type'] === 'Print voucher') {
                                    echo anchor("print/{$quantity['v_id']}/{$quantity['id']}", 'Print voucher', 'class="theme-btn-1 btn btn-effect-1 text-uppercase hide-in-print"');
                                } else {
                                    echo anchor("scan/{$quantity['v_id']}/{$quantity['id']}", 'continue to pay', 'class="theme-btn-1 btn btn-effect-1 text-uppercase hide-in-print"');                                    
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-2"></div>
            <div class="col-lg-8">
                <div class="section-title-area ltn__section-title-2--- mt-5">
                    <?= anchor("shop/{$quantity['v_id']}", 'Back', 'class="theme-btn-1 btn btn-effect-1 text-uppercase"') ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
// window.onload = function() { window.print(); }
</script>