<?php

namespace humhub\modules\nofc\intranet\announcement\space\configurator;

use Yii;
use yii\helpers\Url;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\User;

class Module extends \humhub\components\Module
{
    /**
     * @inheritdoc
     */
    public $resourcesPath = 'resources';
    /**
     * @inheritdoc
     */
    public function getConfigUrl()
    {
        return Url::to([
                    '/auto-follow/admin'
        ]);
    }

    /**
     * Returns currently defined followable records
     * 
     * @return \humhub\modules\content\components\ContentContainerActiveRecord[] the automatic followable records
     */
    public function getAnnouncementSpacesForUserAutoAddition()
    {
        $announcementSpaces = [];

        $spaces = $this->settings->getSerialized('spaces');
        if ($spaces !== null && is_array($spaces)) {
            foreach ($spaces as $guid) {
                $s = Space::findOne(['guid' => trim($guid)]);
                if ($s !== null) {
                    $announcementSpaces[] = $s;
                }
            }
        }

        return $announcementSpaces;
    }

}
