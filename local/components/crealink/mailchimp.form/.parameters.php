<?php

use Crealink\Mailchimp\Api;

if (!CModule::IncludeModule("crealink.mailchimp"))
    return;

$arParams = Array(
    "API_KEY" => Array(
        "PARENT" => "BASE",
        "NAME" => "API-".GetMessage("CREALINK_MAILCHIMP_KLUC")." Mailchimp",
        "TYPE" => "TEXT",
        "DEFAULT" => "",
        "REFRESH" => "Y",
    ),
    "CHECK_SUBSCRIBES" => Array(
        "PARENT" => "BASE",
        "NAME" => GetMessage("CREALINK_MAILCHIMP_PROVERATQ_STATUS_POD"),
        "TYPE" => "CHECKBOX",
        "DEFAULT" => "Y",
    ),
);


if (!empty($arCurrentValues["API_KEY"])) {

    $mailchimp = new Api($arCurrentValues["API_KEY"]);

    $lists = $mailchimp->get('lists');
    $arLists = Array();
    foreach ($lists['lists'] as $list) {
        $arLists[$list['id']] = $list['name'];
    }
    $arParams["LIST_ID"] = Array(
        "PARENT" => "BASE",
        "NAME" => GetMessage("CREALINK_MAILCHIMP_SPISOK")." Mailchimp",
        "TYPE" => "LIST",
        "VALUES" => $arLists,
        "ADDITIONAL_VALUES" => "N",
        "REFRESH" => "Y",
    );
}


if (!empty($arCurrentValues["LIST_ID"])) {

    $arComponentParameters["GROUPS"]["GROUPINGS"] = Array(
        "NAME" => GetMessage("CREALINK_MAILCHIMP_RASSYLKI")." Mailchimp",
        "SORT" => 0,
    );

    $groupings = $mailchimp->get('lists/' . $arCurrentValues["LIST_ID"] . '/interest-categories?count=50&offset=0');

    $arGroupings = Array();
    foreach ($groupings['categories'] as $grouping) {
        $arGroupings[$grouping['id']] = $grouping['title'];
    }

    $arParams["GROUPINGS"] = Array(
        "PARENT" => "GROUPINGS",
        "NAME" => GetMessage("CREALINK_MAILCHIMP_PREDLAGATQ_RASSYLKI"),
        "TYPE" => "LIST",
        "VALUES" => $arGroupings,
        "MULTIPLE" => "Y",
        "ADDITIONAL_VALUES" => "N",
        "REFRESH" => "Y",
        'SIZE' => 15
    );

    if (is_array($arCurrentValues["GROUPINGS"])) {
        foreach ($groupings['categories'] as $grouping) {
            if (!in_array($grouping['id'], $arCurrentValues["GROUPINGS"])) continue;

            $res = $mailchimp->get('lists/' . $arCurrentValues["LIST_ID"] . '/interest-categories/' . $grouping['id'] . '/interests');

            $group_id = "GROUPING_{$grouping['id']}";

            $arComponentParameters["GROUPS"][$group_id] = Array(
                "NAME" => GetMessage("CREALINK_MAILCHIMP_RASSYLKA") . $grouping['title'],
            );

            $arGroups = Array();
            foreach ($res['interests'] as $group) {
                $arGroups[$group['id']] = $group['name'];
            }
            $arParams["{$group_id}_GROUPS"] = Array(
                "PARENT" => $group_id,
                "NAME" => GetMessage("CREALINK_MAILCHIMP_GRUPPY_V")." {$grouping['title']}",
                "TYPE" => "LIST",
                "VALUES" => $arGroups,
                "MULTIPLE" => "Y",
                "REFRESH" => "N",
            );

        }
    }
}

$arParams['AJAX_MODE'] = Array();
$arParams['CACHE_TIME'] = Array();

$arComponentParameters["PARAMETERS"] = $arParams;
