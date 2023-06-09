<?php

namespace BaoPham\TreeParser;

use PHPUnit\Framework\TestCase;

class TreeParserTest extends TestCase
{
    public function test_parsing_tree()
    {
        $tree = <<<TREE
  Root
    |- Level 1 - Order 1
      |- Level 2 - Order 2
        |- Level 3 - Order 3
        |- Level 3 - Order 4
      |- Level 2 - Order 5
    |- Level 1 - Order 6
      |- Level 2 - Order 7
        |- Level 3 - Order 8
          |- Level 4 - Order 9
TREE;
        $parser = new TreeParser($tree);

        $root = $parser->parse();

        $this->assertEquals(file_get_contents(__DIR__.'/fixtures/expected_parsed_tree.json'), json_encode($root, JSON_PRETTY_PRINT));
    }

    public function test_setting_node_parent()
    {
        $tree = <<<TREE
  Root
    |- Level 1 - Order 1
      |- Level 2 - Order 2
        |- Level 3 - Order 3
        |- Level 3 - Order 4
      |- Level 2 - Order 5
    |- Level 1 - Order 6
      |- Level 2 - Order 7
        |- Level 3 - Order 8
          |- Level 4 - Order 9
TREE;
        $parser = new TreeParser($tree);

        $root = $parser->parse();

        $orderedNodes = $parser->getOrderedNodes();

        $lastItem = array_pop($orderedNodes);

        $this->assertEquals('Level 3 - Order 8', $lastItem->parent->name);
        $this->assertEquals('Level 2 - Order 7', $lastItem->parent->parent->name);
        $this->assertEquals('Level 1 - Order 6', $lastItem->parent->parent->parent->name);
        $this->assertEquals($root, $lastItem->parent->parent->parent->parent);
    }

    public function test_validating_number_of_spaces()
    {
        $this->expectException(InvalidNumberOfSpaces::class);
        $tree = <<<TREE
  Root
   |- Level 1 - Order 1
TREE;
        $parser = new TreeParser($tree);

        $parser->parse();
    }

    public function test_setting_custom_indentation()
    {
        $tree = <<<TREE
  Root
      |- Level 1 - Order 1
TREE;
        $parser = new TreeParser($tree);

        $parser->setIndentation(4);

        $root = $parser->parse();

        $this->assertCount(1, $root->children);
    }
}
