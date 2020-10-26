<?php
use Joomla\CMS\MVC\Model\ListModel;

defined('_JEXEC') or die;

class MkvModelEvents extends ListModel
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'h.id',
                'h.dat',
            );
        }
        parent::__construct($config);
        $this->section = $config['section'];
        $this->itemID = $config['itemID'];
        $this->action = $config['action'];
    }

    protected function _getListQuery()
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        /* Сортировка */
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');

        $query
            ->select("h.*")
            ->select("u.name as manager")
            ->from("#__mkv_history h")
            ->leftJoin("#__users u on u.id = h.managerID");

        if ($this->section !== null) {
            $query->where("h.section = {$db->q($this->section)}");
        }

        if ($this->itemID !== null) {
            $query->where("h.itemID = {$db->q($this->itemID)}");
        }

        if ($this->action !== null) {
            $query->where("h.action like {$db->q($this->action)}");
        }

        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        $result = [];
        foreach ($items as $item) {
            $arr = array();
            $arr['id'] = $item->id;
            $arr['dat'] = JDate::getInstance($item->dat)->format("d.m.Y H:i");
            $action = mb_strtoupper($item->action);
            $arr['action'] = JText::sprintf("COM_MKV_HISTORY_ACTION_{$action}");
            $arr['old_data'] = (!empty($item->old_data)) ? json_decode($item->old_data, true) : [];
            $arr['new_data'] = (!empty($item->new_data)) ? json_decode($item->new_data, true) : [];
            $arr['manager'] = MkvHelper::getLastAndFirstNames($item->manager);
            $arr['show_link'] = JHtml::link($this->getShowUri($item->id), JText::sprintf('COM_MKV_HEAD_VERSION_SHOW'));
            $result[] = $arr;
        }
        return $result;
    }

    private function getShowUri(int $id): string
    {
        $uri = JUri::getInstance();
        $uri->setVar('version', $id);
        return $uri->toString();
    }

    /* Сортировка по умолчанию */
    protected function populateState($ordering = 'h.dat', $direction = 'DESC')
    {
        parent::populateState($ordering, $direction);
    }

    protected function getStoreId($id = '')
    {
        return parent::getStoreId($id);
    }

    private $section, $itemID, $action;
}
