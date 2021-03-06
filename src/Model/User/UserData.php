<?php
namespace Pecee\Model\User;

use Pecee\Model\Model;

class UserData extends Model
{
    const IDENTIFIER_KEY = 'user_id';

    protected $timestamps = false;

    protected $columns = [
        'id',
        'key',
        'value',
    ];

    protected $table = 'user_data';

    public function __construct($userId = null, $key = null, $value = null)
    {

        parent::__construct();

        $this->columns = array_merge($this->columns, [
            static::IDENTIFIER_KEY,
        ]);

        $this->user_id = $userId;
        $this->key = $key;
        $this->value = $value;
    }

    public function exists()
    {
        if ($this->{$this->primary} === null) {
            return false;
        }

        return ($this->where('key', '=', $this->key)->where(static::IDENTIFIER_KEY, '=', $this->{static::IDENTIFIER_KEY})->first() !== null);
    }

    public static function destroyByIdentifier($identifierId)
    {
        return static::instance()->where(static::IDENTIFIER_KEY, '=', $identifierId)->delete();
    }

    public static function getByIdentifier($identifierId)
    {
        return static::instance()->where(static::IDENTIFIER_KEY, '=', $identifierId)->all();
    }
}