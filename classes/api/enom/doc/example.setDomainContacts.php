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

$contacts = array();
$contacts['registrant']['EmailAddress'] = 'foo@some-email.com';
$contacts['registrant']['Fax'] = '20.1234567890';
$contacts['registrant']['Phone'] = '20.1234567890';
$contacts['registrant']['Country'] = 'Egypt';
$contacts['registrant']['PostalCode'] = '33333';
$contacts['registrant']['StateProvinceChoice'] = '';
$contacts['registrant']['City'] = 'Cairo';
$contacts['registrant']['Address1'] = '10 FOO STREET.';
$contacts['registrant']['Address2'] = '';
$contacts['registrant']['FirstName'] = 'Bakr';
$contacts['registrant']['LastName'] = 'Alsharif';
$contacts['registrant']['JobTitle'] = 'Chairman';
$contacts['registrant']['OrganizationName'] = 'SysTurn';

$result = $enom->setDomainContacts('systurn.com', $contacts);

echo '<pre>';
var_dump($result);
echo '</pre>';

?>
