<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    */

    'required' => ':attribute mező kitöltése kötelező.',
    'email' => ':attribute nem érvényes e-mail cím.',
    'unique' => 'Ez az :attribute már regisztrálva van.',
    'min' => [
        'string' => ':attribute legalább :min karakter hosszú legyen.',
    ],
    'confirmed' => ':attribute megerősítése nem egyezik.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    */

    'attributes' => [
        'name' => 'Teljes név',
        'email' => 'E-mail',
        'password' => 'Jelszó',
        'password_confirmation' => 'Jelszó megerősítése',
    ],

];
