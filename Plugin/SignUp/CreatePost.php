<?php

namespace Mnm\Iys\Plugin\SignUp;

class CreatePost
{

    public function afterExecute(\Magento\Customer\Controller\Account\CreatePost $subject)
    {
        var_dump("sdadada");
        exit(1);

    }

}
