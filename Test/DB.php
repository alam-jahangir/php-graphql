<?php
/**
 * Created by PhpStorm.
 * User: jahangir<jahangir033003@gmail.com>
 * Date: 4/18/19
 * Time: 3:56 PM
 */
namespace Test;

class DB
{
    private static $pdo;

    /**
     * Connect Database
     * @param $config
     * @throws \Exception
     */
    public static function init($config)
    {
        try {
            self::$pdo = new \PDO("mysql:host={$config['host']};dbname={$config['database']}", $config['username'], $config['password']);
            self::$pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);

        } catch(PDOException $ex){
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Fetch All Data
     * @param $query
     * @return mixed
     */
    public static function fetchAll($query)
    {
        $statement = self::$pdo->query($query);
        return $statement->fetchAll();
    }

    /**
     * Fetch Specific Data
     * @param $query
     * @return mixed
     */
    public static function fetch($query)
    {
        $statement = self::$pdo->query($query);
        return $statement->fetch();
    }

    /**
     * Save Data
     * @param $prepareStmt
     * @param $data
     * @return string
     */
    public static function setData($prepareStmt, $data)
    {
        $stmt = self::$pdo->prepare($prepareStmt);
        if ($stmt->execute($data)) {
            return "Saved Successfully";
        }

        return "Failed to Save data";
    }
}