<?php


namespace Mnm\Iys\Model\Ajax;


interface IysAjaxInterface
{

    /**
     * @param $path
     * @return string
     */
    public function getAuthToken($path);


    /**
     * @param $message
     * @return mixed
     */
    public function jsonEncode($message);

    /**
     * @param $message
     * @return mixed
     */
    public function jsonDecode($message);

    /**
     * @return String
     */
    public function getApiBaseUrl();

    /**
     * @return string
     */
    public function registerCustomer($message);


    public function readCustomer($recordId);


}
