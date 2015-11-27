<?php
/**
 * Created by PhpStorm.
 * User: danielfiebig
 * Date: 04/05/15
 * Time: 11:49
 */

namespace bdart\mailchimp;
use Yii;
use yii\base\Component;
use \Mailchimp as MailchimpApi;

/**
 * Class Mailchimp
 * @package bdart\mailchimp
 *
 * @param string $apikey
 */
class Mailchimp extends Component implements MailInterface
{
    /**
     * the api key in use
     * @var  string $apikey
     */
    public $apikey;
    /**
     * The options for mailchimp API
     * @var array
     */
    public $opts = [];

    /**
     * @var
     */
    public $mailChimp;

    /**
     * the mailchimp API version
     * @var string $version
     */
    public $version;

    /**
     *
     */
    public function init()
    {
        $this->mailChimp = new MailchimpApi($this->apikey, $this->opts);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        try{
            parent::__get($name);
        }catch(\yii\base\UnknownPropertyException $e){
            return $this->mailChimp->$name;
        }
    }

    /**
     * @param string $name
     * @param array $parameters
     * @return mixed
     */
    public function __call($name, $parameters = [])
    {
        return call_user_func_array([$this->mailChimp, $name], $parameters);
    }

    /**
     * Subscribe new users to a list
     *
     * @param $batch
     * @param $id
     * @param $action
     * @return array
     */
    public function subscribe($batch, $id, $action='lists/batch-subscribe')
    {
        $optin = false; //yes, send optin emails
        $up_exist = true; // yes, update currently subscribed users
        $replace_int = true; // no, add interest, don't replace

        $service_url = $this->url().$action.'.json';

        $data = array(
                "apikey" => $this->apikey,
                "id" => $id,
                "batch" => $batch,
                "double_optin" => $optin,
                "update_existing" => $up_exist,
                "replace_interests" => $replace_int
            );

        return \Yii::$app->restclient->post($service_url, $data);
    }

    /**
     * Unsubscribe users from a list
     *
     * @param $batch
     * @param $id
     * @param string $action
     * @return mixed
     */
    public function unsubscribe($batch, $id, $action='lists/batch-unsubscribe')
    {
        $delete_member = false; //yes, send optin emails
        $send_goodbye = false; // yes, update currently subscribed users
        $send_notify = false; // no, add interest, don't replace

        $service_url = $this->url().$action.'.json';

        $data = array(
            "apikey" => $this->apikey,
            "id" => $id,
            "batch" => $batch,
            "delete_member" => $delete_member,
            "send_goodbye" => $send_goodbye,
            "send_notify" => $send_notify
        );

        return \Yii::$app->restclient->post($service_url, $data);
    }

    /**
     * @param string $action
     * @return mixed
     */
    public function lists($action='lists/list')
    {
        $service_url = $this->url().$action.'.json?apikey='.$this->apikey;

        return \Yii::$app->restclient->get($service_url);
    }

    /**
     * @param $id
     * @param string $action
     * @return mixed
     */
    public function groups($id, $action='lists/interest-groupings')
    {
        $service_url = $this->url().$action.'.json?apikey='.$this->apikey.'&id='.$id;

        return \Yii::$app->restclient->get($service_url);
    }

    /**
     * builds the url for the API batch
     * @return string
     */
    private function url()
    {
        $dc = substr($this->apikey, strpos($this->apikey, '-')+1);
        return 'https://'.$dc.'.api.mailchimp.com/'.$this->version.'/';
    }
}