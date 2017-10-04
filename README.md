Web Action Handler Documentation
===================


In this documentation you'll be able to learn how framework works in background and how to properly use it.
Idea behind this framework is different approach to resolving control flow of an request, instead of using one PHP class as controller for many related actions `e.g UserController` we split each different action per class, and inside that class we implement wanted and required interfaces to make everything work.

Here we'll go through all important and helpful stuff. So please buckle up because we are about to go deep.

> **Note:**
> Every part of framework is expecting interfaces as input parameters, so it has flexibility to completely change flow of specific part of it.


> **Note:**
> This documentation might and will change.

----------


Sections
-------------

## Modules ##
 - [Application](#application)
 - Database
 - Exception
 - Model
 - Request
 - Response
 - Router

## Utilities ##

 - DataFilter
 - Decorator
 - InputValidator
 - ObjectFactory

---

Modules
=======

## Application ##

Consider following list as well

 - [Application Request](#application-request)


Application is hearth of framework, its used to boot everything up, which means to prepare database connections, to register all routes and to handle request with corresponding route handler.
It has three public methods **boot**, **getAttribute** and **setAttribute**

---

**Application::boot(\Closure)**
Used to load configuration, connect to database, and after success connection execute callback method that provides instance of `\RequestHandler\Modules\Router\IRouter` used to register routes.

Example:
```
<?php
use \RequestHandler\Modules\Application\IApplication;
use \RequestHandler\Modules\Router\IRouter;

$app = ObjectFactory::create(IApplication::class, "/path/to/config.json");

$app->boot(function (IRouter $router) {
	/*
		This will get triggered after configuration file is loaded and
		connection to database was established
	*/
	$router
		->get('/user/:user_id', \Controllers\User\GetInfo::class)
		->patch('/user/:user_id', \Controllers\User\UpdateInfo::class);
});
```
---

##### **setAttribute(string: name, mixed: value): IApplication**
Sets attribute that is shared through request entire flow (middlewares, filters, validators etc...) and can be accessed using [**getAttribute**](#getattributestring-name-mixed-default-null)

Example:
```
$app->setAttribute('server-region', 'eu');
```

---

##### **getAttribute(string: name[, mixed: default = null])**
Retrieve value that was set through [**setAttribute**](#setattributestring-name-mixed-value-iapplication) or return `$default` if attribute is not set.

Example:
```
$reqion = $app->getAttribute('server-region', 'us');
```

---

#### Application Request

 - [Request Handler](#request-handler-aka-ihandle)
 - [Request Middleware](#request-middleware-aka-imiddleware)
 - [Request Validator](#request-validator-aka-ivalidate)
 - [Request Filter](#request-filter-aka-ifilter)

##### **Request Handler a.k.a IHandle**
Interface `\RequestHandler\Modules\Application\ApplicationRequest\IHandle` is used to tell application that given class is able to handle single request, if class that is provided to router does not implement this interface exception `ApplicationException` is thrown. As framework is written using PHP 7.1+ syntax required return type of `handle` method is `IResponse`.

Example:
```
namespace Controllers\User;

use \RequestHandler\Modules\Application\ApplicationRequest\IHandle;

class GetInfo implements IHandle
{
	
	public function handle(IRequest $request, IResponse $response): IResponse
	{
		$user = new User();
		// Do some logic			
		// Return response
		return $response->data(['user' => $user]);
	}
}
```
---

##### **Request Middleware a.k.a IMiddleware**
Interface `\RequestHandler\Modules\Application\ApplicationRequest\IMiddleware` is used to tell application that given request handler also has some middlewares that needs to be executed, after or before request handle method is executed. If any of middlewares failes, request is finished with error message what failed and all custom response errors.
Middleware method gives access to `IMiddlewareContainer` that is used to register and execute request middlewares from application.
Request middlewares must implement `RequestHandler\Modules\Middleware\IMiddlewareHandler` in order for application to execute them.

Example:
```

// app/middlewares/Authenticate.php

use RequestHandler\Modules\Middleware\IMiddlewareContainer;
use RequestHandler\Modules\Middleware\IMiddlewareHandler;
use RequestHandler\Modules\Request\IRequest;
use RequestHandler\Modules\Response\IResponse;

class CustomMiddleware implements IMiddlewareHandler
{
	
	public function handle(IRequest $request, IResponse $response, IMiddlewareContainer $middleware): void
	{
		// Some Logic
		if ($allGood) {
		
			$middleware->next();
		}
		// Can put some logic here as well
	
	}
}

// app/controllers/user/GetInfo.php

namespace Controllers\User;

use \RequestHandler\Modules\Application\ApplicationRequest\IHandle;
use \RequestHandler\Modules\Application\ApplicationRequest\IMiddleware;

class GetInfo implements IHandle, IMiddleware
{
	
	public function handle(IRequest $request, IResponse $response): IResponse
	{
		$user = new User();
		// Do some logic			
		// Return response
		return $response->data(['user' => $user]);
	}
	
	public function middleware(IMiddlewareContainer $middleware): IMiddlewareContainer
	{

		return $middleware->add(new CustomMiddleware());
	}
}
```
---
##### **Request Validator a.k.a IValidate**
Interface `\RequestHandler\Modules\Application\ApplicationRequest\IValidate` is used to tell application that given request handler also contains request input validation, if validation failed response with errors will be returned and request handler method  won't get executed

Example:
```
namespace Controllers\User;

use \RequestHandler\Modules\Application\ApplicationRequest\IHandle;
use \RequestHandler\Modules\Application\ApplicationRequest\IValidate;

class GetInfo implements IHandle, IValidate
{
	
	public function handle(IRequest $request, IResponse $response): IResponse
	{
		$user = new User();
		// Do some logic			
		// Return response
		return $response->data(['user' => $user]);
	}
	
	public function validate(IInputValidator $validator): IInputValidator
	{
		
		return $validator->validate([
			'field_name' => 'rule|another_rule:param,another_param'
		]);
	}
}
```
---
##### **Request Filter a.k.a IFilter**
Interface `\RequestHandler\Modules\Application\ApplicationRequest\IFilter` is used to tell application that we want some of request input data filtered when fetching them.
In following example we are telling script that when accessing `user` and `age` input data (query, form body etc) we want those values converted (filtered) into different types, we want `user` to be matched with user id in database and retrieved as user model, and `age ` to be retrieved as integer.

Example:
```
namespace Controllers\User;

use \RequestHandler\Modules\Application\ApplicationRequest\IHandle;
use \RequestHandler\Modules\Application\ApplicationRequest\IFilter;

use \App\Models\User;

class GetInfo implements IHandle
{
	
	public function handle(IRequest $request, IResponse $response): IResponse
	{
		
		// We are sure this value is instance of "User" model
		$user = $request->get('user');
		
		// We are sure this value has type integer
		$age = $request->get('age');

		return $response->data(['user' => $user]);
	}

	public function filter(IRequestFilter $filter): IRequestFilter
	{
	
		return $filter
				->add('user', new ModelFilter(User::class, 'id'))
				->add('age', new IntFilter());
	}
}
```

---