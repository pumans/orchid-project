<?php

namespace App\Orchid\Screens\Mails;

use App\Models\Phone;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class PhoneListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Звернення по телефону';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'phones' => Phone::filters()->paginate(10),
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            Layout::table('phones', [
                TD::make('name', 'Імя клієнта')->cantHide()->sort()->filter(TD::FILTER_TEXT),
                TD::make('phone', 'телефон клієнта')->cantHide()->sort()->filter(TD::FILTER_TEXT),
                TD::make('text', 'текст звернення')->filter(TD::FILTER_TEXT),
                TD::make('status', 'статус ')->render(function(Phone $phone){
                    return Phone::STATUS[$phone->status];
                })->width('150px')->popover('Статус розгляду звернення')->sort(),
                TD::make('action', 'Редагувати')->render( function (Phone $phone) {
                    return ModalToggle::make('Редагувати')
                        ->modal('edit')
                        ->method('update')
                        ->modalTitle('Редагування звернення: ' . $phone->name)
                        ->asyncParameters([
                            'phone' => $phone->id,
                        ]);
                }),
            ]),
            Layout::modal('edit', layout::rows([
                Input::make('phone.id')->type('hidden'),
                Input::make('phone.name')->disabled()->title('Імя'),
                Input::make('phone.phone')->disabled()->title('Телефон'),
                Select::make('phone.status')->required()->options([
                    'not_reviewed' => 'не розглянуто',
                    'reviewed' => 'розглянуто',
                ])->help('результати розгляду звернення')
            ]))->async('asyncGetPhone')
        ];
    }
    public function asyncGetPhone(Phone $phone):array
    {
        return [
            'phone' => $phone,
        ];
    }

    public function update(Request $request){
        Phone::find( $request->input('phone.id'))->update($request->phone);
        Toast::info('Дані про звернення оновлені');
    }
}
