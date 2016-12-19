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
 		$this->iterations = $iterations;
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
	 				$unlink = unlink($filepath);
	 			}

	 			return $unlink;
	 		}

	 		return false;

	 	} catch (\Exception $e) {
	 		throw new \Exception($e->getCode().' :: '.$e->getMessage().' :: Line ('.$e->getLine().')');
	 	}
 	}

 	/**
 	 * Determines if the file exists & is read/write. If not try to change it.
 	 *
 	 * @param  string $filepath
 	 * @return bool
 	 */
 	public function fileWritable($filepath)
 	{
 		if (is_readable($filepath) && is_writable($filepath)) {
 			return true;
 		} else {
 			if (file_exists($filepath)) {
 				return chmod($filepath, 0666);
 			}
 		}

 		return false;
 	}

 	/**
 	 * Overwrites file n iterations times.
 	 *
 	 * @param class
 	 * @param class
 	 */
 	public function overwriteFile($file, $write)
 	{
 		$iterations = $this->iterations;
 		$tLine2     = 0;

 		while (!$file->eof()) {
 			$tLine = $file->ftell();
 			$line  = $file->fgets();
 			$lLine = strlen($line)-1;

 			if (0 > $lLine) {
 				continue;
 			}


 			for ($n=0; $n<$iterations; $n++) {
 				$write->fseek($tLine);
 				$write->fwrite($this->stringRand($lLine));
 			}
 		}
 	}

 	/**
 	 * Get Random string 'n' length.
 	 *
 	 * @param  integer
 	 * @return string
 	 */
 	public function stringRand($lLine)
 	{
 		$blocks = (int)(($lLine)/3);

 		if ($blocks > 1) {
 			$s = '';
 			$rest = $lLine - ($blocks*3);

			for ($n=0; $n<3; $n++) {
				$s .= str_repeat(chr(mt_rand(0, 255)), $blocks);
			}

			if (0 < $rest) {
				$s .= str_repeat(chr(mt_rand(0,255)), $rest);
			}
 		} else {
 			$s = str_repeat(chr(mt_rand(0,255)), ($lLine));
 		}

 		return $s;
 	}
}