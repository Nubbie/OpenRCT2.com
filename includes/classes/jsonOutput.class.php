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

/*
	JSON output class
*/

define('JSON_RESULT_OK',0);
define('JSON_RESULT_ERROR',1);

class JSONOutput
{
	var $output_array = array();
	
	/**
	* Construct
	*/
	function __construct()
	{
		$this->output_array['result_code'] = JSON_RESULT_OK;
		$this->output_array['result_message'] = '';

		//Empty in here..
	}

	/**
	* Get value
	*/
	public function getVariable($name)
	{
		if (!isset($this->output_array[$name]))
			return false;
	
		return $this->output_array[$name];
	}
	
	/**
	* Set value
	*/
	public function setVariable($name,$value)
	{
		//Set global array
		$this->output_array[$name] = $value;

		return true;
	}
	
	/**
	* Set result
	*/
	public function setResult($code = JSON_RESULT_OK,$message = '')
	{
		//Set global array
		$this->output_array['result_code'] = (int)$code;
		$this->output_array['result_message'] = $message;

		return true;
	}

	/**
	* Flush
	*/	
	public function flush($code = JSON_RESULT_OK,$message = '')
	{
		//
		if ((int)$code != JSON_RESULT_OK || !empty($message))
			$this->setResult((int)$code,$message);

		//Set header if possible
		if (!headers_sent())
			header('Content-Type: application/json');
			//header('Content-Type: text/plain');

		//Output array
		echo json_encode($this->output_array);

		//Exit
		exit;

		//Why return when there's an exit?
		return true;
	}
}