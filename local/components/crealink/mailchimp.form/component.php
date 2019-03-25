<?php use Crealink\Mailchimp\Api;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
$module_id = "crealink.mailchimp";

if (!IsModuleInstalled($module_id) || !CModule::IncludeModule($module_id)) {
    ShowError(GetMessage("CREALINK_MAILCHIMP_DLA_RABOTY_KOMPONENT") . $module_id);
    return;
}

$arParams['GROUPINGS'] = is_array($arParams['GROUPINGS']) ? $arParams['GROUPINGS'] : array();

if (empty($arParams['GROUPINGS'])) {
    ShowError(GetMessage("CREALINK_MAILCHIMP_V_NASTROYKAH_KOMPONE"));
    return;
}

if (empty($arParams['LIST_ID'])) {
    ShowError(GetMessage("CREALINK_MAILCHIMP_V_NASTROYKAH_KOMPONE1") . ' Mailchimp');
    return;
}

$arResult['EMAIL'] = $USER->isAuthorized() ? $USER->GetEmail() : $_POST['email'];
$arResult['FNAME'] = $USER->isAuthorized() ? $USER->GetFirstName() : htmlspecialcharsEx(trim($_POST['fname']));
$arResult['LNAME'] = $USER->isAuthorized() ? $USER->GetLastName() : htmlspecialcharsEx(trim($_POST['lname']));

$api_key = $arParams['API_KEY'];

if (empty($api_key)) {
    ShowError(GetMessage("CREALINK_MAILCHIMP_UKAJITE") . ' API-' . GetMessage("CREALINK_MAILCHIMP_KLUC_V_NASTROYKAH_KO"));
    return;
}

$mailchimp = new Api($api_key);

$obCache = new CPHPCache;
$cache_id = "mailchimp_form" . md5(serialize($arParams));
$cache_time = intval($arParams["CACHE_TIME"]);

if ($obCache->InitCache($cache_time, $cache_id)) {
    $vars = $obCache->GetVars();
    $arResult['GROUPINGS'] = $vars['GROUPINGS'];
} else {

    $arResult['GROUPINGS'] = array();

    $groupings = $mailchimp->get('lists/' . $arParams["LIST_ID"] . '/interest-categories');

    foreach ($groupings['categories'] as $grouping) {
        if (!in_array($grouping['id'], $arParams['GROUPINGS'])) continue;

        $groups = (is_array($arParams['GROUPING_' . $grouping['id'] . '_GROUPS']) ? $arParams['GROUPING_' . $grouping['id'] . '_GROUPS'] : array());

        $arGrouping = array(
            'ID' => $grouping['id'],
            'NAME' => $grouping['title'],
            'GROUPS' => array(),
        );
        $res = $mailchimp->get('lists/' . $arParams["LIST_ID"] . '/interest-categories/' . $grouping['id'] . '/interests');


        foreach ($res['interests'] as $group) {
            if (!in_array($group['id'], $groups)) continue;

            $arGroup = array(
                'ID' => $group['id'],
                'NAME' => $group['name'],
            );
            $arGrouping['GROUPS'][$group['id']] = $arGroup;
        }
        $arResult['GROUPINGS'][$grouping['id']] = $arGrouping;
    }
    if (empty($arResult['GROUPINGS'])) {
        $obCache->AbortDataCache();
        ShowError(GetMessage("CREALINK_MAILCHIMP_NASTROYTE_GRUPPY_V") . " Mailchimp");
        return;
    } else {
        if ($obCache->StartDataCache($cache_time, $cache_id)) {
            $obCache->EndDataCache(array(
                'GROUPINGS' => $arResult['GROUPINGS'],
            ));
        }
    }
}

if ($USER->isAuthorized() && $arParams['CHECK_SUBSCRIBES'] == 'Y') {
    $res = $mailchimp->get('lists/' . $arParams['LIST_ID'] . '/members/' . md5($USER->GetEmail()));

    if ($res['id'] && $res['status'] == 'subscribed') {
        $user = $res;
        $arResult['USER'] = array();

        foreach (array_keys($arResult['GROUPINGS']) as $group) {
            foreach ($arResult['GROUPINGS'][$group]['GROUPS'] as $interest => &$arGroup) {
                if ($user['interests'][$interest]) {
                    $arGroup['SUBSCRIBED'] = 'Y';
                    $arResult['USER'][] = $interest;
                }
            }
        }
    }


}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $arResult['ERRORS'] = array();
    $arResult['MESSAGES'] = array();

    if (empty($arResult['EMAIL']) || !check_email($arResult['EMAIL'])) {
        $arResult['ERRORS'][] = GetMessage("CREALINK_MAILCHIMP_VVEDITE_KORREKTNYY") . ' email';
    }

    $format = 'html';
    $groups = is_array($_POST['groups']) ? $_POST['groups'] : array();

    $arGroupings = array();

    $arGroups = array();
    if ($_POST['all'] !== 'yes') {
        foreach ($arResult['GROUPINGS'] as &$arGrouping) {
            foreach ($arGrouping['GROUPS'] as &$arGroup) {
                if (in_array($arGroup['ID'], $groups[$arGrouping['ID']])) {
                    $arGroup['SUBSCRIBED'] = 'Y';
                    $arGroups[$arGroup['ID']] = true;
                } else {
                    $arGroups[$arGroup['ID']] = false;
                    $arGroup['SUBSCRIBED'] = 'N';
                }
            }
            if (!empty($arGroups)) {
                $arGroupings[] = Array(
                    'id' => $arGrouping['ID'],
                    'groups' => $arGroups,
                );
            }
        }
    } else {
        foreach ($arResult['GROUPINGS'] as $arGrouping) {
            $arGroups = array();
            foreach ($arGrouping['GROUPS'] as $arGroup) {
                $arGroups[$arGroup['ID']] = true;
            }
        }
    }

    if (empty($arResult['ERRORS'])) {

        if (!empty($arGroups)) {
            $res = $mailchimp->put('lists/' . $arParams["LIST_ID"] . '/members/' . md5($arResult['EMAIL']), array(
                'email_address' => $arResult['EMAIL'],
                'merge_vars' => array(
                    'FNAME' => $arResult['FNAME'],
                    'LNAME' => $arResult['LNAME'],
                ),
                'interests' => $arGroups,
                'email_type' => $format,
                'status_if_new' => 'subscribed',
            ));

            if ($res['errors']) {
                $arError = reset($res['errors']);
                $arResult['ERRORS'][] = $arError['field'] . ':' . $arError['message'];
            } else {
                $arResult['MESSAGES'][] = GetMessage("CREALINK_MAILCHIMP_PODPISKA_OBNOVLENA");
            }
        } else {
            if (!empty($arResult['USER'])) {
                $mailchimp->delete('lists/' . $arParams["LIST_ID"] . '/members/' . md5($arResult['EMAIL']));
            } else {
                $arResult['ERRORS'][] = GetMessage("CREALINK_MAILCHIMP_NEOBHODIMO_VYBRATQ_H");
            }
        }

    }

}

$this->IncludeComponentTemplate();


?>