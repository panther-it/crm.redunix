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

$result = $enom->checkDomain('systurn', 'com', true);   // This enables domain spinner
//$result = $enom->checkDomain('systurn', 'com');
// $result = $enom->checkDomain('systurn', 'com,net');
// $result = $enom->checkDomain('systurn', '@');        // This checks com, net, and org TLDs
// $result = $enom->checkDomain('systurn', '*1');       // This checks com, net, org, info, biz, us, and ws TLDs
// $result = $enom->checkDomain('systurn', '*2');       // This checks com, net, org, info, biz, and us TLDs
// $result = $enom->checkDomain('systurn', '*');        // This checks 11 of the most commonly used TLDs

echo '<pre>';
var_dump($result);
echo '</pre>';

?>
