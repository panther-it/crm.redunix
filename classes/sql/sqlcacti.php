<?
require_once __DIR__ . "/../databaseCacti.php";

class SqlCacti
{
        public static function updateInterface($switch,$server)
        {
                global $databaseCacti;
                
                $sql = "UPDATE graph_templates_graph "
                     . "   SET title          = '|host_description| - Traffic - |query_ifName| - "    . $server->label . "' "
                     . "     , title_cache    = '" . $switch->name . " - Traffic - "  . $switch->port . " - " . $server->label . "' "
                     . " WHERE title_cache like '" . $switch->name . " - Traffic - %" . $switch->port . "%'";
                print($sql);
                $status = $databaseCacti->mutate($sql);
                return $status;
        }


        public static function insertUser($contact)
        {
                global $databaseCacti;

		if (empty($contact->username) || empty($contact->password)) 

			return "Invalid login details for '" . $contact->firstname . " " . $contact->lastname . "' (" . $contact->id . ")";
		$sql = "INSERT INTO user_auth
                                  ( username
                                  , password
				  , realm
				  , full_name
				  , must_change_password
				  , show_tree
				  , show_list
				  , show_preview
				  , graph_settings
				  , login_opts
				  , policy_graphs
				  , policy_trees
				  , policy_hosts
				  , policy_graph_templates
				  , enabled
				  )
			     VALUES 
				  ( '" . mysql_escape_string($contact->username) . "'
                                  , '" . md5($contact->password)                 . "'
				  , 0
				  , '" . mysql_escape_string($contact->organization_name . " - " . $contact->firstname . " " . $contact->lastnem) . "'
				  , '', 'on', 'on', 'on', 'on'
				  , 1, 2, 2, 2, 2, 'on'
				  )";
                print($sql);
		$uid     = $databaseCacti->mutate($sql);
		print("uid=" . $uid);
                $status  = $databaseCacti->mutate("INSERT INTO user_auth_realm VALUES (7," . $uid . ")");
                $status .= $databaseCacti->mutate("INSERT INTO settings_graphs VALUES (  " . $uid . ",'default_view_mode','3')");
                $status .= $databaseCacti->mutate("INSERT INTO settings_graphs VALUES (  " . $uid . ",'default_tree_id'  ,'9')");
                $status .= $databaseCacti->mutate("INSERT INTO settings_graphs VALUES (  " . $uid . ",'default_datechar' ,'0')");
                return $status;
        }

	public static function insertPermission($contact,$switch)
	{
                global $databaseCacti;

		if (empty($contact->username) || empty($contact->password)) 
			return "Invalid login details for '" . $contact->firstname . " " . $contact->lastname . "' (" . $contact->id . ")";
                
		$sql = "INSERT INTO user_auth_perms
				  ( user_id
				  , item_id
				  , type
				  )
			SELECT u.id              AS user_id
			     , i.local_graph_id  AS item_id
			     , 1
			  FROM user_auth             u
			     , graph_templates_graph i
			 WHERE u.username       = '" . mysql_escape_string($contact->username) . "'
                           AND i.title_cache like '" . $switch->name . " - Traffic - %" . $switch->port . "%'";
                print($sql);
		return $databaseCacti->mutate($sql);
	}

        public static function deleteUser($contact)
        {
		if (empty($contact->username))
			return "Invalid login details for '" . $contact->firstname . " " . $contact->lastname . "' (" . $contact->id . ")";

                global $databaseCacti;
		$sql = "DELETE user_auth
                             , user_auth_realm
                             , settings_graphs
                          FROM user_auth
                             , user_auth_realm
                             , settings_graphs
			 WHERE user_auth.id = user_auth_realm.user_id
                           AND user_auth.id = settings_graphs.user_id
                           AND user_auth.username = '" . mysql_escape_string($contact->username) . "'";
                print($sql);
		$status  = $databaseCacti->mutate($sql);
                return $status;
        }

}

?>
