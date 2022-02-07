<?php

namespace App\Orchid\Screens\Mails;

use App\Models\Client;
use App\Models\Mail;
use App\Models\Phone;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class EmailListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Звернення по email';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'emails' => Mail::filters()->paginate(10),

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
            Layout::table('emails', [
                TD::make('name', 'Імя клієнта')->cantHide()->sort()->filter(TD::FILTER_TEXT),
                TD::make('email', 'email клієнта')->cantHide()->sort()->filter(TD::FILTER_TEXT),
                TD::make('text', 'текст звернення')->filter(TD::FILTER_TEXT),
                TD::make('status', 'статус ')->render(function(Mail $mail){
                    return Mail::STATUS[$mail->status];
                })->width('150px')->popover('Статус розгляду звернення')->sort(),
                TD::make('action', 'Редагувати')->render( function (Mail $mail){
                    return ModalToggle::make('Редагувати')
                        ->modal('edit')
                        ->method('update')
                        ->modalTitle('Редагування звернення: '. $mail->name)
                        ->asyncParameters([
                            'mail' => $mail->id,
                        ]);
                }),
            ]),
            Layout::modal('edit', layout::rows([
                Input::make('mail.id')->type('hidden'),
                Input::make('mail.name')->disabled()->title('Імя'),
                Input::make('mail.email')->disabled()->title('email'),
                Select::make('mail.status')->required()->options([
                    'not_reviewed' => 'не розглянуто',
                    'reviewed' => 'розглянуто',
                ])->help('результати розгляду звернення')
            ]))->async('asyncGetMail')
        ];
    }
    public function asyncGetMail(Mail $mail):array
    {
        return [
          'mail' => $mail,
        ];
    }

    public function update(Request $request){
        Mail::find( $request->input('mail.id'))->update($request->mail);
        Toast::info('Дані про звернення оновлені');
    }
}
