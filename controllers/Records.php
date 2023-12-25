<?php namespace BizMark\Collector\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use BizMark\Collector\Models\Record;

/**
 * Records Backend Controller
 *
 * @link https://docs.octobercms.com/3.x/extend/system/controllers.html
 */
class Records extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class,
    ];

    /**
     * @var string formConfig file
     */
    public $formConfig = 'config_form.yaml';

    /**
     * @var string listConfig file
     */
    public $listConfig = 'config_list.yaml';

    /**
     * @var array required permissions
     */
    public $requiredPermissions = ['bizmark.collector.records'];

    /**
     * __construct the controller
     */
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('BizMark.Collector', 'collector', 'records');
    }

    public function preview($recordId = null, $context = null)
    {
        parent::preview($recordId, $context);
        Record::where('id', $recordId)->where('is_read', 0)->update(['is_read' => 1]);
    }

    public function listInjectRowClass($record, $definition = null) {
        if (!$record->is_read) {
            return 'important';
        }
    }
}
