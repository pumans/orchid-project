<?php

namespace App\Orchid\Screens;

use App\Models\Client;
use App\Orchid\Layouts\Charts\DynamicsInterviewedClients;
use App\Orchid\Layouts\Charts\PercentagFeedbackClients;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class AnalyticsAndReportsScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Аналітика та звіти';
    public $permission = ['platform.analytics', 'platform.reports'];
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'percentageFeedback' => Client::whereNotNull('assessment')->countForGroup('assessment')->toChart(),
            'interviewedClients' => [
                Client::whereNotNull('assessment')->countByDays(startDate:null, stopDate:null, dateColumn:'updated_at')->toChart('Опитані'),
                Client::countByDays()->toChart('Нові клієнти'),
                ],
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
    public function exportClients()
    {
        $clients = Client::with('mail')->get(['name', 'phone', 'email', 'status', 'assessment', 'mail_id']);
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=clients.csv'
        ];
        $columns = ['Імя', 'Телефон', 'email', 'Статус', 'Оценка', 'Сервис'];
        $callback = function () use ($clients, $columns) {
            $stream = fopen('php://output', 'w');
            fputcsv($stream, $columns);

            foreach ($clients as $client) {
                fputcsv($stream, [
                    'Імя' => $client->name,
                    'Телефон' => $client->phone,
                    'Email'   => $client->email,
                    'Статус'  => Client::STATUS[$client->status],
                    'Оценка' => $client->assessment,
                ]);
            }
            fclose($stream);
        };
        return response()->stream($callback, 200, $headers);
    }
    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            Layout::columns([
                PercentagFeedbackClients::class,
                DynamicsInterviewedClients::class,
            ]),
            Layout::tabs([
                'Звіт по клієнтам' => [
                    Layout::rows([
                        Button::make('Скачать')
                            ->method('exportClients')
                            ->rawClick()
                    ])
                ]
            ])
        ];
    }
}
