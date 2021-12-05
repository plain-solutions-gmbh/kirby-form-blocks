<?php
[
    'microman.formblock' => [
        'placeholders' => [
            'summary' => [
                'label' => "Summary",
                'value' => function ($fields) {

                    $table = "";

                    foreach ($fields as $field) {
                        $table .= $field->label() . ": " . $field->value() . "<br/>";
                    }

                    return $table;
                }
            ],
            'ip' => [
                'label' => "IP address",
                'value' => function ($fields) {
                    return $_SERVER['REMOTE_ADDR'];
                }
            ],

        ]
    ]
];