<?php


namespace Mnm\Iys\Logger\Handler;

use Magento\Framework\Filesystem\DriverInterface;
use Magento\Framework\Logger\Handler\Base;


class IysLogin extends Base
{
    protected $loggerType = \Monolog\Logger::INFO;

    public function __construct(DriverInterface $filesystem, $filePath = null, $fileName = null)
    {
        $fileName='/var/log/mnm/iys-login-requests.log';
        parent::__construct($filesystem, $filePath, $fileName);
    }


}
