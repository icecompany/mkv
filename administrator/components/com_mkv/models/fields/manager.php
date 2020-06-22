<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldManager extends JFormFieldList
{
    protected $type = 'Manager';
    protected $loadExternally = 0;

    protected function getOptions()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("distinct u.id, u.name")
            ->from("`#__users` u")
            ->order("u.name");
        $projectID = PrjHelper::getActiveProject();
        if ($projectID > 0) {
            $groupID = $this->getProjectGroup($projectID);
            $query
                ->leftJoin("#__user_usergroup_map m on m.user_id = u.id")
                ->where("m.group_id = {$db->q($groupID)}");
        }
        $userID = JFactory::getUser();
        if ($userID !== 377) {
            $query->where("u.id not in (377, 440, 441, 442, 447, 427)");
        }
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

    private function getProjectGroup(int $projectID): int
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("groupID")
            ->from("#__mkv_projects")
            ->where("id = {$db->q($projectID)}");
        return $db->setQuery($query)->loadResult() ?? 0;
    }

    public function getOptionsExternally()
    {
        $this->loadExternally = 1;
        return $this->getOptions();
    }
}