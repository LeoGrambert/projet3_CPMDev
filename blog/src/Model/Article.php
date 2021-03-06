<?php

/**
 * User: leo
 * Date: 25/03/17
 * Time: 15:56
 */

namespace src\Model;

use app\App;

/**
 * Class Article
 * @package src\Model
 */
class Article
{
    private $id;
    private $date_add;
    private $content;
    private $title;
    private $summary;
    private $picture;
    private $addAnArticle = false;
    private $updateAnArticle = false;

    /**
     * @return string
     */
    public function getUrl(){
        return 'index.php/article?id='.$this->id;
    }

    /**
     * @return mixed
     */
    public function getId(){
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getDateAdd(){
        return $this->date_add;
    }

    /**
     * @return mixed
     */
    public function getTitle(){
        return $this->title;
    }

    /**
     * @param $title
     * @return $this
     */
    public function setTitle($title){
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSummary(){
        return $this->summary;
    }

    /**
     * @param $summary
     * @return $this
     */
    public function setSummary($summary){
        $this->summary = $summary;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent(){
        return $this->content;
    }

    /**
     * @param $content
     * @return $this
     */
    public function setContent($content){
        $this->content = $content;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPicture(){
        return $this->picture;
    }

    /**
     * @param $picture
     * @return $this
     */
    public function setPicture($picture){
        $this->picture = $picture;
        return $this;
    }

    /**
     * Query to get all articles
     * @return array
     */
    public function getArticles(){
        return App::getDatabase()
            ->query(
                'SELECT * FROM Article ORDER BY date_add DESC',
                __CLASS__
            );
    }

    /**
     * Query to get all articles id in an array
     * @return array
     */
    public function getArticlesIdInArray(){
        $ids = [];
        $results = App::getDatabase()
        ->query(
            'SELECT Article.id FROM Article',
            __CLASS__
        );
        foreach ($results as $result){
            $ids [] = intval($result->getId());
        }
        return $ids;
    }

    /**
     * Query to get articles on front
     * @return array
     */
    public function getArticlesFront(){
        if(isset($_GET['p']) && $_GET['p'] > 0 && $_GET['p'] <= count($this->getArticles())){
            $curPage = $_GET['p'];
        } else {
            $curPage = 1;
        }
        $perPage = 5;
        
        return App::getDatabase()
            ->query(
                'SELECT * FROM Article ORDER BY date_add DESC LIMIT '.(($curPage-1)*$perPage).','.$perPage,
                __CLASS__
            );
    }

    /**
     * Query to get articles in admin
     * @return array
     */
    public function getArticlesAdmin(){
        if(isset($_GET['p']) && $_GET['p'] > 0 && $_GET['p'] <= count($this->getArticles())){
            $curPage = $_GET['p'];
        } else {
            $curPage = 1;
        }
        $perPage = 10;

        return App::getDatabase()
            ->query(
                'SELECT * FROM Article ORDER BY date_add DESC LIMIT '.(($curPage-1)*$perPage).','.$perPage,
                __CLASS__
            );
    }

    /**
     * Query to get one article by id
     * @return array|mixed
     */
    public function getArticleById(){
        return App::getDatabase()
            ->prepare(
                'SELECT * FROM Article WHERE id = ?',
                [$_GET['id']],
                __CLASS__,
                true
            );
    }

    /**
     * This method return an array with the next id.
     * @return array
     */
    public function getNextArticle(){
        $currentId = htmlspecialchars($_GET['id']);
        $nextArticle = App::getDatabase()
            ->query('SELECT Article.id FROM Article WHERE Article.id >'.$currentId,
                __CLASS__
            );
        return $nextArticle;
    }

    /**
     * This method return an array with all id to the displayed item
     * @return array
     */
    public function getPreviousArticle(){
        $currentId = htmlspecialchars($_GET['id']);
        $previousArticles = App::getDatabase()
            ->query('SELECT Article.id FROM Article WHERE Article.id <'.$currentId,
                __CLASS__
            );
        return $previousArticles;
    }

    /**
     * Query to add an article
     * @return array|mixed
     */
    public function addAnArticle(){
        if (empty($_POST['picture'])){
            $this->picture = "";
        } else {
            $this->picture = htmlspecialchars($_POST['picture']);
        }
        $add = App::getDatabase()
            ->prepare(
                'INSERT INTO Article (date_add, title, summary, content, picture)
                 VALUES (NOW(), ?, ?, ?, ?)',
                ([
                    htmlspecialchars($_POST['title']),
                    htmlspecialchars_decode($_POST['summary']),
                    htmlspecialchars_decode($_POST['content']),
                    $this->picture
                ]),
                __CLASS__
            );
        $this->addAnArticle = true;
        return $add;
    }

    /**
     * Method to verify that the article has been added
     * @return bool
     */
    public function getAddAnArticle(){
        return $this->addAnArticle;
    }

    /**
     * Query to delete an article
     * @return array|mixed
     */
    public function deleteAnArticle(){
        return App::getDatabase()
            ->prepare(
                'DELETE FROM Article WHERE id = ?',
                [$_POST['id']],
                __CLASS__
            );
    }

    /**
     * Query to update an article
     * @return array|mixed
     */
    public function updateAnArticle(){
        if (!isset($_POST['picture'])){
            $this->picture = "";
        } else {
            $this->picture = htmlspecialchars($_POST['picture']);
        }
        $update = App::getDatabase()
            ->prepare(
                'UPDATE Article SET title = ?, summary = ?, content = ?, picture = ? WHERE id = ?',
                ([
                    htmlspecialchars($_POST['title']),
                    htmlspecialchars_decode($_POST['summary']),
                    htmlspecialchars_decode($_POST['content']),
                    $this->picture,
                    $_GET['id']
                ]),
                __CLASS__
            );
        $this->updateAnArticle = true;
        return $update;
    }

    /**
     * Method to verify that the article has been updated
     * @return bool
     */
    public function getUpdateAnArticle(){
        return $this->updateAnArticle;
    }
}