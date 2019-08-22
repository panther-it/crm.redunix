<?php
//
// +-----------------------------------+
// |          Enom API v 1.0           |
// |      http://www.SysTurn.com       |
// +-----------------------------------+
//
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the ISLAMIC RULES and GNU Lesser General Public
//   License either version 2, or (at your option) any later version.
//
//   ISLAMIC RULES should be followed and respected if they differ
//   than terms of the GNU LESSER GENERAL PUBLIC LICENSE
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the license with this software;
//   If not, please contact support @ S y s T u r n .com to receive a copy.
//
require_once('class.EnomService.php');

$enom = new EnomService('username', '********', false, true);
$enom->debug = true;

$hosts = array();
$hosts[] = array('name'=>'@', 'type'=>'A', 'address'=>'66.150.5.189');
$hosts[] = array('name'=>'photos', 'type'=>'CNAME', 'address'=>'photos.msn.com');
$hosts[] = array('name'=>'yahoo', 'type'=>'URL', 'address'=>'204.71.200.72');
$hosts[] = array('name'=>'msn', 'type'=>'FRAME', 'address'=>'http://www.msn.com');
$hosts[] = array('name'=>'mail', 'type'=>'MX', 'address'=>'mail.yahoo.com', 'pref'=>'10');
$result = $enom->setDomainHosts('systurn.com', $hosts);

echo '<pre>';
var_dump($result);
echo '</pre>';

?>
