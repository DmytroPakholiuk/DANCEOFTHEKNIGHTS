<p align="center">

<h1 align="center">Welcome to DANCEOFTHEKNIGHTS</h1>
<br>

[//]: # (</p>)

So this is a test task for PandaTeam and such. It just watches over
prices of olx.ua and messages the subscribers on its changes.

INSTALLATION
------------

If you have Linux with docker-compose, just clone the repo and 
configure the files in <code>config</code> directory of the
project, then run <code>manage.sh</code> and
chose "Full project installation".


CONFIGURATION
------------

Most notably, it needs access to gmail account by application key
issued by Google in order to send emails. To get such a key, 
enable 2-factor authentication in your Google account, and then
in its settings request the 16-symbol application code. Then 
insert your email and app code in their places in <code>
gmail_client_example.php</code> and then rename it to <code>
gmail_client.php</code>. That way, the locally deployed app will 
use your email to message the subscribers.

