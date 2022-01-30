<?php

namespace App\Orchid\Screens\Client;

use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Models\Mail;
use App\Models\Phone;
use App\Orchid\Layouts\Client\ClientListTable;
use App\Orchid\Layouts\CreateOrUpdateClient;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;

use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ClientListScreen extends Screen
{

    public $name = 'Клієнти';
    public $description = 'Список клієнтів, які відправили звернення з сайту';

    public function query(): array
    {
        return [
            'clients' => Client::filters()->defaultSort('created_at', 'desc')->paginate(10)
        ];
    }

    public function commandBar(): array
    {
        return [
            ModalToggle::make('Створити клієнта')->modal('createClient')->method('createOrUpdateClient'),
            ModalToggle::make('... з email')->modal('createEmailClient')->method('createEmailClient'),
            ModalToggle::make('... з телефону')->modal('createPhoneClient')->method('createPhoneClient'),
        ];
    }

    public function layout(): array
    {
        return [
            ClientListTable::class,
            Layout::modal('createClient', CreateOrUpdateClient::class)->title('Створення картки клієнта')->applyButton('Створити'),

            Layout::modal('editClient', CreateOrUpdateClient::class)->applyButton('Редагувати')->async('asyncGetClient'),

            Layout::modal('createEmailClient', Layout::rows([
                Select::make('mail_id')->fromQuery(Mail::where('status', '=', 'not_reviewed'), 'email')
                    ->title('Звернення з email форми'),
            ]))->title('Створення картки клієнта з email')->applyButton('Створити'),

            Layout::modal('createPhoneClient', Layout::rows([
                Select::make('phone_id')->fromQuery(Phone::where('status', '=', 'not_reviewed'), 'name')
                    ->title('Звернення з телефоном'),
            ]))->title('Створення картки клієнта з телефоном')->applyButton('Створити'),
        ];
    }

    public function createEmailClient(ClientRequest $request): void
    {
        $mail = Mail::findOrFail($request['mail_id']);

        Client::create(array_merge($request->validated(),[
            'name' => $mail->name,
            'email' => $mail->email,
        ]));
        Toast::info('Картку клієнта створено');
    }
    public function createPhoneClient(ClientRequest $request): void
    {
        $phone = Phone::findOrFail($request['phone_id']);

        Client::create(array_merge($request->validated(),[
            'name' => $phone->name,
            'phone' => $phone->phone,
        ]));
        Toast::info('Картку клієнта створено');
    }

    public function asyncGetClient(Client $client): array
    {
        return [
          'client' => $client,
        ];
    }

    public function createOrUpdateClient(ClientRequest $request): void
    {
        $clientId = $request->input('client.id');
        Client::updateOrCreate([
            'id' => $clientId
        ], array_merge( $request->validated()['client'], [
            'status' => 'interviewed',
            ]));
        is_null($clientId) ? Toast::info('Картку клієнта створено') : Toast::info('Картку клієнта оновлено');
    }
}
