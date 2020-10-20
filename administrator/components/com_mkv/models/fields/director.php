<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldDirector extends JFormFieldList
{
    protected $type = 'Director';
    protected $loadExternally = 0;

    protected function getOptions()
    {
        $directors_group = MkvHelper::getConfig('directors_group_id', 47);
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("distinct u.id, u.name")
            ->from("`#__users` u")
            ->leftJoin("#__user_usergroup_map m on m.user_id = u.id")
            ->where("m.group_id = {$db->q($directors_group)}")
            ->order("u.name");
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item) {
            $options[] = JHtml::_('select.option', $item->id, $item->name);
        }

        if (!$this->loadExternally) {
            $options = array_merge(parent::getOptions(), $options);
        }

        return $options;
    }

    public function getOptionsExternally()
    {
        $this->loadExternally = 1;
        return $this->getOptions();
    }
}