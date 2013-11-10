<?php
/*
 * This file is part of PussyPress
 * Copyright (C) 2013  Carlos Garcia Gomez  neorazorx@gmail.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require 'post.class.php';

/// cargamos todos los posts
$all_posts = array();
foreach(scandir('.') as $year)
{
	if( is_dir($year) AND is_numeric($year) )
   {
   	foreach(scandir($year) as $month)
      {
      	if( is_dir($year.'/'.$month) AND is_numeric($month) )
         {
         	foreach(scandir($year.'/'.$month) as $file)
            {
               if( substr($file, -5) == '.post' )
               	$all_posts[] = new post($year.'/'.$month.'/'.$file);
            }
         }
      }
   }
}


$year = '';
$month = '';
foreach($all_posts as $i => $value)
{
   /// enlazamos los posts y generamos el HTML
	if( isset($all_posts[$i-1]) )
      $all_posts[$i]->previous_link = $all_posts[$i-1]->link;

	if( isset($all_posts[$i+1]) )
      $all_posts[$i]->next_link = $all_posts[$i+1]->link;

	$all_posts[$i]->compile($value->link);

   /*
    * También hay que generar el html de los listados de posts
    * para cada mes y cada año.
    */
	if($value->year() != $year)
	{
		$year = $value->year();
      $all_posts[$i]->compile($year.'/index.html');
	}
	if($value->month() != $month)
	{
		$month = $value->month();
		$all_posts[$i]->compile($year.'/'.$month.'/index.html');
	}
}

/// generamos el html para la raiz.
end($all_posts)->compile('index.html');

?>