<?php

namespace BaoPham\TreeParser;

class TreeParser
{
    private $spaces = 2;

    /**
     * @var string
     */
    private $tree;

    /**
     * @var int
     */
    private $initialSpaces;

    /**
     * @var array
     */
    private $structure;

    /**
     * @var array
     */
    private $orderedNodes;

    /**
     * TreeParser constructor.
     * @param string $tree
     *
     * <<<TREE
     *  Root
     *    |- Level 1 - Order 1
     *      |- Level 2 - Order 2
     *        |- Level 3 - Order 3
     *        |- Level 3 - Order 4
     *      |- Level 2 - Order 5
     *    |- Level 1 - Order 6
     *      |- Level 2 - Order 7
     *        |- Level 3 - Order 8
     *          |- Level 4 - Order 9
     * TREE;
     */
    public function __construct($tree)
    {
        $this->tree = $tree;
    }

    public function parse()
    {
        $lines = explode(PHP_EOL, $this->tree);

        $this->setStructureAndOrderedNodes($lines);

        $root = $this->structure[TreeNode::ROOT_LEVEL][TreeNode::ROOT_ORDER];

        foreach ($this->orderedNodes as $node) {
            if ($node->isRoot) {
                continue;
            }

            $parent = $this->getParentForNode($node);

            $parent->children[] = $node;

            $node->parent = $parent;
        }

        return $root;
    }

    public function setTree($tree)
    {
        $this->tree = $tree;
    }

    public function getTree()
    {
        return $this->tree;
    }

    /**
     * @return array
     */
    public function getOrderedNodes()
    {
        return $this->orderedNodes;
    }

    /**
     * @return array
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * @param int $spaces
     * @return TreeParser
     */
    public function setSpaces($spaces)
    {
        $this->spaces = $spaces;
        return $this;
    }

    public function getParentForNode(TreeNode $node)
    {
        if ($node->level === TreeNode::ROOT_LEVEL) {
            return null;
        }

        $parentLevel = $node->level - 1;

        $candidates = $this->structure[$parentLevel];

        $order = $node->order - 1;

        while ($order >= TreeNode::ROOT_ORDER) {
            if (isset($candidates[$order])) {
                return $candidates[$order];
            }

            $order--;
        }
    }

    private function numberOfSpaces($line)
    {
        return strspn($line, ' ');
    }

    private function spacesToLevel($numberOfSpaces)
    {
        if ($numberOfSpaces === $this->initialSpaces) {
            return 0;
        }

        if (($numberOfSpaces - $this->initialSpaces) % $this->spaces !== 0) {
            throw new InvalidNumberOfSpaces(
                "Make sure children's leading spaces being a multiple of {$this->spaces}"
            );
        }

        return ($numberOfSpaces - $this->initialSpaces) / $this->initialSpaces;
    }

    private function lineToNode($line)
    {
        $line = trim($line);
        $line = ltrim($line, '|-');
        $line = trim($line);

        $node = new TreeNode();

        $node->name = $line;

        return $node;
    }

    private function setStructureAndOrderedNodes($lines)
    {
        $this->orderedNodes = [];

        $this->initialSpaces = null;

        $this->structure = [];

        foreach ($lines as $order => $line) {
            $node = $this->lineToNode($line);

            if ($this->initialSpaces === null) {
                $this->initialSpaces = $this->numberOfSpaces($line);
                $node->isRoot = true;
            }

            $node->order = $order;

            $node->level = $this->spacesToLevel($this->numberOfSpaces($line));

            $this->structure[$node->level][$node->order] = $node;

            $this->orderedNodes[] = $node;
        }
    }
}
