<?php
declare(strict_types=1);


namespace GuzabaPlatform\Platform\Application;


use Azonmedia\UrlRewriting\Interfaces\RewriterInterface;
use Guzaba2\Base\Base;
use Psr\Http\Message\RequestInterface;

/**
 * Class UrlRewritingRules
 * Rewrites ALL URLs that do not begin with any of the $prefixes to /
 * This is to be used to redirect all requests that are not served by the static handler and are not API requests (begin with /api/) to /.
 * @package GuzabaPlatform\Platform\Application
 */
class UrlRewritingRules extends Base
    implements RewriterInterface
{

    protected array $prefixes = [];

    public function __construct(array $prefixes)
    {
        parent::__construct();
        $this->prefixes = $prefixes;
    }

    public function add_prefix(string $prefix) : void
    {
        $this->prefixes[] = $prefix;
    }

    public function rewrite_uri(string $uri): string
    {
        // TODO: Implement rewrite_uri() method.
    }

    /**
     * @param RequestInterface $Request
     * @return RequestInterface
     */
    public function rewrite_request(RequestInterface $Request): RequestInterface
    {
        $path = $Request->getUri()->getPath();
        $to_rewrite = TRUE;
        foreach ($this->prefixes as $prefix) {
            if (strpos($path, $prefix) ===0 ) { //does not start with...
                $to_rewrite = FALSE;
                break;
            }
        }
        if ($to_rewrite) {
            $path = '/';
            $Uri = $Request->getUri();
            $Uri = $Uri->withPath($path);
            $Request = $Request->withUri($Uri);
        }

        return $Request;
    }
}