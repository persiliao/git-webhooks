<?php
/**
 * @author Persi.Liao
 * @email xiangchu.liao@gmail.com
 * @link https://www.github.com/persiliao
 */

declare(strict_types=1);

namespace PersiLiao\GitWebhooks;

use PersiLiao\Entity\Repository;
use PersiLiao\Exception\InvalidArgumentException;
use function strpos;
use function substr;

class Util
{
    /**
     * @param string $ref
     * @return string
     */
    public static function getBranchName($ref)
    {
        if(self::getPushType($ref) != Repository::TYPE_BRANCH){
            throw new InvalidArgumentException("Branch ref isn't a branch");
        }

        return substr($ref, 11);
    }

    /**
     * @param string $ref
     * @return string
     */
    public static function getPushType($ref)
    {
        if(strpos($ref, 'refs/tags/') === 0){
            return Repository::TYPE_TAG;
        }

        if(strpos($ref, 'refs/heads/') === 0){
            return Repository::TYPE_BRANCH;
        }

        throw new InvalidArgumentException("Push type not supported");
    }

    /**
     * @param string $ref
     * @return string
     */
    public static function getTagName($ref)
    {
        if(self::getPushType($ref) != Repository::TYPE_TAG){
            throw new InvalidArgumentException("Tag ref isn't a tag");
        }

        return substr($ref, 10);
    }
}