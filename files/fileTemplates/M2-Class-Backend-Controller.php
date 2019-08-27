<?php declare(strict_types=1);
#parse("M2-PHP-File-Header")

#if (${NAMESPACE})
namespace ${NAMESPACE};
#end

use Magento\Backend\App\AbstractAction;

class ${NAME} extends AbstractAction
{
    /**
     *
     */
    public function execute()
    {
        #[[$END$]]#
    }
}