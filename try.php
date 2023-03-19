<?php

# open html file and parse it
function openParseHTML(){
    $html = new DOMDocument();
    $html->formatOutput = true;
    $htmlpath = $argv[2] or exit("html file should be provided or provide a description");
    $html->loadHTMLFile($htmlpath);
    $htmlTitle = $html->getElementById('title-rss');
    $htmlDescription = $html->getElementById('description-rss');
}

# open rss.xml and create the file if if didn't exist in the working dir
function openParseOrCreateXML(){
    $xml = simplexml_load_file("rss.xml") or die("file not found");
    $xml = new DOMDocument("1.0");
    $xml->encoding="utf-8";
    $xml->formatOutput=true;
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

    # user configs
    $configs = include 'config.php';

    $author->textContent = $configs['author'];
    $title->textContent = $configs['title'];
    $link->textContent = $configs['link'];
    $description->textContent = $configs['description'];
    $lastBuildDate->textContent = date("m d y");

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
    $itemGuid->textContent =$fileLink; 
    $itemPubDate->textContent =date("y m d");

    $rssItem->appendChild($itemTitle);
    $rssItem->appendChild($itemLink);
    $rssItem->appendChild($itemDescription);
    $rssItem->appendChild($itemGuid);
    $rssItem->appendChild($itemPubDate);

    $channel->appendChild($rssItem);
}
echo "".$xml->saveXML()."";
?>

