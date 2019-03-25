<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div id="mc_embed_signup">
    <form action="" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate">
        <input type="hidden" value="yes">
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

        <div class="mc-field-group">
            <input type="email" value="<?= $arResult['EMAIL']; ?>" name="email" class="required email"
                   placeholder="Email" required>
            <input type="submit" value="<?=GetMessage("CREALINK_MAILCHIMP_PODPISATQSA")?>" name="subscribe" id="mc-embedded-subscribe" class="button">
        </div>

    </form>
</div>
