<?php
foreach ($word_list as $news) {
    print_r($news['search_word']);
    echo "<br>";
    print_r($news['replace_word']);
    echo "<br>";
    //foreach ($news as $row) {
        //print_r($row);
        //echo '<li>'.$news.'</li>';
    //}
    echo "<br>";
}
?>