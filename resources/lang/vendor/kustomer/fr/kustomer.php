<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Tooltip Message
    |--------------------------------------------------------------------------
    |
    | Text that appears in the tooltip when the cursor hover the bubble, before
    | the popup opens.
    |
    */

    'tooltip' => 'Give feedback',

    /*
    |--------------------------------------------------------------------------
    | Popup Title
    |--------------------------------------------------------------------------
    |
    | This is the text that will appear below the logo in the feedback popup
    |
    */

    'title' => 'Aidez nous à améliorer le logiciel',

    /*
    |--------------------------------------------------------------------------
    | Success Message
    |--------------------------------------------------------------------------
    |
    | This message will be displayed if the feedback message is correctly sent.
    |
    */

    'success' => 'Merci pour votre retour ! ',

    /*
    |--------------------------------------------------------------------------
    | Placeholder
    |--------------------------------------------------------------------------
    |
    | This text will appear as the placeholder of the textarea in which the
    | the user will type his feedback.
    |
    */

    'placeholder' => 'Tapez votre retour ici',

    /*
    |--------------------------------------------------------------------------
    | Button Label
    |--------------------------------------------------------------------------
    |
    | Text of the confirmation button to send the feedback.
    |
    */

    'button' => 'Envoyer le retour',

    /*
    |--------------------------------------------------------------------------
    | Feedback Texts
    |--------------------------------------------------------------------------
    |
    | Must match the feedbacks array from the config file
    |
    */
    'feedbacks' => [
        'like' => [
            'title' => 'J\'apprécie quelque chose',
            'label' => 'Qu\'appréciez vous ?',
        ],
        'dislike' => [
            'title' => 'Je n\'apprécie pas quelque chose',
            'label' => 'Qu\'est ce que vous n\'appréciez pas ?',
        ],
        'suggestion' => [
            'title' => 'J\'ai une suggestion',
            'label' => 'Quelle est votre suggestion ?',
        ],
         'bug' => [
             'title' => 'J\'ai trouvé un bug',
             'label' => 'Expliquez le bug',
         ],
    ],
];
