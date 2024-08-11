Several useful functions available for IPv4 addresses. It is implemented using mostly bitwise expressions so it should be easily ported to other languages. There is very little done in the way of error checking. References are stated within comment blocks.

Introduction
------------
For a midsize project I wanted to store IP ranges in the database with
the option to to also store CIDR blocks. CIDR blocks, though powerful
are somewhat difficult for a typical user. Additional using them is not
as precise for all ip ranges. There are a plethora of [tools][iptocidr]
available on the internet that will do what I would like. However,
incorporating these tools would not be practical. What I want is to
mimic the functionality of these tools so that it can be easily
imported into any of my projects. The most coveted tool for me would be
to convert an IP range to a precise range of CIDR blocks. This required
specific functionality not naturally provided in PHP.

* Check for Valid Netmask
* Check whether an IP address is within a CIDR block.
* Take user input and a Netmask and make it into a valid CIDR block.
* CIDR number into Netmask
* Netmask to CIDR
* Take an IP range and fit it into an exact range of CIDR blocks.

This presents some difficulty in that PHP's [network functions]
[phpnetwork] are not thorough enough. The revelation came when I
realized that an IP address is merely a number. In fact the whole
protocol is rooted in binary using very specific patterns. With that in
mind I thought we could develop very light weight methods to solve our
problem.

The Code
--------
It is important to note that the methods provided are meant for IPv4 
addresses only are only tested on a 32bit system. Also, I did not care
to do much in the way of error checking, but doing so, like testing
whether the CIDR number is unsigned and less than or equal to 32,
should be trivial.

Though the solution I sought after would require PHP I didn't limit
myself to that language only. In fact the PHP code I found seemed
inefficient. Most involved a number conversions or parsing the address
using sprintf using loops and nested if statements. Indeed the most
efficient code, which shouldn't surprise most was in ANSI C. [Bit
Twiddling Hacks][bithacks] resource proved very useful.

### About Binary ###
I am not attempting to teach binary math. Since the code does not "read
like prose" a small amount of knowledge is required in order to
understand the code. Some excellent resources are Wikipedia's article
on [CIDR][wpcidr] and [PHP binary operators][phpbitwise].


[iptocidr]: http://ip2cidr.com/ "IP to CIDR tool"  
[phpnetwork]: http://www.php.net/manual/en/ref.network.php "PHP Network Functions"  
[bithacks]: http://graphics.stanford.edu/~seander/bithacks.html "Bit Twiddling Hacks by Sean Eron Anderson"  
[wpcidr]: http://en.wikipedia.org/wiki/Classless_Inter-Domain_Routing "Classless Inter-Domain Routing"  
[phpbitwise]: http://www.php.net/manual/en/language.operators.bitwise.php "PHP's Bitwise Operators"  
