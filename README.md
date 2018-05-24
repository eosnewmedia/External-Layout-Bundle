External-Layout-Bundle
======================
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/b6266ec0-d13c-4558-8c74-772f97c18da6/mini.png)](https://insight.sensiolabs.com/projects/b6266ec0-d13c-4558-8c74-772f97c18da6)

This bundle integrates [enm/external-layout](https://github.com/eosnewmedia/External-Layout) into your symfony project.

## Installation

```sh
    composer require enm/external-layout-bundle e-moe/guzzle6-bundle
```

## YAML Confiugration
The YAML configuration ("layouts") equal the array structure from [enm/external-layout](https://github.com/eosnewmedia/External-Layout).

```yaml
enm_external_layout:
    useGuzzle: true # default: false; requires a service "GuzzleHttp\ClientInterface" (e.g. e-moe/guzzle6-bundle)
    layouts:
      test:
          source: 'http://example.com'
          destination: '%kernel.project_dir%/templates/test.html.twig'
          blocks:
              prepend:
                  headline: 'body'
              append:
                  stylesheets: 'head'
              replace:
                  title: '$title$'
```

## Commands
### enm:external-layout:create
This command get the configs and create a twig template file for each configured layout.

```sh
# Create all templates
bin/console enm:external-layout:create

# Create the template "test.html.twig"
bin/console enm:external-layout:create --layout=test
```
