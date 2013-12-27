# Classes
---

## eos.class.php

### Equation Operating System

This class makes it incredibly easy to use and parse/solve equations in
your own applications. It includes a graph generator to turn an equation
with a variable in to a `y=x` graph, with the capability to calculate
the upper and lower `y-bounds`.  __NOTE__ NONE of the functions within
these two classes are static, any example that looks like a static function
call is representational of the class being used, but should be initialized
and assigned to a variable first.  It is also important to note that these
classes throw exceptions if running in to errors, please read the beginning
of the `eos.class.php` file for the defines of the exceptions thrown. Exceptions
includes a descriptive message of the error encountered and within `eqEOS` will
also typically include the full equation used.

#### eqEOS

This class has one important function, `eqEOS::solveIF()` which does all the legwork,
so we'll start there and end with examples.  
To initialize this class, use:

    $eos = new eqEOS();

##### solveIF($infix, $variables)

To use this function:

    $value = $eos->solveIF($eq, $vars);

###### _$infix_

Is simply a standard equation with variable support. Variables
have two forms, one is native to PHP programmers already, prefixed with '$'.
The other way to declare a variable is with '&amp;' and is included for
backward compatibility for with the initial version from 2005.  
Example Equations:

    2(4$x)
    2(4&x)
    5+ ((1+2)*4) +3
    5+4(1+2)+3
    10*sin($x)
    10*cos($x)

The first two pairs shown are exactly the same.  The parser has good implied
multiplication, for everything but allowed functions.  Allowed functions require
an implicit operator on either/both sides to work properly, I hope to change
that in the next revision; but for now, note that it will not work as you would
expect.  
For example:

    5sin(1.5707963267) = 51
    5*sin(1.5707963267) = 5
    sin(1.5707963267)5 = 15

The reason is because there is no implied multiplication being applied, the result
of `sin(1.5707963267) = 1` is being concatenated with the number 5, giving
incredibly odd results if you are not expecting it.

###### _$variables_

The variables are fairly simple to understand.  If it contains a scalar (ie
a non-array value) _every_ variable within the equation will be replaced with
that number.  If it contains an array, there will be a by-variable replacement -
note that the array MUST be in the format of `'variable' => value`  
Such as:

    array(
        'x' => 2,
        'y' => 3
    )

Given the equation:

    5$x^$y

If this is called by:

    eqEOS::solveIF('5$x^$y', 2)

It will equal '20', as every variable is replaced by 2.  However, if called like:

    eqEOS::solveIF('5$x^$y', array(
                                'x' => 2,
                                'y' => 3);

You will get the result of '40' as it would equate to '5*2^3', as expected.

#### eqGraph

This is the fun class that can create graphs.  It extends `eqEOS`.  
To initialize use:

    $graph = new eqGraph($width, $height);

The `$width` and `$height` are the values used for the image size, defaulting to
a `640x480` image size if initialized with `$graph = new eqGraph();`

##### graph($eq, $xLow, $xHigh, $xStep, [$xyGrid, $yGuess, ...])

This method will generate the graph for the equation (`$eq`) with a min and max
`x` range that it will parse through. All Variables explained:
* `$eq`
    The Standard Equation to use.  _Must_ have a variable in it. (ie `$x`)
* `$xLow`
    The starting point for the calculations - the left side of the graph.
* `$xHigh`
    The last point calculated for the variable - the right side of the graph.
* `$xStep`
    Stepping point for the variable.  Suggested not to use a value less than
    `.01`.  This is the precision of the graph.
* `$xyGrid = false`
    Show `x/y` gridlines on the graph.  Defaults to false.  Each grid line
    is set at every integer (ie `1,2,3,...100`). If working with small ranges,
    it is suggested to turn this on.
* `$yGuess = true`
    Guess the Lower and Upper `y-bounds` (The bottom and top of the image
    respectively.)  This will set the the bounds to the lowest `y` value
    encountered for the `$yLow`, and the largest `y` value for `$yHight`.
* `$yLow = false`
    Lower bound for `y`, will be reset if a lower value for `y` is found.
* `$yHigh = false`
    Upper bound for `y`, will be reset if a larger `y` value is found.

TODO:
* Add `x` and `y` labels
* Smart `grid spacing` calculations so can be effective with large ranges.
* Smart (default) `$xStep` calcuations based on image size and ranges.

To set up a graph with a `21x21` window (ie `-10 to 10`) for the equation
`sin($x)` and output as PNG, would use as:

    $graph->graph('sin($x)', -10, 10, 0.01, true, false, -10, 10);
    $graph->outPNG();

It would look like:  
![Sin(x)](http://img825.imageshack.us/img825/1380/sinx21x21.png)
---