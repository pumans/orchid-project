<?php

namespace App\Orchid\Screens;

use App\Models\Client;
use App\Models\Mail;
use App\Models\Phone;
use App\Orchid\Layouts\Charts\DynamicsInterviewedClients;
use App\Orchid\Layouts\Charts\PercentagFeedbackClients;
use App\Orchid\Layouts\Charts\PercentagFeedbackEmails;
use App\Orchid\Layouts\Charts\PercentagFeedbackPhones;
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
            'percentageFeedbackEmails' => Mail::countForGroup('status')->toChart(),
            'percentageFeedbackPhones' => Phone::countForGroup('status')->toChart(),
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
    public function exportMails()
    {
        $mails = Mail::get(['name', 'email', 'text', 'status']);
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=mails.csv'
        ];
        $columns = ['Імя', 'email', 'Текст звернення', 'Статус'];
        $callback = function () use ($mails, $columns) {
            $stream = fopen('php://output', 'w');
            fputcsv($stream, $columns);

            foreach ($mails as $mail) {
                fputcsv($stream, [
                    'Імя' => $mail->name,
                    'Email'   => $mail->email,
                    'Текст звернення' => $mail->text,
                    'Статус'  => Mail::STATUS[$mail->status],
                ]);
            }
            fclose($stream);
        };
        return response()->stream($callback, 200, $headers);
    }
    public function exportPhones()
    {
        $phones = Phone::get(['name', 'phone', 'text', 'status']);
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=phones.csv'
        ];
        $columns = ['Імя', 'Телефон', 'Текст звернення', 'Статус'];
        $callback = function () use ($phones, $columns) {
            $stream = fopen('php://output', 'w');
            fputcsv($stream, $columns);

            foreach ($phones as $phone) {
                fputcsv($stream, [
                    'Імя' => $phone->name,
                    'Телефон'   => $phone->phone,
                    'Текст звернення' => $phone->text,
                    'Статус'  => Phone::STATUS[$phone->status],
                ]);
            }
            fclose($stream);
        };
        return response()->stream($callback, 200, $headers);
    }
    public function exportClients()
    {
        $clients = Client::get(['name', 'phone', 'email', 'status', 'assessment']);
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=clients.csv'
        ];
        $columns = ['Імя', 'Телефон', 'email', 'Статус', 'Оценка'];
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
                PercentagFeedbackEmails::class,
                PercentagFeedbackPhones::class,
            ]),
            Layout::columns([
                PercentagFeedbackClients::class,
                DynamicsInterviewedClients::class,
            ]),
            Layout::tabs([
                'Звіт по email зверненням' => [
                    Layout::rows([
                        Button::make('Завантажити')
                            ->method('exportMails')
                            ->rawClick()
                    ])
                ],
                'Звіт по телефонним зверненням' => [
                    Layout::rows([
                        Button::make('Завантажити')
                            ->method('exportPhones')
                            ->rawClick()
                    ])
                ],
                'Звіт по клієнтам' => [
                    Layout::rows([
                        Button::make('Завантажити')
                            ->method('exportClients')
                            ->rawClick()
                    ])
                ]
            ])
        ];
    }
}
