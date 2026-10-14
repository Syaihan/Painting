<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionLog extends Model
{
    protected $table = 't_log_session_history';

    protected $primaryKey = 't_log_session_history_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        't_log_session_history_username',
        't_log_session_history_name',
        't_log_session_history_action',
        't_log_session_history_ip',
        't_log_session_history_browser',
        't_log_session_history_timestamp',
    ];
}
