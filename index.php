<?php

load([
    'microman\\FormBlueprint' => '/classes/FormBlueprint.php',
    'microman\\Form' => '/classes/Form.php',
    'microman\\FormFields' => '/classes/FormFields.php',
    'microman\\FormField' => '/classes/FormField.php',
], __DIR__);

use microman\Form;
use microman\FormBlueprint;
use Kirby\Http\Server;

\Kirby\Cms\App::plugin('microman/formblock', [
    'options' => [
        'from_email' => 'no-reply@' . Server::host(),
        'placeholders' => FormBlueprint::getPlaceholders(),
        'disable_inbox' => false,
        'disable_confirm' => false,
        'disable_notify' => false,
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
        'selectoption' => [
            'props' => []
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
                            ['read' => ($this->requestQuery('state') == "false") ? "" : date('Y-m-d H:i:s')]
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
