<?php use App\View\Helper\AlertBox; ?>

<section class="intro">
    <div class="intro-body  va-top">
        <div class="container pt10">
            <div class="row">
                <?php
                if (isset($message)) {
                    $box = new AlertBox();
                    echo $box->alertBox($message);
                }
                ?>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <img alt="Logo" src="/img/skull_and_crossbones.png" />
                    <h1>Oops</h1>
                    <p class="lead">There was a problem.</p>
                </div>
            </div>
        </div>
    </div>
</section>
