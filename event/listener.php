<?php
/**
 *
 * List subforums in columns.
 * Allows to choose how subforums are listed - in line or in column(s) on per-forum basis.
 * An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, rxu, https://www.phpbbguru.net
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace rxu\listsubforumsincolumns\event;

/**
 * @ignore
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * List subforums in columns extension Event listener.
 */
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\request\request_interface */
	protected $request;

	/**
	 * Constructor
	 *
	 * @param \phpbb\request\request_interface     $request  Request object
	 */
	public function __construct(\phpbb\request\request_interface $request)
	{
		$this->request = $request;
	}

	static public function getSubscribedEvents()
	{
		return [
			'core.display_forums_modify_template_vars'		=> 'switch_columns',
			'core.acp_manage_forums_request_data'			=> 'acp_manage_forums_add_data',
			'core.acp_manage_forums_initialise_data'		=> 'acp_manage_forums_add_data',
			'core.acp_manage_forums_display_form'			=> 'acp_manage_forums_add_data',
		];
	}

	/**
	 * Display subforums
	 *
	 * @param \phpbb\event\data	$event		Event object
	 */
	public function switch_columns($event)
	{
		$row = $event['row'];
		$forum_row = $event['forum_row'];
		$subforums_row = $event['subforums_row'];
		$subforums_count = count($subforums_row);
		if ($subforums_count && (int) $row['forum_subforumslist_type'])
		{
			$forum_row['S_COLUMNS_ENABLED'] = true;
			$rows_per_column = (int) ceil($subforums_count / (int) $row['forum_subforumslist_type']);

			foreach ($subforums_row as $number => $subforum_row)
			{
				if (($number + 1) < $subforums_count && ($number + 1) % $rows_per_column == 0)
				{
					$subforums_row[$number]['S_SWITCH_COLUMN'] = true;
				}
			}
			$event['forum_row'] = $forum_row;
			$event['subforums_row'] = $subforums_row;
		}
	}

	/**
	 * Add ACP option to Manage forums
	 *
	 * @param \phpbb\event\data	$event		Event object
	 * @param string			$eventname	Name of the event
	 */
	public function acp_manage_forums_add_data($event, $eventname)
	{
		$forum_data = $event['forum_data'];

		switch ($eventname)
		{
			case 'core.acp_manage_forums_request_data':
			case 'core.acp_manage_forums_initialise_data':
				$forum_data += [
					'forum_subforumslist_type'	=> $this->request->variable('subforumslist_type', 0),
				];
				$event['forum_data'] = $forum_data;
			break;

			case 'core.acp_manage_forums_display_form':
				$template_data = $event['template_data'];
				$template_data += [
					'SUBFORUMSLIST_TYPE'	=> $forum_data['forum_subforumslist_type'],
				];
				$event['template_data'] = $template_data;
			break;
		}
	}
}
