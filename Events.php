<?php

namespace humhub\modules\nofc\intranet\announcement\space\configurator;

use humhub\modules\space\models\Space;
use Yii;
use yii\base\InvalidConfigException;

/**
 * Event Callbacks
 *
 * @author luke
 */
class Events
{

    public static function onAfterUserCreate($event)
    {
        $user = $event->sender;
        $module = Yii::$app->getModule('nofc-intranet-announcement-space-configurator');

        foreach ($module->getAnnouncementSpacesForUserAutoAddition() as $container) {

            if ($container instanceof Space && $container->isMember($user->id)) {
                
                $membership = $container->getMembership($user->id);

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

            } elseif($container instanceof Space && !$container->isMember($user->id)) {

                // Add the new user as a member of the current space & make sure
                // the new user can't leave the space. An admin would have to
                // remove them
                try {

                    $container->addMember($user->id, 0, false);

                } catch (\Throwable $e) {
                    
                    //TODO: Send notification or email to site Administrator with details
                }
            } // if ($container instanceof Space && $container->isMember($user->id))
        } // foreach ($module->getAnnouncementSpacesForUserAutoAddition() as $container)
    } // public static function onAfterUserCreate($event)
}
