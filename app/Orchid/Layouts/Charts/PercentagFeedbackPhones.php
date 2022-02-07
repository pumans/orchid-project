<?php

namespace App\Orchid\Layouts\Charts;

use Orchid\Screen\Layouts\Chart;

class PercentagFeedbackPhones extends Chart
{
    /**
     * Add a title to the Chart.
     *
     * @var string
     */
    protected $title = 'Розглянуті звернення з телефоном';

    /**
     * Available options:
     * 'bar', 'line',
     * 'pie', 'percentage'.
     *
     * @var string
     */
    protected $type = 'bar';

    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the chart.
     *
     * @var string
     */
    protected $target = 'percentageFeedbackPhones';

    /**
     * Determines whether to display the export button.
     *
     * @var bool
     */
    protected $export = true;
    protected $colors = [
        '#00CC66',
        '#F1C40F',
    ];
}
