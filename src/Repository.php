<?php
/**
 * @author Persi.Liao
 * @email xiangchu.liao@gmail.com
 * @link https://www.github.com/persiliao
 */

declare(strict_types=1);

namespace PersiLiao\GitWebhooks;

use Closure;
use PersiLiao\GitWebhooks\Event\AbstractEvent;
use PersiLiao\GitWebhooks\Event\PingEvent;
use PersiLiao\GitWebhooks\Event\PushEvent;
use PersiLiao\GitWebhooks\Exception\InvalidArgumentException;
use PersiLiao\GitWebhooks\Provider\AbstractProvider;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Persi.Liao
 * @email xiangchu.liao@gmail.com
 * @link https://www.github.com/persiliao
 * Class EventFactory
 * @package PersiLiao\GitWebhooks
 * @method $this onCommitComment(Closure $handler) - Commit or diff commented on.
 * @method $this onCreate(Closure $handler) - Branch or tag created.
 * @method $this onDelete(Closure $handler) - Branch or tag deleted.
 * @method $this onDeployment(Closure $handler) - Repository deployed.
 * @method $this onDeploymentStatus(Closure $handler) - Deployment status updated from the API.
 * @method $this onFork(Closure $handler) - Repository forked.
 * @method $this onGollum(Closure $handler) - Wiki page updated.
 * @method $this onIssueComment(Closure $handler) - Issue comment created, edited, or deleted.
 * @method $this onIssues(Closure $handler) - Issue opened, edited, closed, reopened, assigned, unassigned, labeled, unlabeled, milestoned, or demilestoned.
 * @method $this onLabel(Closure $handler) - Label created or deleted.
 * @method $this onMember(Closure $handler) - Collaborator added to a repository.
 * @method $this onMilestone(Closure $handler) - Milestone created, closed, opened, edited, or deleted.
 * @method $this onPageBuild(Closure $handler) - Pages site built.
 * @method $this onPrivateToPublic(Closure $handler) - Repository changes from private to public .
 * @method $this onPullRequest(Closure $handler) - Pull request opened, closed, reopened, edited, assigned, unassigned, labeled, unlabeled, or synchronized.
 * @method $this onPullRequestReview(Closure $handler) - Pull request review submitted.
 * @method $this onPullRequestReviewComment(Closure $handler) - Pull request diff comment created, edited, or deleted.
 * @method $this onPing(Closure $handler) - Git ping to a repository.
 * @method $this onPush(Closure $handler) - Git push to a repository.
 * @method $this onRelease(Closure $handler) - Release published in a repository.
 * @method $this onStatus(Closure $handler) - Commit status updated from the API.
 * @method $this onTeamAdd(Closure $handler) - Team added or modified on a repository.
 * @method $this onWatch(Closure $handler) - User stars a repository.
 */
class Repository
{
    /**
     * @var AbstractProvider[]
     */
    protected $providers = [];

    /**
     * @var string[]
     */
    protected $secrets = [];

    /**
     * Repository constructor.
     * @param array $providers
     * @param array $secrets
     */
    public function __construct(array $providers, array $secrets = [])
    {
        $this->secrets = $secrets;
        foreach($providers as $provider){
            $this->addProvider($provider);
        }
    }

    /**
     *
     * @param AbstractProvider $provider
     *
     * @return $this
     */
    public function addProvider(AbstractProvider $provider): self
    {
        $this->providers[] = $provider;

        return $this;
    }

    /**
     * @return AbstractEvent|PushEvent|PingEvent
     */
    public function createEvent(): AbstractEvent
    {
        foreach($this->providers as $provider){
            if(!$provider->support()){
                continue;
            }
            $event = $provider->create();
            $provider->validate($event, $this->secrets);
            return $event;
        }
        throw new InvalidArgumentException('Git webhook not support', Response::HTTP_BAD_REQUEST);
    }

    public function __call($name, $arguments)
    {
        foreach($this->providers as $provider){
            if(!$provider->support()){
                continue;
            }

            $provider->addHandle($name, $arguments[0]);
        }
    }
}