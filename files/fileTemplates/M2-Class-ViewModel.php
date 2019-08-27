<?php declare(strict_types=1);
#parse("M2-PHP-File-Header")

#if (${NAMESPACE})
namespace ${NAMESPACE};
#end

use Magento\Framework\View\Element\Block\ArgumentInterface;

class ${NAME} implements ArgumentInterface
{
    #[[$END$]]#
}
