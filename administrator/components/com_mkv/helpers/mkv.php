<?php
defined('_JEXEC') or die;

class MkvHelper
{
    public static function addSubmenu()
    {

    }
    public static function getGroupUsers(int $groupID): array
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("user_id")
            ->from("#__user_usergroup_map")
            ->where("group_id = {$db->q($groupID)}");
        return $db->setQuery($query)->loadColumn() ?? [];
    }
    /**
     * Возвращает только фамилию из ФИО
     *
     * @param string $fio строка с ФИО через пробел
     *
     * @return string
     *
     * @since version 1.0.0
     */
    public static function getLastName(string $fio): string
    {
        $fio = explode(' ', $fio);
        return $fio[0];
	}

    /**
     * Возвращает только фамилию из ФИО
     *
     * @param string $fio строка с ФИО через пробел
     *
     * @return string
     *
     * @since version 1.0.0
     */
    public static function getLastAndFirstNames(string $fio): string
    {
        $fio = explode(' ', $fio);
        return $fio[0] . " " . $fio[1];
	}

    public static function getTaskColor(int $status): string
    {
        $arr = [-2 => '#FF0000', 1 => '#008000', 2 => '#0000FF', 3 => '#000000'];
        return $arr[$status] ?? $arr[3];
    }

    public static function getContractSmallTitle(string $number, string $date = ''): string
    {
        if (!empty($date)) {
            return JText::sprintf('COM_MKV_CONTRACT_TITLE_NUMBER_DATE', $number, $date);
        }
        else {
            return JText::sprintf('COM_MKV_CONTRACT_TITLE_NUMBER', $number);
        }
    }

    public static function getConfig(string $param, $default = null)
    {
        $config = JComponentHelper::getParams("com_mkv");
        return $config->get($param, $default);
    }
}
