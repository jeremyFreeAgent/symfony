<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Config\Tests\Definition;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\ArrayNode;
use Symfony\Component\Config\Definition\PrototypedArrayNode;
use Symfony\Component\Config\Definition\ScalarNode;

class NodePathTest extends TestCase
{
    public function testGetPathForBaseNode()
    {
        $rootNode = new ArrayNode('root');
        $this->assertSame('root', $rootNode->getPath());

        $rootNode = new ArrayNode('root');
        $parentNode = new ArrayNode('parent', $rootNode);
        $childNode = new ScalarNode('child', $parentNode);

        $this->assertSame('root.parent.child', $childNode->getPath());

        $rootNode = new ArrayNode('root');
        $parentNode = new ArrayNode(null, $rootNode);
        $childNode = new ScalarNode('child', $parentNode);
        $otherChildNode = new ScalarNode('other', $parentNode);

        $this->assertSame('root[].child', $childNode->getPath());
    }

    public function testGetPathForPrototypedArrayNode()
    {
        $rootNode = new ArrayNode('root');
        $parentNode = new PrototypedArrayNode(null, $rootNode);
        $parentNode->setKeyAttribute('name', true);
        $nameNode = new ScalarNode('name', $parentNode);
        $childNode = new ScalarNode('child', $parentNode);

        $this->assertSame('root[name].name', $nameNode->getPath());
        $this->assertSame('root[name].child', $childNode->getPath());
    }

    public function testGetPathForPrototypedArrayNode2()
    {
        $rootNode = new ArrayNode('root');
        $parentNode = new PrototypedArrayNode('parent', $rootNode);
        $parentNode->setKeyAttribute('name', true);
        $nameNode = new ScalarNode('name', $parentNode);
        $childNode = new ScalarNode('child', $parentNode);

        $this->assertSame('root.parent[name].name', $nameNode->getPath());
        $this->assertSame('root.parent[name].child', $childNode->getPath());
    }

    public function testGetPathForPrototypedArrayNode3()
    {
        $rootNode = new ArrayNode('root');
        $parentNode = new PrototypedArrayNode('parent', $rootNode);
        $nameNode = new ScalarNode('name', $parentNode);
        $childNode = new ScalarNode('child', $parentNode);

        $this->assertSame('root.parent[].name', $nameNode->getPath());
        $this->assertSame('root.parent[].child', $childNode->getPath());
    }

    public function testGetPathForPrototypedArrayNode4()
    {
        $rootNode = new ArrayNode('root');
        $arraysList = new ArrayNode('arrays_list', $rootNode);
        $prototypeArrayNode = new PrototypedArrayNode(null, $arraysList);
        $prototypeArrayNode->setKeyAttribute('name', true);
        $prototypeNameNode = new ScalarNode('name', $prototypeArrayNode);
        $nameNode = new ScalarNode('name', $rootNode);

        $this->assertSame('root.arrays_list[name].name', $prototypeNameNode->getPath());
        $this->assertSame('root.name', $nameNode->getPath());
    }

    public function testGetPathForPrototypedArrayNode5()
    {
        $rootNode = new ArrayNode('root');
        $arraysList = new ArrayNode('arrays_list', $rootNode);
        $prototypeArrayNode = new PrototypedArrayNode(null, $arraysList);
        $prototypeNameNode = new ScalarNode('name', $prototypeArrayNode);
        $nameNode = new ScalarNode('name', $rootNode);

        $this->assertSame('root.arrays_list[].name', $prototypeNameNode->getPath());
        $this->assertSame('root.name', $nameNode->getPath());
    }

    public function testMixingPrototypeArrayNodeWithOtherNode()
    {
        $rootNode = new ArrayNode('root');
        $prototypeArrayNode = new PrototypedArrayNode(null, $rootNode);
        $prototypeNameNode = new ScalarNode('name', $prototypeArrayNode);
        $prototypeChildNode = new ScalarNode('child', $prototypeArrayNode);
        $otherNode = new ScalarNode('other', $rootNode);

        $this->assertSame('root[].child', $prototypeChildNode->getPath());
        $this->assertSame('root.other', $otherNode->getPath());
    }
}
