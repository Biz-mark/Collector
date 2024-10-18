<?php namespace BizMark\Collector;

use Backend;
use BizMark\Collector\Components\Collector;
use BizMark\Collector\Models\Record;
use BizMark\Collector\Models\Settings;
use System\Classes\PluginBase;

/**
 * Plugin Information File
 *
 * @link https://docs.octobercms.com/3.x/extend/system/plugins.html
 */
class Plugin extends PluginBase
{
    /**
     * pluginDetails about this plugin.
     */
    public function pluginDetails()
    {
        return [
            'name' => 'Collector',
            'description' => 'No description provided yet...',
            'author' => 'Biz-Mark',
            'icon' => 'icon-envelope'
        ];
    }

    /**
     * register method, called when the plugin is first registered.
     */
    public function register()
    {
        //
    }

    /**
     * boot method, called right before the request route.
     */
    public function boot()
    {
        //
    }

    /**
     * registerMailTemplates registers any mail templates implemented by this package.
     */
    public function registerMailTemplates() {
        return [
            'bizmark.collector::mail.notification',
            'bizmark.collector::mail.response',
        ];
    }

    /**
     * registerComponents used by the frontend.
     */
    public function registerComponents()
    {
        return [
            Collector::class => 'Collector',
        ];
    }

    /**
     * registerPermissions used by the backend.
     */
    public function registerPermissions()
    {
        return [
            'bizmark.collector.records' => [
                'tab' => 'Collector',
                'label' => 'Доступ к формам'
            ],
        ];
    }

    /**
     * registerNavigation used by the backend.
     */
    public function registerNavigation()
    {
        return [
            'collector' => [
                'label' => 'Формы',
                'url' => Backend::url('bizmark/collector/records'),
                'icon' => 'icon-envelope',
                'permissions' => ['bizmark.collector.records'],
                'order' => 500,
                'counter' => Record::unread()->count(),
                'counterLabel' => 'Unread records'
            ],
        ];
    }

    /**
     * registerSettings registers any backend configuration links used by this package.
     */
    public function registerSettings()
    {
        return [
            'settings' => [
                'label' => 'Настройки Collector',
                'description' => 'Управление настройками форм обратной связи',
                'category' => 'Collector',
                'icon' => 'icon-cog',
                'class' => Settings::class,
            ]
        ];
    }
}
