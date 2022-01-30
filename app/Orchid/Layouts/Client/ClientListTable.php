<?php

namespace App\Orchid\Layouts\Client;

use App\Models\Client;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ClientListTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'clients';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('name', 'Имя')->width('150px')->sort()->filter(TD::FILTER_TEXT),
            TD::make('last_name', 'Прізвище')->defaultHidden(),
            TD::make('phone', 'Телефон')->width('150px')->canSee($this->isWorkTime()),
            TD::make('status', 'Статус')->render(function(Client $client){
                return $client->status === 'interviewed' ? 'контактували' : 'не контактували';
            })->width('150px')->popover('Статус комунікації з клієнтом')->sort(),
            TD::make('email', 'Email')->width('150px'),
            TD::make('birthday', 'Дата народження')->defaultHidden()->sort(),
            TD::make('action', 'Дії')->render( function (Client $client){
                return ModalToggle::make('Редагувати')
                    ->modal('editClient')
                    ->method('createOrUpdateClient')
                    ->modalTitle('Редагування клієнта: '. $client->name)
                    ->asyncParameters([
                        'client' => $client->id
                    ]);
            }),
        ];
    }
    public function isWorkTime():bool
    {
        $night = CarbonPeriod::create('20:00', '00:00');
        return $night->contains(Carbon::now(config('app.timezone'))) === false;
    }
}
