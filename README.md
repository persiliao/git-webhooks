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

- [ ] **`Github`**
    - [x] `onPush` - Git push to a repository.
- [ ] **`Gitea`**
    - [x] `onPush` - Git push to a repository.
- [ ] **`Gogs`**
    - [x] `onPush` - Git push to a repository.
- [ ] **`Gitlab`**


