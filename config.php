<?php

use humhub\modules\user\models\User;

return [
    'id' => 'nofc-intranet-announcement-space-configurator',
    'class' => 'humhub\modules\nofc\intranet\announcement\space\configurator\Module',
    'namespace' => 'humhub\modules\nofc\intranet\announcement\space\configurator',
    'events' => [

        [
                'class' => User::class, 
                'event' => User::EVENT_AFTER_INSERT, 
             'callback' => ['humhub\modules\nofc\intranet\announcement\space\configurator\Events', 'onAfterUserCreate']],
    ]
];
