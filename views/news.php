<?php

class NewsView{
    public static function render($newspost){
        ?>
        <h1>News page!</h1>
        <p>Welcome to the news page</p>
        <h2>Latest</h2>
        <?php
        if($newspost == null){
            echo "<h3>No news found!</h3>";
        }
        else{
            ?>
            <h3><?php echo $newspost->title; ?></h3>
            <p>Date: <?php echo $newspost->date->format('Y-m-d');; ?></p>
            <p><?php echo $newspost->body; ?></p>
            <?php
        }
    }
}