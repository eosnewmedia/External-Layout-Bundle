External-Layout-Bundle
======================
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/b6266ec0-d13c-4558-8c74-772f97c18da6/mini.png)](https://insight.sensiolabs.com/projects/b6266ec0-d13c-4558-8c74-772f97c18da6)

This bundle loads html layouts from remote, convert the layouts to twig templates and store it for local usage.

## Installation

```sh
    composer require enm/external-layout-bundle
```

in your `app/AppKernel.php`:

```php
 public function registerBundles()
    {
        $bundles = [
            // ...
            new Enm\Bundle\ExternalLayoutBundle\EnmExternalLayoutBundle(),
            //...
        ];
        //...
    }
```

## Configuration
The bundle have to be configured via the global `config.yml` like this:

```yml 
enm_external_layout:
    layouts:
        # example config for template ":remote:your_layout_name.html.twig"
        your_layout_name:
            destination: '%kernel.root_dir%/Resources/views/remote' # location where your template will be created
            source:
                scheme: "http" # scheme can be "http" or "https". "http" is the default
                host: "www.your-domain.de"
                path: "/" # path have to begin with "/", "/" is also the default
                user: "username"  # Optional parameter for http basic auth, can not be empty if defined  
                password: "password" # Optional parameter for http basic auth 
            blocks:
                prepand:
                    headline: "body" # add block "body" as first child of html element "headline"
                append:
                    stylesheets: "head" # add block "stylesheets" as last child of html element "head"
                    javascripts: "body" # add block "javascripts" as last child of html element "body"
                replace:
                    title: "%title%" # replace string "%title%" with block "title"
                    content: "$$content$$" # replace string "$$content$$" with block "content"
```

### Example (from config above):
Source:

```html
<html>
    <head>
        <title>%title%</title>
    </head>
    <body>
        <main>%content%</main>
    </body>
</html>
```

Generated Twig-Template:

```html
<html>
    <head>
        <title>{% block title %}{% endblock %}</title>
        {% block stylesheets %}{% endblock %}
    </head>
    <body>
        {% block headline %}{% endblock %}
        <main>{% block content %}{% endblock %}</main>
        {% block javascripts %}{% endblock %}
    </body>
</html>
```

### Layouts:
The layout section can hold any number of layout configuration.

Each layout has its own configuration under a name which will be used as template name.

#### Destination
The destination have to be an absolute path where the bundle should create your layout files.

For example: 

```yml
enm_external_layout:
    layouts:
        layout:
            destination: '%kernel.root_dir%/Resources/views/remote'
        #...
```

will store your template in `app/Resources/views/remote/layout.html.twig` which will become the template `:remote:layout.html.twig` in symfony.

#### Source
The source section configures where to find and load the layout from.

The `scheme` can be `http` or `https`. If no scheme is given, `http` will be used as default.

The `host` is the domain (without a slash at the end ). This a required parameter.

The `path` have to start with a slash. If no path is given, `/` will be used as default.

#### Blocks
The block section configures which twig blocks should be added to the template.

There are three possibilities for adding a block. Each possibility holds an array with key value pairs.

The key later will be the block name in the template.

1. `prepand`: Here you can use a "css-selector" to define a single element where the named block should be added as first child.
1. `append`: Here you can use a "css-selector" to define a single element where the named block should be added as last child.
1. `replace`: Here you can use any string, which will be replaced by the bundle with the named block.

## Commands
### enm:external-layout:create
This command get the configs and create a twig template file for each configured layout.

```sh
# Create all templates
bin/console enm:external-layout:create

# Create the template "your_layout.html.twig"
bin/console enm:external-layout:create --layout=your_layout
```

## Extending the Bundle
The Bundle provide some ways to extend or change the default implementation.

### Source Loader
Source loaders define how to get the html. The default source loader uses "Guzzle" to call the given url via http "GET" method.
If you need a different loading process, for example with custom authentication, simply implement `SourceLoaderInterface` and register 
your source loader via the service container for a configured layout:

```yml
# services.yml
services:
    app.source_loader:
        class: AppBundle\SourceLoader\YourSourceLoader
        tags:
            - { name: "external_layout.source_loader", layout: "your_layout" }
```

### Block Builder
Block builders define how to replace, prepend or append twig blocks in the given html. If you need a custom way, simply
implement `BlockBuilderInterface` and register your block builder via service container for a configured layout:

```yml
# services.yml
services:
    app.block_builder:
        class: AppBundle\BlockBuilder\YourBlockBuilder
        tags:
            - { name: "external_layout.block_builder", layout: "your_layout" }
```

### Events
There are currently two events which can be used to manipulate html while processing:
1. `Events::HTML_LOADED` (`enm.external_layout.html_loaded`): Triggered after html was loaded from source. Gives you an `HtmlEvent`.
1. `Events::HTML_MANIPULATED` (`enm.external_layout.html_manipulated`): Triggered after html was manipulated by block builder. Gives you an `HtmlEvent`.

