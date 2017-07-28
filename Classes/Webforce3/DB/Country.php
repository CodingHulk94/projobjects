<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes\Webforce3\DB;
use Classes\Webforce3\Config\Config;
use Classes\Webforce3\Exceptions\InvalidSqlQueryException;
/**
 * Description of Country
 *
 * @author Etudiant
 */
class Country extends DbObject{
	/** @var string */
    protected $name;
 
        function __construct($id=0, $name='', $inserted=0) {
            $this->name = $name;
            
            parent::__construct($id, $inserted);
        }
        
    /**
     * @param int $id
     * @return DbObject
     */
    public static function get($id) {
        // TODO: Implement get() method.
        $sql = '
            SELECT cou_id, cou_name
            FROM country
            WHERE cou_id = :id
            ORDER BY cou_name ASC
        ';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        if ($stmt->execute() === false) {
            throw new InvalidSqlQueryException($sql, $stmt);
        }
        else {
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!empty($row)) {
                $currentObject = new Country(
                    $row['cou_id'],
                    $row['cou_name']
                );
                return $currentObject;
            }
        }
        return false;   
    }
    /**
     * @return DbObject[]
     */
    public static function getAll() {
        // TODO: Implement getAll() method.
        $returnList = array();
        
        $sql = '
            SELECT cou_id, cou_name
            FROM country
            WHERE cou_id > 0
            ORDER BY cou_name ASC
        ';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        if ($stmt->execute() === false) {
            throw new InvalidSqlQueryException($sql, $stmt);
        }
        else {
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!empty($row)) {
                $currentObject = new Country(
                    $row['cou_id'],
                    $row['cou_name']
                );
                $returnList[] = $currentObject;
            }
        }
        return $returnList;
    }
    /**
     * @return array
     */
    public static function getAllForSelect() {
        $returnList = array();
        $sql = '
            SELECT cou_id, cou_name
            FROM country
            WHERE cou_id > 0
            ORDER BY cou_name ASC
        ';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        if ($stmt->execute() === false) {
            print_r($stmt->errorInfo());
        }
        else {
            $allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($allDatas as $row) {
                $returnList[$row['cou_id']] = $row['cou_name'];
            }
        }
        return $returnList;
    }
    /**
     * @return bool
     */
    public function saveDB() {
        // TODO: Implement saveDB() method.
        if ($this->id > 0) {
            $sql = '
                    UPDATE country
                    SET cou_name = :name,
                    WHERE cou_id = :id
            ';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
            $stmt->bindValue(':name', $this->name);
            if ($stmt->execute() === false) {
                    throw new InvalidSqlQueryException($sql, $stmt);
            }
            else {
                    return true;
            }
        }
        else {
            $sql = '
                    INSERT INTO country (cou_name)
                    VALUES (:name)
            ';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
//            $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
            $stmt->bindValue(':name', $this->name);
            if ($stmt->execute() === false) {
                    throw new InvalidSqlQueryException($sql, $stmt);
            }
            else {
                    $this->id = Config::getInstance()->getPDO()->lastInsertId();
                    return true;
            }
        }
            return false;
    }
    /**
     * @param int $id
     * @return bool
     */
    public static function deleteById($id) {
        // TODO: Implement deleteById() method.
        $sql = '
            DELETE FROM country WHERE cou_id = :id
            ';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        
        if ($stmt->execute() === false) {
            print_r($stmt->errorInfo());
        }
        else {
            return true;
        }
        return false;
    }
    function getName() {
        return $this->name;
    }
    function setName($name) {
        $this->name = $name;
    }

}
