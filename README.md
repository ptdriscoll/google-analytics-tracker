# Google Analytics Tracker

This is a PHP web page that responds to an URL query parameter by loading a Google tag and then redirecting the user to the intended location.

The application looks for a query key called "url" that can be a redirect address or a label that can be run through a custom filter in the `setURL` function to create a redirect URL - everything else is ignored

Example requests:

- http://localhost/dev/nav/?url=https://video.klrn.org/video/call-the-midwife-season-13-episode-1/
- http://localhost/dev/nav/?url=klrn-passport-donation
- http://localhost/dev/nav/?url=klrn-passport-donation&referrer=pbsvideo://video.klrn.org/video/call-the-midwife-season-13-episode-1/

## Code overview

**setURL(params):** This is where conditionals can be added to filter query parameters and create custom redirect URLs, and as an example it includes a filter for the label `klrn-passport-donation`.

**loadGoogleTag(url):** Google tag is loaded, a page_view event is sent to Google Tag Manager, a `submitRedirect` function is invoked in the event_callback, and as a backup a `setTimeout` will call `submitRedirect` if the Google tag takes too long to load.

**submitRedirect(url):** A form is created and submitted to the server to send the redirect URL as a post, and at the top of the page PHP looks for `$\_POST['url']` and, when set, makes the redirect.

**loader:** CSS and keyframes turn a div element into a loading spinner that shows, if needed, until the redirect.

## References

- [Google Analytics gtag.js ready callback](https://stackoverflow.com/questions/47695531/google-analytics-gtag-js-ready-callback/52774905#52774905)
- [Google tag (gtag.js) parameter reference](https://developers.google.com/tag-platform/gtagjs/reference/parameters)
- [Google tag event reference](https://developers.google.com/tag-platform/gtagjs/reference/events)
