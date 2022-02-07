<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;

class Mail extends Model
{
    use HasFactory;
    use AsSource;
    use Filterable;
    use Chartable;

    protected $fillable = [
        'name',
        'email',
        'text',
        'status',
    ];
    public const STATUS = [
        'reviewed' => 'розглянуто',
        'not_reviewed' => 'не розглянуто'
    ];
    protected $allowedSorts = [
        'name','email','status'
    ];
    protected $allowedFilters = [
        'name','email','text',
    ];
}
