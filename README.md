External-Layout-Bundle
======================
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
        # example config for template "EnmExternalLayoutBundle::your_layout_name.html.twig"
        your_layout_name:
            source:
                scheme: "http" # scheme can be "http" or "https". "http" is the default
                host: "www.your-domain.de"
                path: "/" # path have to begin with "/", "/" is also the default
                user: "[userName]"  # Optional parameter for htaccess. if set, it cant't be empty  
                password: "[password]" # Optional parameter for htaccess. it requires 'user' parameter not be empty  
            blocks:
                prepand:
                    headline: "body"
                append:
                    stylesheets: "head"
                    javascripts: "body"
                replace:
                    title: "%title%"
                    content: "%content%"
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

For example `your_layout` will become the template file `EnmExternalLayoutBundle::your_layout.html.twig`.

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

# Create the template "EnmExternalLayoutBundle::your_layout.html.twig"
bin/console enm:external-layout:create --layout=your_layout
```

## Extending the Bundle
The Bundle provide some ways to extend or change the default implementation.

### Source Loader
Source loaders define how to get the html. The default source loader uses "Guzzle" to call the given url via http "GET" method.
If you need a different loading process, for example with authentication, simply implement `SourceLoaderInterface` and register 
your source loader via service container:

```yml
# services.yml
services:
    app.source_loader:
        class: AppBundle\SourceLoader\YourSourceLoader
        tags:
            - { name: "external_layout.source_loader" }
```

### Block Builder
Block builders define how to replace, prepend or append twig blocks in the given html. If you need a custom way, simply
implement `BlockBuilderInterface` and register your block builder via service container:

```yml
# services.yml
services:
    app.block_builder:
        class: AppBundle\BlockBuilder\YourBlockBuilder
        tags:
            - { name: "external_layout.block_builder" }
```

### Events
There are currently two events which can be used to manipulate html while processing:
1. `Events::HTML_LOADED` (`enm.external_layout.html_loaded`): Triggered after html was loaded from source. Gives you an `HtmlEvent`.
1. `Events::HTML_MANIPULATED` (`enm.external_layout.html_manipulated`): Triggered after html was manipulated by block builder. Gives you an `HtmlEvent`.

