<?php

declare(strict_types=1);

namespace AbterPhp\Files\Grid\Factory\Table;

use AbterPhp\Admin\Grid\Factory\Table\BodyFactory;
use AbterPhp\Files\Grid\Factory\Table\Header\File as HeaderFactory;
use AbterPhp\Framework\Grid\Table\Table;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    /** @var File - System Under Test */
    protected $sut;

    /** @var MockObject|HeaderFactory */
    protected $headerFactoryMock;

    /** @var MockObject|BodyFactory */
    protected $bodyFactoryMock;

    public function setUp(): void
    {
        $this->headerFactoryMock = $this->createMock(HeaderFactory::class);
        $this->bodyFactoryMock   = $this->createMock(BodyFactory::class);

        $this->sut = new File($this->headerFactoryMock, $this->bodyFactoryMock);
    }

    public function testCreate()
    {
        $getters    = [];
        $rowActions = null;
        $params     = [];
        $baseUrl    = '';

        $actualResult = $this->sut->create($getters, $rowActions, $params, $baseUrl);

        $this->assertInstanceOf(Table::class, $actualResult);
    }
}
