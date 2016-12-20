<?php

namespace Shred;

/**
 * Shred - v0.5
 * @author Dani C. - dani.c.@alguncorreo.com -
 *
 * Free to use and abuse under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 */
class Shred {

	/* Number of iterations. Default = 3. */
 	private $iterations;


 	/**
 	 * Set the iterations.
 	 *
 	 * @param integer
 	 */
 	public function __construct($iterations=3)
 	{
 		$this->iterations = (int)$iterations;
 	}

 	/**
 	 * Safely Remove and/or Overwrite file
 	 *
 	 * @param  string
 	 * @param  bool
 	 * @return bool
 	 */
 	public function remove($filepath, $remove=true)
 	{
 		$unlink = true;

 		try {
	 		if ($this->fileWritable($filepath)) {
	 			$read  = new \SplFileObject($filepath, 'r');
	 			$write = new \SplFileObject($filepath, 'r+');

	 			$this->overwriteFile($read, $write);

	 			if ($remove) {
	 				$write->ftruncate(0);
	 				$unlink = unlink($filepath);
	 			}

	 			return $unlink;
	 		}

	 		echo 'false';
	 		return false;

	 	} catch (\Exception $e) {
	 		throw new \Exception($e->getCode().' :: '.$e->getMessage().' :: Line ('.$e->getLine().')');
	 	}
 	}

 	/**
 	 * Determines if the file exists & is read/write. If not try to change it.
 	 *
 	 * @param  string
 	 * @return bool
 	 */
 	public function fileWritable($filepath)
 	{
 		if (is_readable($filepath) && is_writable($filepath)) {
 			return true;
 		}

 		return false;
 	}

 	/**
 	 * Overwrites file n iterations times.
 	 *
 	 * @param class
 	 * @param class
 	 */
 	public function overwriteFile($read, $write)
 	{
 		$iterations = $this->iterations;

 		while (!$read->eof()) {
 			$line_tell   = $read->ftell();
 			$line        = $read->fgets();
 			$line_length = strlen($line);

 			if (0 === $line_length) {
 				continue;
 			}

 			for ($n=0; $n<$iterations; $n++) {
 				$write->fseek($line_tell);
 				$write->fwrite($this->stringRand($line_length));
 			}
 		}

 		return true;
 	}

 	/**
 	 * Get Random string 'n' length.
 	 *
 	 * @param  integer
 	 * @return string
 	 */
 	public function stringRand($line_length)
 	{
 		$blocks = (int)(($line_length)/3);

 		if ($blocks > 1) {
 			$s = '';
 			$rest = $line_length - ($blocks*3);

			for ($n=0; $n<3; $n++) {
				$s .= str_repeat(chr(mt_rand(0, 255)), $blocks);
			}

			if (0 < $rest) {
				$s .= str_repeat(chr(mt_rand(0,255)), $rest);
			}
 		} else {
 			$s = str_repeat(chr(mt_rand(0,255)), ($line_length));
 		}

 		return $s;
 	}
}