<?php namespace BizMark\Collector\Models;

use Arr;
use Mail;
use Model;
use Illuminate\Mail\Message;
use October\Rain\Argon\Argon;

/**
 * Record Model
 *
 * @link https://docs.octobercms.com/3.x/extend/system/models.html
 *
 * @property int        $id
 * @property bool       $is_read
 * @property string     $group
 * @property array      $properties
 * @property string     $ip
 * @property Argon      $created_at
 * @property Argon      $updated_at
 *
 * Scopes
 * @method static $this unread()
 */
class Record extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table name
     */
    public $table = 'bizmark_collector_records';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'is_read',
        'group',
        'properties',
        'ip'
    ];

    /**
     * @var array rules for validation
     */
    public $rules = [
        'properties' => 'required'
    ];

    /**
     * @var array jsonable attribute names that are json encoded and decoded from the database
     */
    protected $jsonable = [
        'properties'
    ];

    // Events

    public function beforeCreate(): void
    {
        $this->storeIPAddress();
    }

    public function afterCreate(): void
    {
        $this->notify();
    }

    // Scopes

    public function scopeUnread($query): void
    {
        $query->where('is_read', 0);
    }

    // Misc

    protected function storeIPAddress(): void
    {
        if (empty($this->ip)) {
            $ip = request()->ip();
            if (!empty($ip)) {
                $this->ip = $ip;
            }
        }
    }

    protected function notify(): void
    {
        $recipients = Arr::get(Settings::get('notification'), 'recipients');
        $messageData = [
            'id' => $this->id,
            'ip' => $this->ip,
            'group' => $this->group,
            'data' => $this->properties,
            'date' => $this->created_at->timezone('Europe/Moscow')->format('d-m-Y-H-i-s')
        ];

        if (!empty($recipients)) {
            $recipients = array_map(fn ($recipient) => $recipient['email'], $recipients);
            $subject = !empty($this->group) ? 'Новая заявка: ' .$this->group : 'Новая запись в коллекторе';

            try {
                Mail::send('bizmark.collector::mail.notification', $messageData, function(Message $message) use ($recipients, $subject) {
                    $message->to($recipients);
                    $message->subject($subject);
                });
            } catch (\Exception $ex) {
                trace_log($ex);
            }
        }

        $responseEnabled = Arr::get(Settings::get('notification'), 'response', 0);
        if ($responseEnabled) {
            $responseEmail = Arr::get($this->properties, 'email');
            if (empty($responseEmail)) {
                return;
            }

            try {
                Mail::send('bizmark.collector::mail.response', $messageData, function(Message $message) use ($responseEmail) {
                    $message->to($responseEmail);
                });
            } catch (\Exception $ex) {
                trace_log($ex);
            }
        }
    }
}
