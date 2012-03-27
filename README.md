PHP-Events is a php events system for PHP 5.4

It's based on traits, which means every class can implement the event system as easily as:

<pre>
class MyClass {
    use Events;
}
</pre>

Binding and triggering events is equally simple:

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