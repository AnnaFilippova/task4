<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<?php
    /
    include_once('simple_html_dom.php');

    $POST_ID = $_GET['id'] ? $_GET['id'] : 1;

    $html = file_get_html('https://habrahabr.ru/post/'.$POST_ID);

    if (!$html) {
        echo json_encode(array(status => 'error'));
    }

    $data = array();

    function get_first_sentence($string) {
        $array = preg_split("/[\.!?]+/", $string, -1, PREG_SPLIT_DELIM_CAPTURE);
        return $array[0];
    }


    // надеемся и молимся что здесь ничего не отвалится в процессе....
    $data['title'] = $html->find('h1.post__title span')[0]->innertext;
    $data['first_sentence'] = get_first_sentence($html->find('.content')[0]->innertext);
    $data['date'] = trim($html->find('.post__time_published')[0]->innertext);
    $data['rating'] =$html->find('.voting-wjt__counter-score')[0]->innertext;
    $data['stars'] = $html->find('.favorite-wjt__counter')[0]->innertext;
    $data['views'] = $html->find('.views-count_post')[0]->innertext;
    $tagsEls = $html->find('.post__tags a');

    if (count($tagsEls) > 0) {
        $data['tags'] = array();
        foreach($tagsEls as $tagEl) {
            array_push($data['tags'], $tagEl->innertext);
        }
    }

    echo "<pre>";
    echo json_encode($data, JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);
    echo "</pre>";
?>
</body>
</html>
