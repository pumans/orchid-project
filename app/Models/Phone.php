<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;

class Phone extends Model
{
    use HasFactory;
    use AsSource;
    use Filterable;
    use Chartable;

    protected $fillable = [
        'name',
        'phone',
        'text',
        'status',
    ];
    public const STATUS = [
        'reviewed' => 'розглянуто',
        'not_reviewed' => 'не розглянуто'
    ];

    protected $allowedSorts = [
        'name','phone','status'
    ];
    protected $allowedFilters = [
        'name','phone','text',
    ];
}
