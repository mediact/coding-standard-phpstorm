<?php declare(strict_types=1);
#parse("M2-PHP-File-Header")

#if (${NAMESPACE})
namespace ${NAMESPACE};
#end

use Magento\Framework\View\Element\Template;

class ${NAME} extends Template
{
    #[[$END$]]#
}