<?php

return [
    'states' => ['S0', 'S1', 'S2'], // Q
    'initial-state' => 'S0', // q0
    'final-states' => ['S0', 'S1', 'S2'], // F
    'alphabet' => [1, 0], // Σ
    'transitions' => [ // δ
        'S0' => ['0' => 'S0', '1' => 'S1'],
        'S1' => ['0' => 'S2', '1' => 'S0'],
        'S2' => ['0' => 'S1', '1' => 'S2'],
    ],
    'output-map' => ['S0' => 0, 'S1' => 1, 'S2' => 2],
];
