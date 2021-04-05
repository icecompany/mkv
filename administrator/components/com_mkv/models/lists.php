<?php
use Joomla\CMS\MVC\Model\ListModel;

defined('_JEXEC') or die;

class MkvModelLists extends ListModel
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'l.id',
                'l.title',
                'search',
            );
        }
        parent::__construct($config);
    }

    protected function _getListQuery()
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        /* Сортировка */
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');

        $query
            ->select("l.id, l.title")
            ->select("t.title as type")
            ->from("#__mkv_lists l")
            ->leftJoin("#__mkv_lists_types t on l.typeID = t.id");

        $query->order("l.title ASC");

        $this->setState("list.limit", 0);

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        $result = [];
        foreach ($items as $item) {
            $arr = [];
            $arr['id'] = $item->id;
            $arr['title'] = $item->title;
            $arr['type'] = $item->type;
            $result[$item->id] = $arr;
        }
        return $result;
    }

    /* Сортировка по умолчанию */
    protected function populateState($ordering = 'l.title', $direction = 'ASC')
    {
        parent::populateState($ordering, $direction);
    }

    protected function getStoreId($id = '')
    {
        return parent::getStoreId($id);
    }
}
