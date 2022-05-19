<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\nofc\intranet\announcement\space\configurator\models;

use humhub\modules\content\components\ContentContainerActiveRecord;
use Yii;
use humhub\modules\user\models\User;

/**
 * ConfigureForm
 *
 * @author Luke
 */
class ConfigureForm extends \yii\base\Model
{

    public $spaces;
    //public $users;
    public $assignAll;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //['users', 'safe'],
            ['assignAll', 'boolean'],
            ['spaces', 'safe']
        ];
    }

    public function save()
    {

        $module = Yii::$app->getModule('nofc-intranet-announcement-space-configurator');
        $module->settings->setSerialized('spaces', $this->spaces);
        //$module->settings->setSerialized('users', $this->users);

        if ($this->assignAll) {

            $announcementSpaces = $module->getAnnouncementSpacesForUserAutoAddition();
            
            foreach (User::find()->active()->all() as $user) {
                
                foreach ($announcementSpaces as $announcementSpace) {

                    /** @var ContentContainerActiveRecord $announcementSpace */
                    if ($announcementSpace instanceof \humhub\modules\space\models\Space) {
                        
                        if ($announcementSpace->isMember($user->id)) {

                            $membership = $announcementSpace->getMembership($user->id);

                            if(
                                $membership->can_cancel_membership.'' === '1'
                            ) {
                                // make sure members of the space can't leave the space
                                $membership->can_cancel_membership = 0;
                            }

                            if(
                                $membership->send_notifications.'' === '0'
                            ) {
                                // make sure members of the space always get notifications
                                $membership->send_notifications = 1;
                            }

                            $membership->save();

                            continue;
                        } else {

                            // Add the user as a member of the current space & make sure
                            // the user can't leave the space. An admin would have to
                            // remove them.
                            try {

                                $announcementSpace->addMember($user->id, 0, false);

                            } catch (\Throwable $e) {
                                //TODO: Send notification or email to site Administrator with details
                            }
                        }
                    }
                    //$follow->follow($user, false);
                }
            }
        }

        return true;
    }

    public function attributeLabels()
    {
        return [
            'assignAll' => Yii::t('NofcIntranetAnnouncementSpaceConfiguratorModule.setting', 'Force all existing users to become members of specified space(s)')
        ];
    }

    public function loadSettings()
    {
        $module = Yii::$app->getModule('nofc-intranet-announcement-space-configurator');

        $this->spaces = $module->settings->getSerialized('spaces');
        //$this->users = $module->settings->getSerialized('users');
    }

}
