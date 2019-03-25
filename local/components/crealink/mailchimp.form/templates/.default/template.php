<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div id="mc_embed_signup">
    <form action="" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate"
          autocomplete="off">
        <h2><?= GetMessage("CREALINK_MAILCHIMP_PODPISYVAYTESQ_NA_NA") ?></h2>

        <? if (!empty($arResult['ERRORS'])): ?>
            <? foreach ($arResult['ERRORS'] as $sError): ?>
                <p class="error"><?= $sError; ?></p>
            <? endforeach; ?>
        <? endif; ?>

        <? if (!empty($arResult['MESSAGES'])): ?>
            <? foreach ($arResult['MESSAGES'] as $sMessage): ?>
                <p class="info"><?= $sMessage; ?></p>
            <? endforeach; ?>
        <? endif; ?>

        <? if (!$USER->isAuthorized()): ?>
            <div class="indicates-required"><span
                    class="asterisk">*</span> <?= GetMessage("CREALINK_MAILCHIMP_OBAZATELQNYE_POLA") ?></div>
            <div class="mc-field-group">
                <label for="mce-EMAIL">Email <span class="asterisk">*</span></label>
                <input type="email" value="<?= $arResult['EMAIL']; ?>" name="email" class="required email" required>
            </div>
            <div class="mc-field-group">
                <label><?= GetMessage("CREALINK_MAILCHIMP_IMA") ?></label>
                <input type="text" value="<?= $arResult['FNAME']; ?>" name="fname" class="">
            </div>
            <div class="mc-field-group">
                <label><?= GetMessage("CREALINK_MAILCHIMP_FAMILIA") ?></label>
                <input type="text" value="<?= $arResult['LNAME']; ?>" name="lname" class="">
            </div>
        <? endif; ?>

        <? foreach ($arResult['GROUPINGS'] as $arGrouping): ?>
            <? if (empty($arGrouping['GROUPS'])) continue; ?>
            <div class="mc-field-group input-group">
                <strong><?= $arGrouping['NAME']; ?> </strong>
                <ul>
                    <? foreach ($arGrouping['GROUPS'] as $arGroup): ?>
                        <li>
                            <label>
                                <input type="checkbox" value="<?= $arGroup['ID']; ?>" autocomplete="off"
                                       name="groups[<?= $arGrouping['ID']; ?>][]" <?= ($arGroup['SUBSCRIBED'] == 'Y' ? 'checked' : ''); ?>>&nbsp;<?= $arGroup['NAME']; ?>
                            </label>
                        </li>
                    <? endforeach; ?>
                </ul>
            </div>
        <? endforeach; ?>

        <div class="clear">
            <? if (!empty($arResult['USER'])): ?>
                <input type="submit" value="<?= GetMessage("CREALINK_MAILCHIMP_OBNOVITQ_PODPISKU") ?>" name="subscribe"
                       id="mc-embedded-subscribe"
                       class="button">
            <? else: ?>
                <input type="submit" value="<?= GetMessage("CREALINK_MAILCHIMP_PODPISATQSA") ?>" name="subscribe"
                       id="mc-embedded-subscribe" class="button">
            <?endif; ?>
        </div>
    </form>
</div>
