<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="ltn__category-area section-bg-1--- page_content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area ltn__section-title-2--- text-center">
                    <h1 class="section-title text-white"><?= $message ?></h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-1 col-md-1 col-sm-1 col-12"></div>
            <div class="col-lg-10 col-md-10 col-sm-10 col-12">
                <div class="ltn__category-item ltn__category-item-3 text-center br-15">
                    <h2 class="mt-3">
                        <?= !empty($disableThankyou) ? $disableThankyou : 'Have you placed container/bag below the dispensing nozzle?'; ?>
                    </h2>
                    <div class="ltn__category-item-name">
                        <?= anchor(!empty($disableThankyou) ? "" : "filling/{$quantity['v_id']}/{$quantity['id']}", !empty($disableThankyou) ? "Back to Home" : 'Yes', 'class="theme-btn-1 btn btn-effect-1 text-uppercase"'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>