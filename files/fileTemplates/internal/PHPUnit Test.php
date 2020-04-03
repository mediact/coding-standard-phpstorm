<?php declare(strict_types=1);
#parse("PHP File Header")

#if (${NAMESPACE})
namespace ${NAMESPACE};
#end

use PHPUnit\Framework\TestCase;
use ${TESTED_NAMESPACE}\\${TESTED_NAME};

/**
 * @coversDefaultClass \\${TESTED_NAMESPACE}\\${TESTED_NAME}
 */
class ${NAME} extends TestCase
{
}
