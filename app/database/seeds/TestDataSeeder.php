<?php

use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        $mark = User::where('email', 'like', 'mwwardle@gmail.com')->firstOrFail();

        $wikiCat = Category::create(array(
            'name' => 'The Wiki',
            Category::MODIFIED_BY => $mark->id,
            Category::CREATED_BY => $mark->id,
        ));

        $content = <<<'EOD'
== Introduction ==

The markup for this wiki follows closely with the [[http://www.wikicreole.com|Creole]] standard, though it borrows from other sources where it needs to.

== Structure ==

Wiki markup is broken up into following block groups

==== Paragraphs ====

A paragraph begins after the end of a different block group.  Different paragraphs are separated by an empty line.

>>> callout
###
This is a paragraph.

This is another paragraph.
###
>>>

The above will be converted to:

>>> callout
&&&
This is a paragraph.

This is another paragraph.
&&&
>>>

Another example would be:

>>> callout
###
```
This is a code block
```
This is a paragraph.
###
>>>

Because the code block ends when it finds a {{{```}}} at the beginning of a line, the next line is read as a paragraph, even though there is no line break between them.

>>> callout
&&&
```
This is a code block.
```
This is a paragraph.
&&&
>>>

A single line break is not sufficient to separate paragraphs.  Instead, the lines will be concatenated into a single paragraph, separated by a space.


>>> callout
###
This is a paragraph.
This is the same paragraph.
###
>>>

The result:

>>> callout
&&&
This is a paragraph.
This is the same paragraph.
&&&
>>>

----

==== Lists ====

Unordered and Ordered lists can be nested. An unordered list is denoted by an asterisks(`*`) and an ordered list is denoted by a hash(`#`). Each must be followed by a space before there content.  A list ends when a blank line is found.

>>> callout
###
* Hellos
*# George
*# Mason
* Goodbyes
*# Henry
*# Jills
*#* Smith
*#* Ford
* Test
###
>>>

>>> callout
* Hellos
*# George
*# Mason
* Goodbyes
*# Henry
*# Jills
*#* Smith
*#* Ford
* Test
>>>

----

==== Tables ====

A table is created when a line begins with a pipe (`|`) character. Each row is separated by a newline and must also begin with a pipe.  A header cell is denoted by an equals sign (`=`) immediately following the pipe.  Each cell begins with a pipe character. A pipe character at the end of a row is optional and will not be rendered if present.

>>> callout
###
|= Header1 |= Header2 |
| Content1 | Content2 |
###
>>>

Renders as:

>>> callout
|= Header1 |= Header2 |
| Content1 | Content2 |
>>>

----

==== Headlines ====

A headline is specified by a line beginning with one or more `=` signs.  Additional equals sign after the headline text is optional and will be ignored when the headline is rendered.  The number of equals signs determines the level of the heading (fewer is bigger).

>>> callout
###
= H1 =
== H2
=== H3
==== H4 ====
===== H5
====== H6 ======
###
>>>

Rendered:

>>> callout
= H1 =
== H2
=== H3
==== H4 ====
===== H5
====== H6 ======
>>>

----

==== Fenced Blocks ====

There are several fenced blocks which alter the way everything it contains is rendered.  In some cases you can modify the fenced block by specifying a parameter after the first fenced block(a space is required between the fence and the parameter)

>>> callout
###
 >>>
 This will render a panel
 >>>

 >>> callout
 This will render a callout panel (blue-tinted, like the one that contains this text)
 >>>

 >>> callout radius
 This will render a callout panel with rounded edges.
 >>>
>>>

>>>
This will render a panel
>>>

>>> callout
This will render a callout panel (blue-tinted, like the one that contains this text)
>>>

>>> callout radius
This will render a callout panel with rounded edges.
>>>

A pre block can be created with three consecutive ### signs at the beginning of a line.  No markup will be rendered within the fence.

>>> callout
###
 ###
 This a fenced block.
      **I can put** all __kinds__ of mark^^up^^ here and it {{{ won't be }}} rendered.
                   White space is preserved as well.
 ###
###
>>>

Rendered:

>>> callout
###
 This a fenced block.
      **I can put** all __kinds__ of mark^^up^^ here and it {{{ won't be }}} rendered.
                   White space is preserved as well.
###
>>>

A code block is specified by three consecutive tick characters.  You can optionally specify the language used after the first fence (Note: language based syntax highlighting is not yet implemented).

>>> callout
###
``` php
function hello($person) {
    echo 'hello' . $person;
}
```
###
>>>

Rendered:

>>> callout
``` php
function hello($person) {
    echo 'hello' . $person;
}
```
>>>

A block that begins with three curly left braces at the beginning of a line and ends with three right curly braces at the beginning of a line will be rendered as a paragraph, but with none of the markup parsed.

>>> callout
###
{{{
This a fenced block.
      **I can put** all __kinds__ of mark^^up^^ here and it {{{ won't be }}} rendered.
                   White space will not be preserved though,
because it will render as a paragraph.
}}}
###
>>>

>>> callout
{{{
This a fenced block.
      **I can put** all __kinds__ of mark^^up^^ here and it {{{ won't be }}} rendered.
                   White space will not be preserved though,
because it will render as a paragraph.
}}}
>>>

One other kind of fenced block is an escaped html block.  An escaped html block is contained within a pair of three ampersands(`&`).  The markup will be parsed, and then escaped afterwards.

>>> callout
###
&&&
The **markup** here //will// be __parsed__, but rendered as htm^^l^^.
|= This |= Is |
| A | Table |
&&&
###
>>>

The actual output on the page will be:

>>> callout
&&&
The **markup** here //will// be __parsed__, but rendered as htm^^l^^.
|= This |= Is |
| A | Table |
&&&
>>>

-----------------------------------------------------------------------------------------

==== Horizontal Rules ====

A horizontal rules is created when the a line starts with four or more dashes(`----`). Both of the following create a horizontal rule:

>>> callout
###
----
----------------------
###
>>>

---------------------------------------------------------------------------------

==== Block Quotes ====

A block quote consists of consecutive lines that begin with a right angle bracket (`>`) character.  There must be a space between the bracket and the content.

>>> callout
###
> This is a blockquote.
> Each line has a bracket.
> It is useful for quotes that should go in blocks.
> Not so much for poetry (probably want a //pre// for that one).
###
>>>

The output:

> This is a blockquote
> Each line has a bracket
> It is useful for quotes that should go in blocks.
> Not so much for poetry (probably want a //pre// for that one)

-----------------------------------------------------------------------------------

== Inline Markup ==

Inline markup can occur anywhere in the document to modify how content is rendered.  However, If the markup has an opening and closing tag, they both must be contained on the same line.

==== Styling Tags ====

|= Markup |= Rendered |
| {{{**Strong**}}} | **Strong** |
| {{{//Emphasis//}}} | //Emphasis// |
| {{{__Underline__}}} | __Underline__ |
| {{{Super^^script^^}}} | Super^^script^^ |
| {{{Sub,,script,,}}} | Sub,,script,, |
| {{{`Code`}}} | `Code` |
| {{{##Monospace## }}} | ##Monospace## |

----

==== Links ====

Any word in the document beginning with either ##http://## or ##https://## will be rendered as an implied link.  No markup is required.  However, if you want the link replaced with a name, you do need to use markup.  The link and the name are separated by a pipe(`|`) character.

>>> callout
###
http://themarkside.com
//
[[http://themarkside.com | My Web Site]]
###
>>>

Rendered:

>>> callout
http://themarkside.com
//
[[http://themarkside.com | My Web Site]]
>>>

An internal link can be created this way as well.  To link to another page on the wiki, simply replace the url with the slug for that page.  It's just fine to create a link to an internal page that doesn't exist yet.  A link will still be created, but it won't go anywhere (and should be highlighted in a different color to denote it doesn't exist yet, though this isn't quite implemented yet).

If we had a page with a slug of 'wooly-mammoth' we could do this.

>>> callout
###
Click on the link to read about the [[ wooly-mammoth | Wooly Mammoth ]]
###
Click on the link to read about the [[ wooly-mammoth | Wooly Mammoth ]]
>>>

---------------------------------------

==== Line Breaks ====

A manual line break can be created with two backslashes(`\\`)

>>> callout
###
Manual \\ \\ Line \\ \\ \\ Breaks \\ \\ \\ \\
###
>>>

>>> callout
Manual \\ \\ Line \\ \\ \\ Breaks \\ \\ \\ \\
>>>

----------------------------

==== Inline Escaping ====

You can escape markup inline by enclosing the content in triple curly braces

>>> callout
###
The content {{{ **in** the //curly// braces \\ will __not__ }}} be parsed.
{{{Though}}} **outside** of it, it //will//.
###
>>>

Rendered:

>>> callout
The content {{{ **in** the //curly// braces will __not__ }}} be parsed.
{{{Though}}} **outside** of it, it //will//.
>>>

You can also escape a single character with a tilde(`~`) character.  I wouldn't rely on it yet, it's still a bit unstable when it comes to nesting tags.

>>> callout
###
This will not be ~**bolded~**
###
\\
This will not be ~**bolded~**
>>>

EOD;


        $markup = Page::create(array(
            'category_id' => $wikiCat->id,
            'title' => 'Markup',
            'slug' => 'wiki-markup',
            'content' => $content,
            'mod_by_id' => $mark->id,
            'created_by_id' => $mark->id,
        ));



        $this->command->info('Test Data seeded.');
    }
}