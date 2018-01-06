# Heroku MCGG Buildpack

This is a [Heroku Buildpack](https://devcenter.heroku.com/articles/buildpacks)
for running a Minecraft server in a [dyno](https://devcenter.heroku.com/articles/dynos).

[![Deploy to Heroku](https://www.herokucdn.com/deploy/button.png)](https://heroku.com/deploy)

## Usage

Create a [free ngrok account](https://ngrok.com/) and copy your Auth token.
Create a [free dropbox account](https://dropbox.com/) and copy your Dev App token

### Press the Button and WHOOsh! Your Server is on the GO!

## Warning
If you run both server and website, they will cost quite a lost of free dyno hours

Recommended to run both dynos only 16 hours a day (if account have visa)

Also, Free dyno have max RAM of 1024MB (include swap), if you exceed over 512MB, it will send you an E14 error (Mem quota exceeded)

If you reach the limit 1024MB, Error E15 will occur and reset all dynos, so.. be ware
