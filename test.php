<?php
$xmldata = simplexml_load_file("test.rss");
echo $xmldata->channel->title;
?>
