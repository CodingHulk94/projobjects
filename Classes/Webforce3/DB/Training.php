<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes\Webforce3\DB;

use Classes\Webforce3\Config\Config;

/**
 * Description of Training
 *
 * @author Etudiant
 */
class Training extends DbObject{
	
	protected $name;
	
	function __construct($id=0, $name='', $inserted=0) {
		$this->name = $name;
		
		parent::__construct($id, $inserted);
	}
	
	function getName() {
		return $this->name;
	}

	function setName($name) {
		$this->name = $name;
	}

	
	

	public static function deleteById($id) {
		
	}

	public static function get($id) {
		$sql = '
			SELECT *
			FROM training
			WHERE tra_id = :id
		';
		
		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		$stmt->bindValue(':id', $id, \PDO::PARAM_INT);
		
		if ($stmt->execute() === false) {
			throw new InvalidSqlQueryException($sql, $stmt);
		}
		else {
			$row = $stmt->fetch(\PDO::FETCH_ASSOC);
			if (!empty($row)) {
				$locationObject = new City(
					$row['tra_id'],
					$row['tra_name']
				);
				return $locationObject;
			}
		}	
	}

	public static function getAll() {
		
	}

	public static function getAllForSelect() {
		$returnList = array();

		$sql = '
			SELECT tra_id, tra_name
			FROM training
			WHERE tra_id > 0
			ORDER BY tra_name ASC
		';
		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		if ($stmt->execute() === false) {
			print_r($stmt->errorInfo());
		}
		else {
			$allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			foreach ($allDatas as $row) {
				$returnList[$row['tra_id']] = $row['tra_name'];
			}
		}

		return $returnList;
	}
	

	public function saveDB() {
		
	}

}
