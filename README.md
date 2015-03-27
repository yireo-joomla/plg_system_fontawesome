FontAwesome for Joomla
===============
This project offers a Joomla! plugin to add FontAwesome icons. 

First of all, the plugin simply loads the FontAwesome library remotely.

Second, any FontAwesome tag in your content will be translated into an icon:

    {fa fa-camera-retro fa-2x}
    {fa fa-spinner fa-spin}
    {fa fa-circle-o-notch fa-spin}

The arguments after `{fa ...}` can include the `fa-` prefix, or the prefix can be skipped.

    {fa fa-book}
    {fa book}

Stacking is done by adding brackets:

    {fa fa-stack fa-lg [fa-square-o fa-stack-2x][fa-twitter fa-stack-1x]}

To make it easier to use the same sequence multiple times, you can add the following to the plugins alias box:

    stuff="fa-stack fa-lg [fa-square-o fa-stack-2x][fa-twitter fa-stack-1x]"

Or shorter:

    stuff="stack lg [square-o stack-2x][twitter stack-1x]"

And now the following will do as well:

    {fa stuff}

## Benefits
* Short tags require less typing
* Tags are visible any WYSIWYG editor

