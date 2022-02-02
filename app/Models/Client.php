<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;

class Client extends Model
{
    use HasFactory;
    use AsSource;
    use Filterable;
    use Chartable;

    protected $fillable = ['phone', 'name', 'last_name', 'status', 'email', 'mail_id', 'phone_id', 'birthday','assessment'];

    protected $allowedSorts = [
        'name','status', 'created_at'
    ];

    protected $allowedFilters = [
        'name'
    ];

}
