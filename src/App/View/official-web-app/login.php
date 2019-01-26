<?php use App\View\Helper\AlertBox; ?>

<section class="intro">
    <div class="">
        <br>
        <div class="container">
            <div class="row">
                <?php if (isset($message)) {
                    $box = new AlertBox();
                    echo $box->alertBox($message);
                }
                ?>
                <div class="col-md-8 col-md-offset-2">

                    <h1><img src="/img/skull_and_crossbones.png" /> Login</h1>
                    <div class="page-scroll">
                        <div class="well overflow" style="color: black;">
                            <?= $form->render(); ?>
                            <?php if(isset($email)) { ?>
                            <a class="pull-left" href="/website/forgot-password/<?= $this->e($email) ;?>">Forgot your password?</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>