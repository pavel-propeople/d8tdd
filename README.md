<h1>You want to test Drupal-8.*</h1>
<p>Very challenging task. For Every test you need to write tons and tons of preparation code.</p>
<br/>
<h2>Helpful tolls are here!</h2>
<p>Lets start with the Kernel tests for your module.</p>
<p>First you need to create abstract class that extends KernelTestBase class. KernelTestBase lives in this library.</p>
<ul>
    <li>From terminal, go to the root directory of your drupal installation;</li>
    <li>Let say you have module "my_module". Execute the following command:<br>
        <code>php ./vendor/mpndev/d8tdd/src/generate.php make:kerneltest MyModule</code><br>
        <small>(use the name of your module in PascalCase!)</small>
        <p>This will scaffold the abstract class for you.</p>
    </li>
    <li>Every test class, that will make kernel-tests, must extends "MyModuleKernelTestBase";</li>
    <li>This will give you the power of "factory" and "jsonRequest" functionality:
        <br>
        <br>

```php
<!-- <?php -->

$this->factory(Node::class)->make();

$this->jsonRequest($url)->using('POST')->send();

```

<small>(below we have examples for using this tools in your testing code...)</small>
    </li>
</ul>
<hr>
<br>
<br>
<br>
<h2>Factories:</h2>
<br>
<p>Let assume we need Node instance from bundle "project". The very first thing is to define factory for default "project" implementation.</p>
<p>Open MyModuleKernelTestBase.php and add the fallowing in the setUp method</p>
<br>

```

$this->factory(Node::class)->define('project', [
  'type' => 'project',
  'title' => 'Some Project Title'
]);

```

<br>
<hr>
<br>
<p>When you need to make instance of "project" in your tests do:</p>
<br>

```

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

```

$project = $this->factory(Node::class)->make('project', [
  'title' => 'Some Another Title'
  'field_something' => 'Something'
]);

$project->bundle();  // will return 'project'

$project->get('title')->getString();  // will return 'Some Another Title'

$project->get('field_something')->getString();  // will return 'Something'

```

<br>
<p>This will override default behavior of the factory for bundle "project"</p>
<br>
<hr>
<br>
<p>You can also make multiple instances of "project" like this:</p>
<br>

```

$projects = $this->factory(Node::class, 15)->make('project');

```

<br>
<p>That will give you array of 15 "projects".</p>
<br>
<hr>
<br>
<p>If you use "create" instead of "make", factory will save the "project" in database:</p>
<br>

```

$this->factory(Node::class)->create('project');

```

<br>
<hr>
<br>
<p>You can "make" "project" and save it later (normal Drupal staff):</p>
<br>

```

$project = $this->factory(Node::class)->make('project');

//Do some things...

$project->save()

```

<br>
<hr>
<br>
<p>You can access "project" with closure like a third argument of "make" or "create" methods to make modifications like attaching something to reference field:</p>
<br>

```

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
<p>- In closure, aways return the passed instance variable! (In our example <code>$project_instance</code>);</p>
<p>- <code>appendItem()</code> method will expect saved instance in database for attached item! (In our example "environment", that was created on the fly, again with factory);</p>
<br>
<hr>
<br>
<br>
<h2>JsonRequest:</h2>
<br>
<p>Helper, that make available json request(with application/json header) for testing endpoints.</p>
<p>The request will not go outside. Instead, will be handled from the drupal kernel, and the response can be inspected. Nice isn't it?</p>
<br>

```

$response = $this->jsonRequest('http://localhost/some/endpoint')
  ->using('GET')
  ->send();

```

<br>

```

$response = $this->jsonRequest('http://localhost/some/endpoint')
  ->using('POST')
  ->send();

```

<br>

```

$response = $this->jsonRequest('http://localhost/some/endpoint')
  ->using('PUT')
  ->send();

```

<br>

```

$response = $this->jsonRequest('http://localhost/some/endpoint')
  ->using('PATCH')
  ->send();

```

<br>

```

$response = $this->jsonRequest('http://localhost/some/endpoint')
  ->using('DELETE')
  ->send();

```

<br>
<hr>
<br>
<p>Attach cookie:</p>
<br>

```

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

```

$response = $this->jsonRequest('http://localhost/some/endpoint')
  ->using('POST')
  ->withContent($some_content)
  ->send();

```

<br>
<hr>
<br>
<p>Attach server:</p>

```

$response = $this->jsonRequest('http://localhost/some/endpoint')
  ->using('POST')
  ->withServer($some_server)
  ->send();

```

<br>
<hr>
<br>
<p>Attach files:</p>

```

$response = $this->jsonRequest('http://localhost/some/endpoint')
  ->using('POST')
  ->withFiles($some_files)
  ->send();

```

<br>
<hr>
<br>
<h2>Happy testing :)</h2>