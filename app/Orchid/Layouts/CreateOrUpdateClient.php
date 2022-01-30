<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\SimpleMDE;
use Orchid\Screen\Layouts\Rows;

class CreateOrUpdateClient extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): array
    {
        $isClientExists = is_null( $this->query->getContent('client')) === false;
        return [
            Input::make('client.id')->type('hidden'),
            Group::make([
                Input::make('client.name')->title('Імя'),
                Input::make('client.last_name')->title('Прізвище'),
            ]),
            DateTimer::make('client.birthday')->format('Y-m-d')->title('дата народження'),
            Group::make([
                Input::make('client.phone')->title('Телефон'),
                Input::make('client.email')->type('email')->title('Email'),
            ]),
            //SimpleMDE::make('markdown'),
            Select::make('client.assessment')->options([
                'Відмінно' => 'Відмінно',
                'Добре' => 'Добре',
                'Задовільно' => 'Задовільно',
                'Не задовільно' => 'Не задовільно',
            ])->help('Враження від клієнта')->empty('Не відомо', 'Не відомо'),
        ];
    }
}
