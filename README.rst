AlexMultisiteBundle - Branding et Internationalization
======================================================

This bundle allows you to manage multiple brandings and multiple locales in a Symfony2 application.

**Requirements**:

* FrameworkExtraBundle
* TwigBundle

Features
--------

* Multiple routes for each site
* *UPCOMING* templates per site
* *UPCOMING* configuration per site

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
            branding_A:
                en_GB: { host: branding-a.com }
                fr_FR: { host: branding-a.com, prefix: /fr }
            branding_B:
                en_GB: { host: branding-b.com }

In this section, you must configure your brandings and locales.

Declare your routes
-------------------

You can configure a multisite route in two ways:

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

Access from template
--------------------

If you're in a template and need to access current branding or locale,
use the global variable **site_context**, which returns a
``Alex\MultisiteBundle\Branding\SiteContext`` instance:

.. code-block:: html+jinja

    You are currently on {{ site_context.currentBrandingName }}
    Your locale is {{ site_context.currentLocale }}
