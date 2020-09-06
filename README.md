Translated to English & Updated by Ren232.

<h1> Features </h1>
- You can access the file manager of the server.<br>
- Console<br>- Admin panel with account registration<br>
- Trial users & Premium users<br>
- Server Map (Dynmap required)<br>
- Player list<br>
- Version Selector<br>
- User deletion<br>
- Easy to install<br>
- And more!


<h1> Requirements </h1>
- A Heroku Account (https://dashboard.heroku.com/)
<br>- A dropbox account + API key (Goto https://www.dropbox.com/developers/apps Click on create app, Select Dropbox API, Select App folder, Name the app & create it, Click on the app, Goto Generated access token, Click on Generate and Copy the key.)
<br>- A Ngrok account (https://dashboard.ngrok.com/)

<h1> Setup </h1>

1. Click on the button below, Give the app a name (This is also going to be your panel's subdomain), Paste your Dropbox API key, and click on deploy.<br>
[![Deploy](https://www.herokucdn.com/deploy/button.svg)](https://heroku.com/deploy)

2. Now goto https://APP_NAME.herokuapp.com/panel/install.php to create an account and server (Please notice that the total server ram is limited to 1024 mb.)

3. Paste your ngrok key on the Dashboard page.

4. Goto https://wakemydyno.com Enter the website and click on submit.

5. Done!

<h1> Notice </h1>
The total ram of a free dyno is limited to 1024 mb.<br>
When the ram usage is more than 1024 mb, it will reset.<br>
The file manager upload is only intended to be used for small plugins etc.<br>
When a file takes more than 30 seconds to upload it will result in heroku blocking it.
