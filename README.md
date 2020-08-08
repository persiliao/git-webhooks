# Git Web Hooks

normalise webhook events for Github, Gitlab, Bitbucket, Gogs, Gitea

## Installation (using composer)

```bash
composer require persiliao/git-webhooks:dev-master
```

#### Examples

###### Example
automatically `git pull` on your server after every push

```php
<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use PersiLiao\GitWebhooks\Provider\GiteaProvider;
use PersiLiao\GitWebhooks\Repository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

try{
    $response = new Response();
    // Webhook Secret
    $secrets = [
        // 'repository name' => 'webhook secret'
        'git-webhooks' => '2IlCA4awiMB098FDboKzdxuOtyRPV76r'
    ];
    $repository = new Repository([
        new GiteaProvider(Request::createFromGlobals())
    ], $secrets);
    $event = $repository->createEvent();
    $repository->onPush(function() use ($event, $response){
        // do something...
        if($event->getBranchName() === 'master'){
            exec('cd /path/to/your/project && git pull');
        }
        $response->setContent("git pull success");
    });
}catch(Exception $e){
    $response->setStatusCode($e->getCode())->setContent($e->getMessage());
}finally{
    $response->send();
}
```

## Support 

- [ ] **Github**
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
    - [x] `onPush` - Git push to a repository.
    - [ ] `onRelease` - Release published in a repository.
    - [ ] `onStatus` - Commit status updated from the API.
    - [ ] `onTeamAdd` - Team added or modified on a repository.
    - [ ] `onWatch` - User stars a repository.
- [ ] **Gitea**
    - [x] `onPush` - Git push to a repository.
- [ ] **Gogs**
    - [x] `onPush` - Git push to a repository.
- [ ] **Gitlab**


