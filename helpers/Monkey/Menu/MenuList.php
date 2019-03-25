<?php

namespace Monkey\Menu;


use Countable;
use Iterator;

/**
 * Description of MenuList
 *
 * @author Tomas
 */
class MenuList implements Iterator, Countable {
    /**
     * @var int $index
     */
    private $index = 0;

    /**
     * @var Menu[] $list
     */
    private $list = [];

    /**
     * @var bool $prepared
     */
    private $prepared = false;

    /**
     * @return array
     */
    public function getList(): array {
        return $this->list;
    }

    /**
     * @param $list
     * @return MenuList
     */
    public function setList($list): MenuList {
        $this->list = $list;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasSubList(): bool {
        return count($this->getList()) > 0;
    }

    /**
     * @param Menu $menuItem
     * @return Menu
     */
    public function addMenuItem(Menu $menuItem): Menu {
        $this->list[] = &$menuItem;
        return $menuItem;
    }

    /**
     * @return bool
     */
    public function isPrepared(): bool {
        return $this->prepared;
    }

    /**
     * @return MenuList
     */
    public function setPrepared(): MenuList {
        $this->prepared = true;
        return $this;
    }

    /**
     * Return the current element
     * @link https://php.net/manual/en/iterator.current.php
     * @return Menu Can return any type.
     * @since 5.0.0
     */
    public function current() {
        return $this->list[$this->index];
    }

    /**
     * Move forward to next element
     * @link https://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next() {
        ++$this->index;
    }

    /**
     * Return the key of the current element
     * @link https://php.net/manual/en/iterator.key.php
     * @return int scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key() {
        return $this->index;
    }

    /**
     * Checks if current position is valid
     * @link https://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid() {
        return isset($this->list[$this->index]);
    }

    /**
     * Rewind the Iterator to the first element
     * @link https://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind() {
        $this->index = 0;
    }

    /**
     * Count elements of an object
     * @link https://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count() {
        return count($this->list);
    }
}
