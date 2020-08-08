# Git Web Hooks

normalise webhook events for Github, Gitlab, Bitbucket, Gogs, Gitea, Gitee

## Installation (using composer)

```bash
# Stable
composer require persiliao/git-webhooks

# Development
composer require persiliao/git-webhooks:dev-master
```

## Supported Events

- [ ] **Github**
    - [x] `onPing` - Add webhook to a repository.
    - [x] `onPush` - Git push to a repository.
    - [ ] `onCommitComment` - Commit or diff commented on.
    - [ ] `onCreate` - Branch or tag created.
    - [ ] `onDelete` - Branch or tag deleted.
    - [ ] `onDeployment` - Repository deployed.
    - [ ] `onDeploymentStatus` - Deployment status updated from the API.
    - [ ] `onFork` - Repository forked.
    - [ ] `onGollum` - Wiki page updated.
    - [ ] `onIssueComment` - Issue comment created, edited, or deleted.
    - [ ] `onIssues` - Issue opened, edited, closed, reopened, assigned, unassigned, labeled, unlabeled, milestoned, or
 demilestoned.
    - [ ] `onLabel` - Label created or deleted.
    - [ ] `onMember` - Collaborator added to a repository.
    - [ ] `onMilestone` - Milestone created, closed, opened, edited, or deleted.
    - [ ] `onPageBuild` - Pages site built.
    - [ ] `onPrivateToPublic` - Repository changes from private to public.
    - [ ] `onPullRequest` - Pull request opened, closed, reopened, edited, assigned, unassigned, labeled, unlabeled, or
 synchronized.
    - [ ] `onPullRequestReview` - Pull request review submitted.
    - [ ] `onPullRequestReviewComment` - Pull request diff comment created, edited, or deleted.
    - [ ] `onRelease` - Release published in a repository.
    - [ ] `onStatus` - Commit status updated from the API.
    - [ ] `onTeamAdd` - Team added or modified on a repository.
    - [ ] `onWatch` - User stars a repository.
- [ ] **Gitea**
    - [x] `onPush` - Git push to a repository.
- [ ] **Gogs**
    - [x] `onPush` - Git push to a repository.
- [ ] **Gitlab**
    - [x] `onPush` - Git push to a repository.
- [ ] **Gitee**
    - [x] `onPush` - Git push to a repository.

## Examples

##### Example
automatically `git pull` on your server after every push

```php
<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use PersiLiao\GitWebhooks\Provider\GiteaProvider;
use PersiLiao\GitWebhooks\Provider\GithubProvider;
use PersiLiao\GitWebhooks\Provider\GitlabProvider;
use PersiLiao\GitWebhooks\Provider\GogsProvider;
use PersiLiao\GitWebhooks\Repository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

try{
    $response = new Response();
    $request = Request::createFromGlobals();
    // Webhook Secret
    $secrets = [
        // 'repository name' => 'webhook secret'
        'git-webhooks' => '2IlCA4awiMB098FDboKzdxuOtyRPV76r'
    ];
    $repository = new Repository([
        new GithubProvider($request),
        new GitlabProvider($request),
        new GiteaProvider($request),
        new GogsProvider($request)
    ], $secrets);
    $event = $repository->createEvent();
    $repository->onPing(function() use ($event, $response){
        // support Github
        $response->setContent(sprintf('pong success %s',$event->getBranchName()));
    });
    $repository->onPush(function() use ($event, $response){
        // do something...
        if($event->getBranchName() === 'master'){
            exec('cd /path/to/your/project && git pull');
        }
        $response->setContent('git pull success');
    });
}catch(Exception $e){
    $response->setStatusCode($e->getCode())->setContent($e->getMessage());
}finally{
    $response->send();
}
```

## License

MIT License

Copyright (c) 2020 Persi Liao <xiangchu.liao@gmail.com>



