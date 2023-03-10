<?php
const RSS_URL = "http://mysite.local/news/rss.xml";
const FILE_NAME = "news.xml";
//todo TTL - time to live in seconds
const RSS_TTL = 3600;
//todo Кэширующая функция
function download($url, $filename){
    //todo file_get_contents — Читает содержимое файла в строку.
    // file_put_contents — Пишет данные в файл.
    $file = file_get_contents($url);
    if ($file) file_put_contents($filename, $file);
}
//todo Проверка, если файл не найден, то тогда создать news.xml
// и загрузить в него xml данные.
if (!is_file(FILE_NAME))
    download(RSS_URL, FILE_NAME);

?>
<!DOCTYPE html>

<html>
<head>
	<title>News feed</title>
	<meta charset="utf-8" />
</head>
<body style="background-color: #4d4d4e; color: #bd6f31;">

<h1>Latest News</h1>
<?php
 $xml = simplexml_load_file(FILE_NAME);
 foreach ($xml->channel->item as $item){
     echo <<<ITEM
    <h3>{$item->title}</h3>
    <p>
        {$item->description}<br>
        Category: {$item->category},
        Published: {$item->pubDate}
    </p>
    <p align="right">
    <a href="{$item->link}">Read more...</a>
    </p>
ITEM;
 }
 if (time() > time()+RSS_TTL)
     download(RSS_URL, FILE_NAME);
?>
</body>
</html>