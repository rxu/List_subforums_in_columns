<?php
/**
 *
 * @package ListSubforumsInColumns
 * @copyright (c) 2014 Палыч (gfksx)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace gfksx\ListSubforumsInColumns\event;

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
    exit;
}

/**
* Event listener
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
    public function __construct(\phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, \phpbb\auth\auth $auth, \phpbb\template\template $template, \phpbb\user $user, $phpbb_root_path, $php_ext)
    {
        $this->template = $template;
        $this->user = $user;
		$this->auth = $auth;
		$this->db = $db;
		$this->config = $config;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
    }

	static public function getSubscribedEvents()
	{
		return array(
			'core.display_forums_modify_template_vars'		=> 'display_forums_modify_template_vars',
			'core.acp_manage_forums_request_data'			=> 'acp_manage_forums_request_data',
			'core.acp_manage_forums_initialise_data'		=> 'acp_manage_forums_initialise_data',
			'core.acp_manage_forums_display_form'			=> 'acp_manage_forums_display_form',
		);
	}

	public function display_forums_modify_template_vars($event)
	{
		$row = $event['row'];
		$forum_row = $event['forum_row'];

		if (isset ($forum_row['SUBFORUMS']) && $row['forum_subforumslist_type'])
		{
			$s_subforums_list_m = array();
			$s_subforums_list_str ='';
			$s_subforums_list_m = explode($this->user->lang['COMMA_SEPARATOR'], $forum_row['SUBFORUMS']);
			$sf_list = count($s_subforums_list_m);
			if ($sf_list)
			{
				$rows = ceil ($sf_list / $row['forum_subforumslist_type']); 
				$s_subforums_list_m = array_chunk($s_subforums_list_m, $rows);		
				$s_subforums_list_str = '<br /> <span style="float: left;">';
				$s_subforums_list_str .= (string) implode(',<br />', $s_subforums_list_m[0]);
				$s_subforums_list_str .= '</span> ';
				for ($i=1; $i*$rows < $sf_list; $i++)
				{
					$s_subforums_list_str .= '<span style="float: left;">&nbsp;&nbsp;';		
					$s_subforums_list_str .= (string) implode(',<br />&nbsp;&nbsp;', $s_subforums_list_m[$i]);
					$s_subforums_list_str .= '</span>';
				}
				$forum_row['FORUM_SUBFORUMSLIST_TYPE'] = (int) $row['forum_subforumslist_type'];
				$forum_row['SUBFORUMS'] = $s_subforums_list_str;
				$event['forum_row'] = $forum_row;
			}
		}
	}

	public function acp_manage_forums_request_data($event)
	{
		$forum_data = $event['forum_data'];

		$forum_data += array(
			'forum_subforumslist_type'	=> request_var('subforumslist_type', 0),
		);

		$event['forum_data'] = $forum_data;
	}

	public function acp_manage_forums_initialise_data($event)
	{
		$this->user->add_lang_ext('gfksx/ListSubforumsInColumns', 'info_acp_sflist');

		$forum_data = $event['forum_data'];
		$forum_data += array(
			'forum_subforumslist_type'	=> 0,
		);

		$event['forum_data'] = $forum_data;
	}

	public function acp_manage_forums_display_form($event)
	{
		$forum_data = $event['forum_data'];
		$template_data = $event['template_data'];

		$template_data += array(
			'SUBFORUMSLIST_TYPE'	=> $forum_data['forum_subforumslist_type'],
		);

		$event['template_data'] = $template_data;
	}
}
