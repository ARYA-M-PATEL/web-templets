<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="ltn__category-area section-bg-1--- page_content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area ltn__section-title-2--- text-center">
                    <h1 class="section-title text-white"><?= $title ?></h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-1 col-md-1 col-sm-1 col-12"></div>
            <div class="col-lg-10 col-md-10 col-sm-10 col-12">
                <div class="ltn__category-item ltn__category-item-3 text-center br-15">
                    <h2 class="mt-3">
                        <?= !empty($message) ? $message : 'The machine is currently unable to dispense rice.'; ?>
                    </h2>
                </div>
            </div>
        </div>
    </div>
</div>