<?php declare(strict_types=1);
#parse("M2 PHP File Header")

#if (${NAMESPACE})
namespace ${NAMESPACE};
#end

use Magento\Framework\Event\ObserverInterface;

class ${NAME} implements ObserverInterface
{
    #[[$END$]]#
}
