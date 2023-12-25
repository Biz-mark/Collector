<?php namespace BizMark\Collector\Models;

use \System\Models\SettingModel;

class Settings extends SettingModel
{
    public $settingsCode = 'bizmark_collector_settings';

    public $settingsFields = 'fields.yaml';
}
