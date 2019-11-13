<h1 color="green">You want to test Drupal-8.*</h1>
<p>Now that's a very challenging task! For every test you need to write tons and tons of preparation code.</p>
<br/>
<h2>Here are some helpful tools!</h2>
<p>Lets start with the Kernel tests for your module.</p>
<p>First you need to create an abstract class that extends KernelTestBase class. KernelTestBase lives in this library.</p>
<ul>
    <li>From terminal, go to the root directory of your Drupal installation;</li>
    <li>Let's say you have module "my_module". Execute the following command:
<br>
<br>
    
```
php ./vendor/mpndev/d8tdd/src/generate.php make:kerneltest MyModule
```

<br>
        <small>(use the name of your module in PascalCase!)</small>
        <p>This will scaffold the abstract class for you.</p>
    </li>
    <li>Every test class, that will make kernel-tests, must extends "MyModuleKernelTestBase";</li>
    <li>This will give you the powers of "factory" and "jsonRequest" functionalities:
        <br>
        <br>

```php
<?php

$this->factory(Node::class)->make();

$this->jsonRequest('http://localhost/some/endpoint')
  ->using('POST')
  ->send();

```

<small>(below we have examples on how to use these tools.)</small>
    </li>
</ul>
<hr>
<br>
<br>
<br>
<h2>Factories:</h2>
<br>
<p>Let's assume we need Node instance from bundle "project". The very first thing is to define factory for default "project" implementation.</p>
<p>Open MyModuleKernelTestBase.php and add the following in the setUp method</p>
<br>

```php
<?php

$this->factory(Node::class)->define('project', [
  'type' => 'project',
  'title' => 'Some Project Title'
]);

```

<br>
<hr>
<br>
<p>If you need to make an instance of a "project" in your tests you can do so by using:</p>
<br>

```php
<?php

$project = $this->factory(Node::class)->make('project');

$project->bundle();  // will return 'project'

$project->get('title')->getString();  // will return 'Some Project Title'

```

<br>
<p>$project will be fresh content type of bundle "project"</p>
<br>
<hr>
<br>
<p>You can override and add fields on the fly:</p>
<br>

```php
<?php

$project = $this->factory(Node::class)->make('project', [
  'title' => 'Some Another Title'
  'field_something' => 'Something'
]);

$project->bundle();  // will return 'project'

$project->get('title')->getString();  // will return 'Some Another Title'

$project->get('field_something')->getString();  // will return 'Something'

```

<br>
<p>This will override the default behavior of the "factory" for bundle "project"</p>
<br>
<hr>
<br>
<p>You can also make multiple instances of "project" like this:</p>
<br>

```php
<?php

$projects = $this->factory(Node::class, 15)->make('project');

```

<br>
<p>That will give you an array of 15 "projects".</p>
<br>
<hr>
<br>
<p>If you use "create" instead of "make", factory will save the "project" in the database:</p>
<br>

```php
<?php

$this->factory(Node::class)->create('project');

```

<br>
<hr>
<br>
<p>You can "make" "project" and save it later (normal Drupal stuff):</p>
<br>

```php
<?php

$project = $this->factory(Node::class)->make('project');

//Do something...

$project->save()

```

<br>
<hr>
<br>
<p>You can access the “project” instance by using closure as a third argument in the “make” or “create” methods. By doing so you can modify the “project” instance i.e. by adding something via a reference field:</p>
<br>

```php
<?php

/**
 * make
 */
$project = $this->factory(Node::class)->make('project', [], function($project_instance){
  $project_instance->get('field_environments')->appendItem($this->factory(Paragraph::class)->create('environment'));
  return $project_instance;
});

/**
 * create
 */
$project = $this->factory(Node::class)->create('project', [], function($project_instance){
  $project_instance->get('field_environments')->appendItem($this->factory(Paragraph::class)->create('environment'));
  return $project_instance;
});

```

<br>
<h3>IMPORTANT!!!</h3>
<p>- In closure, always return the passed instance variable! (In our example <code>$project_instance</code>);</p>
<p>- <code>appendItem()</code> method will expect the saved instance to be in the database for the attached item! (In our example "environment", that was created on the fly, again with factory);</p>
<br>
<hr>
<br>
<br>
<h2>JsonRequest:</h2>
<br>
<p>Helper, that makes available json requests (with application/json header) for testing endpoints.</p>
<p>The request will not go outside. Instead, it will be handled from the Drupal kernel, and the response can be inspected. Nice isn't it?</p>
<br>

```php
<?php

$response = $this->jsonRequest('http://localhost/some/endpoint')
  ->using('GET')
  ->send();

```

<br>

```php
<?php

$response = $this->jsonRequest('http://localhost/some/endpoint')
  ->using('POST')
  ->send();

```

<br>

```php
<?php

$response = $this->jsonRequest('http://localhost/some/endpoint')
  ->using('PUT')
  ->send();

```

<br>

```php
<?php

$response = $this->jsonRequest('http://localhost/some/endpoint')
  ->using('PATCH')
  ->send();

```

<br>

```php
<?php

$response = $this->jsonRequest('http://localhost/some/endpoint')
  ->using('DELETE')
  ->send();

```

<br>
<hr>
<br>
<p>Attach cookie:</p>
<br>

```php
<?php

$response = $this->jsonRequest('http://localhost/some/endpoint')
  ->using('POST')
  ->withCookie($some_cookie)
  ->send();

```

<br>
<hr>
<br>
<p>Attach content:</p>
<br>

```php
<?php

$response = $this->jsonRequest('http://localhost/some/endpoint')
  ->using('POST')
  ->withContent($some_content)
  ->send();

```

<br>
<hr>
<br>
<p>Attach server:</p>

```php
<?php

$response = $this->jsonRequest('http://localhost/some/endpoint')
  ->using('POST')
  ->withServer($some_server)
  ->send();

```

<br>
<hr>
<br>
<p>Attach files:</p>

```php
<?php

$response = $this->jsonRequest('http://localhost/some/endpoint')
  ->using('POST')
  ->withFiles($some_files)
  ->send();

```

<br>
<hr>
<br>
<h2>Happy testing :)</h2>