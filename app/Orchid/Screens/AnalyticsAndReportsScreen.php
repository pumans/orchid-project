<?php

namespace App\Orchid\Screens;

use App\Models\Client;
use App\Orchid\Layouts\Charts\DynamicsInterviewedClients;
use App\Orchid\Layouts\Charts\PercentagFeedbackClients;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;

class AnalyticsAndReportsScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Аналітика та звіти';

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

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            \Orchid\Support\Facades\Layout::columns([
                PercentagFeedbackClients::class,
                DynamicsInterviewedClients::class,
            ])
        ];
    }
}
