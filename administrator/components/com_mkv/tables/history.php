<?php
defined('_JEXEC') or die;

use Joomla\CMS\Table\Table;

class TableMkvHistory extends Table
{
    var $id = null;
    var $dat = null;
    var $managerID = null;
    var $itemID = null;
    var $section = null;
    var $action = null;
    var $old_data = null;
    var $new_data = null;

	public function __construct(JDatabaseDriver $db)
	{
		parent::__construct('#__mkv_history', 'id', $db);
	}

    public function store($updateNulls = true)
    {
        return parent::store($updateNulls);
    }
}
