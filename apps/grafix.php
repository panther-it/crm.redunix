<?
require_once(__DIR__ . "/../classes/authorization.php");
require_once(__DIR__ . "/../classes/sql/sqlcustomers.php"); 
require_once(__DIR__ . "/../classes/sql/sqlcontacts.php"); 
require_once(__DIR__ . "../lib/grafix/client_class.php");

if (!$auth->valid) $auth->redirect("/login.php");

//-- Default values --
$gfx_dc_id    = 6;
$day          = date("d");
$month        = date("m");
$year         = date("Y");
$hour         = date("H", time() + 600);
$minute       = floor(date("i", time() + 600) /5) * 5; //plus 10 minutes driving to the datacenter
$access_ids   = $_POST["access_ids"];
$message      = "";
$duration     = "60";

if (isset($_GET["quick"]))
{
	$access_ids = $_GET["quick"];
	$hour       = date("H")     ; //now
	$minute     = date("i")     ; //now
}
else
{
	if (isset($_POST["minute"]  )) $minute   = $_POST["minute"]  ;
	if (isset($_POST["hour"]    )) $hour     = $_POST["hour"]    ;
	if (isset($_POST["day"]     )) $day      = $_POST["day"]     ;
	if (isset($_POST["month"]   )) $month    = $_POST["month"]   ;
	if (isset($_POST["year"]    )) $year     = $_POST["year"]    ;
	if (isset($_POST["duration"])) $duration = $_POST["duration"];
}

 

if (isset($access_ids))
{
	if (!validKeyCard())
		$message = "Ongeldig pasnummer";
	else
        	$message = grafix_access();
}

function validKeyCard()
{
	global $access_ids;
	global $auth;
	global $database;
	global $gfx_dc_id;

        $rs        = $database->query(SqlCustomers::queryAccessDevices($auth->customer->id,"accessdevices.accesstype='card' AND datacenters.id=" . $gfx_dc_id ,Settings::ASLIST));
	$valid_ids = array();

        while($r = mysql_fetch_array($rs))
		array_push($valid_ids,$r[0]);

	if (is_array($access_ids))
	{
		$new_ids = array();
		foreach($access_ids as $id)
			if (in_array($id,$valid_ids)) 
				array_push($new_ids,$id);
		$access_ids = $new_ids;
		if (empty($access_ids))
			return FALSE;
		else
			return TRUE;
	}	
	else if (is_string($access_ids))
		if (in_array($access_ids,$valid_ids))
			return TRUE;
		else
			return FALSE;
	return FALSE;	
}

function getKeyCardIdsAsCheckBoxes()
{
	global $auth;
        global $database;
	global $gfx_dc_id;

        $rs   = $database->query(SqlCustomers::queryAccessDevices($auth->customer->id,"accessdevices.accesstype='card' AND datacenters.id=" . $gfx_dc_id,Settings::ASLIST));
        $html = "";
        while($r = mysql_fetch_array($rs))
                $html .= "<INPUT TYPE=\"checkbox\" NAME=\"access_ids\" CHECKED value=\"" . $r[0] . "\"/>" . $r[1] . "<BR/>\n";
        return $html;
}

function grafix_access()
{

        //global $database;
        //$rs   = $database->query(SqlCustomers::query(Settings::ASGRID,"id=" . $auth->customer->id);
        //$customer  = mysql_fetch_object($rs);
	global $auth;
	global $year;
	global $month;
	global $day;
	global $hour;
	global $minute;
	global $access_ids;
	global $duration;

        $cmd['command']   = "createvisit";
        $cmd['contactid'] = $auth->contact->grafix_id;
        $cmd['cardid']    = $access_ids;
        $cmd['date']      = $day  . $month . $year;
        $cmd['time']      = $hour . '' . $minute;
        $cmd['worktime']  = $duration;
        $cmd['nocid']     = "1";
        $cmd['note']      = $_POST["reason"];

        $grafix = new NOC_interface();
        $grafix->AddParam($cmd);                
        $grafix->DoTransaction();              

        if ($grafix->values['is_success'])
        {
                grafix_mail($grafix->values);
                return "Aanmelding geslaagd.<BR/>Uw toegangscode is " . $grafix->values['NOCcode'] . ".<BR/>Zie ook uw emailbox " . $auth->contact->email . ".";
        }
        else
        {
                print("<pre>");
		print_r($cmd);
                print_r($grafix);
		print("</pre>");
                return "Aanmelding mislukt.<BR/>" . $grafix->values['RETcode'] . ": " . $grafix->values['RETtext'];
        }

}

function grafix_mail($grafix)
{
	global $auth;
	global $year;
	global $month;
	global $day;
	global $hour;
	global $minute;
	global $access_ids;

	
        mail($auth->contact->email
            ,"Aanmelding bezoek DataCenter @ " . $year . "-" . $month . "-" . $day . " " . $hour . ":" . $minute
            ,"=====================================\n"
            ."Aanmelding bezoek DataCenter         \n"
            ."=====================================\n"
            ."Datum       : " . $year . "-" . $month . "-" . $day . " " . $hour . ":" . $minute . "\n"  
            ."Naam        : " . $auth->contacts->firstname . " " . $auth->contacts->lastname    . "\n"
            ."Bedrijfsnaam: " . $auth->contacts->organization_name . "\n"
            ."Reden       : " . $_POST["reason"]   . "\n"
            ."Toegangspas : " . $access_ids . "\n"
            ."Locatie     : GrafiX NOC             \n"
            ."Status      : ACCEPTED               \n"
            ."ID          : " . $grafix["NOCcode"] . "\n"
            ."=====================================\n"
            ."Uw aanvraag is geaccepteerd.         \n"
            ."=====================================\n"
            ,"From: REDUNIX - Internet Diensten <info@redunix.nl>\nBCC: hostmaster@redunix.nl");
}
?>


<HTML>
<HEAD>
        <TITLE>Toegang DataCenter</TITLE>
</HEAD>


<BODY>
        <CENTER>
        <H1>Bezoek aanmelden</H1>
        <BR/>
        <DIV style="color:red; font-width:bold;"><?=$message?></DIV>
        <BR/>
        <FORM action="grafix.php" method="POST">
        <TABLE border="0">
                <TR><TD>Pasnummer:</TD><TD><?= getKeyCardIdsAsCheckBoxes() ?></TD></TR>
                <TR>
                        <TD>Datum:    </TD>
                        <TD>
                                <SELECT name="day">
                                        <OPTION value="01" <?= ($day == "01") ? "SELECTED" : "" ?>>&nbsp;1</OPTION>
                                        <OPTION value="02" <?= ($day == "02") ? "SELECTED" : "" ?>>&nbsp;2</OPTION>
                                        <OPTION value="03" <?= ($day == "03") ? "SELECTED" : "" ?>>&nbsp;3</OPTION>
                                        <OPTION value="04" <?= ($day == "04") ? "SELECTED" : "" ?>>&nbsp;4</OPTION>
                                        <OPTION value="05" <?= ($day == "05") ? "SELECTED" : "" ?>>&nbsp;5</OPTION>
                                        <OPTION value="06" <?= ($day == "06") ? "SELECTED" : "" ?>>&nbsp;6</OPTION>
                                        <OPTION value="07" <?= ($day == "07") ? "SELECTED" : "" ?>>&nbsp;7</OPTION>
                                        <OPTION value="08" <?= ($day == "08") ? "SELECTED" : "" ?>>&nbsp;8</OPTION>
                                        <OPTION value="09" <?= ($day == "09") ? "SELECTED" : "" ?>>&nbsp;9</OPTION>
                                        <OPTION value="10" <?= ($day == "10") ? "SELECTED" : "" ?>>10</OPTION>
                                        <OPTION value="11" <?= ($day == "11") ? "SELECTED" : "" ?>>11</OPTION>
                                        <OPTION value="12" <?= ($day == "12") ? "SELECTED" : "" ?>>12</OPTION>
                                        <OPTION value="13" <?= ($day == "13") ? "SELECTED" : "" ?>>13</OPTION>
                                        <OPTION value="14" <?= ($day == "14") ? "SELECTED" : "" ?>>14</OPTION>
                                        <OPTION value="15" <?= ($day == "15") ? "SELECTED" : "" ?>>15</OPTION>
                                        <OPTION value="16" <?= ($day == "16") ? "SELECTED" : "" ?>>16</OPTION>
                                        <OPTION value="17" <?= ($day == "17") ? "SELECTED" : "" ?>>17</OPTION>
                                        <OPTION value="18" <?= ($day == "18") ? "SELECTED" : "" ?>>18</OPTION>
                                        <OPTION value="19" <?= ($day == "19") ? "SELECTED" : "" ?>>19</OPTION>
                                        <OPTION value="20" <?= ($day == "20") ? "SELECTED" : "" ?>>20</OPTION>
                                        <OPTION value="21" <?= ($day == "21") ? "SELECTED" : "" ?>>21</OPTION>
                                        <OPTION value="22" <?= ($day == "22") ? "SELECTED" : "" ?>>22</OPTION>
                                        <OPTION value="23" <?= ($day == "23") ? "SELECTED" : "" ?>>23</OPTION>
                                        <OPTION value="24" <?= ($day == "24") ? "SELECTED" : "" ?>>24</OPTION>
                                        <OPTION value="25" <?= ($day == "25") ? "SELECTED" : "" ?>>25</OPTION>
                                        <OPTION value="26" <?= ($day == "26") ? "SELECTED" : "" ?>>26</OPTION>
                                        <OPTION value="27" <?= ($day == "27") ? "SELECTED" : "" ?>>27</OPTION>
                                        <OPTION value="28" <?= ($day == "28") ? "SELECTED" : "" ?>>28</OPTION>
                                        <OPTION value="29" <?= ($day == "29") ? "SELECTED" : "" ?>>29</OPTION>
                                        <OPTION value="30" <?= ($day == "30") ? "SELECTED" : "" ?>>30</OPTION>
                                        <OPTION value="31" <?= ($day == "31") ? "SELECTED" : "" ?>>31</OPTION>
                                </SELECT>
                                <SELECT name="month">
                                        <OPTION value="01" <?= ($month == "01") ? "SELECTED" : "" ?>>Januari</OPTION>
                                        <OPTION value="02" <?= ($month == "02") ? "SELECTED" : "" ?>>Februari</OPTION>
                                        <OPTION value="03" <?= ($month == "03") ? "SELECTED" : "" ?>>Maart</OPTION>
                                        <OPTION value="04" <?= ($month == "04") ? "SELECTED" : "" ?>>April</OPTION>
                                        <OPTION value="05" <?= ($month == "05") ? "SELECTED" : "" ?>>Mei</OPTION>
                                        <OPTION value="06" <?= ($month == "06") ? "SELECTED" : "" ?>>Juni</OPTION>
                                        <OPTION value="07" <?= ($month == "07") ? "SELECTED" : "" ?>>Juli</OPTION>
                                        <OPTION value="08" <?= ($month == "08") ? "SELECTED" : "" ?>>Augustus</OPTION>
                                        <OPTION value="09" <?= ($month == "09") ? "SELECTED" : "" ?>>September</OPTION>
                                        <OPTION value="10" <?= ($month == "10") ? "SELECTED" : "" ?>>Oktober</OPTION>
                                        <OPTION value="11" <?= ($month == "11") ? "SELECTED" : "" ?>>November</OPTION>
                                        <OPTION value="12" <?= ($month == "12") ? "SELECTED" : "" ?>>December</OPTION>
                                </SELECT>
                                <SELECT name="year">
                                        <OPTION value="<?= $year   ?>"><?= $year   ?></OPTION>
                                        <OPTION value="<?= $year+1 ?>"><?= $year+1 ?></OPTION>
                                </SELECT>
                        </TD>
                </TR>
                <TR>
                        <TD>Tijd:</TD>
                        <TD>
                                <SELECT name="hour">
                                        <OPTION value="00" <?= ($hour == "00") ? "SELECTED" : "" ?>>&nbsp;0</OPTION>
                                        <OPTION value="01" <?= ($hour == "01") ? "SELECTED" : "" ?>>&nbsp;1</OPTION>
                                        <OPTION value="02" <?= ($hour == "02") ? "SELECTED" : "" ?>>&nbsp;2</OPTION>
                                        <OPTION value="03" <?= ($hour == "03") ? "SELECTED" : "" ?>>&nbsp;3</OPTION>
                                        <OPTION value="04" <?= ($hour == "04") ? "SELECTED" : "" ?>>&nbsp;4</OPTION>
                                        <OPTION value="05" <?= ($hour == "05") ? "SELECTED" : "" ?>>&nbsp;5</OPTION>
                                        <OPTION value="06" <?= ($hour == "06") ? "SELECTED" : "" ?>>&nbsp;6</OPTION>
                                        <OPTION value="07" <?= ($hour == "07") ? "SELECTED" : "" ?>>&nbsp;7</OPTION>
                                        <OPTION value="08" <?= ($hour == "08") ? "SELECTED" : "" ?>>&nbsp;8</OPTION>
                                        <OPTION value="09" <?= ($hour == "09") ? "SELECTED" : "" ?>>&nbsp;9</OPTION>
                                        <OPTION value="10" <?= ($hour == "10") ? "SELECTED" : "" ?>>10</OPTION>
                                        <OPTION value="11" <?= ($hour == "11") ? "SELECTED" : "" ?>>11</OPTION>
                                        <OPTION value="12" <?= ($hour == "12") ? "SELECTED" : "" ?>>12</OPTION>
                                        <OPTION value="13" <?= ($hour == "13") ? "SELECTED" : "" ?>>13</OPTION>
                                        <OPTION value="14" <?= ($hour == "14") ? "SELECTED" : "" ?>>14</OPTION>
                                        <OPTION value="15" <?= ($hour == "15") ? "SELECTED" : "" ?>>15</OPTION>
                                        <OPTION value="16" <?= ($hour == "16") ? "SELECTED" : "" ?>>16</OPTION>
                                        <OPTION value="17" <?= ($hour == "17") ? "SELECTED" : "" ?>>17</OPTION>
                                        <OPTION value="18" <?= ($hour == "18") ? "SELECTED" : "" ?>>18</OPTION>
                                        <OPTION value="19" <?= ($hour == "19") ? "SELECTED" : "" ?>>19</OPTION>
                                        <OPTION value="20" <?= ($hour == "20") ? "SELECTED" : "" ?>>20</OPTION>
                                        <OPTION value="21" <?= ($hour == "21") ? "SELECTED" : "" ?>>21</OPTION>
                                        <OPTION value="22" <?= ($hour == "22") ? "SELECTED" : "" ?>>22</OPTION>
                                        <OPTION value="23" <?= ($hour == "23") ? "SELECTED" : "" ?>>23</OPTION>
                                </SELECT>
                                <SELECT name="minute">
                                        <OPTION value="00" <?= ($minute ==  "0") ? "SELECTED" : "" ?>>00</OPTION>
                                        <OPTION value="05" <?= ($minute ==  "5") ? "SELECTED" : "" ?>>05</OPTION>
                                        <OPTION value="10" <?= ($minute == "10") ? "SELECTED" : "" ?>>10</OPTION>
                                        <OPTION value="15" <?= ($minute == "15") ? "SELECTED" : "" ?>>15</OPTION>
                                        <OPTION value="20" <?= ($minute == "20") ? "SELECTED" : "" ?>>20</OPTION>
                                        <OPTION value="25" <?= ($minute == "25") ? "SELECTED" : "" ?>>25</OPTION>
                                        <OPTION value="30" <?= ($minute == "30") ? "SELECTED" : "" ?>>30</OPTION>
                                        <OPTION value="35" <?= ($minute == "35") ? "SELECTED" : "" ?>>35</OPTION>
                                        <OPTION value="40" <?= ($minute == "40") ? "SELECTED" : "" ?>>40</OPTION>
                                        <OPTION value="45" <?= ($minute == "45") ? "SELECTED" : "" ?>>45</OPTION>
                                        <OPTION value="50" <?= ($minute == "50") ? "SELECTED" : "" ?>>50</OPTION>
                                        <OPTION value="55" <?= ($minute == "55") ? "SELECTED" : "" ?>>55</OPTION>
                                </SELECT>
                        </TD>
                </TR>
                <TR>
                        <TD>Tijdsduur:</TD>
                        <TD>
                                <SELECT NAME="duration">
                                        <OPTION value="30"  >&nbsp;0:30</OPTION>
                                        <OPTION value="60"  SELECTED>&nbsp;1:00</OPTION>
                                        <OPTION value="90"  >&nbsp;1:30</OPTION>
                                        <OPTION value="120" >&nbsp;2:00</OPTION>
                                        <OPTION value="150" >&nbsp;2:30</OPTION>
                                        <OPTION value="180" >&nbsp;3:00</OPTION>
                                        <OPTION value="210" >&nbsp;3:30</OPTION>
                                        <OPTION value="240" >&nbsp;4:00</OPTION>
                                        <OPTION value="300" >&nbsp;5:00</OPTION>
                                        <OPTION value="360" >&nbsp;6:00</OPTION>
                                        <OPTION value="420" >&nbsp;7:00</OPTION>
                                        <OPTION value="480" >&nbsp;8:00</OPTION>
                                        <OPTION value="540" >&nbsp;9:00</OPTION>
                                        <OPTION value="600" >10:00</OPTION>
                                        <OPTION value="720" >12:00</OPTION>
                                        <OPTION value="840" >14:00</OPTION>
                                        <OPTION value="960" >16:00</OPTION>
                                        <OPTION value="1200">20:00</OPTION>
                                        <OPTION value="1440">24:00</OPTION>
                                </SELECT>
                        </TD>
                </TR>
                <TR>
                        <TD>Omschrijving bezoek:</TD>
                        <TD>
                                <INPUT TYPE="text" NAME="reason" SIZE="60" />
                        </TD>
                </TR>
                <TR>
                        <TD COLSPAN="2" ALIGN="right">
                                <INPUT TYPE="submit" VALUE="Versturen"/>
                        </TD>
                </TR>
        </TABLE>
        </FORM>
        </CENTER>
</BODY>
</HTML>
