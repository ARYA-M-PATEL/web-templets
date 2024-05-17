<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="ltn__category-area section-bg-1--- page_content ">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area ltn__section-title-2--- text-center">
                    <h1 class="section-title text-white">Varieties</h1>
                    <p class="text-white">Elevate your plate with Daawat's carefully curated rice varieties.</p>
                </div>
            </div>
        </div>
        <div class="row ltn__category-slider-active--- slick-arrow-1">
            <?php if(!empty($varieties)): foreach($varieties as $variety): ?>
                <div class="col-lg-4 col-md-4 col-sm-4 col-6">
                    <div class="ltn__category-item ltn__category-item-3 text-center br-15">
                        <div class="ltn__category-item-img">
                            <?= anchor('shop/'.$variety['id'], img($this->images_url.$variety['image'])) ?>
                        </div>
                        <div class="ltn__category-item-name">
                            <h5><?= $variety['v_name'] ?></h5>
                            <p><?= $variety['description'] ?></p>
                            <?= anchor('shop/'.$variety['id'], 'select', 'class="theme-btn-1 btn btn-effect-1 text-uppercase"') ?>
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
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area ltn__section-title-2--- mt-4">
                    <?= anchor('', 'Back', 'class="theme-btn-1 btn btn-effect-1 text-uppercase"') ?>
                </div>
            </div>
        </div>
    </div>
</div>