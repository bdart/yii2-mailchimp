<?php
/**
 * Created by PhpStorm.
 * User: danielfiebig
 * Date: 04/05/15
 * Time: 11:54
 */

namespace bdart\mailchimp;

/**
 * Interface MailInterface
 * @package bdart\mailchimp
 */
interface MailInterface
{
    /**
     * @param $batch
     * @param $id
     * @param $action
     * @return mixed
     */
    public function subscribe($batch, $id, $action='lists/batch-subscribe');

    /**
     * @param $batch
     * @param $id
     * @param string $action
     * @return mixed
     */
    public function unsubscribe($batch, $id, $action='lists/batch-unsubscribe');

    /**
     * @param $id
     * @param string $action
     * @return mixed
     */
    public function groups($id, $action='lists/interest-groupings');

    /**
     * @param string $action
     * @return mixed
     */
    public function lists($action='lists/list');
}