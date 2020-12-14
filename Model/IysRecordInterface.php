<?php


namespace Mnm\Iys\Model;



use phpDocumentor\Reflection\Types\Integer;

interface IysRecordInterface
{

    /**
     * @return String
     */
    public function getCustomerId();
    /**
     * @return String
     */
    public function getPhoneNumber();
    /**
     * @return Integer
     */
    public function getSmsPermission();
    /**
     * @return Integer
     */
    public function getEmailPermission();
    /**
     * @return Integer
     */
    public function getCallPermission();
    /**
     * @return String
     */
    public function getEmailAddress();
    /**
     * @return String
     */
    public function getFirstName();
    /**
     * @return String
     */
    public function getLastName();
    /**
     * @return Integer
     */
    public function getSourceId();
    /**
     * @return String
     */
    public function getDate();
    /**
     * @return Integer
     */
    public function getIndividual();
    /**
     * @return Integer
     */

    public function getCorporate();
    /**
     * @return String
     */
    public function getNote();
    /**
     * @return Array
     */



    public function convertToArray();


}
