<?php


namespace Mnm\Iys\Logger\Handler;

use Magento\Framework\Filesystem\DriverInterface;
use Magento\Framework\Logger\Handler\Base;


class IysRegistration extends Base
{
    protected $loggerType = \Monolog\Logger::INFO;

    public function __construct(DriverInterface $filesystem, $filePath = null, $fileName = null)
    {
        $fileName='/var/log/mnm/iys-reg-requests.log';
        parent::__construct($filesystem, $filePath, $fileName);
    }


}
