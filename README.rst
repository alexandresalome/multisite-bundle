AlexMultisiteBundle - Branding and Internationalization
=======================================================

`View slides <slides.pdf>`_

* `Tests status <https://travis-ci.org/alexandresalome/multisite-bundle>`_ |test_status|

.. |test_status| image:: https://travis-ci.org/alexandresalome/multisite-bundle.png
   :alt: Build status
   :target: https://travis-ci.org/alexandresalome/multisite-bundle



This bundle allows you to manage multiple brandings and multiple locales in a Symfony2 application.

**Requirements**:

* FrameworkExtraBundle
* TwigBundle

Features
--------

* Multiple routes for each site
* Configuration per site
* Templates per site

Installation
------------

Add to your *composer.json*:

.. code-block:: json

    {
        "require": {
            "alexandresalome/multisite-bundle": "~0.1"
        }
    }

Add the bundle to your kernel:

.. code-block:: php

    # app/AppKernel.php

    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            $bundles = array(
                # ...
                new Alex\MultisiteBundle\AlexMultisiteBundle(),
            );
        }
    }

Configuration
-------------

Add this section to your *config.yml* file:

.. code-block:: yaml

    alex_multisite:
        default_branding: branding_A
        default_locale:   fr_FR
        brandings:
            _defaults:
                register: true
            branding_A:
                en_GB: { host: branding-a.com }
                fr_FR: { host: branding-a.com, prefix: /fr }
            branding_B:
                _defaults:
                    register: false
                en_GB: { host: branding-b.com }
                de_DE: { host: branding-b.de, register: false }

In this section, you must configure your brandings and locales.

You can also add extra options, like the **register** option here.

Declare your routes
-------------------

In your controllers, substitute

.. code-block:: php

   use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

with

.. code-block:: php

   use Alex\MultisiteBundle\Annotation\Route;

You can then configure a multisite route in two ways:

.. code-block:: php

    /**
     * @Route(name="login", paths={
     *   "fr_FR"="/connexion",
     *   "en_GB"="/login"
     * })
     */
    public function loginAction()
    # ...

The path will be the same for all brandings, but will be localized. If you
want a different path for same locale in different sites:

.. code-block:: php

    /**
     * @Route(name="login", paths={
     *   "branding_A"={
     *     "fr_FR"="/connexion-on-A",
     *     "en_GB"="/login-on-A",
     *   },
     *   "branding_B"={
     *     "en_GB"="/login-on-B",
     *   },
     * })
     */
    public function loginAction()
    # ...

Override templates
------------------

If you want to change a template for a specific site, create a similarly named file with branding/locale option in it:

Given your default template is ``AcmeDemoBundle::contact.html.twig``.

You can override it with branding, locale, or both:

- ``AcmeDemoBundle::_branding_locale/contact.html.twig``
- ``AcmeDemoBundle::_branding_/contact.html.twig``
- ``AcmeDemoBundle::__locale/contact.html.twig``

Just create the file and it will automatically be loaded in place of the previous one.

Read the site context
---------------------

**From templates**, use the global variable **site_context**, which returns a ``Alex\MultisiteBundle\Branding\SiteContext`` instance:

.. code-block:: html+jinja

    You are currently on {{ site_context.currentBrandingName }}
    Your locale is {{ site_context.currentLocale }}

You can also read options from config with:

.. code-block:: html+jinja

    The option register is {{ site_context.option('register') ? 'enabled': 'not enabled' }}

**In your controllers**, use service *site_context*:

.. code-block:: php

    public function indexAction()
    {
        $this->get('site_context')->getCurrentLocale();
        $this->get('site_context')->getOption('register');
    }
