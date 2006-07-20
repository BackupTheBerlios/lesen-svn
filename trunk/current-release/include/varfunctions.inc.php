<?php

  /**
   * miscellaneous functions
   *
   * PHP version 4 and 5
   *
   * @author 	Rodrigo Lazo <rlazo.paz@gmail.com>	
   * @license 	http://www.gnu.org/licenses/gpl.html GPL License version 2
   * @version   0.3
   */

  /**
   * Creates an "index" array, with letters as keys
   *
   * @param array $values      array with the "words" to index
   * @param string $pivot      sets the name of the key to sort at
   * @param bool $full         whether use the full name of the key or just 
   *                           the first letter
   *
   * @return array
   **/
function generateIndex($values, $pivot = '', $full = false, $case = false)
{
  //cuando se quiere toda la palabra en el array
  if ($full) {
    if ($pivot == '')
      foreach ($values as $val){
	$index = $val;
	array_push($ans[$index],$val);    
      }
    else 
      foreach ($values as $val){
	$index = $val[$pivot];
	if (isset($ans[$index][0]))
	  array_push($ans[$index],$val);
	else
	  $ans[$index] = array($val);
      }
  }
  //solo una parte
  else {
    if ($pivot == '')
      foreach ($values as $val) {
	if (!$case)
	  $index = strtolower($val{0});
	else 
	  $index = strtoupper($val{0});
	array_push($ans[$index],$val);    
      }
    else 
      foreach ($values as $val) {
	if (!$case)
	  $index = strtolower($val[$pivot]{0});
	else
	  $index = strtoupper($val[$pivot]{0});
	if (isset($ans[$index][0]))
	  array_push($ans[$index],$val);
	else
	  $ans[$index] = array($val);
      }  
  }
  return $ans;    
}

/**
 * Extract keys from an "index" and creates links to them inside the
 * same html
 *
 * @param array $values      array with the "words" to index
 *
 * @return array
 **/
function navigationIndex($values)
{
  foreach ($values as $key => $value) {
    $arr[$key] = "#".$key;
  }
  return $arr;
}

?>