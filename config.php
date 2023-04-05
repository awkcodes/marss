#!/usr/bin/env php


<?php

# open html file and parse it
$html = new DOMDocument();
$html->formatOutput = true;
$htmlpath = $argv[2] or exit("html file should be provided");
$html->loadHTMLFile($htmlpath);
$htmlTitle = $html->getElementById('title-rss') or exit("html element title-rss id not found");
$htmlDescription = $html->getElementById('description-rss') or exit("html element description-rss id not found");
# check for null in case this item didn't exist and use current time for pubDate
# use Y-m-d format
$htmlPubDate = $html->getElementById('pubdate-rss')->nodeValue;

# if this is an update of an old publication
# you can use the same identifier as an cmd args
$presetGuid = $argv[3] or null;
$configs = include 'config.php';
# open rss.xml file to see if it exists
$xml = new DOMDocument("1.0");
$xml->formatOutput=true;

if (!$xml->load('rss.xml')){ 
    $xml->encoding="utf-8";
    $rss = $xml->createElement("rss");
    $rss ->setAttribute("version", "2.0");
    $xml->appendChild($rss);
    $channel = $xml->createElement("channel");
    $rss->appendChild($channel);

    $author = $xml->createElement("author");
    $title = $xml->createElement("title");
    $link = $xml->createElement("link");
    $description = $xml->createElement("description");
    $lastBuildDate = $xml->createElement("lastBuildDate");
    $author->textContent = $configs['author'];
    $title->textContent = $configs['title'];
    $link->textContent = $configs['link'];
    $description->textContent = $configs['description'];
    $lastBuildDate->textContent = date("r", time());

    $channel->appendChild($title);
    $channel->appendChild($author);
    $channel->appendChild($link);
    $channel->appendChild($description);
    $channel->appendChild($lastBuildDate);

    $rssItem = $xml->createElement("item");

    $itemTitle = $xml->createElement("title");
    $itemLink = $xml->createElement("link");
    $itemDescription = $xml->createElement("description");
    $itemGuid = $xml->createElement("guid");
    $itemPubDate = $xml->createElement("pubDate");


    $fileLink = $argv[1] or exit("unable to take arg\n");
    $itemLink->textContent = $fileLink; 
    $itemDescription->textContent = $htmlDescription->nodeValue;
    $itemTitle->textContent = $htmlTitle->nodeValue;

    if ($presetGuid)  $itemGuid->textContent =$presetGuid; 
    else  $itemGuid->textContent =uniqid(rand(), true); 
    
    if ($htmlPubDate) $itemPubDate->textContent = date("D, d M Y H:i:s T", strtotime($htmlPubDate));
    else $itemPubDate->textContent = date("r", time());
    
    $rssItem->appendChild($itemTitle);
    $rssItem->appendChild($itemLink);
    $rssItem->appendChild($itemDescription);
    $rssItem->appendChild($itemGuid);
    $rssItem->appendChild($itemPubDate);

    $channel->appendChild($rssItem);
    echo $xml->saveXML();

} else {
    $channels = $xml->getElementsByTagName('channel');
    $channel = $channels[0];

    $rssItem = $xml->createElement("item");

    $itemTitle = $xml->createElement("title");
    $itemLink = $xml->createElement("link");
    $itemDescription = $xml->createElement("description");
    $itemGuid = $xml->createElement("guid");
    $itemPubDate = $xml->createElement("pubDate");


    $fileLink = $argv[1] or exit("unable to take arg\n");
    $itemLink->textContent = $fileLink; 
    $itemDescription->textContent = $htmlDescription->nodeValue;
    $itemTitle->textContent = $htmlTitle->nodeValue;

    if ($presetGuid)  $itemGuid->textContent =$presetGuid; 
    else  $itemGuid->textContent = uniqid(rand(), true); 
    
    if ($htmlPubDate) $itemPubDate->textContent = date("D, d M Y H:i:s T", strtotime($htmlPubDate));
    else $itemPubDate->textContent = date("r", time());
 
    $rssItem->appendChild($itemTitle);
    $rssItem->appendChild($itemLink);
    $rssItem->appendChild($itemDescription);
    $rssItem->appendChild($itemGuid);
    $rssItem->appendChild($itemPubDate);

    $channel->appendChild($rssItem);

    echo $xml->save("rss.xml") . " bytes written";
}
?>

