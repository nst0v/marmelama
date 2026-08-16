<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'kitten_id',
    'name',
    'phone',
    'email',
    'message',
    'status',
    'internal_notes',
    'mail_status',
    'mail_sent_at',
    'mail_error',
])]
class ContactRequest extends Model
{
    public const STATUSES = [
        'new' => 'Новая',
        'read' => 'Просмотрена',
        'in_progress' => 'В работе',
        'closed' => 'Закрыта',
    ];

    public const MAIL_STATUSES = [
        'pending' => 'Ожидает отправки',
        'sent' => 'Письмо отправлено',
        'failed' => 'Ошибка отправки',
    ];

    protected function casts(): array
    {
        return [
            'mail_sent_at' => 'datetime',
        ];
    }

    public function kitten(): BelongsTo
    {
        return $this->belongsTo(Kitten::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getMailStatusLabelAttribute(): string
    {
        return self::MAIL_STATUSES[$this->mail_status] ?? $this->mail_status;
    }
}
