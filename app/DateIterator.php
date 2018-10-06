<?php


namespace App;


use Cake\Chronos\Chronos;

class DateIterator implements \Iterator
{
    /**
     * @var Chronos
     */
    private $start;

    /**
     * @var Chronos
     */
    private $end;

    /**
     * @var Chronos
     */
    private $current;



    public function __construct(\DateTimeInterface $start, \DateTimeInterface $end)
    {
        $this->start = Chronos::instance($start);
        $this->end = Chronos::instance($end);

        $this->rewind();
    }

    /**
     * Return the current element
     * @link https://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * Move forward to next element
     * @link https://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        $this->current = $this->current->addDay();
    }

    /**
     * Return the key of the current element
     * @link https://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->current->diffInDays($this->start);
    }

    /**
     * Checks if current position is valid
     * @link https://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return $this->current() <= $this->end;
    }

    /**
     * Rewind the Iterator to the first element
     * @link https://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->current = Chronos::instance($this->start);
    }
}
