EASTOR
=======

[![Facelift](https://f.cloud.github.com/assets/1009003/515405/f1003c6a-be74-11e2-84b9-14776c652afb.png)](http://strut.io)

#### Une interface WYSIWYG pour Authoring ImpressJS, Bespoke.js et EAST ####

Savez vous ce que c'est que EAST?  Une démo d'une presentation EAST : http://bartaz.github.com/impress.js/#/bored

### Utiliser EASTOR http://eastor.fr/editor/
(Fonctionne sous Firefox, Chrome et Safari)

### Site officiel: http://eastor.fr ###

#### Connaitre EASTOR
* http://www.youtube.com/watch?v=TTpiDXEIulg
* previous video: http://www.youtube.com/watch?v=zA5s8wwme44


### Mail ###
z.benmss@gmail.com

### Construire son EASTOR - Créateur de présentation EAST ###
Afin de construire votre propre EASTOR, vous avez besoin de GRUNT =< 0.4.4


1. Installer la dernière version de GRUNT : `npm install -g grunt-cli`
2. Cloner le git: `git clone git://github.com/ziyad77/EASTOR.git`
3. `cd Strut`
4. Intaller les dépendance de EASTOR: `npm install`
5. Lancer EASTOR: `grunt server` (le serveur sera lancé sous localhost:9000)

Afin de mettre en production votre EASTOR lancer `grunt build`.
Votre EASTOR sera situé sous `Strut/dist`.

### RELEASE NOTES ###

v0.5.3 - Positioning and transformations of components in edit mode
now exactly match the positioning and transformations of components in the final presentation.

### Contributing ###


`Strut` is composed of several bundles which provide distinct features to `Strut`.  The set of bundles that compose
`Strut` are defined in https://github.com/tantaman/Strut/blob/master/app/scripts/features.js

This design allows features to be added and removed from `Strut` just by adding or removing bundles from the list
 in features.js.  E.g., if you wanted a build of Strut without `RemoteStorage` you can just remove
the `RemoteStorage` bundle from features.js.  If you didn't want any slide components for some reason then you can remove
`strut/slide_components/main` from features.js.

Bundles contribute functionality to `Strut` by registering that functionality with the `ServiceRegistry`.
You can take a look at the `RemoteStorage` bundle for an example: https://github.com/tantaman/Strut/blob/master/app/bundles/common/tantaman.web.remote_storage/main.js

If a service is missing `Strut` continues to run without the functionality provided by that service.

New development that isn't essential to the core of Strut should follow this pattern in order to keep the code 
modular and allow the removal of features that don't pan out or can only exist in specific environments.  For example,
`RemoteStorage` can't be loaded if `Strut` is being served from a `file://` url.

The `ServiceRegistry` also allows for runtime modularity.  I.e., services can be added and removed at runtime and `Strut`
will update to reflect the current set of features & services that are isntalled.  Try to keep in mind the
fact that services won't necessarily be present or if they are present they might go away.  This 
approach allows the user to add and remove plugins from 3rd parties at runtime.

### Acknowledgements ###

* ImpressJS https://github.com/bartaz/impress.js/
* Bespoke.js https://github.com/markdalgleish/bespoke.js
* BackboneJS http://documentcloud.github.com/backbone/
* Spectrum https://github.com/bgrins/spectrum
* Etch http://etchjs.com/
* Bootstrap http://twitter.github.io/bootstrap/
* lodash http://lodash.com/
* mousetrap http://craig.is/killing/mice
* RequireJS http://requirejs.org/
* JQuery http://jquery.com/
* HandlebarsJS http://handlebarsjs.com/
* Grunt http://gruntjs.com/
