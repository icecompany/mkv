<?php
defined('_JEXEC') or die;

class MkvHelper
{
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
        return $arr[$status];
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
}
