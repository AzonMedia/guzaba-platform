<?php


namespace GuzabaPlatform\Platform\Application;

use Guzaba2\Base\Base;
use Guzaba2\Base\Exceptions\InvalidArgumentException;
use Guzaba2\Base\Exceptions\RunTimeException;
use Guzaba2\Translator\Translator as t;
use Psr\Http\Server\MiddlewareInterface;

/**
 * Class Middlewares
 * @package GuzabaPlatform\Platform\Application
 * A service that provides access to inject middlewares by the components
 */
class Middlewares extends Base implements \Iterator, \Countable
{
    private iterable $middlewares = [];

    public function __construct(MiddlewareInterface ...$middlewares)
    {
        $this->add_multiple(...$middlewares);
    }

    /**
     * @return iterable
     */
    public function get_middlewares() : iterable
    {
        return $this->middlewares;
    }

    public function add_multiple(MiddlewareInterface ... $middlewares) : void
    {
        foreach ($middlewares as $Middleware) {
            $this->add($Middleware);
        }
    }

    /**
     * If no BeforeMiddleware is provided then the new Middleware will be appended.
     * Returns FALSE if the Middleware being added is already added.
     * If the BeforeMiddleware it not added already an RunTimeException will be thrown.
     * @param MiddlewareInterface $Middleware
     * @param MiddlewareInterface|string|null $before_middleware_class
     * @return bool
     */
    public function add(MiddlewareInterface $Middleware, $BeforeMiddleware = NULL) : bool
    {
        if ($this->has_middleware($Middleware)) {
            return FALSE;
        }
        if ($BeforeMiddleware) {
            if (!$this->has_middleware($BeforeMiddleware)) {
                throw new RunTimeException(sprintf(t::_('The $BeforeMiddleware instance of class %s is not found in the middlewares list.'), is_object($BeforeMiddleware) ? get_class($BeforeMiddleware) : $BeforeMiddleware ));
            }
            $middlewares = [];
            if (is_string($BeforeMiddleware)) {
                foreach ($this->middlewares as $AddedMiddleware) {
                    if (get_class($AddedMiddleware) === $BeforeMiddleware) {
                        $middlewares[] = $Middleware;
                    }
                    $middlewares[] = $AddedMiddleware;
                }
            } elseif ($BeforeMiddleware instanceof MiddlewareInterface) {
                foreach ($this->middlewares as $AddedMiddleware) {
                    if ($AddedMiddleware === $BeforeMiddleware) {
                        $middlewares[] = $Middleware;
                    }
                    $middlewares[] = $AddedMiddleware;
                }
            } else {
                throw new InvalidArgumentException(sprintf(t::_('An unsupported type %s was provided to $BeforeMiddleware argument of %s().'), gettype($BeforeMiddleware), __METHOD__ ));
            }
            $this->middlewares = $middlewares;
        } else {
            $this->middlewares[] = $Middleware;
        }
        return TRUE;
    }

    /**
     * @param string|MiddlewareInterface $Middleware
     * @return bool
     */
    public function has_middleware($Middleware) : bool
    {
        $ret = FALSE;
        if (is_string($Middleware)) {
            if (!class_exists($Middleware)) {
                throw new InvalidArgumentException(sprintf(t::_('The provided $Middleware argument %s to method %s() does not contain an existing class name.'), $Middleware, __METHOD__ ));
            }
            if (!is_a($Middleware, MiddlewareInterface::class, TRUE)) {
                throw new InvalidArgumentException(sprintf(t::_('The provided $Middleware argument %s to method %s() does not implement %s.'), $Middleware, __METHOD__ , MiddlewareInterface::class));
            }

            foreach ($this->middlewares as $AddedMiddleware) {
                if ($Middleware === get_class($AddedMiddleware)) {
                    $ret = TRUE;
                }
            }
        } elseif ($Middleware instanceof MiddlewareInterface) {
            foreach ($this->middlewares as $AddedMiddleware) {
                if ($Middleware === $AddedMiddleware) {
                    $ret = TRUE;
                }
            }
        } else {
            throw new InvalidArgumentException(sprintf(t::_('An unsupported type %s was provided to $Middleware argument of %s().'), gettype($BeforeMiddleware), __METHOD__ ));
        }

        return $ret;
    }

    public function current()
    {
        return current($this->middlewares);
    }

    public function next()
    {
        next($this->middlewares);
    }

    public function key()
    {
        return key($this->middlewares);
    }

    public function valid()
    {
        return $this->current() !== FALSE;
    }

    public function rewind()
    {
        reset($this->middlewares);
    }

    public function count() /* int */
    {
        return count($this->middlewares);
    }
}