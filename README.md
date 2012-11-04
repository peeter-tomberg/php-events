PHP-Events is a php events system for PHP 5.4

It's based on traits, which means every class can implement the event system as easily as:

<pre>
class MyClass {
    use Events;
}
</pre>

Binding and triggering events
-----------------------------

<pre>
$class = new MyClass();
$class->bind("boom", function() {
    echo "headshot";
});
$class->trigger("boom");
</pre>

You can also pass in arguments:

<pre>
$class = new MyClass();
$class->bind("hello", function($name) {
    echo "hello " . $name;
});
$class->trigger("hello", "Peeter");
</pre>

This also supports priority and stopping event propagation. By default, all events are triggered in the order they are bound. 
You can change that by assiging a priority to an event, and returning false to stop propagation.

<pre>
$class = new MyClass();
$class->bind("hello", function($name) {
    echo "hello " . $name;
});
$class->bind("hello", function($name) {
    echo "hello my dear sir " . $name;
    return false;
    
}, 2);
$class->trigger("hello", "Peeter");
</pre>

Would produce

<pre>
hello my dear sir Peeter