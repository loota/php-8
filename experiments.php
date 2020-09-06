<?php
// Error enforcements

echo UNDEFINED_CONSTANT; // error, which is nice!

class Bah {
    public function notAStaticMethod() {
    }
}
$bah = (new Bah)->notAStaticMethod();
Bah::notAStaticMethod(); // error, which is nice!

$nothing->notExisting = 'foo'; // error, nice!

// and many more notices and warnings have been promoted to errors, some notices to warnings.

// Union types
function foo(null|array|int $bar): object|array {
    return [];
}
foo(null);
foo(10);
foo([]);
foo(false);
foo('not a number'); // TypeError

// Mixed type
function quux(mixed $almostAnything) {
}
quux(1);
quux("stringula");
quux([]);
quux(new Exception);
quux(INF);

// Stringable type
function blah(Stringable $string) {
    return $string;
}
class someClass {
    public function __toString() {
        return 'some class';
    }
}
$someClass = new someClass;
blah($someClass);
blah('foobar'); // error, stringable means something which has __toString. A normal string does not

// Static type
class Babar {
  public function returnStatic(): static {
    return new static();
  }
  public function returnSelf(): static {
    return new self();
  }
  public function returnBabar(): static {
    return new Babar();
  }
  public function returnObject(): static {
    return new StdClass; // TypeError
  }
}
$babar = new Babar;
$babar->returnStatic();
$babar->returnSelf();
$babar->returnBabar();
$babar->returnObject();

// String functions
var_dump(str_contains('Viimeinkin!', 'e'));
var_dump(str_contains('Viimeinkin!', 'x'));
var_dump(str_starts_with('Alkaako', 'Alk'));
var_dump(str_starts_with('Alkaako', 'Ei ala'));
var_dump(str_ends_with('Loppuuko', 'uuko'));
var_dump(str_ends_with('Loppuuko', 'Ei lopu'));

// Named arguments
function callMeByAnyName($first, $second) {
    return $first . ' ' . $second;
}
echo callMeByAnyName(second: 'toka', first: 'eka') . PHP_EOL;
echo callMeByAnyName(first: 'eka', 'toka') . PHP_EOL; // error, can not mix named and positional args

// Nullsafe (optional chaining)
$someFunction = fn() => new SplStack;
var_dump($someFunction()?->count()); // 0
$returnsNull = fn() => null;
var_dump($returnsNull()?->valid()); // null
$returnsFalse = fn() => false;
var_dump($returnsFalse()?->valid()); // Error. Nullsafe is not safe with other than nulls

// Match expressions
function checkMatch($somethingWeAreChecking): string|array {
    $matched = match($somethingWeAreChecking) {
        10 => 'ten',
        '10' => 'ten as a string',
        [1,2] => ['one', 'two'],
        'null as a string' => null,
        default => 'nothing matched', // this can be in the middle
        'a', 'b' => 'either a or b',
        false => 'boolean', // note that there can be a trailing comma here
    } ?? 'match returned null';
    return $matched;
}
var_dump(checkMatch(10));
var_dump(checkMatch('10'));
var_dump(checkMatch([1,2]));
var_dump(checkMatch('null as a string'));
var_dump(checkMatch(false));
var_dump(checkMatch('a'));
var_dump(checkMatch('b'));
var_dump(checkMatch(new StdClass));

// Constructor property promotion
class Barbapapa {
    public function __construct(public string $color) {}
}
$barbapapa = new Barbapapa('pink');
var_dump($barbapapa->color);

// Throw expressions and non-capturing catches
try {
    $exception = throw new Exception('This is thrown as an expression which is not possible in PHP < 8');
} catch (Exception) {
    echo 'Not required to capture a variable anymore';
}

// WeakMap
class StrongKey {
    public function __toString() {
        return 'strongKey';
    }
}
$strongMap = [];
$strongKey = new StrongKey; // We need a class since object can't be an array without __toString
$strongMap[(string) $strongKey] = 2;
var_dump($strongMap);
unset($strongKey);
var_dump($strongMap); // reference exists
$map = new WeakMap;
$key = new StrongKey;
$map[$key] = 1;
var_dump($map);
unset($key);
var_dump($map); // no more reference


// "attributes" which are really annotations
// OCI8 aliased function changes, OCI-Lob -> OCILob OCI-Collection -> OCICollection
// LC_CTYPE is no longer inherited from env
// DOM has some changes
// changes to zip, curl, date and numerous other things
