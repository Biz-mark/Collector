<?php namespace BizMark\Collector\Components;

use Cms\Classes\ComponentBase;
use October\Rain\Exception\AjaxException;

use BizMark\Collector\Models\Record;

/**
 * Collector Component
 *
 * @link https://docs.octobercms.com/3.x/extend/cms-components.html
 */
class Collector extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Collector Component',
            'description' => 'No description provided yet...'
        ];
    }

    /**
     * @link https://docs.octobercms.com/3.x/element/inspector-types.html
     */
    public function defineProperties()
    {
        return [
            'group' => [
                'title' => 'Collector group',
                'type' => 'string'
            ],
            'success_message' => [
                'title' => 'Success message',
                'type' => 'string',
                'default' => 'Your form was saved successfully!'
            ],
            'error_message' => [
                'title' => 'Error message',
                'type' => 'string',
                'default' => 'Something went wrong, try again later'
            ],
        ];
    }

    /**
     * @throws AjaxException
     */
    public function onSubmit(): void
    {
        $ip = request()->ip();
        $properties = collect(request()->all());
        $group = $properties->get('group', $this->property('group'));

        try {
            Record::create([
                'ip' => $ip,
                'group' => $group,
                'properties' => $properties->except(['_session_key', '_token', 'group'])
            ]);
        } catch (\Exception $ex) {
            trace_log($ex);
            throw new AjaxException(['X_OCTOBER_ERROR_MESSAGE' => $this->property('error_message')]);
        }

        \Flash::success($this->property('success_message'));
    }
}
