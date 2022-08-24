thumbgun Symfony application
============================

* ["Serving resized S3 images on the fly" on Medium](https://medium.com/@dotcom.software/serving-resized-s3-images-on-the-fly-2ed98e10bf3a)
* [Support my work](https://medium.com/@dotcom.software)

## Running

1. Copy `.env.dist` to `.env` and modify which `docker-compose.yml` files to use
2. Run `docker-compose up -d`

## Xdebug

To enable:

1. Make sure `docker-compose.xdebug.yml` is included in your `.env`
2. Add server in PHPStorm named `xdebug` and map the `app/` directory to the `/var/www/html`
3. Use xdebug extension in the browser to start the session. To run a command with debugger 
   enabled, use the xdebug wrapper in the container, eg. `xdebug bin/console some:command`

To disable:

1. Make sure `docker-compose.xdebug.yml` is not included in your `.env`
