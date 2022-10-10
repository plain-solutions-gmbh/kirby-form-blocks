<?php

load([
    'microman\\FormBlueprint' => '/classes/FormBlueprint.php',
    'microman\\Form' => '/classes/Form.php',
    'microman\\FormFields' => '/classes/FormFields.php',
    'microman\\FormField' => '/classes/FormField.php',
], __DIR__);

use microman\Form;
use microman\FormBlueprint;
use Kirby\Cms\App as Kirby;

Kirby::plugin('microman/formblock', [
    'options' => [
        'from_email' => 'no-reply@' . Kirby::instance()->environment()->host(),
        'placeholders' => FormBlueprint::getPlaceholders(),
        'honeypot_variants' => ["email", "name", "url", "tel", "given-name", "family-name", "street-address", "postal-code", "address-line2", "address-line1", "country-name", "language", "bday"],
        'disable_inbox' => false,
        'disable_confirm' => false,
        'disable_notify' => false,
        'verify_content' => true,
        'translations' => [
            'en' => [
                'success_message' => 'Thank you {{ given-name }}. We will get back to you as soon as possible.',
                'fatal_message'  => 'Something went wrong. Contact the administrator or try again later.',
                'field_message'  => 'This field is required.',
                'invalid_message' => 'Please check these fields: {{ fields }}.',
                'exists_message' => 'The form has already been filled in.',
                'send_button'  => 'Send',
            ],
            'de' => [
                'success_message' => 'Danke {{ given-name }}. Wir werden uns schnellst möglich bei dir melden.',
                'fatal_message'  => 'Es ist etwas schief gelaufen. Kontaktieren Sie den Administrator oder versuchen Sie es später noch einmal.',
                'field_message'  => 'Dieses Feld ist erforderlich.',
                'invalid_message' => 'Bitte überprüfen Sie diese Felder: {{ fields }}.',
                'exists_message' => 'Das Formular wurde bereits ausgefüllt.',
                'send_button'  => 'Senden',
            ]
        ]
    ],
    'blueprints' => [
        'blocks/form' => [
            'name' => 'form.block.fromfields',
            'icon' => 'form',
            'tabs' => [
                'inbox' => FormBlueprint::getInbox(),
                'form' => FormBlueprint::getForm(),
                'options' => FormBlueprint::getOptions()
            ]
        ],
        'pages/formrequest' => FormBlueprint::getBlueprint('pages/formrequest'),
        'pages/formcontainer' => FormBlueprint::getBlueprint('pages/formcontainer'),
    ],
    'snippets' => [
        'blocks/form' => __DIR__ . '/snippets/blocks/form.php',
        'blocks/formfields/checkbox' => __DIR__ . '/snippets/blocks/formfields/checkbox.php',
        'blocks/formfields/input' => __DIR__ . '/snippets/blocks/formfields/input.php',
        'blocks/formfields/radio' => __DIR__ . '/snippets/blocks/formfields/radio.php',
        'blocks/formfields/select' => __DIR__ . '/snippets/blocks/formfields/select.php',
        'blocks/formfields/textarea' => __DIR__ . '/snippets/blocks/formfields/textarea.php'
    ],
    'hooks' => [
        'page.update:before' => function ($page, $values, $strings) {
            
        },
        // TODO: Add Hooks
    ],
    'fields' => [
        'mailview' => [
            'props' => [
                'parent' => function () {
                    return false;
                }
            ],
        ],
    ],
    'blockModels' => [
        'form' => Form::class
    ],
    'siteMethods' => [
        'formRequests' => function ($formSlug = "", $onlyContent = false) {

            if ($form = $this->index(true)->findBy('slug', $formSlug))
                return $onlyContent ? $form->drafts()->map(fn ($a) => $a->content()) : $form->drafts();

            return false;
        },
    ],
    'api' => [
        'routes' => [
            [
                'pattern' => 'form/get-requests',
                'action'  => function ()
                {

                    if ($requests =  $this->site()->formRequests($this->requestQuery('form'), true))
                        return $requests->toArray();

                    return [];
                }
            ],
            [
                'pattern' => 'form/get-requests-count',
                'action'  => function ()
                {
                    if ($requests = $this->site()->formRequests($this->requestQuery('form'))) {

                        return [
                            $requests->count(),
                            $requests->filterBy('read', '')->count(),
                            $requests->filterBy([['read', ''],['error', '!=', '']])->count()
                        ];
                    }

                    return [0,0];
                }
            ],
            [
                'pattern' => 'form/set-read',
                'action'  => function ()
                {

                    $this->site()->formRequests($this->requestQuery('form'))
                        ->find($this->requestQuery('request'))->update(
                            ['read' => ($this->requestQuery('state') == "false") ? "" : date('Y-m-d H:i:s', time())]
                        );

                    return $this->site()->formRequests($this->requestQuery('form'), true)->toArray();

                }
            ],
            [
                'pattern' => 'form/delete-request',
                'action'  => function ()
                {
                    
                    $this->site()->formRequests($this->requestQuery('form'))
                        ->find($this->requestQuery('request'))->delete(true);

                        return $this->site()->formRequests($this->requestQuery('form'), true)->toArray();

                }
            ]
        ]
    ],
    'translations' => [
        'en' => require __DIR__ . '/lib/languages/en.php',
        'de' => require __DIR__ . '/lib/languages/de.php'
    ]
]);
