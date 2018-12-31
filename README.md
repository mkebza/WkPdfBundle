Installation
============

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```console
$ composer require mkebza/wk-pdf-bundle
```

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require mkebza/wk-pdf-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new MKebza\WkPDF\MKebzaWkPdfBundle(),
        );

        // ...
    }

    // ...
}
```

How to to use
=============

Generate new PDF document from file and send it as response. Controller function:

```php
public function myAction(PDFRenderer $pdf): Response
{
    $html = $this->renderView('my template');
    // or you can use fromFile(function)
    return $pdf->fromHtml($html, 'a4')->getResponse();
}
```

As profile can be profile name, array of options (new anonymous profile will be created), PDFRenderingProfile object or null (default profile is used).


Configuration
-------------

```yaml

m_kebza_wk_pdf:
    bin: /usr/local/bin/wkhtmltopdf # Path to your wkhtmltopdf bin, default
    tmp: /var/ # where temporary files are written, this is necessary to be writable directory
    default_profile: a4 # name of default profile
    profiles: # See profiles won
    
```

Profiles
--------

There are 2 predefined profiles `a4`, `a4_borderless`. you can define your own in configuration

```yaml
m_kebza_wk_pdf:
    profiles:
        myprofile:
            - --lowquality
            - --dpi 96
            - --margin-top 7mm
            - --margin-left 1mm
            - --margin-right 1mm
            - --margin-bottom 1mm
            - --page-size 'A4'
            - --encoding 'UTF-8'
```

parameters aren't parset nor escaped, they are passed to wkhtmltopdf bin as are. So be careful. Wkhtmltopdf [documentation](https://wkhtmltopdf.org/usage/wkhtmltopdf.txt).

if you name your profile with same name as one of default one, yours will overwrite default one. 

