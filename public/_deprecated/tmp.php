<?php

// then logout if remote
if (gethostname() != $request->getHost()) {
    file_put_contents('REQUEST.LOG', print_r("chachi", true).PHP_EOL.PHP_EOL, FILE_APPEND);
}

$logoutEvent = new LogoutEvent(Request::create('/logout'), $this->tokenStorage->getToken());
$dispatcher = new EventDispatcher();
$listener = $this->defaultLogoutListener;
$dispatcher->addListener("Symfony\Component\Security\Http\Event\LogoutEvent", [$listener, 'onLogout']);
$dispatcher->dispatch($logoutEvent);

$response = $logoutEvent->getResponse();
if (!$response instanceof Response) {
    throw new \RuntimeException('No logout listener set the Response, make sure at least the DefaultLogoutListener is registered.');
}

$this->tokenStorage->setToken(null); // actual logout  