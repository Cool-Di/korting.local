<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = array(
    "NAME" => GetMessage("CREALINK_MAILCHIMP_FORMA_PODPISKI")." Mailchimp",
    "DESCRIPTION" => GetMessage("CREALINK_MAILCHIMP_KOMPONENT_OTOBRAJAET"),
    "ICON" => "/images/subscr_form.gif",
    "SORT" => 20,
    "CACHE_PATH" => "Y",
    /*"PATH" => array(
        "ID" => "service",
        "CHILD" => array(
            "ID" => "subscribe",
            "NAME" => "Рассылки"
        )
    ),*/
    "PATH" => array(
        "ID" => "crealink",
        "NAME" => GetMessage("CREALINK_MAILCHIMP_KREALINK"),
    ),
);

?>