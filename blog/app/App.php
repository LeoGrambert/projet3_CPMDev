<?php
/**
 * User: leo
 * Date: 28/03/17
 * Time: 10:02
 */

namespace app;

use src\Database;


class App
{
    const DB_NAME = 'blog_JF';
    const DB_USER = 'root';
    const DB_PASS = 'root';
    const DB_HOST = '127.0.0.1';

    private static $_database;

    /**
     * Use Singleton
     * @return Database
     */
    public static function getDatabase(){
        if (is_null(self::$_database)){
            self::$_database = new Database(self::DB_NAME, self::DB_USER, self::DB_PASS, self::DB_HOST);
        }
        return self::$_database;
    }

}