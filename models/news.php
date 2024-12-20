<?php
include_once __DIR__ . "/dbConnect.php";

//Uitgebreide model uitwerking. Volledig OOP datamodel (wel met global pdo object)
class NewsModel{
    private $id;
    public $date;
    public $title;
    public $body;

    function __construct(){
        $this->date = new DateTime();
        $this->title = "";
        $this->body = "";
        $this->id = null;
    }

    //Creates database table if it doesnt exist yet.
    public static function initializeDatabase(){
        global $pdo;
        $pdo->prepare(
            "CREATE TABLE IF NOT EXISTS news (
            id INT AUTO_INCREMENT PRIMARY KEY,
            date DATETIME NOT NULL,
            title VARCHAR(255) NOT NULL,
            body TEXT NOT NULL
            );")->execute();
    }

    public function getID(){
        return $this->id;
    }

    //Saves any changes.
    public function save() {
        global $pdo;
        if ($this->id != null) {
            //Id already exists, overwrite existing database entry.
            $stmt = $pdo->prepare("UPDATE news SET date = :date, title = :title, body = :body WHERE id = :id");
            $stmt->execute([':date' => $this->date->format('Y-m-d H:i:s'), ':title' => $this->title, ':body' => $this->body, ':id' => $this->id]);
        } else {
            //No id found yet, insert new, then fetch created id.
            $stmt = $pdo->prepare("INSERT INTO news (date, title, body) VALUES (:date, :title, :body)");
            $stmt->execute([':date' => $this->date->format('Y-m-d H:i:s'), ':title' => $this->title, ':body' => $this->body]);
            $idfetchstatement = $pdo->prepare("SELECT LAST_INSERT_ID();");
            $idfetchstatement->execute();
            $this->id = $idfetchstatement->fetchAll()[0]["LAST_INSERT_ID()"];
        }
    }

    public function delete() {
        global $pdo;
        if (!$this->id) {
            throw new Exception("Item doesn't exist in db");
        }

        $stmt = $pdo->prepare("DELETE FROM news WHERE id = :id");
        $stmt->execute([':id' => $this->id]);

        $this->id = null;
        $this->date = null;
        $this->title = null;
        $this->body = null;
    }


    //Load by id, returns new instance.
    public static function load($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM news WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        var_dump($data);

        if(count($data) != 1){
            return null;
        }

        //Create a new instance with the data
        return NewsModel::loadSingleResult($data[0]);
    }

    //Get the latest added story, returns new instance.
    public static function getLatestNewsStory(){
        global $pdo;
        $prepared = $pdo->prepare("SELECT * FROM news ORDER BY date DESC LIMIT 1;");
        $prepared->execute();
        $data = $prepared->fetchAll();

        //No story found
        if(count($data) == 0){
            return null;
        }
        return NewsModel::loadSingleResult($data[0]);
    }

    //utility function to transform a fetched result into an instance of this class.
    public static function loadSingleResult($data){
        if (!isset($data["id"]) || !isset($data["date"])|| !isset($data["title"])|| !isset($data["body"])){
            var_dump($data);
            throw new Exception("Some required elements not found for creating NewsPost");
        }

        $newNewsItem = new NewsModel();
        $newNewsItem->id = $data["id"];
        $newNewsItem->title = $data["title"];
        $newNewsItem->body = $data["body"];
        $newNewsItem->date = new DateTime($data["date"]);
        return $newNewsItem;
    }
}

//Voorbeeldgebruik

//Aanmaken nieuw
//$test = new NewsModel();
//$test->body = "test body";
//$test->title = "test title";
//$test->save();

//Ophalen en veranderen
//$test = NewsModel::load(1);
//$test->body = "new body 2";
//$test->save();

//Deleten
//$test = NewsModel::load(1);
//$test->delete();
