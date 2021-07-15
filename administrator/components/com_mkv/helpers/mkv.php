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

    public static function getSortedFields(array $heads = [], array $filter = []): array
    {
        if (empty($heads) && empty($filter)) return [];

        $result = [];
        foreach ($heads as $field => $params) if (isset($params['column']) && !empty($params['column'])) $result[] = $params['column'];
        if (!empty($filter)) $result = array_merge($result, $filter);
        return $result;
    }

    public static function formatField($type = '', $value = '', $itemID = 0, $return = '', $export = false)
    {
        if ($type === '') return $value;
        switch ($type) {
            case 'manager': {
                return self::getLastAndFirstNames($value);
            }
            case 'date': {
                return (!empty($value)) ? JDate::getInstance($value)->format("d.m.Y") : '';
            }
            case 'status': {
                $status = $value ?? JText::sprintf('COM_MKV_STATUS_IN_PROJECT');
                if ($export) return $status;
                $url = JRoute::_("index.php?option=com_contracts&amp;task=contract.edit&amp;id={$itemID}&amp;return={$return}");
                return JHtml::link($url, $status);
            }
            case 'company': {
                if ($export) return $value;
                $url = JRoute::_("index.php?option=com_companies&amp;task=company.edit&amp;id={$itemID}&amp;return={$return}");
                return JHtml::link($url, $value);
            }
            case 'contact': {
                if ($export) return $value;
                $url = JRoute::_("index.php?option=com_companies&amp;task=contact.edit&amp;id={$itemID}&amp;return={$return}");
                return JHtml::link($url, $value);
            }
            case 'email': {
                if ($export) return $value;
                return JHtml::link("mailto:{$value}", $value);
            }
            case 'phone': {
                if ($export) return $value;
                return $value;
            }
            case 'money_usd': {
                $money = self::getMoney($value, $export);
                if ($export) return $money;
                return JText::sprintf("COM_MKV_AMOUNT_USD_SHORT", $money);
            }
            case 'money_eur': {
                $money = self::getMoney($value, $export);
                if ($export) return $money;
                return JText::sprintf("COM_MKV_AMOUNT_EUR_SHORT", $money);
            }
            case 'money_rub': {
                $money = self::getMoney($value, $export);
                if ($export) return $money;
                return JText::sprintf("COM_MKV_AMOUNT_RUB_SHORT", $money);
            }
            default: return $value;
        }
    }

    public static function getMoney(float $value, bool $export)
    {
        return number_format(
            (float) $value,
            MKV_FORMAT_DEC_COUNT,
            MKV_FORMAT_SEPARATOR_FRACTION,
            (!$export) ? MKV_FORMAT_SEPARATOR_DEC : ''
        );
    }

    public static function getMailer() {
        $mailer = JFactory::getMailer();
        $mailer->isHtml(true);
        $mailer->setFrom("xakepok@xakepok.com", "MKV");
        return $mailer;
    }
}

define('MKV_FORMAT_DEC_COUNT', MkvHelper::getConfig('dec_count', 2));
define('MKV_FORMAT_SEPARATOR_DEC', MkvHelper::getConfig('separator_dec', ' '));
define('MKV_FORMAT_SEPARATOR_FRACTION', MkvHelper::getConfig('separator_fraction', ','));
const MKV_COMPONENT_COMPANIES = JPATH_ADMINISTRATOR . "/components/com_companies";
const MKV_COMPONENT_COMPANIES_MODELS = MKV_COMPONENT_COMPANIES . "/models";
const MKV_COMPONENT_CONTRACTS = JPATH_ADMINISTRATOR . "/components/com_contracts";
const MKV_COMPONENT_CONTRACTS_MODELS = MKV_COMPONENT_CONTRACTS . "/models";
const MKV_COMPONENT_PROJECTS = JPATH_ADMINISTRATOR . "/components/com_prj";
const MKV_COMPONENT_PROJECTS_MODELS = MKV_COMPONENT_PROJECTS . "/models";
const MKV_COMPONENT_SCHEDULER = JPATH_ADMINISTRATOR . "/components/com_scheduler";
const MKV_COMPONENT_SCHEDULER_MODELS = MKV_COMPONENT_SCHEDULER . "/models";
const MKV_COMPONENT_REPORTS = JPATH_ADMINISTRATOR . "/components/com_reports";
const MKV_COMPONENT_REPORTS_MODELS = JPATH_ADMINISTRATOR . "/models";
const MKV_COMPONENT_PRICES = JPATH_ADMINISTRATOR . "/components/com_prices";
const MKV_COMPONENT_PRICES_MODELS = MKV_COMPONENT_PRICES . "/models";
const MKV_COMPONENT_STANDS = JPATH_ADMINISTRATOR . "/components/com_stands";
const MKV_COMPONENT_STANDS_MODELS = MKV_COMPONENT_STANDS . "/models";
