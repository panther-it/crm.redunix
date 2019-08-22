<?
require_once(__DIR__ . "/classes/grafix.php");
echo "<pre>
select c.id,c.name,c.company,r.id,r.name,s.id,s.name,s.floor,d.id,d.name, a.id, a.accesstype, a.accessid,c.grafix_id from racks r, suites s, datacenters d, customers c, accessdevices a, colo_access ca where r.suite = s.id and s.datacenter = d.id and ca.customer = c.id and ca.rack = r.id and ca.accessdevice = a.id;
+-----------+---------------------+-----------------------+----+--------+----+------+-------+----+------------+----+------------+----------+
| id        | name                | company               | id | name   | id | name | floor | id | name       | id | accesstype | accessid |
+-----------+---------------------+-----------------------+----+--------+----+------+-------+----+------------+----+------------+----------+
| 5083330   | Sam Terburg         | REDUNIX               |  6 | gfx-21 |  2 | POP2 | 1     |  6 | GrafiX NOC |  2 | rackkey    | 21       | 
| 5083330   | Sam Terburg         | REDUNIX               |  8 | gfx-23 |  2 | POP2 | 1     |  6 | GrafiX NOC |  4 | rackkey    | 23       | 
| 5083330   | Sam Terburg         | REDUNIX               |  4 | gfx-19 |  2 | POP2 | 1     |  6 | GrafiX NOC |  6 | rackkey    | 19       | 
+-----------+---------------------+-----------------------+----+--------+----+------+-------+----+------------+----+------------+----------+
";


echo Grafix::CreateContact("-5590");

echo "</pre>";
?>
