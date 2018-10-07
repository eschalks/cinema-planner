<?php

use App\Enums\MovieQuality;

return [
    'quality' => [
        MovieQuality::IMAX        => 'IMAX',
        MovieQuality::DOLBY_ATMOS => 'Dolby Atmos',
        MovieQuality::NORMAL      => '',
    ],
];
