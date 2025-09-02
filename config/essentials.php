<?php

declare(strict_types=1);

return [
    NunoMaduro\Essentials\Configurables\ForceScheme::class => env('APP_ENV') === 'local' ? false : true,
];
