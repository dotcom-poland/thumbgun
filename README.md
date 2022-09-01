Thumbgun Symfony application
============================

* ["Serving resized S3 images on the fly" on Medium](https://medium.com/@dotcom.software/serving-resized-s3-images-on-the-fly-2ed98e10bf3a)
* [Support my work](https://medium.com/@dotcom.software)

A Symfony application that in response to a specific GET request:

1. retrieves the image from the configured data storage,
2. resizes the image on the fly,
3. outputs the image in the requested output format.

## Running

1. Add `thumbgun.local` to the `hosts` of your system
2. Copy `.env.dist` to `.env` and modify which `docker-compose.yml` files to use
3. Run `docker-compose up -d`
4. Install vendors `docker-compose exec php composer install`

## Stopping

`docker-compose down`

### Are you working in Linux? Fix permission issues

1. Copy `docker-compose.custom.yml.dist` to `docker-compose.custom.yml`
2. Change `USER_UID` and `USER_GID` to your user's id and group id
3. Include this custom docker-compose file in the `COMPOSE_FILE` of the `.env` file 

## Xdebug

To enable:

1. Make sure `docker-compose.xdebug.yml` is included in your `.env`
2. Add server in PHPStorm named `xdebug` and map the `app/` directory to the `/var/www/html`
3. Use xdebug extension in the browser to start the session. To run a command with debugger 
   enabled, use the xdebug wrapper in the container, eg. `xdebug bin/console some:command`

To disable:

1. Make sure `docker-compose.xdebug.yml` is not included in your `.env`

## Configuring AWS S3 access

1. Create a policy in the AWS IAM console to grant accessto the 
   to the S3 bucket containing your images
2. Create a programmatic user in the AWS IAM console 
   with the policy above attached to it and store the credentials
3. Copy the `app/.env.local.dist` to `app/.env.local` and modify the values

Example IAM policy granting only read access to a bucket:

```yaml
{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Sid": "VisualEditor0",
            "Effect": "Allow",
            "Action": "s3:GetObject",
            "Resource": "arn:aws:s3:::your-bucket-name/*"
        }
    ]
}
```

## Generating image url

CLI tool is available to generate image link, eg:

`docker-compose exec php bin/console dev:url fixed 100x100 some/file/id.jpg webp`

will output:

`/t/r87mc0861cc/fixed/100x100/webp/some/file/id.jpg`
