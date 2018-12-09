<script type="text/javascript" src="/js/pwstrength.min.js"></script>
<section class="intro">
    <div class="">
        <br>
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">

                    <h1><img src="/img/skull_and_crossbones.png" /> Register</h1>
                    <div class="page-scroll">
                        <div class="well" style="color: black;">
                            <?= $form->render(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    $('#regpassword').pwstrength({
        ui: {
            showVerdictsInsideProgressBar: true,
            bootstrap3: true,
            progressBarEmptyPercentage: 15,
            progressBarMinPercentage: 15
        }
    });
</script>