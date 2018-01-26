<?php
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
    /**
     * @return void
     *
     * @covers ::__construct
     */
    public function testConstructor(): void
    {
        $this->assertInstanceOf(
            ${TESTED_NAME}::class,
            new ${TESTED_NAME}()
        );
    }
}
