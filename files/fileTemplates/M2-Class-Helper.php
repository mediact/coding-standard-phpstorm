<?php declare(strict_types=1);
#parse("M2-PHP-File-Header")

#if (${NAMESPACE})
namespace ${NAMESPACE};
#end

use Magento\Framework\App\Helper\AbstractHelper;

class ${NAME} extends AbstractHelper
{
    #[[$END$]]#
}