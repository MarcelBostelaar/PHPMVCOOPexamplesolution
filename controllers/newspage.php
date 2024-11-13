<?php
include_once __DIR__ . "/../models/news.php";
include_once __DIR__ . "/../views/news.php";

class NewsPageController{
    public static function execute(){
        NewsModel::initializeDatabase();
        $latestNewsPost = NewsModel::getLatestNewsStory();
        NewsView::render($latestNewsPost);
    }
}