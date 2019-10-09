<?php

// namespace Aiur\Moviex;

//LÄGG TILL?
// namespace Aiur\Moviex;

// Get essentials
// require __DIR__ . "/../../vendor/autoload.php";




/**
 * Show all movies?
 */
$app->router->get("filter-text", function () use ($app) {
    $title = "Textfilter";
    $filter = new \Aiur\MyTextFilter\MyTextFilter();
    $text = "En [b]fet[/b] moped. http://www.google.com ..... One line.\nAnother line.";
    $html = $filter->parse($text, ["bbcode", "link", "nl2br"]);
    // echo "1";
    // echo $html;
    // echo "<br>";



    $filter2 = new \Aiur\MyTextFilter\MyTextFilter();
    $text2 = file_get_contents(__DIR__ . "/../src/sample.md");
    // echo "<br>";
    // echo $text2;

    $html2 = $filter->parse($text2, ["markdown"]);
    // echo "2";
    // echo $html2;
    // echo "<br>";


    $data = [
        "text" => $text,
        "text2" => $text2,
        "html" => $html,
        "html2" => $html2,
    ];

    $app->page->add("filtertext/filter-text", $data);

    return $app->page->render([
        "title" => $title,
    ]);
});
