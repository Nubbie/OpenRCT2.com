<?php
/*****************************************************************************
 * Copyright (c) 2015 Jarno Veuger / Limetric.com
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *****************************************************************************/

class Database
{
	var $link = NULL;

	const WHERE_METHOD_OR = 1;
	const WHERE_METHOD_AND = 2;
	
	/**
	* Construct
	*/
	function __construct($host,$username,$password,$dbname)
	{
		//Setup connection
		$this->link = new mysqli($host,$username,$password,$dbname);

		//Check for connection errors
		if($this->link->connect_errno)
		    trigger_error("Error connecting: #". $this->link->connect_errno ." - ". $this->link->connect_error . PHP_EOL);
		
		//Set charset to UTF8 if not set already
		if ($this->link->get_charset() != 'utf8')
			if (!$this->link->set_charset("utf8"))
				trigger_error("Error setting charset to UTF8: #". $this->link->errno ." - ". $this->link->error . PHP_EOL);
			
		//Note: We're not saving the precious password etc.
	}
	
	/**
	* Read host information
	*/
	public function readHostInfo($querycode)
	{
		//Return host_info
		return $this->link->host_info;
	}

	/**
	* Check if connection is active
	*/
	public function isConnected()
	{
		//
		return (bool)$this->link->ping();
	}
	
	/**
	* Retrieves MySQLi error
	*/
	public function getError()
	{
		//
		return (bool)$this->link->error();
	}
	

	/**
	* Escapes a string (alias)
	*/
	public function escape($string)
	{
		//Return escaped string
		return $this->link->real_escape_string($string);
	}

	/**
	*
	*/
	public function readQuery($query)
	{
		//Execute query
		$result = $this->link->query($query);

		//Check for query error
		if ($result === false)
			return false;
		
		//Push results to array	
		$output = $result->fetch_object();

		//Output array
		return $output;
	}
	
	/**
	*
	*/
	public function readQueryMulti($query)
	{
		//Execute query
		$result = $this->executeQuery($query);

		//Check for query error
		if ($result === false)
			return false;
		
		//Push results to array
		$output = array();		
		while($object = $result->fetch_object())
			$output[] = $object;
		
		//Check for empty result
		if(count($output) == 0)
			return false;

		//Output array
		return $output;
	}

	/**
	* Build and execute insert query based on an array
	*/
	public function insertArray($tablename,$array,$return_insert_id = false)
	{
		//Check for invalid array
		if (!is_array($array) && count($array) > 0)
			return false;
	
		//Build query array
		$setarray = array();
		foreach($array as $key => $value)
		{
			if (is_bool($value))
				$value_final = (int)$value;
			elseif (is_numeric($value))
				$value_final = (int)$value;
			else
				$value_final = "'". $this->escape($value) ."'";
			
			$setarray[] = "`". $this->escape($key) ."` = ". $value_final ."";
		}
		
		//Implode query array
		$set = implode(", ",$setarray);	
		
		//Build actual query
		$query = "INSERT INTO `" . $this->escape($tablename) . "` SET ". $set;
		
		//Execute query, check if it failed
		if(!$this->executeQuery($query))
			return false;
			
		//Check if we return the insert_id
		if((bool)$return_insert_id == true)
			return $this->link->insert_id;
		
		//
		return true;
	}
	
	/**
	* Build and execute update query based on an array
	*/
	public function updateArray($tablename,$values,$where_clause)
	{
		//Check for invalid array
		if (!is_array($values) && count($values) > 0)
			return false;
	
		//Build values array
		$values_array = array();
		foreach($values as $key => $value)
		{
			if (is_bool($value))
				$value_final = (int)$value;
			elseif (is_numeric($value))
				$value_final = (int)$value;
			elseif (is_string($value))
				$value_final = "'". $this->escape($value) ."'";
			else
				continue;
			
			$values_array[] = "`". $this->escape($key) ."` = ". $value_final ."";
		}
		
		//Implode query value array
		$query_value = implode(', ',$values_array);	

		//Build where clause array
		$where_clause_array = array();
		foreach($where_clause as $key => $value)
		{
			if ($key == '.method')
				continue;

			if (is_bool($value))
				$value_final = (int)$value;
			elseif (is_numeric($value))
				$value_final = (int)$value;
			elseif (is_string($value))
				$value_final = "'". $this->escape($value) ."'";
			else
				continue;
			
			$where_clause_array[] = "`". $this->escape($key) ."` = ". $value_final ."";
		}

		$where_clause_type = 'AND';
		if (isset($where_clause_array['.method']))
		{
			if ($where_clause_array['.method'] = WHERE_METHOD_OR)
				$where_clause_type = 'OR';
		}
		
		//Implode query where clause array
		$query_where_clause = implode(' '. $where_clause_type .' ',$where_clause_array);	
		
		//Build actual query
		$query = "UPDATE `" . $this->escape($tablename) . "` SET ". $query_value . (isset($query_where_clause) ? ' WHERE '. $query_where_clause : '');
		

		//Execute query, check if it failed
		if(!$this->executeQuery($query))
			return false;

		return true;
	}
	
	/**
	* Build and execute delete query
	*/
	public function deleteRows($tablename,$where_key,$where_value)
	{
		//Build initial query
		$query = "DELETE FROM `". $this->escape($tablename) ."`";
		
		//Build where-clausule
		if (isset($where_key) && !empty($where_key) && isset($where_value))
			$query .= " WHERE `". $this->escape($where_key) ."` = ". (is_numeric($where_value) ? (int)$where_value : "'". $this->escape($where_value) ."'");
		
		//Execute query
		return $this->executeQuery($q);
	}
	
	/**
	* Execute query
	*/
	public function executeQuery($query)
	{
		//Execute actual query
		return $this->link->query($query);
	}
}