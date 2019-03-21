<?
echo '1111';
$version = curl_version();
print_r($version);
phpinfo();
exit();
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

//if(isset($_REQUEST['test']))
//	$USER->Authorize(1);

exit();
$lnk = mysql_connect('localhost', 'krtg_mysql', 'dwuv7skr')
       or die ('Not connected : ' . mysql_error());

// сделать foo текущей базой данных
mysql_select_db('krtg_academy', $lnk) or die ('Can\'t use foo : ' . mysql_error());

//$result = mysql_query("UPDATE b_agent SET ACTIVE = \"N\" ");
$result = mysql_query("SELECT * FROM b_agent");

    while ($row = mysql_fetch_assoc($result)) {
        echo '<pre>'.print_r($row, 1).'</pre>';
    }


exit();


phpinfo();

exit();

if (mail("obdj@yandex.ru","test subject kokon", "test body kokon","From: test@yandex.ru"))
	echo "1";
else
	echo "2";
	
exit();


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

CModule::IncludeModule('iblock');

dump($_SESSION['TESTS']);
echo intval((21 * 100) / 30);

exit();

$section_id;
$test_result		= array();
$all_test_result 	= array();
$arSelect 			= Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_SERIALIZE");
$arFilter 			= Array("IBLOCK_ID"=>IntVal(19), "PROPERTY_TEST" => 103);
$res 				= CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>999), $arSelect);
while($ob = $res->GetNextElement())
{
	$arFields	 		= $ob->GetFields();
	//echo htmlspecialchars_decode($arFields['PROPERTY_SERIALIZE_VALUE']['TEXT']).'<br/>';
	if(trim($arFields['PROPERTY_SERIALIZE_VALUE']['TEXT']) != '')
	{
		$answer_data		= unserialize(htmlspecialchars_decode($arFields['PROPERTY_SERIALIZE_VALUE']['TEXT']));
		
		if(is_array($answer_data) && sizeof($answer_data) > 0)
		{
			$all_test_result[]	= $answer_data;
			foreach($answer_data as $question_id => $one_test_answer)
				$test_result[$question_id][$one_test_answer]	+= 1;
		}
	}
	
	
}
echo sizeof($all_test_result);
dump($test_result);
//dump(unserialize('a:2:{i:1293;s:1:"1";i:1294;s:1:"2";}'));

exit();
if (mail("antonmashtakov@gmail.com","test subject", "test body","From: test@yandex.ru"))
	echo "1";
else
	echo "2";
?>