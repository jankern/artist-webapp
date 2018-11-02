# artist-webapp

Webpack 4 scaffold to build an ES6 / MATERIALIZE-CSS web frontend. 

### Scripting types

Server side: 
* (Middle tier sources are not part of this package)

Client side:
* HTML5, SASS/CSS3, Javascript ES6, materialize-css (0.100.2)
* Nodejs/Babel/Webpack 4 for ES6 browser transpiling, source bundling and compressing
* Nodejs/node-sass for transpiling sass sources

## Installation

```
$ git clone git@gitlab.com:jankern/hearing-aid-webapp.git
$ cd hearing-aid-app
$ npm install
```

## Run the code

To develop and extend the sources webpack needs to be processed to compile the sources (each time files are being saved). Start the build in webpack development server
```
$ npm run build-dev
```
A browser will be opened at http://localhost:9000 to verfy code changes.
SASS sources are stored in ./src/scss/ (landingpage styles have to be added to ./src/scss/partials/_content_landingpage.scss)

To build the sources for production purposes run
```
$ npm run build
```



