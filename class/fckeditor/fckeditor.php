<?php
/*
 * FCKeditor - The text editor for Internet - http://www.fckeditor.net
 * Copyright (C) 2003-2007 Frederico Caldeira Knabben
 *
 * == BEGIN LICENSE ==
 *
 * Licensed under the terms of any of the following licenses at your
 * choice:
 *
 *  - GNU General Public License Version 2 or later (the "GPL")
 *    http://www.gnu.org/licenses/gpl.html
 *
 *  - GNU Lesser General Public License Version 2.1 or later (the "LGPL")
 *    http://www.gnu.org/licenses/lgpl.html
 *
 *  - Mozilla Public License Version 1.1 or later (the "MPL")
 *    http://www.mozilla.org/MPL/MPL-1.1.html
 *
 * == END LICENSE ==
 *
 * This is the integration file for PHP (All versions).
 *
 * It loads the correct integration file based on the PHP version (avoinding
 * strict error messages with PHP 5).
 */
 
$tadabspath=explode("modules",str_replace("\\","/",dirname(__FILE__)));
include_once($tadabspath[0]."mainfile.php");
$mod_path=explode("/",$tadabspath[1]);
$mdir=$mod_path[1];

if ( version_compare( phpversion(), '5', '<' ) )
	include_once( XOOPS_ROOT_PATH."/modules/{$mdir}/class/fckeditor/fckeditor_php4.php" ) ;
else
	include_once( XOOPS_ROOT_PATH."/modules/{$mdir}/class/fckeditor/fckeditor_php5.php" ) ;

?>
