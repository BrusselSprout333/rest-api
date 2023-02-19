# REST API
<p>REST API application with services for authorization, creating user links and their shortlinks (including full functionality for working with links) and sending messages about changing links to Mail and SMS.</p>
<p>Sending messages is implemented through the redis pub/sub functionality (+ events and listeners) and redis caching was also used for information displayed to the user. Several feature and unit tests were also carried out.</p>
