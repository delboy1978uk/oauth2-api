<?php $this->layout('/emails/layout', ['title' => $this->t('email.user.register.thanks'), 'siteUrl' => $siteUrl]) ?>
<tr>
    <td bgcolor="#ffffff" align="center" style="padding: 20px 15px 70px 15px;" class="section-padding">
        <!--[if (gte mso 9)|(IE)]>
        <table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
            <tr>
                <td align="center" valign="top" width="500">
        <![endif]-->
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 500px;" class="responsive-table">
            <tr>
                <td>
                    <!-- HERO IMAGE -->
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="padding" align="center">
                                <a href="<?= $this->e($siteUrl) ;?>" target="_blank">
                                    <img src="<?= $this->e($siteUrl) ;?>/img/emails/pirateship.jpg" width="400" height="164" border="0" alt="<?= $this->e($siteUrl) ;?>" style="display: block; color: #666666;  font-family: Helvetica, arial, sans-serif; font-size: 16px;" class="img-max">
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <!-- COPY -->
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td align="center" style="font-size: 25px; font-family: Helvetica, Arial, sans-serif; color: #333333; padding-top: 30px;" class="padding"><?= $this->t('email.user.register.thanks') ;?></td>
                                    </tr>
                                    <tr>
                                        <td align="center" style="padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding"><?= $this->t('email.user.register.body') ;?></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td align="center">
                                <!-- BULLETPROOF BUTTON -->
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td align="center" style="padding-top: 25px;" class="padding">
                                            <table border="0" cellspacing="0" cellpadding="0" class="mobile-button-container">
                                                <tr>
                                                    <td align="center" style="border-radius: 3px;" bgcolor="#256F9C">
                                                        <a href="<?= $this->e($siteUrl . $activationLink) ;?>" target="_blank" style="font-size: 16px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; border-radius: 3px; padding: 15px 25px; border: 1px solid #256F9C; display: inline-block;" class="mobile-button"><?= $this->t('email.user.register.button') ;?> &rarr;</a></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!--[if (gte mso 9)|(IE)]>
        </td>
        </tr>
        </table>
        <![endif]-->
    </td>
</tr>
