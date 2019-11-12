<h1>You want to test Drupal-8.*</h1>
<p>Very challenging task. For Every test you need to write tons and tons of preparation code.</p>
<br/>
<h2>Helpful tolls are here!</h2>
<p>Lets start with the Kernel tests for your module.</p>
<p>First you need to create abstract class that extends KernelTestBase class from this library.</p>
<ul>
    <li>From terminal, go to root directory of your drupal installation</li>
    <li>Let say you have module "my_module". Execute the following command:<br>
        <code>php ./vendor/mpndev/d8tdd/src/generate.php make:kerneltest MyModule</code><br>
        <small>(use the name of your module in PascalCase!)</small>
    </li>
    <li>This will scaffold the abstract class for you.</li>
    <li>Every test class, that will test the kernel, must extends "MyModuleKernelTestBase".</li>
    <li>This give you the power to use factory and json-request functionality.
        <br>
        <code>
        $this->factory(Node::class)->make();
        </code>
        <br>
        <code>
        $this->jsonRequest($url, $method);
        </code>
        <br>
        <small>(below we have examples for using this tools in your testing code...)</small>
    </li>
</ul>
<hr>
<br>
<br>
<br>
<h2>Factories:</h2>
<p>Let assume we need Node instance from bundle "project". The very first thing is to define default factory for project.</p>
<p>Open MyModuleKernelTestBase.php and add the fallowing in the setUp method</p>
<code>$this->factory(Node::class)->define('project', [<br>&nbsp;&nbsp;'type' => 'project', 'title' => 'Some Project Title'<br>]);</code>
<br>
<p>When you need to make instance of project in your tests do:</p>
<code>
$project = $this->factory(Node::class)->make('project');
</code><br>
<p>$project will be fresh content type of bundle "project"</p>
<p>You can override and create fields on the fly:</p><br>
<code>
$project = $this->factory(Node::class)->make('project', [<br>
&nbsp;&nbsp;'title' => 'Some Another Title'<br>
&nbsp;&nbsp;'field_something' => 'Something'<br>
]);
</code><br>
<p>This will override default behavior of the factory for bundle "project"</p>
<p>You can also make multiple instances of "project" like this:</p>
<code>$projects = $this->factory(Node::class, 15)->make('project');</code>
<p>That will give you array of 15 projects.</p>
<p>If you use "create" instead of "make", factory will save the project in database:</p>
<code>$projects = $this->factory(Node::class)->create('project');</code>
<p>You can access "project" with closure like a third argument of "make" or "create" methods to make modifications like attaching something to reference field:</p>
<code>$projects = $this->factory(Node::class)->make('project', [], function($project_instance){<br>
&nbsp;&nbsp;$project_instance->get('field_environments')->appendItem($this->factory(Paragraph::class)->create())<br>
&nbsp;&nbsp;return $project_instance<br>
});</code><br><br>
<code>$projects = $this->factory(Node::class)->create('project', [], function($project_instance){<br>
&nbsp;&nbsp;$project_instance->get('field_environments')->appendItem($this->factory(Paragraph::class)->create())<br>
&nbsp;&nbsp;return $project_instance<br>
});</code><br><br>

<h3>IMPORTANT!!!</h3>
<p>Aways return the instance in the closure and "appendItem()" method will expect saved instance in database for attached item("environment")!</p>
<br><br><br><hr>
<h2>JsonRequest</h2>
<p>Helper, that make available json request(with application/json header) for testing endpoints.</p>
<p>The request will not go outside. Instead, will be handled from the drupal kernel, and the response can be inspected. Nice isn't it?</p>
<code>$response = $this->jsonRequest('http://localhost/some/endpint')->using('GET')->send();</code><br><br>
<code>$response = $this->jsonRequest('http://localhost/some/endpint')->using('POST')->send();</code><br><br>
<code>$response = $this->jsonRequest('http://localhost/some/endpint')->using('PUT')->send();</code><br><br>
<code>$response = $this->jsonRequest('http://localhost/some/endpint')->using('PATCH')->send();</code><br><br>
<code>$response = $this->jsonRequest('http://localhost/some/endpint')->using('DELETE')->send();</code><br><br>
<p>Attach cookie:</p>
<code>$response = $this->jsonRequest('http://localhost/some/endpint')->using('POST')->withCookie($some_cookie)->send();</code><br><br>
<p>Attach content:</p>
<code>$response = $this->jsonRequest('http://localhost/some/endpint')->using('POST')->withContent($some_content)->send();</code><br><br>
<p>Attach server:</p>
<code>$response = $this->jsonRequest('http://localhost/some/endpint')->using('POST')->withServer($some_server)->send();</code><br><br>
<p>Attach files:</p>
<code>$response = $this->jsonRequest('http://localhost/some/endpint')->using('POST')->withFiles($some_files)->send();</code><br><br>
<hr>