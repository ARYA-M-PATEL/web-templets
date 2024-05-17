<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="ltn__category-area section-bg-1--- page_content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area ltn__section-title-2--- text-center">
                    <h1 class="section-title text-white">Scan & Pay</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="ltn__category-item ltn__category-item-3 text-center br-15">
                    <div class="ltn__category-item-img">
                        <h1>Please do not refresh this page...</h1>
                    </div>
                    <div class="ltn__category-item-name"></div>
                </div>
            </div>
        </div>
        <form method="post" action="<?php echo PAYTM_TXN_URL ?>" name="f1">
            <?php
                foreach($paramList as $name => $value) {
                    echo '<input type="hidden" name="' . $name .'" value="' . $value . '">';
                }
                echo '<input type="hidden" name="CHECKSUMHASH" value="' . $checkSum . '">';
            ?>
        </form>
    </div>
</div>
<script>
    // document.f1.submit();
</script>