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
        <code>
        $this->factory(Node::class)->make();
        </code>
        <br>
        <code>
        $this->jsonRequest($url)->using('POST')->send();
        </code>
        <br>
        <br>
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
<code>$this->factory(Node::class)->define('project', [
<br>
&nbsp&nbsp;'type' => 'project',
<br>
&nbsp;&nbsp;'title' => 'Some Project Title'
<br>
]);</code>
<br>
<br>
<hr>
<br>
<p>When you need to make instance of "project" in your tests do:</p>
<br>
<code>
$project = $this->factory(Node::class)->make('project');
</code>
<br>
<code>
$project->bundle();
</code> // will return 'project'
<br>
<code>
$project->get('title')->getString();
</code> // will return 'Some Project Title'
<br>
<br>
<br>
<p>$project will be fresh content type of bundle "project"</p>
<br>
<hr>
<br>
<p>You can override and add fields on the fly:</p>
<br>
<code>
$project = $this->factory(Node::class)->make('project', [<br>
&nbsp;&nbsp;'title' => 'Some Another Title'<br>
&nbsp;&nbsp;'field_something' => 'Something'<br>
]);
</code>
<br>
<code>
$project->bundle();
</code> // will return 'project'
<br>
<code>
$project->get('title')->getString();
</code> // will return 'Some Another Title'
<br>
<code>
$project->get('field_something')->getString();
</code> // will return 'Something'
<br>
<br>
<p>This will override default behavior of the factory for bundle "project"</p>
<br>
<hr>
<br>
<p>You can also make multiple instances of "project" like this:</p>
<br>
<code>$projects = $this->factory(Node::class, 15)->make('project');</code>
<br>
<br>
<p>That will give you array of 15 "projects".</p>
<br>
<hr>
<br>
<p>If you use "create" instead of "make", factory will save the "project" in database:</p>
<br>
<code>$this->factory(Node::class)->create('project');</code>
<br>
<br>
<hr>
<br>
<p>You can "make" "project" and save it later (normal Drupal staff):</p>
<br>
<code>$project = $this->factory(Node::class)->make('project');</code>
<br>
<code>//Do some things...</code>
<br>
<code>$project->save()</code>
<br>
<br>
<hr>
<br>
<p>You can access "project" with closure like a third argument of "make" or "create" methods to make modifications like attaching something to reference field:</p>
<br>
<code>$project = $this->factory(Node::class)->make('project', [], function($project_instance){<br>
&nbsp;&nbsp;$project_instance->get('field_environments')->appendItem($this->factory(Paragraph::class)->create('environment'));<br>
&nbsp;&nbsp;return $project_instance;<br>
});</code><br><br>
<code>$project = $this->factory(Node::class)->create('project', [], function($project_instance){<br>
&nbsp;&nbsp;$project_instance->get('field_environments')->appendItem($this->factory(Paragraph::class)->create('environment'));<br>
&nbsp;&nbsp;return $project_instance;<br>
});</code>
<br>
<br>
<h3>IMPORTANT!!!</h3>
<p>- In closure, aways return the passed instance variable! (In our example $project_instance);</p>
<p>- "appendItem()" method will expect saved instance in database for attached item! (In our example "environment", that was created on the fly, again with factory);</p>
<br>
<hr>
<br>
<br>
<h2>JsonRequest:</h2>
<br>
<p>Helper, that make available json request(with application/json header) for testing endpoints.</p>
<p>The request will not go outside. Instead, will be handled from the drupal kernel, and the response can be inspected. Nice isn't it?</p>
<br>
<code>$response = $this->jsonRequest('http://localhost/some/endpoint')->using('GET')->send();</code>
<br>
<br>
<code>$response = $this->jsonRequest('http://localhost/some/endpoint')->using('POST')->send();</code>
<br><br>
<code>$response = $this->jsonRequest('http://localhost/some/endpoint')->using('PUT')->send();</code>
<br>
<br>
<code>$response = $this->jsonRequest('http://localhost/some/endpoint')->using('PATCH')->send();</code>
<br>
<br>
<code>$response = $this->jsonRequest('http://localhost/some/endpoint')->using('DELETE')->send();</code>
<br>
<br>
<hr>
<br>
<p>Attach cookie:</p>
<br>
<code>$response = $this->jsonRequest('http://localhost/some/endpoint')->using('POST')->withCookie($some_cookie)->send();</code>
<br>
<br>
<hr>
<br>
<p>Attach content:</p>
<code>$response = $this->jsonRequest('http://localhost/some/endpoint')->using('POST')->withContent($some_content)->send();</code>
<br>
<br>
<hr>
<br>
<p>Attach server:</p>
<code>$response = $this->jsonRequest('http://localhost/some/endpoint')->using('POST')->withServer($some_server)->send();</code>
<br>
<br>
<hr>
<br>
<p>Attach files:</p>
<code>$response = $this->jsonRequest('http://localhost/some/endpoint')->using('POST')->withFiles($some_files)->send();</code>
<br>
<br>
<hr>
<br>
<h2>Happy testing :)</h2>