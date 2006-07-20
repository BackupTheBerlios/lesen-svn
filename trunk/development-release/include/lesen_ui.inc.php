<?php
/**
 * Lesen user interface - web functions
 *
 * This file contains all the functions and classes used by the lesen
 * user interface to present the information contained in the db and
 * also some other goodies like comments for each paper
 *
 * PHP version 4 and 5(not tested)
 *

 * This source code is free software; you can redistribute it
 * and/or modify it under the terms of the GNU Lesser General
 * Public License as published by the Free Software Foundation;
 * either version 2.1 of the License, or (at your option) any
 * later version.

 * This code is distributed in the hope that it will be
 * useful, but WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
 * PURPOSE. See the GNU Lesser General Public License for more
 * details.

 * You should have received a copy of the GNU Lesser General
 * Public License along with this library; if not, write to the
 * Free Software Foundation, Inc., 59 Temple Place, Suite 330,
 * Boston, MA 02111-1307 USA
 *
 *
 * @author   Rodrigo Lazo Paz <rlazo.paz@gmail.com>
 * @license  http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version  0.1
 */

require_once(dirname(__FILE__) . '/../bundled-libs/HTML_BBCodeParser/BBCodeParser.php');

/**
 * Stores a well verified comment into the database
 *
 * @return null
 * @access public
 */
function lesen_saveComment($name, $email, $url, $body, $paper_id, $par)
{
  global $db;
  $parser = new HTML_BBCodeParser(); 
  $parser->setText($body); 
  $parser->parse(); 
  $body = $parser->getParsed();

  $query = "insert into lesen_comments(author, email, url, body, 
                                       timestamp, paper_id, parent_id)
            values (" . $db->quote($name) . "," . $db->quote($email) . ","
    . $db->quote($url) . "," .$db->quote($body,'text') . "," 
    . "'".date('Y-m-d H:i:s')."'" . "," . $db->quote($paper_id) . "," . $db->quote($par) . ")";
  
  $db->query($query);
}

/**
 * Retrieves from the database all the comments of a paper
 *
 * @return array
 * @access public
 */
function lesen_getComment($paper_id)
{
  global $db;
  $query = "select * from lesen_comments where paper_id = " 
    . $db->quote($paper_id) . "order by timestamp asc";
  
  $row =& $db->queryAll($query);

  return $row;
}

?>