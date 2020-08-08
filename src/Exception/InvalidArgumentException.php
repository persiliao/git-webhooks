<?php
/**
 * @author Persi.Liao
 * @email xiangchu.liao@gmail.com
 * @link https://www.github.com/persiliao
 */

declare(strict_types=1);

namespace PersiLiao\GitWebhooks\Exception;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

class InvalidArgumentException extends \InvalidArgumentException
{
    public function __construct($message = "", $code = Response::HTTP_FORBIDDEN, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}