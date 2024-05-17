<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="ltn__slider-area ltn__slider-4 position-relative home_page">
        <div class="ltn__slide-one-active----- slick-slide-arrow-1----- slick-slide-dots-1----- arrow-white----- ltn__slide-animation-active">
            <video autoplay muted loop id="myVideo">
                <source src="<?= base_url($this->video_url.$this->machine['home_video']) ?>" type="video/mp4" />
            </video>
            <div class="ltn__slide-item ltn__slide-item-2 ltn__slide-item-7 bg-image--- bg-overlay-theme-black-20">
                <div class="text-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="slide-item-info sn_btn">
                                    <div class="slide-item-info-inner ltn__slide-animation">
                                        <h1 class="slide-title text-uppercase white-color animated ">
                                            <?= anchor('varieties', 'shop now', 'class="theme-btn-1 btn btn-effect-1 text-uppercase"') ?>
                                        </h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>