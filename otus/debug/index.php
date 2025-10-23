<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<h2>Часть 1</h2>
<ul>
    <li><a href="/otus/debug/page_access.txt" >Файл лога</a></li>
    <li><a href="/otus/debug/debug.php" >Файл, генерирующий лог</a></li>
    <li><a href="/bitrix/admin/fileman_file_edit.php?path=%2Flocal%2Fphp_interface%2Flib%2FExtendedDebug.php&full_src=Y&site=s1&lang=ru&&filter=Y&set_filter=Y" >Файл с классом кастомного логгера</a></li>
</ul>

<h2>Часть 2</h2>
<ul>
    <li><a href="/local/logs/log.txt" >Файл лога</a></li>
    <li><a href="/otus/debug/error.php" >Файл, генерирующий лог</a></li>
    <li><a href="https://cd52759.tw1.ru/bitrix/admin/fileman_file_edit.php?path=%2Flocal%2Fphp_interface%2Fsrc%2FOtus%2FDiag%2FFileExceptionFileLogCustom.php&full_src=Y&site=s1&lang=ru&&filter=Y&set_filter=Y">Файл с классом ловца исключений</a></li>
</ul>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>

