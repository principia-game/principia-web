# Privacy Policy
This is the privacy policy for this site, principia-web.

The only person who currently has full server and database access is ROllerozxa, he is the server administrator. Moderators do not have full server or database access, and can only use moderation tools provided by them.

Currently principia-web is hosted on the [Voxelmanip server](http://51.68.173.17/), located at OVH in Germany. Backups are stored by ROllerozxa in Sweden.

Whenever a request is sent, it is logged in the server's access logs. This will include your IP, user agent and HTTP referer. These access logs are only accessible to the server administrator and are purged on a weekly basis. They will be used for temporary statistics and for logging potential errors that occur. In the case of illegal activity (e.g. a DDoS attack) the offending parts of an access log may be saved for further analysis.

This site consists mainly of user generated content, and whenever you e.g. upload a level, write a comment or make a forum post you should be aware that this content will be publicly available and searchable on the Internet.


## Interaction with Principia
If you are playing the open source version of Principia, the game will make connections to principia-web.

Outside of obvious interactions such as logging in or downloading community levels, the game will make requests to `/principia-version-code` initially to check for updates, and if you're logged in it will regularily ping the site for notifications which will show up in the top left corner of the game next to your username.


## User information
When you register an account, you provide an username, email and password. The username is visible and should not be your real name, but a pseudonym you are comfortable with others seeing.

The password you register with is one-way hashed with bcrypt and the hash is only visible to the server admin, who cannot convert this back into the original password.

The email you register with is likewise one-way hashed, with salted SHA256. It cannot be retrieved unless you yourself confirm it afterwards, in the case of password resets by email (NYI) or for confirming at a later date that you are the account holder.

Whenever you are logged in, your latest IP address is stored in the database (independent of access logs). This is only visible to the server administrator and is kept strictly private unless abuse occurs.

Any additional user information you add to your profile (About text, location field...) is strictly optional and will be displayed publicly on your user page, do not put private or otherwise personal information you do not want accessible on the internet.


## Cookies
This site only uses a token cookie (`_PRINCSECURITY`) to keep you logged in, this token should not be publicly disclosed to *anyone* as it will make them able to log into your account. When authenticating with the client, it will store the token cookie in a file called `c` in your Principia user folder, which likewise shouldn't be sent to *anyone*.

We do not use any additional cookies, tracking or otherwise. You can verify this by looking at the cookie storage for this site in your respective web browser.

Users may hotlink images on the forums which send requests to third party image hosting sites that make use of analytics. You can choose to block these with your content blocker of choice if so desired.


## Removal Requests
If you have published personal information you would like to remove, use the tools given to edit it out if applicable. If this is not possible (e.g. you want to rename a personally identifiable username) or you would like to fully erase it, please contact [ROllerozxa](/user/1).


## Transparency
The principia-web site is fully open source and its source code [is available on GitHub](https://github.com/principia-preservation-project/principia-web). You may inspect the source code yourself to see how your data is handled by the software.


## Updates
Updates to this privacy policy are done as commits to the principia-web Git repository ([View changes](https://github.com/principia-preservation-project/principia-web/commits/master/templates/markdown/privacy.md)). You will be alerted of any substantial changes made to the privacy policy through Principia community channels.
