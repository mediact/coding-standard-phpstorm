<?php
#parse("PHP File Header")

#if (${NAMESPACE})
namespace ${NAMESPACE};
#end

use PHPUnit_Framework_TestCase;
use ${TESTED_NAMESPACE}\\${TESTED_NAME};

/**
 * @coversDefaultClass \\${TESTED_NAMESPACE}\\${TESTED_NAME}
 */
class ${NAME} extends PHPUnit_Framework_TestCase
{
    /**
     * @return ${TESTED_NAME}
     *
     * @covers ::__construct
     */
    public function testConstructor(): ${TESTED_NAME}
    {
        return new ${TESTED_NAME}();
    }
}
