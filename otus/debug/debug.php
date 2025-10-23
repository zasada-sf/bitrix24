<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>

<?require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/lib/ExtendedDebug.php';


ExtendedDebug::logPageAccess();
echo "Логирование завершено!";?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>

